<?php
/**
 * Cron Job: Check for low stock and send vendor alerts
 * Run this script every hour via cron:
 * 0 * * * * php /path/to/cron/check_stock_alerts.php
 */

chdir(dirname(__DIR__));
require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Asia/Phnom_Penh');

use Core\Database;

$db = Database::getInstance();

// Find products at or below low-stock threshold
$stmt = $db->prepare("SELECT p.*, s.name as supplier_name, s.email as supplier_email, s.contact_name
    FROM products p
    LEFT JOIN suppliers s ON p.supplier_id = s.id
    WHERE p.stock <= 10
      AND p.is_active = 1");

$stmt->execute();
$lowStockProducts = $stmt->fetchAll();

foreach ($lowStockProducts as $product) {
    // Check if alert already sent in the last 24 hours
    $stmt = $db->prepare("SELECT id FROM stock_alerts
        WHERE product_id = ?
          AND status != 'resolved'
          AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
    $stmt->execute([$product['id']]);
    
    if (!$stmt->fetch()) {
        $alertType = $product['stock'] == 0 ? 'out_of_stock' : 'low_stock';
        
        // Create alert record
        $stmt = $db->prepare("INSERT INTO stock_alerts (product_id, current_stock, reorder_level, alert_type)
            VALUES (?, ?, ?, ?)");
        $stmt->execute([$product['id'], $product['stock'], $product['reorder_level'], $alertType]);
        
        // Send email to supplier
        $subject = "URGENT: Stock Alert - " . $product['name'];
        $message = "
            <h2>Stock Alert for {$product['name']}</h2>
            <p>Current stock: <strong>{$product['stock']}</strong></p>
            <p>Reorder level: {$product['reorder_level']}</p>
            <p>Recommended order quantity: {$product['reorder_quantity']}</p>
            <p>Please contact us immediately to arrange restocking.</p>
        ";
        
        // Update alert as sent only when an email was actually sent
        if (!empty($product['supplier_email'])) {
            $stmt = $db->prepare("UPDATE stock_alerts SET status = 'sent', notified_at = NOW()
                WHERE product_id = ? AND status = 'pending'");
            $stmt->execute([$product['id']]);
            error_log("Stock alert sent for product {$product['id']}: {$product['name']}");
        } else {
            error_log("Stock alert created for product {$product['id']} with no supplier: {$product['name']}");
        }
    }
}

echo "Stock check completed at " . date('Y-m-d H:i:s') . "\n";
