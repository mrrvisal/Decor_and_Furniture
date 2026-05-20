<?php
/**
 * Admin model - backend users
 */
namespace App\Models;

use Core\Database;
use PDO;

class Admin
{
    private PDO $db;
    private string $table = 'admins';

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

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch() ?: null;
    }

    public function verify(string $emailOrUsername, string $password): ?array
    {
        $admin = $this->findByEmail($emailOrUsername) ?? $this->findByUsername($emailOrUsername);
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return null;
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $values = [];
        foreach (['username', 'email', 'name', 'avatar', 'address', 'city', 'postcode', 'country'] as $f) {
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
}