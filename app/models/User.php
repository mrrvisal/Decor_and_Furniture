<?php
/**
 * User model - customers
 */
namespace App\Models;

use Core\Database;
use PDO;

class User
{
    private PDO $db;
    private string $table = 'users';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (name, email, password, phone, email_verified_at)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['phone'] ?? null,
            $data['email_verified_at'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $values = [];
        foreach (['name', 'phone', 'email_verified_at', 'is_active', 'address', 'city', 'postcode', 'country'] as $f) {
            if (array_key_exists($f, $data)) {
                $fields[] = "{$f} = ?";
                $values[] = $data[$f];
            }
        }
        if (isset($data['password'])) {
            $fields[] = 'password = ?';
            $values[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $values[] = $id;
        $stmt = $this->db->prepare("UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?");
        return $stmt->execute($values);
    }

    public function verifyEmail(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET email_verified_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Get all users with optional filters
     */
    public function getAll(array $filters = []): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        // Search filter (name or email)
        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE ? OR email LIKE ?)";
            $term = '%' . $filters['search'] . '%';
            $params[] = $term;
            $params[] = $term;
        }

        // Status filter (is_active)
        if (isset($filters['status']) && $filters['status'] !== '') {
            $sql .= " AND is_active = ?";
            $params[] = (int) $filters['status'];
        }

        // Date filter
        if (!empty($filters['date_from'])) {
            $sql .= " AND created_at >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND created_at <= ?";
            $params[] = $filters['date_to'];
        }

        // Order
        $orderBy = $filters['order_by'] ?? 'created_at';
        $orderDir = strtoupper($filters['order_dir'] ?? 'DESC');
        $allowedOrderCols = ['id', 'name', 'email', 'created_at', 'is_active'];
        $orderBy = in_array($orderBy, $allowedOrderCols) ? $orderBy : 'created_at';
        $orderDir = in_array($orderDir, ['ASC', 'DESC']) ? $orderDir : 'DESC';
        $sql .= " ORDER BY {$orderBy} {$orderDir}";

        // Pagination
        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = min(100, max(1, (int) ($filters['per_page'] ?? 20)));
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get count of users with optional filters
     */
    public function getCount(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) as cnt FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE ? OR email LIKE ?)";
            $term = '%' . $filters['search'] . '%';
            $params[] = $term;
            $params[] = $term;
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $sql .= " AND is_active = ?";
            $params[] = (int) $filters['status'];
        }

        if (!empty($filters['date_from'])) {
            $sql .= " AND created_at >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND created_at <= ?";
            $params[] = $filters['date_to'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return (int) ($row['cnt'] ?? 0);
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_active = NOT is_active WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Delete user (soft delete via deactivation recommended)
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Get user statistics for admin dashboard
     */
    public function getStats(): array
    {
        $totalStmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        $activeStmt = $this->db->query("SELECT COUNT(*) as active FROM {$this->table} WHERE is_active = 1");
        $newStmt = $this->db->query("SELECT COUNT(*) as new FROM {$this->table} WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");

        $total = $totalStmt->fetch();
        $active = $activeStmt->fetch();
        $new = $newStmt->fetch();

        return [
            'total' => (int) ($total['total'] ?? 0),
            'active' => (int) ($active['active'] ?? 0),
            'inactive' => (int) (($total['total'] ?? 0) - ($active['active'] ?? 0)),
            'new_this_week' => (int) ($new['new'] ?? 0),
        ];
    }
}