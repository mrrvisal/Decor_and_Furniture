<?php
/**
 * Order model
 */
namespace App\Models;

use Core\Database;
use PDO;

class Order
{
    private PDO $db;
    private string $table = 'orders';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findByOrderNumber(string $orderNumber): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE order_number = ?");
        $stmt->execute([$orderNumber]);
        return $stmt->fetch() ?: null;
    }

    public function getByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getItems(int $orderId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function getAll(array $filters = []): array
    {
        $sql = "SELECT o.*, u.name AS customer_name, u.email AS customer_email FROM {$this->table} o LEFT JOIN users u ON o.user_id = u.id WHERE 1=1";
        $params = [];
        if (!empty($filters['status'])) {
            $sql .= " AND o.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (o.order_number LIKE ? OR u.name LIKE ? OR u.email LIKE ?)";
            $term = '%' . $filters['search'] . '%';
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }
        $sql .= " ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create(int $userId, array $data): int
    {
        $orderNumber = 'ORD-' . strtoupper(uniqid()) . '-' . time();
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (user_id, order_number, total_amount, status, payment_method, shipping_name, shipping_email, shipping_phone, shipping_address, shipping_city, shipping_postcode, notes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $status = $data['payment_method'] === 'qr_code' ? 'waiting_payment' : 'pending';
        $stmt->execute([
            $userId,
            $orderNumber,
            $data['total_amount'],
            $status,
            $data['payment_method'],
            $data['shipping_name'],
            $data['shipping_email'],
            $data['shipping_phone'],
            $data['shipping_address'],
            $data['shipping_city'] ?? null,
            $data['shipping_postcode'] ?? null,
            $data['notes'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function addItem(int $orderId, int $productId, string $productName, float $price, int $quantity): void
    {
        $subtotal = $price * $quantity;
        $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$orderId, $productId, $productName, $price, $quantity, $subtotal]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $allowed = ['pending', 'waiting_payment', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($status, $allowed)) return false;
        $extra = $status === 'paid' ? ', paid_at = NOW()' : '';
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = ?{$extra} WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    /**
     * Dashboard stats
     */
    public function getStats(): array
    {
        $stmt = $this->db->query("
            SELECT
                COUNT(*) AS total_orders,
                COALESCE(SUM(CASE WHEN status = 'pending' OR status = 'waiting_payment' THEN 1 ELSE 0 END), 0) AS pending_orders,
                COALESCE(SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END), 0) AS delivered_orders,
                COALESCE(SUM(total_amount), 0) AS total_revenue
            FROM {$this->table}
        ");
        return $stmt->fetch();
    }
}
