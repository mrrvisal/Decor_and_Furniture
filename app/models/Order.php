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
        $this->ensureDeliveryColumns();
    }

    private function ensureDeliveryColumns(): void
    {
        $columns = $this->db->query("SHOW COLUMNS FROM {$this->table}")->fetchAll(PDO::FETCH_COLUMN);
        $add = [];

        if (!in_array('delivery_method', $columns, true)) {
            $add[] = "ADD delivery_method enum('pickup','local','province') NOT NULL DEFAULT 'local'";
        }
        if (!in_array('delivery_fee', $columns, true)) {
            $add[] = "ADD delivery_fee decimal(10,2) NOT NULL DEFAULT 0.00";
        }
        if (!in_array('delivery_status', $columns, true)) {
            $add[] = "ADD delivery_status enum('not_ready','ready','out_for_delivery','delivered') NOT NULL DEFAULT 'not_ready'";
        }
        if (!in_array('courier_name', $columns, true)) {
            $add[] = "ADD courier_name varchar(100) DEFAULT NULL";
        }
        if (!in_array('tracking_number', $columns, true)) {
            $add[] = "ADD tracking_number varchar(100) DEFAULT NULL";
        }
        if (!in_array('estimated_delivery_date', $columns, true)) {
            $add[] = "ADD estimated_delivery_date date DEFAULT NULL";
        }
        if (!in_array('delivered_at', $columns, true)) {
            $add[] = "ADD delivered_at timestamp NULL DEFAULT NULL";
        }

        if ($add) {
            $this->db->exec("ALTER TABLE {$this->table} " . implode(', ', $add));
        }
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
        if (!empty($filters['delivery_status'])) {
            $sql .= " AND o.delivery_status = ?";
            $params[] = $filters['delivery_status'];
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
            INSERT INTO {$this->table} (user_id, order_number, total_amount, status, payment_method, delivery_method, delivery_fee, delivery_status, shipping_name, shipping_email, shipping_phone, shipping_address, shipping_city, shipping_postcode, notes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $status = $data['payment_method'] === 'qr_code' ? 'waiting_payment' : 'pending';
        $stmt->execute([
            $userId,
            $orderNumber,
            $data['total_amount'],
            $status,
            $data['payment_method'],
            $data['delivery_method'] ?? 'local',
            $data['delivery_fee'] ?? 0,
            'not_ready',
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
        if ($status === 'shipped') {
            $extra .= ", delivery_status = 'out_for_delivery'";
        } elseif ($status === 'delivered') {
            $extra .= ", delivery_status = 'delivered', delivered_at = COALESCE(delivered_at, NOW())";
        } elseif ($status === 'cancelled') {
            $extra .= ", delivery_status = 'not_ready', delivered_at = NULL";
        }
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = ?{$extra} WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function updateDelivery(int $id, array $data): bool
    {
        $allowedStatuses = ['not_ready', 'ready', 'out_for_delivery', 'delivered'];
        $deliveryStatus = $data['delivery_status'] ?? 'not_ready';
        if (!in_array($deliveryStatus, $allowedStatuses, true)) {
            return false;
        }

        $extra = $deliveryStatus === 'delivered' ? ', delivered_at = COALESCE(delivered_at, NOW())' : ', delivered_at = NULL';
        $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET delivery_status = ?, courier_name = ?, tracking_number = ?, estimated_delivery_date = ?{$extra}
            WHERE id = ?
        ");

        return $stmt->execute([
            $deliveryStatus,
            trim($data['courier_name'] ?? '') ?: null,
            trim($data['tracking_number'] ?? '') ?: null,
            trim($data['estimated_delivery_date'] ?? '') ?: null,
            $id,
        ]);
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
