<?php require __DIR__ . '/../helpers.php'; ?>
<section class="order-detail-section container">
    <!-- Order Header -->
    <div class="order-detail-header">
        <a href="<?= base_url('order/my-orders') ?>" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Orders
        </a>
        <div class="order-header-content">
            <div class="order-header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
            </div>
            <div class="order-header-info">
                <h1>Order <?= e($order['order_number']) ?></h1>
                <div class="order-meta">
                    <span class="order-status status status-<?= e($order['status']) ?>">
                        <?php
                        $statusText = ucfirst(str_replace('_', ' ', $order['status']));
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
                    <span class="separator">·</span>
                    <span class="order-date">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <?= date('M j, Y H:i', strtotime($order['created_at'])) ?>
                    </span>
                    <span class="separator">·</span>
                    <span class="payment-method">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <?= e(ucfirst(str_replace('_', ' ', $order['payment_method']))) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="order-detail-grid">
        <!-- Shipping Info Card -->
        <div class="order-detail-card shipping-card">
            <div class="card-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <h2>Shipping Address</h2>
            </div>
            <div class="card-body">
                <p class="shipping-name"><?= e($order['shipping_name']) ?></p>
                <p class="shipping-contact">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <?= e($order['shipping_email']) ?>
                </p>
                <p class="shipping-contact">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                        </path>
                    </svg>
                    <?= e($order['shipping_phone']) ?>
                </p>
                <p class="shipping-address"><?= nl2br(e($order['shipping_address'])) ?></p>
                <p class="shipping-city"><?= e($order['shipping_city']) ?> <?= e($order['shipping_postcode']) ?></p>
            </div>
        </div>

        <!-- Order Items Card -->
        <div class="order-detail-card items-card">
            <div class="card-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <h2>Order Items</h2>
            </div>
            <div class="card-body">
                <table class="order-items-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $i): ?>
                        <tr>
                            <td class="item-name"><?= e($i['product_name']) ?></td>
                            <td class="item-price">$<?= number_format($i['price'], 2) ?></td>
                            <td class="item-qty"><?= $i['quantity'] ?></td>
                            <td class="item-subtotal">$<?= number_format($i['subtotal'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="order-total-section">
                    <span class="total-label">Total</span>
                    <span class="total-amount">$<?= number_format($order['total_amount'], 2) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Timeline -->
    <div class="order-timeline">
        <div class="timeline-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <h2>Order Timeline</h2>
        </div>
        <div class="timeline-steps">
            <div class="timeline-step completed">
                <div class="step-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <div class="step-content">
                    <span class="step-title">Order Placed</span>
                    <span class="step-date"><?= date('M j, Y H:i', strtotime($order['created_at'])) ?></span>
                </div>
            </div>
            <div
                class="timeline-step <?= in_array($order['status'], ['paid', 'processing', 'shipped', 'delivered']) ? 'completed' : '' ?>">
                <div class="step-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <div class="step-content">
                    <span class="step-title">Payment Confirmed</span>
                </div>
            </div>
            <div
                class="timeline-step <?= in_array($order['status'], ['processing', 'shipped', 'delivered']) ? 'completed' : '' ?>">
                <div class="step-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <div class="step-content">
                    <span class="step-title">Processing</span>
                </div>
            </div>
            <div class="timeline-step <?= in_array($order['status'], ['shipped', 'delivered']) ? 'completed' : '' ?>">
                <div class="step-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <div class="step-content">
                    <span class="step-title">Shipped</span>
                </div>
            </div>
            <div class="timeline-step <?= $order['status'] === 'delivered' ? 'completed' : '' ?>">
                <div class="step-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <div class="step-content">
                    <span class="step-title">Delivered</span>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Order Detail Header */
.order-detail-header {
    margin-bottom: 2rem;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-muted);
    text-decoration: none;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    transition: color 0.2s ease;
}

.back-link:hover {
    color: var(--accent);
}

.order-header-content {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border);
}

.order-header-icon {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, var(--accent), var(--accent-hover));
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    box-shadow: 0 8px 24px rgba(255, 107, 53, 0.2);
}

.order-header-info h1 {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text);
}

.order-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.5rem;
    font-size: 0.9rem;
}

.order-meta .separator {
    color: var(--border);
}

.order-status {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}

.order-date,
.payment-method {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    color: var(--text-muted);
}

.order-date svg,
.payment-method svg {
    opacity: 0.7;
}

/* Order Detail Grid */
.order-detail-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

@media (max-width: 900px) {
    .order-detail-grid {
        grid-template-columns: 1fr;
    }
}

/* Order Detail Card */
.order-detail-card {
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
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

.card-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border);
    background: linear-gradient(180deg, var(--primary), var(--primary-light));
    color: #fff;
}

.card-header svg {
    opacity: 0.9;
}

.card-header h2 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.card-body {
    padding: 1.5rem;
}

/* Shipping Card */
.shipping-name {
    font-weight: 600;
    font-size: 1.05rem;
    margin: 0 0 0.75rem;
    color: var(--text);
}

.shipping-contact {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0.35rem 0;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.shipping-contact svg {
    opacity: 0.6;
    flex-shrink: 0;
}

.shipping-address {
    margin: 1rem 0 0.35rem;
    color: var(--text);
    line-height: 1.5;
}

.shipping-city {
    color: var(--text-muted);
    font-size: 0.9rem;
}

/* Order Items Table */
.order-items-table {
    width: 100%;
    border-collapse: collapse;
}

.order-items-table th {
    background: var(--bg);
    color: var(--text-muted);
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    padding: 0.75rem 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border);
}

.order-items-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}

.order-items-table tbody tr:last-child td {
    border-bottom: none;
}

.order-items-table tbody tr:hover {
    background: rgba(255, 107, 53, 0.02);
}

.item-name {
    font-weight: 500;
    color: var(--text);
}

.item-price,
.item-qty,
.item-subtotal {
    color: var(--text-muted);
    font-size: 0.9rem;
}

.item-subtotal {
    font-weight: 600;
    color: var(--text);
}

/* Order Total Section */
.order-total-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.25rem;
    padding-top: 1rem;
    border-top: 2px solid var(--border);
}

.total-label {
    font-weight: 600;
    font-size: 1.1rem;
    color: var(--text);
}

.total-amount {
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--accent);
}

/* Order Timeline */
.order-timeline {
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 1.5rem;
    animation: fadeInUp 0.4s ease;
}

.timeline-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.timeline-header svg {
    color: var(--accent);
}

.timeline-header h2 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text);
}

.timeline-steps {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.timeline-step {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding-bottom: 1.5rem;
    position: relative;
}

.timeline-step:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 15px;
    top: 32px;
    width: 2px;
    height: calc(100% - 16px);
    background: var(--border);
}

.timeline-step.completed::after {
    background: var(--success);
}

.step-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--bg);
    border: 2px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    z-index: 1;
    color: var(--text-muted);
}

.timeline-step.completed .step-icon {
    background: var(--success);
    border-color: var(--success);
    color: #fff;
}

.step-content {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.step-title {
    font-weight: 500;
    color: var(--text);
}

.step-date {
    font-size: 0.85rem;
    color: var(--text-muted);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .order-header-content {
        flex-direction: column;
        text-align: center;
    }

    .order-meta {
        justify-content: center;
    }

    .order-detail-grid {
        gap: 1rem;
    }

    .order-timeline {
        padding: 1.25rem;
    }
}
</style>