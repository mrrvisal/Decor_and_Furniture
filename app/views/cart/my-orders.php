<?php require __DIR__ . '/../helpers.php'; ?>
<section class="my-orders-section container">
    <!-- Orders Header -->
    <div class="orders-header">
        <div class="orders-header-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </div>
        <div class="orders-header-content">
            <h1>My Orders</h1>
            <span class="orders-count"><?= empty($orders) ? '0' : count($orders) ?>
                <?= empty($orders) ? '' : (count($orders) === 1 ? 'order' : 'orders') ?></span>
        </div>
    </div>

    <?php if (empty($orders)): ?>
    <!-- Empty Orders State -->
    <div class="empty-orders">
        <div class="empty-orders-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </div>
        <h2>You have no orders yet</h2>
        <p>Once you place an order, it will appear here.</p>
        <a href="<?= base_url() ?>" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            Start Shopping
        </a>
    </div>
    <?php else: ?>
    <!-- Orders Table -->
    <div class="orders-table-wrapper">
        <table class="orders-table">
            <thead>
                <tr>
                    <th class="order-col">Order #</th>
                    <th class="date-col">Date</th>
                    <th class="total-col">Total</th>
                    <th class="status-col">Status</th>
                    <th class="action-col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <td data-label="Order #">
                        <span class="order-number"><?= e($o['order_number']) ?></span>
                    </td>
                    <td data-label="Date">
                        <span class="order-date">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <?= date('M j, Y', strtotime($o['created_at'])) ?>
                        </span>
                    </td>
                    <td data-label="Total">
                        <span class="order-total">$<?= number_format($o['total_amount'], 2) ?></span>
                    </td>
                    <td data-label="Status">
                        <span class="status status-<?= e($o['status']) ?>">
                            <?php
                            $statusText = ucfirst(str_replace('_', ' ', $o['status']));
                            $statusIcons = [
                                'Pending' => '⏳',
                                'Waiting payment' => '💳',
                                'Paid' => '✅',
                                'Processing' => '⚙️',
                                'Shipped' => '📦',
                                'Delivered' => '🎉',
                                'Cancelled' => '❌'
                            ];
                            $icon = $statusIcons[$statusText] ?? '';
                            echo $icon . ' ' . e($statusText);
                            ?>
                        </span>
                    </td>
                    <td data-label="">
                        <a href="<?= base_url('order/' . $o['order_number']) ?>" class="btn btn-sm btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            View Details
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</section>

<style>
/* Orders Header */
.orders-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border);
}

.orders-header-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    box-shadow: 0 8px 24px rgba(11, 18, 32, 0.15);
}

.orders-header-content h1 {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text);
}

.orders-count {
    font-size: 0.9rem;
    color: var(--text-muted);
    margin-top: 0.25rem;
    display: block;
}

/* Empty Orders State */
.empty-orders {
    background: var(--bg-card);
    padding: 4rem 3rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    text-align: center;
    animation: fadeInUp 0.4s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.empty-orders-icon {
    color: var(--text-muted);
    opacity: 0.4;
    margin-bottom: 1.5rem;
}

.empty-orders h2 {
    margin: 0 0 0.75rem;
    font-size: 1.35rem;
    font-weight: 600;
    color: var(--text);
}

.empty-orders p {
    margin: 0 0 2rem;
    color: var(--text-muted);
    font-size: 1rem;
}

.empty-orders .btn {
    padding: 0.85rem 2rem;
    font-size: 1rem;
}

/* Orders Table Wrapper */
.orders-table-wrapper {
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    animation: fadeInUp 0.4s ease;
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
}

.orders-table th {
    background: linear-gradient(180deg, var(--primary), var(--primary-light));
    color: #fff;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    padding: 1rem 1.25rem;
    text-align: left;
}

.orders-table th.order-col {
    width: 160px;
}

.orders-table th.date-col {
    width: 140px;
}

.orders-table th.total-col {
    width: 140px;
}

.orders-table th.status-col {
    width: 180px;
}

.orders-table th.action-col {
    width: 160px;
    text-align: center;
}

.orders-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background 0.2s ease;
}

.orders-table tbody tr:hover {
    background: rgba(255, 107, 53, 0.02);
}

.orders-table tbody tr:last-child {
    border-bottom: none;
}

.orders-table td {
    padding: 1.25rem;
    vertical-align: middle;
}

.orders-table td[data-label]:last-child {
    text-align: center;
}

/* Order Cell Styles */
.order-number {
    font-weight: 600;
    color: var(--accent);
    font-family: monospace;
    font-size: 0.95rem;
}

.order-date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text);
    font-size: 0.95rem;
}

.order-date svg {
    color: var(--text-muted);
}

.order-total {
    font-weight: 600;
    color: var(--text);
    font-size: 0.95rem;
}

.orders-table .status {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.85rem;
}

.orders-table .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .orders-header {
        flex-direction: column;
        text-align: center;
    }

    .orders-table-wrapper {
        overflow-x: auto;
    }

    .orders-table {
        min-width: 600px;
    }

    .orders-table thead {
        display: none;
    }

    .orders-table tr {
        display: block;
        border-bottom: 1px solid var(--border);
        padding: 1rem;
    }

    .orders-table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border: none;
    }

    .orders-table td::before {
        content: attr(data-label);
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .orders-table td[data-label="Order #"] {
        font-weight: 600;
    }

    .empty-orders {
        padding: 3rem 1.5rem;
    }
}
</style>