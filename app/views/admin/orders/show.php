<?php require __DIR__ . '/../helpers.php'; ?>
<section class="admin-order-detail-section">
    <div class="admin-section-header">
        <div class="admin-section-header-content">
            <div class="order-header-top">
                <a href="<?= base_url('admin/orders') ?>" class="back-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                    Back to Orders
                </a>
            </div>
            <h1>Order <?= e($order['order_number']) ?></h1>
            <div class="order-meta">
                <span class="order-date"><?= date('F j, Y \a\t g:i A', strtotime($order['created_at'])) ?></span>
                <span class="status-badge status-<?= e($order['status']) ?>">
                    <?= e(ucfirst(str_replace('_', ' ', $order['status']))) ?>
                </span>
            </div>
        </div>
        <div class="order-actions">
            <span class="order-total-large">$<?= number_format($order['total_amount'], 2) ?></span>
            <span class="order-total-label">Total Amount</span>
        </div>
    </div>

    <div class="order-detail-cards">
        <div class="info-card">
            <div class="info-card-header">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <h2>Customer Information</h2>
            </div>
            <div class="info-card-body">
                <div class="info-row">
                    <span class="info-label">Name</span>
                    <span class="info-value"><?= e($user['name'] ?? '') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= e($user['email'] ?? '') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone</span>
                    <span class="info-value"><?= e($user['phone'] ?? '') ?></span>
                </div>
            </div>
        </div>

        <div class="info-card">
            <div class="info-card-header">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                    <circle cx="12" cy="10" r="3" />
                </svg>
                <h2>Shipping Address</h2>
            </div>
            <div class="info-card-body">
                <div class="info-row">
                    <span class="info-value full-width"><?= e($order['shipping_name']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= e($order['shipping_email']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone</span>
                    <span class="info-value"><?= e($order['shipping_phone']) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Address</span>
                    <span class="info-value full-width"><?= nl2br(e($order['shipping_address'])) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">City & Postcode</span>
                    <span class="info-value"><?= e($order['shipping_city']) ?>
                        <?= e($order['shipping_postcode']) ?></span>
                </div>
            </div>
        </div>

        <div class="info-card info-card-action">
            <div class="info-card-header">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                <h2>Order Actions</h2>
            </div>
            <div class="info-card-body">
                <form method="post" action="<?= base_url('admin/order/update-status/' . $order['id']) ?>"
                    class="status-form">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token ?? '') ?>">
                    <div class="form-group">
                        <label for="status-select">Update Status</label>
                        <select name="status" id="status-select" class="status-select">
                            <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending
                            </option>
                            <option value="waiting_payment"
                                <?= $order['status'] === 'waiting_payment' ? 'selected' : '' ?>>
                                Waiting Payment</option>
                            <option value="paid" <?= $order['status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                            <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>
                                Processing
                            </option>
                            <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped
                            </option>
                            <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>
                                Delivered
                            </option>
                            <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>
                                Cancelled
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        Update Status
                    </button>
                </form>
                <div class="payment-info">
                    <div class="info-row">
                        <span class="info-label">Payment Method</span>
                        <span
                            class="info-value"><?= e(ucfirst(str_replace('_', ' ', $order['payment_method']))) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Order Total</span>
                        <span class="info-value total-highlight">$<?= number_format($order['total_amount'], 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="items-card">
        <div class="info-card-header">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                <line x1="3" y1="6" x2="21" y2="6" />
                <path d="M16 10a4 4 0 0 1-8 0" />
            </svg>
            <h2>Order Items</h2>
        </div>
        <div class="table-responsive">
            <table class="admin-table items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-right">Price</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $i): ?>
                    <tr>
                        <td>
                            <span class="product-name"><?= e($i['product_name']) ?></span>
                        </td>
                        <td class="text-right">$<?= number_format($i['price'], 2) ?></td>
                        <td class="text-center">
                            <span class="qty-badge"><?= $i['quantity'] ?></span>
                        </td>
                        <td class="text-right">
                            <span class="subtotal">$<?= number_format($i['subtotal'], 2) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right total-label">Grand Total</td>
                        <td class="text-right total-value">$<?= number_format($order['total_amount'], 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</section>

<style>
.admin-order-detail-section {
    padding: 0;
}

.admin-section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border);
}

.order-header-top {
    margin-bottom: 0.5rem;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    color: var(--text-muted);
    text-decoration: none;
    font-size: 0.9rem;
    transition: color var(--transition);
}

.back-link:hover {
    color: var(--accent);
}

.admin-section-header-content h1 {
    margin: 0 0 0.5rem;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text);
}

