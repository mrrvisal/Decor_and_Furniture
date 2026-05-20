<?php
/**
 * Category model
 */
namespace App\Models;

use Core\Database;
use PDO;

class Category
{
    private PDO $db;
    private string $table = 'categories';

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

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $slug = $this->slugify($data['name']);
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (name, slug, description) VALUES (?, ?, ?)");
        $stmt->execute([$data['name'], $slug, $data['description'] ?? null]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $slug = isset($data['name']) ? $this->slugify($data['name']) : null;
        $stmt = $this->db->prepare("UPDATE {$this->table} SET name = ?, slug = COALESCE(?, slug), description = ? WHERE id = ?");
        return $stmt->execute([
            $data['name'] ?? '',
            $slug,
            $data['description'] ?? null,
            $id,
        ]);
    }

    private function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', strtolower($text));
        return trim($text, '-') ?: 'category';
    }
}
