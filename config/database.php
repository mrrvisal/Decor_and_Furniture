<?php
/**
 * Database configuration for Decor & Furniture E-commerce
 * Update these values for your XAMPP MySQL setup
 */
return [
    'host'     => getenv('DB_HOST') ?: 'localhost',
    'dbname'   => getenv('DB_NAME') ?: 'decor_furniture',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: '',
    'charset'  => 'utf8mb4',
];