.order-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.order-date {
    color: var(--text-muted);
    font-size: 0.95rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.4rem 0.85rem;
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-pending,
.status-waiting_payment {
    background: var(--warning-bg);
    color: #92400e;
}

.status-paid,
.status-processing,
.status-shipped {
    background: #dbeafe;
    color: #1e40af;
}

.status-delivered {
    background: var(--success-bg);
    color: #0f766e;
}

.status-cancelled {
    background: var(--error-bg);
    color: #b91c1c;
}

.order-actions {
    text-align: right;
}

.order-total-large {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: var(--accent);
}

.order-total-label {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.order-detail-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.25rem;
    margin-bottom: 1.5rem;
}

.info-card {
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.info-card-header {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border);
    background: var(--bg);
}

.info-card-header svg {
    color: var(--accent);
}

.info-card-header h2 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text);
}

.info-card-body {
    padding: 1.25rem;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border);
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-size: 0.9rem;
    color: var(--text-muted);
}

.info-value {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--text);
    text-align: right;
}

.info-value.full-width {
    width: 100%;
    text-align: left;
    white-space: pre-line;
}

.info-card-action {
    display: flex;
    flex-direction: column;
}

.info-card-action .info-card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.status-form {
    margin-bottom: 1.25rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid var(--border);
}

.status-form .form-group {
    margin-bottom: 0.75rem;
}

.status-form label {
    display: block;
    margin-bottom: 0.35rem;
    font-weight: 500;
    font-size: 0.9rem;
}

.status-select {
    width: 100%;
    padding: 0.6rem 1rem;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    font-family: inherit;
    font-size: 0.95rem;
    background: var(--bg);
    cursor: pointer;
}

.status-select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-soft);
}

.btn-block {
    width: 100%;
}

.payment-info .info-row {
    padding: 0.4rem 0;
}

.total-highlight {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--accent);
}

.items-card {
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.items-card .info-card-header {
    background: var(--bg);
}

.items-card .info-card-header h2 {
    font-size: 1.1rem;
}

.table-responsive {
    overflow-x: auto;
}

.items-table {
    margin: 0;
}

.items-table th:first-child,
.items-table td:first-child {
    padding-left: 1.5rem;
}

.items-table th:last-child,
.items-table td:last-child {
    padding-right: 1.5rem;
}

.product-name {
    font-weight: 500;
    color: var(--text);
}

.text-right {
    text-align: right;
}

.text-center {
    text-align: center;
}

.qty-badge {
    display: inline-block;
    padding: 0.2rem 0.6rem;
    background: var(--bg);
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 600;
}

.subtotal {
    font-weight: 600;
    color: var(--primary);
}

.items-table tfoot tr {
    background: var(--bg);
}

.items-table tfoot td {
    border-top: 2px solid var(--border);
    padding: 1rem 1.25rem;
}

.total-label {
    font-weight: 600;
    font-size: 0.95rem;
    color: var(--text-muted);
}

.total-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--accent);
}

@media (max-width: 768px) {
    .admin-section-header {
        flex-direction: column;
        align-items: stretch;
    }

    .order-actions {
        text-align: left;
        margin-top: 1rem;
    }

    .order-detail-cards {
        grid-template-columns: 1fr;
    }
}
</style>