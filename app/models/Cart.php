<?php
/**
 * Cart model
 */
namespace App\Models;

use Core\Database;
use PDO;

class Cart
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getItems(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT c.*, p.name, p.price, p.image, p.stock
            FROM carts c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ? AND p.is_active = 1
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getTotal(int $userId): float
    {
        $items = $this->getItems($userId);
        $total = 0;
        foreach ($items as $item) {
            $qty = min((int) $item['quantity'], (int) $item['stock']);
            $total += $item['price'] * $qty;
        }
        return round($total, 2);
    }

    public function add(int $userId, int $productId, int $quantity = 1): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE quantity = quantity + ?
        ");
        return $stmt->execute([$userId, $productId, $quantity, $quantity]);
    }

    public function updateQuantity(int $userId, int $productId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->remove($userId, $productId);
        }
        $stmt = $this->db->prepare("UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?");
        return $stmt->execute([$quantity, $userId, $productId]);
    }

    public function remove(int $userId, int $productId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM carts WHERE user_id = ? AND product_id = ?");
        return $stmt->execute([$userId, $productId]);
    }

    public function clear(int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM carts WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }

    public function count(int $userId): int
    {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(quantity), 0) AS cnt FROM carts WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (int) $stmt->fetch()['cnt'];
    }
}
