<?php
/**
 * Product Review Model
 */
namespace App\Models;

use Core\Database;
use PDO;

class Review
{
    private PDO $db;
    private string $table = 'product_reviews';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
 * Check if user can review a product 
 * MODIFIED: Allows review if user has purchased (even if previously reviewed)
 */
/**
 * Check if user can review a product for a specific order
 * Users can review the same product if they purchased it again in a different order
 */
public function canReview(int $productId, int $userId, ?int $orderId = null): bool
{
    $sql = "
        SELECT oi.id 
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.id
        WHERE oi.product_id = ? 
          AND o.user_id = ? 
          AND o.status = 'delivered'
    ";
    $params = [$productId, $userId];
    
    if ($orderId) {
        $sql .= " AND o.id = ?";
        $params[] = $orderId;
    }
    
    $sql .= " LIMIT 1";
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    $hasPurchased = $stmt->fetch();
    
    if (!$hasPurchased) {
        return false;
    }
    
    // Check if already reviewed for THIS SPECIFIC order
    if ($orderId) {
        $stmt = $this->db->prepare("
            SELECT id FROM {$this->table}
            WHERE product_id = ? AND user_id = ? AND order_id = ?
            LIMIT 1
        ");
        $stmt->execute([$productId, $userId, $orderId]);
        return !$stmt->fetch();
    }
    
    // If no order specified, check if they have any unreviewed orders for this product
    $stmt = $this->db->prepare("
        SELECT o.id
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.id
        LEFT JOIN {$this->table} r ON r.product_id = oi.product_id 
            AND r.user_id = o.user_id 
            AND r.order_id = o.id
        WHERE oi.product_id = ? 
          AND o.user_id = ? 
          AND o.status = 'delivered'
          AND r.id IS NULL
        LIMIT 1
    ");
    $stmt->execute([$productId, $userId]);
    
    return (bool) $stmt->fetch();
}

/**
 * Get eligible orders for review (orders containing this product that haven't been reviewed yet)
 */
public function getEligibleOrdersForReview(int $productId, int $userId): array
{
    $stmt = $this->db->prepare("
        SELECT o.id, o.order_number, o.created_at
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.id
        LEFT JOIN {$this->table} r ON r.product_id = oi.product_id 
            AND r.user_id = o.user_id 
            AND r.order_id = o.id
        WHERE oi.product_id = ? 
          AND o.user_id = ? 
          AND o.status = 'delivered'
          AND r.id IS NULL
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$productId, $userId]);
    return $stmt->fetchAll();
}

/**
 * Get all reviews by a user for a product
 */
public function getUserReviewsForProduct(int $productId, int $userId): array
{
    $stmt = $this->db->prepare("
        SELECT r.*, o.order_number
        FROM {$this->table} r
        JOIN orders o ON r.order_id = o.id
        WHERE r.product_id = ? AND r.user_id = ?
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$productId, $userId]);
    return $stmt->fetchAll();
}

/**
 * Get the most recent order ID for a product review
 */
public function getOrderIdForReview(int $productId, int $userId): ?int
{
    // Get the most recent delivered order for this product
    $stmt = $this->db->prepare("
        SELECT o.id
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.id
        WHERE oi.product_id = ? 
          AND o.user_id = ? 
          AND o.status = 'delivered'
        ORDER BY o.created_at DESC
        LIMIT 1
    ");
    $stmt->execute([$productId, $userId]);
    $result = $stmt->fetch();
    return $result ? (int) $result['id'] : null;
}

    /**
     * Add a review for a product
     */
    public function create(int $productId, int $userId, int $orderId, int $rating, ?string $title, string $comment): int
    {
        // Check if user purchased this product
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            WHERE oi.product_id = ? 
              AND o.user_id = ? 
              AND o.status = 'delivered'
        ");
        $stmt->execute([$productId, $userId]);
        $result = $stmt->fetch();
        $isVerifiedPurchase = ($result['count'] ?? 0) > 0 ? 1 : 0;
        
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} 
            (product_id, user_id, order_id, rating, title, comment, is_verified_purchase, is_approved)
            VALUES (?, ?, ?, ?, ?, ?, ?, 0)
        ");
        $stmt->execute([$productId, $userId, $orderId, $rating, $title, $comment, $isVerifiedPurchase]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Get reviews for a product (only approved ones for public view)
     */
    public function getForProduct(int $productId, int $limit = 10, int $offset = 0): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                r.*, 
                u.name as user_name,
                (SELECT COUNT(*) FROM review_helpful_votes WHERE review_id = r.id) as helpful_count
            FROM {$this->table} r
            JOIN users u ON r.user_id = u.id
            WHERE r.product_id = ? AND r.is_approved = 1
            ORDER BY r.helpful_count DESC, r.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$productId, $limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Get product rating summary
     */
    public function getRatingSummary(int $productId): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_reviews,
                COALESCE(ROUND(AVG(rating), 1), 0) as average_rating,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as five_star,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as four_star,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as three_star,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as two_star,
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as one_star
            FROM {$this->table}
            WHERE product_id = ? AND is_approved = 1
        ");
        $stmt->execute([$productId]);
        $result = $stmt->fetch();
        
        if (!$result) {
            return [
                'total_reviews' => 0,
                'average_rating' => 0,
                'five_star' => 0,
                'four_star' => 0,
                'three_star' => 0,
                'two_star' => 0,
                'one_star' => 0,
                'five_percent' => 0,
                'four_percent' => 0,
                'three_percent' => 0,
                'two_percent' => 0,
                'one_percent' => 0
            ];
        }
        
        $total = $result['total_reviews'];
        $result['five_percent'] = $total > 0 ? round($result['five_star'] / $total * 100) : 0;
        $result['four_percent'] = $total > 0 ? round($result['four_star'] / $total * 100) : 0;
        $result['three_percent'] = $total > 0 ? round($result['three_star'] / $total * 100) : 0;
        $result['two_percent'] = $total > 0 ? round($result['two_star'] / $total * 100) : 0;
        $result['one_percent'] = $total > 0 ? round($result['one_star'] / $total * 100) : 0;
        
        return $result;
    }

    /**
     * Mark review as helpful (AJAX)
     */
    public function markHelpful(int $reviewId, int $userId): bool
    {
        // Check if already voted
        $stmt = $this->db->prepare("
            SELECT id FROM review_helpful_votes WHERE review_id = ? AND user_id = ?
        ");
        $stmt->execute([$reviewId, $userId]);
        if ($stmt->fetch()) {
            return false;
        }
        
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Add vote
            $stmt = $this->db->prepare("
                INSERT INTO review_helpful_votes (review_id, user_id) VALUES (?, ?)
            ");
            $stmt->execute([$reviewId, $userId]);
            
            // Update helpful count
            $stmt = $this->db->prepare("
                UPDATE {$this->table} SET helpful_count = helpful_count + 1 WHERE id = ?
            ");
            $stmt->execute([$reviewId]);
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Get pending reviews for admin
     */
    public function getPendingReviews(): array
    {
        $stmt = $this->db->prepare("
            SELECT r.*, p.name as product_name, p.id as product_id, u.name as user_name, u.email as user_email
            FROM {$this->table} r
            JOIN products p ON r.product_id = p.id
            JOIN users u ON r.user_id = u.id
            WHERE r.is_approved = 0
            ORDER BY r.created_at ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get all reviews for admin (with filters)
     */
    public function getAllReviews(array $filters = []): array
    {
        $sql = "SELECT r.*, p.name as product_name, u.name as user_name 
                FROM {$this->table} r
                JOIN products p ON r.product_id = p.id
                JOIN users u ON r.user_id = u.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'pending') {
                $sql .= " AND r.is_approved = 0";
            } elseif ($filters['status'] === 'approved') {
                $sql .= " AND r.is_approved = 1";
            }
        }
        
        if (!empty($filters['product_id'])) {
            $sql .= " AND r.product_id = ?";
            $params[] = $filters['product_id'];
        }
        
        $sql .= " ORDER BY r.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Approve a review
     */
    public function approve(int $reviewId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} SET is_approved = 1, updated_at = NOW() WHERE id = ?
        ");
        return $stmt->execute([$reviewId]);
    }

    /**
     * Delete a review
     */
    public function delete(int $reviewId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$reviewId]);
    }

    /**
     * Get review by ID
     */
    public function find(int $reviewId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT r.*, p.name as product_name, u.name as user_name
            FROM {$this->table} r
            JOIN products p ON r.product_id = p.id
            JOIN users u ON r.user_id = u.id
            WHERE r.id = ?
        ");
        $stmt->execute([$reviewId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}