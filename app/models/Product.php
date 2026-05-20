<?php
/**
 * Product model
 */
namespace App\Models;

use Core\Database;
use PDO;

class Product
{
    private PDO $db;
    private string $table = 'products';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug
            FROM {$this->table} p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name
            FROM {$this->table} p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.slug = ? AND p.is_active = 1
        ");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Get products with optional filters
     * @param array $filters Optional filters (category, min_price, max_price, search, in_stock)
     * @param int|null $limit Optional limit for number of results
     * @return array
     */
    public function getList(array $filters = [], ?int $limit = null): array
    {
        $sql = "SELECT p.*, c.name AS category_name FROM {$this->table} p LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = $filters['category'];
        }
        if (isset($filters['min_price']) && $filters['min_price'] !== null && $filters['min_price'] !== '') {
            $sql .= " AND p.price >= ?";
            $params[] = $filters['min_price'];
        }
        if (isset($filters['max_price']) && $filters['max_price'] !== null && $filters['max_price'] !== '') {
            $sql .= " AND p.price <= ?";
            $params[] = $filters['max_price'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $term = '%' . $filters['search'] . '%';
            $params[] = $term;
            $params[] = $term;
        }
        if (isset($filters['in_stock']) && $filters['in_stock']) {
            $sql .= " AND p.stock > 0";
        }
        $sql .= " AND p.is_active = 1";
        $sql .= " ORDER BY p.created_at DESC";

        if ($limit !== null && $limit > 0) {
            $sql .= " LIMIT " . (int) $limit;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $slug = $this->slugify($data['name']);
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (category_id, name, slug, description, price, stock, image, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['category_id'],
            $data['name'],
            $slug,
            $data['description'] ?? null,
            $data['price'],
            $data['stock'] ?? 0,
            $data['image'] ?? null,
            $data['is_active'] ?? 1,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $slug = isset($data['name']) ? $this->slugify($data['name']) : null;
        $stmt = $this->db->prepare("
            UPDATE {$this->table} SET category_id = ?, name = ?, slug = COALESCE(?, slug), description = ?, price = ?, stock = ?, image = COALESCE(?, image), is_active = ?
            WHERE id = ?
        ");
        $product = $this->find($id);
        return $stmt->execute([
            $data['category_id'] ?? $product['category_id'],
            $data['name'] ?? $product['name'],
            $slug,
            $data['description'] ?? $product['description'],
            $data['price'] ?? $product['price'],
            $data['stock'] ?? $product['stock'],
            $data['image'] ?? $product['image'],
            $data['is_active'] ?? $product['is_active'],
            $id,
        ]);
    }

    public function decrementStock(int $id, int $qty): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET stock = stock - ? WHERE id = ? AND stock >= ?");
        return $stmt->execute([$qty, $id, $qty]);
    }

    public function hasOrders(int $id): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM order_items WHERE product_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    public function toggleStatus(int $id): bool
    {
        $product = $this->find($id);
        if (!$product) {
            return false;
        }
        $newStatus = $product['is_active'] ? 0 : 1;
        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_active = ? WHERE id = ?");
        return $stmt->execute([$newStatus, $id]);
    }

    public function delete(int $id): bool
    {
        // Check if product has any orders
        if ($this->hasOrders($id)) {
            return false; // Cannot delete product with existing orders
        }

        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    private function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', strtolower($text));
        return trim($text, '-') ?: 'product';
    }
}