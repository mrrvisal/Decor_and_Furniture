<?php
/**
 * PDO Database connection singleton
 * Uses prepared statements for security
 */
namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    private static array $config;

    /**
     * Get PDO instance
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$config = require dirname(__DIR__) . '/config/database.php';
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                self::$config['host'],
                self::$config['dbname'],
                self::$config['charset']
            );
            try {
                self::$instance = new PDO($dsn, self::$config['username'], self::$config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
                // Set timezone to match MySQL server timezone (UTC+7 for Cambodia/Asia/Bangkok)
                self::$instance->exec("SET time_zone = '+07:00'");
            } catch (PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }

    /** Prevent cloning */
    private function __clone()
    {
    }

    /** Prevent unserialize */
    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize singleton');
    }
}