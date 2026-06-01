<?php require __DIR__ . '/../helpers.php'; ?>
<section class="admin-stock-alerts">
    <div class="admin-section-header">
        <div>
            <h1>Stock Alerts</h1>
            <p class="muted">Review low-stock and out-of-stock products and resolve alerts.</p>
        </div>
        <a href="<?= base_url('admin/stock-alerts') ?>" class="btn btn-ghost btn-sm refresh">Refresh</a>
    </div>

    <?php if (!empty($alerts)): ?>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Supplier</th>
                    <th>Stock</th>
                    <th>Reorder Level</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Email</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alerts as $alert): ?>
                <tr>
                    <td>
                        <div class="product-cell">
                            <?php if (!empty($alert['image'])): ?>
                            <img src="<?= asset('images/products/' . $alert['image']) ?>" alt="<?= e($alert['product_name']) ?>">
                            <?php endif; ?>
                            <span><?= e($alert['product_name']) ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="supplier-cell">
                            <strong><?= e($alert['supplier_name'] ?? 'N/A') ?></strong><br>
                            <?php if (!empty($alert['supplier_email'])): ?>
                                <a href="mailto:<?= e($alert['supplier_email']) ?>"><?= e($alert['supplier_email']) ?></a>
                            <?php else: ?>
                                <span class="muted">-</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><?= (int) $alert['current_stock'] ?></td>
                    <td><?= (int) $alert['reorder_level'] ?></td>
                    <td><?= e(ucfirst(str_replace('_', ' ', $alert['alert_type']))) ?></td>
                    <td>
                        <span class="status-badge status-<?= e($alert['status']) ?>">
                            <?= e(ucfirst($alert['status'])) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($alert['status'] === 'sent' && !empty($alert['notified_at'])): ?>
                            <span class="email-status email-sent">Sent</span>
                            <small><?= date('M j, Y H:i', strtotime($alert['notified_at'])) ?></small>
                        <?php elseif (!empty($alert['supplier_email'])): ?>
                            <span class="email-status email-pending">Pending</span>
                            <small>Will retry on refresh</small>
                        <?php else: ?>
                            <span class="email-status email-missing">No supplier email</span>
                        <?php endif; ?>
                    </td>
                    <td><?= date('M j, Y', strtotime($alert['created_at'])) ?></td>
                    <td>
                        <?php if ($alert['status'] !== 'resolved'): ?>
                        <form method="post" action="<?= base_url('admin/stock-alert/resolve/' . $alert['id']) ?>">
                            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                            <button type="submit" class="btn btn-sm btn-success">Resolve</button>
                        </form>
                        <?php else: ?>
                        <span class="muted">Resolved</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state card">
        <div class="empty-state-icon">📦</div>
        <h3>No stock alerts</h3>
        <p class="muted">No pending or sent stock alerts were found.</p>
    </div>
    <?php endif; ?>
</section>

<style>
.admin-stock-alerts {
    padding: 0;
}
.product-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.product-cell img {
    width: 45px;
    height: 45px;
    object-fit: cover;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
}
.supplier-cell a {
    color: var(--accent);
    text-decoration: none;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.65rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
}
.status-badge.status-pending {
    background: var(--warning-bg);
    color: #92400e;
}
.status-badge.status-sent {
    background: var(--info-bg);
    color: #075985;
}
.status-badge.status-resolved {
    background: var(--success-bg);
    color: #0f766e;
}
.email-status {
    display: block;
    font-weight: 700;
    font-size: 0.8rem;
}
.email-sent {
    color: #0f766e;
}
.email-pending {
    color: #92400e;
}
.email-missing {
    color: var(--text-muted);
}
.admin-table td small {
    display: block;
    color: var(--text-muted);
    margin-top: 0.2rem;
}
.empty-state.card {
    text-align: center;
    padding: 2rem;
    border-radius: var(--radius);
    background: var(--bg);
    border: 1px solid var(--border);
}
.empty-state-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}
.refresh {
    margin-bottom: 1rem;
}
</style>
