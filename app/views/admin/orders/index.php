<?php require __DIR__ . '/../helpers.php'; ?>
<section class="admin-orders-section">
    <div class="admin-section-header">
        <div class="admin-section-header-content">
            <h1>Orders</h1>
            <p class="muted">Manage and track all customer orders</p>
        </div>
    </div>

    <div class="admin-card">
        <form method="get" action="<?= base_url('admin/orders') ?>" class="admin-filters-form">
            <div class="filter-search">
                <input type="text" name="q" placeholder="Search order #, customer..."
                    value="<?= e($filters['search'] ?? '') ?>" class="filter-input">
            </div>
            <div class="filter-select-wrap">
                <select name="status" class="filter-select">
                    <option value="">All statuses</option>
                    <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending
                    </option>
                    <option value="waiting_payment"
                        <?= ($filters['status'] ?? '') === 'waiting_payment' ? 'selected' : '' ?>>
                        Waiting Payment</option>
                    <option value="paid" <?= ($filters['status'] ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                    <option value="processing" <?= ($filters['status'] ?? '') === 'processing' ? 'selected' : '' ?>>
                        Processing
                    </option>
                    <option value="shipped" <?= ($filters['status'] ?? '') === 'shipped' ? 'selected' : '' ?>>Shipped
                    </option>
                    <option value="delivered" <?= ($filters['status'] ?? '') === 'delivered' ? 'selected' : '' ?>>
                        Delivered
                    </option>
                </select>
            </div>
            <?php if (!empty($filters['search']) || !empty($filters['status'])): ?>
            <a href="<?= base_url('admin/orders') ?>" class="btn btn-ghost btn-sm">Clear</a>
            <?php endif; ?>
        </form>

        <div class="table-responsive">
            <table class="admin-table admin-table-orders">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                    <tr>
                        <td>
                            <span class="order-number"><?= e($o['order_number']) ?></span>
                        </td>
                        <td>
                            <div class="customer-info">
                                <span class="customer-name"><?= e($o['customer_name'] ?? '') ?></span>
                                <span class="customer-email"><?= e($o['customer_email'] ?? '') ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="order-total">$<?= number_format($o['total_amount'], 2) ?></span>
                        </td>
                        <td>
                            <span class="status-badge status-<?= e($o['status']) ?>">
                                <?= e(ucfirst(str_replace('_', ' ', $o['status']))) ?>
                            </span>
                        </td>
                        <td>
                            <span class="order-date"><?= date('M j, Y', strtotime($o['created_at'])) ?></span>
                        </td>
                        <td class="text-right">
                            <a href="<?= base_url('admin/order/' . $o['id']) ?>" class="btn btn-sm btn-outline">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (empty($orders)): ?>
        <div class="empty-state">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                </path>
            </svg>
            <h3>No orders found</h3>
            <p class="muted">There are no orders to display at the moment.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.querySelector('.filter-select[name="status"]');
    const searchInput = document.querySelector('.filter-input[name="q"]');
    const filterForm = document.querySelector('.admin-filters-form');

    // Auto-submit on status change
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }

    // Auto-submit on search with debounce
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                filterForm.submit();
            }, 500);
        });
    }
});
</script>

<style>
.admin-orders-section {
    padding: 0;
}

.admin-section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.admin-section-header-content h1 {
    margin: 0 0 0.25rem;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text);
}

.admin-section-header-content p {
    margin: 0;
    color: var(--text-muted);
}

.admin-card {
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.admin-filters-form {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    align-items: center;
    padding: 1.25rem;
    border-bottom: 1px solid var(--border);
    background: var(--bg);
}

.filter-search {
    flex: 1 1 280px;
    max-width: 360px;
}

.filter-input {
    width: 100%;
}

.filter-select-wrap {
    flex: 0 1 180px;
}

.filter-select {
    width: 100%;
}

.table-responsive {
    overflow-x: auto;
}

.admin-table-orders {
    margin-top: 0;
}

.admin-table-orders th:first-child,
.admin-table-orders td:first-child {
    padding-left: 1.5rem;
}

.admin-table-orders th:last-child,
.admin-table-orders td:last-child {
    padding-right: 1.5rem;
}

.order-number {
    font-weight: 600;
    color: var(--primary);
    font-family: 'SF Mono', Monaco, monospace;
    font-size: 0.9rem;
}

.customer-info {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.customer-name {
    font-weight: 500;
    color: var(--text);
}

.customer-email {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.order-total {
    font-weight: 600;
    color: var(--primary);
}

.order-date {
    color: var(--text-muted);
    font-size: 0.9rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.75rem;
    border-radius: 999px;
    font-size: 0.8rem;
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

.text-right {
    text-align: right;
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--border);
    color: var(--text);
}

.btn-outline:hover {
    background: var(--accent-soft);
    border-color: var(--accent);
    color: var(--accent);
}

.empty-state {
    padding: 4rem 2rem;
    text-align: center;
    color: var(--text-muted);
}

.empty-state svg {
    margin-bottom: 1rem;
    opacity: 0.4;
}

.empty-state h3 {
    margin: 0 0 0.5rem;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text);
}

.empty-state p {
    margin: 0;
    font-size: 0.95rem;
}

@media (max-width: 768px) {
    .admin-section-header {
        flex-direction: column;
        align-items: stretch;
    }

    .admin-filters-form {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-search {
        max-width: none;
    }

    .filter-select-wrap {
        flex: 1;
    }
}
</style>