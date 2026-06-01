<?php
/**
 * Stock alert creation and supplier notification.
 */
namespace Core;

use PDO;

class StockAlert
{
    public static function createIfNeeded(int $productId): ?int
    {
        $db = Database::getInstance();
        $product = self::getProductWithSupplier($db, $productId);

        if (!$product) {
            return null;
        }

        $currentStock = (int) $product['stock'];
        $reorderLevel = 10;

        if ($currentStock >= 10) {
            return null;
        }

        $alertType = $currentStock === 0 ? 'out_of_stock' : 'low_stock';
        $existing = $db->prepare("
            SELECT id, status
            FROM stock_alerts
            WHERE product_id = ?
              AND status != 'resolved'
              AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
            LIMIT 1
        ");
        $existing->execute([$productId]);
        $existingAlert = $existing->fetch(PDO::FETCH_ASSOC);
        if ($existingAlert) {
            if ($existingAlert['status'] === 'pending' && self::sendSupplierEmail($product, $alertType)) {
                $update = $db->prepare("UPDATE stock_alerts SET status = 'sent', notified_at = NOW() WHERE id = ?");
                $update->execute([(int) $existingAlert['id']]);
            }
            return (int) $existingAlert['id'];
        }

        $insert = $db->prepare("
            INSERT INTO stock_alerts (product_id, current_stock, reorder_level, alert_type)
            VALUES (?, ?, ?, ?)
        ");
        $insert->execute([$productId, $currentStock, $reorderLevel, $alertType]);
        $alertId = (int) $db->lastInsertId();

        if (self::sendSupplierEmail($product, $alertType)) {
            $update = $db->prepare("UPDATE stock_alerts SET status = 'sent', notified_at = NOW() WHERE id = ?");
            $update->execute([$alertId]);
        }

        return $alertId;
    }

    private static function getProductWithSupplier(PDO $db, int $productId): ?array
    {
        $stmt = $db->prepare("
            SELECT
                p.id,
                p.name,
                p.stock,
                p.reorder_level,
                p.reorder_quantity,
                s.name AS supplier_name,
                s.contact_name AS supplier_contact_name,
                s.email AS supplier_email
            FROM products p
            LEFT JOIN suppliers s ON p.supplier_id = s.id
            WHERE p.id = ?
        ");
        $stmt->execute([$productId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private static function sendSupplierEmail(array $product, string $alertType): bool
    {
        $email = trim((string) ($product['supplier_email'] ?? ''));
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $productName = htmlspecialchars((string) $product['name'], ENT_QUOTES, 'UTF-8');
        $supplierName = htmlspecialchars((string) ($product['supplier_contact_name'] ?: $product['supplier_name'] ?: 'Supplier'), ENT_QUOTES, 'UTF-8');
        $currentStock = (int) $product['stock'];
        $reorderLevel = (int) $product['reorder_level'];
        $reorderQuantity = (int) $product['reorder_quantity'];
        $alertLabel = $alertType === 'out_of_stock' ? 'Out of stock' : 'Low stock';

        $subject = "{$alertLabel}: {$product['name']}";
        $bodyHtml = "
            <div style='font-family: Arial, sans-serif; max-width: 640px; margin: 0 auto; color: #222;'>
                <h2 style='margin-bottom: 8px;'>{$alertLabel} alert</h2>
                <p>Hello {$supplierName},</p>
                <p>The following product needs restocking:</p>
                <table style='width: 100%; border-collapse: collapse; margin: 18px 0;'>
                    <tr>
                        <td style='padding: 8px; border: 1px solid #ddd; font-weight: bold;'>Product</td>
                        <td style='padding: 8px; border: 1px solid #ddd;'>{$productName}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px; border: 1px solid #ddd; font-weight: bold;'>Current stock</td>
                        <td style='padding: 8px; border: 1px solid #ddd;'>{$currentStock}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px; border: 1px solid #ddd; font-weight: bold;'>Reorder level</td>
                        <td style='padding: 8px; border: 1px solid #ddd;'>{$reorderLevel}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px; border: 1px solid #ddd; font-weight: bold;'>Requested quantity</td>
                        <td style='padding: 8px; border: 1px solid #ddd;'>{$reorderQuantity}</td>
                    </tr>
                </table>
                <p>Please prepare a restock for this item.</p>
                <p style='color: #666; font-size: 13px;'>This message was sent automatically by Decor & Furniture Store.</p>
            </div>
        ";
        $bodyText = "{$alertLabel} alert\n\n"
            . "Product: {$product['name']}\n"
            . "Current stock: {$currentStock}\n"
            . "Reorder level: {$reorderLevel}\n"
            . "Requested quantity: {$reorderQuantity}\n\n"
            . "Please prepare a restock for this item.";

        return Mail::send($email, $subject, $bodyHtml, $bodyText);
    }
}
