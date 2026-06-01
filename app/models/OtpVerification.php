<?php
/**
 * OTP verification model (register, forgot password)
 */
namespace App\Models;

use Core\Database;
use PDO;

class OtpVerification
{
    private PDO $db;
    private string $table = 'otp_verifications';
    private int $expiryMinutes = 10;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
 * Clean up expired and used OTPs
 * Should be called periodically (e.g., on each OTP request)
 */
public function cleanup(): int
{
    $stmt = $this->db->prepare("
        DELETE FROM {$this->table} 
        WHERE expires_at < NOW() 
           OR used = 1
           OR created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)
    ");
    $stmt->execute();
    return $stmt->rowCount();
}

    public function create(string $email, string $type): string
    {
        $otp = (string) random_int(100000, 999999);
        $expiresAt = date('Y-m-d H:i:s', time() + ($this->expiryMinutes * 60));
        // Invalidate previous OTPs for this email/type
        $stmt = $this->db->prepare("UPDATE {$this->table} SET used = 1 WHERE email = ? AND type = ?");
        $stmt->execute([$email, $type]);
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (email, otp, type, expires_at) VALUES (?, ?, ?, ?)");
        $stmt->execute([$email, $otp, $type, $expiresAt]);
        return $otp;
    }

    public function verify(string $email, string $otp, string $type): bool
    {
        $stmt = $this->db->prepare("
            SELECT id FROM {$this->table}
            WHERE email = ? AND otp = ? AND type = ? AND used = 0 AND expires_at > NOW()
        ");
        $stmt->execute([$email, $otp, $type]);
        $row = $stmt->fetch();
        if (!$row) return false;
        $stmt = $this->db->prepare("UPDATE {$this->table} SET used = 1 WHERE id = ?");
        $stmt->execute([$row['id']]);
        return true;
    }

    public function isValid(string $email, string $type): bool
    {
        $stmt = $this->db->prepare("
            SELECT id FROM {$this->table}
            WHERE email = ? AND type = ? AND used = 0 AND expires_at > NOW()
        ");
        $stmt->execute([$email, $type]);
        return (bool) $stmt->fetch();
    }
}
