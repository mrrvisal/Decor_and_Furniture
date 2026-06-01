<?php
/**
 * Audit Log Model - Tracks all admin actions
 */
namespace App\Models;

use Core\Database;
use PDO;

class AuditLog
{
    private PDO $db;
    private string $table = 'audit_logs';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Log an admin action
     */
    public function log(
        int $adminId, 
        string $adminName,
        string $action, 
        string $targetType, 
        int $targetId, 
        ?array $oldData = null, 
        ?array $newData = null
    ): bool {
            // Determine allowed enum values for target_type from the DB to avoid SQL truncation errors
            try {
                $schemaStmt = $this->db->prepare("SELECT COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = 'target_type'");
                $schemaStmt->execute([$this->table]);
                $col = $schemaStmt->fetchColumn();
                $allowedTargets = [];
                if ($col && preg_match("/enum\((.*)\)/", $col, $m)) {
                    $vals = explode(',', $m[1]);
                    foreach ($vals as $v) {
                        $allowedTargets[] = trim($v, "' \t\n\r\0\x0B");
                    }
                }
            } catch (\Throwable $e) {
                $allowedTargets = ['product','order','user','category','admin','stock_alert'];
            }

            if (!in_array($targetType, $allowedTargets, true)) {
                // fallback to admin if DB doesn't accept provided target type
                $targetType = 'admin';
            }

            $stmt = $this->db->prepare(
                "INSERT INTO {$this->table} 
                (admin_id, admin_name, action, target_type, target_id, old_data, new_data, ip_address, user_agent)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            return $stmt->execute([
                $adminId,
                $adminName,
                $action,
                $targetType,
                $targetId,
                $oldData ? json_encode($oldData) : null,
                $newData ? json_encode($newData) : null,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);
    }

    /**
     * Get audit trail for a specific target
     */
    public function getForTarget(string $targetType, int $targetId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE target_type = ? AND target_id = ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$targetType, $targetId]);
        return $stmt->fetchAll();
    }

    /**
     * Get recent audit logs with pagination
     */
    public function getRecent(int $limit = 50, int $offset = 0): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Get audit logs summary
     */
    public function getSummary(): array
    {
        $stmt = $this->db->query("
            SELECT 
                action,
                target_type,
                COUNT(*) as count,
                DATE(created_at) as action_date
            FROM {$this->table}
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY action, target_type, DATE(created_at)
            ORDER BY action_date DESC
        ");
        return $stmt->fetchAll();
    }
}