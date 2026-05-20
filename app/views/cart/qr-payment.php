<?php require __DIR__ . '/../helpers.php'; ?>
<section class="qr-payment-section container">
    <!-- Payment Header -->
    <div class="qr-payment-header">
        <div class="qr-header-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
        </div>
        <div class="qr-header-content">
            <h1>Pay with QR Code</h1>
            <span class="qr-subtitle">Quick and secure payment</span>
        </div>
    </div>

    <!-- Order Info Card -->
    <div class="qr-order-card">
        <div class="order-info-row">
            <span class="info-label">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                Order
            </span>
            <span class="order-number"><?= e($order['order_number']) ?></span>
        </div>
        <div class="order-info-row">
            <span class="info-label">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                Amount
            </span>
            <span class="order-amount">$<?= number_format($order['total_amount'], 2) ?></span>
        </div>
        <div class="order-info-row status-row">
            <span class="info-label">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                Status
            </span>
            <span class="qr-status status status-<?= e($order['status']) ?>">
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
        </div>
    </div>

    <!-- QR Code Section -->
    <div class="qr-code-card">
        <div class="qr-card-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            <h2>Scan to Pay</h2>
        </div>
        <div class="qr-code-wrap" id="qr-code-wrap">
            <div class="qr-placeholder" id="qr-placeholder">
                <div class="qr-loading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                </div>
                <p class="scan-text">Scan this QR code</p>
                <p class="qr-data-text"><?= e($qr_data ?? '') ?></p>
            </div>
        </div>
        <div class="qr-instructions">
            <div class="instruction-step">
                <span class="step-number">1</span>
                <span>Open your payment app</span>
            </div>
            <div class="instruction-step">
                <span class="step-number">2</span>
                <span>Scan the QR code above</span>
            </div>
            <div class="instruction-step">
                <span class="step-number">3</span>
                <span>Complete the payment</span>
            </div>
        </div>
    </div>

    <!-- Info Notice -->
    <div class="qr-notice">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
        </svg>
        <p>After payment, the admin will confirm and update your order status. Please keep your payment receipt.</p>
    </div>

    <!-- Actions -->
    <div class="qr-actions">
        <a href="<?= base_url('order/my-orders') ?>" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            My Orders
        </a>
        <button type="button" class="btn btn-primary" onclick="window.print()">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Print Receipt
        </button>
    </div>
</section>
<script src="<?= asset('js/qr.js') ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var data = <?= json_encode($qr_data ?? '') ?>;
        var el = document.getElementById('qr-placeholder');
        if (typeof renderQR === 'function' && data) renderQR(el, data);
    });
</script>

<style>
    /* QR Payment Header */
    .qr-payment-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border);
    }

    .qr-header-icon {
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

    .qr-header-content h1 {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text);
    }

    .qr-subtitle {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
        display: block;
    }

    /* Order Info Card */
    .qr-order-card {
        background: var(--bg-card);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
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

    .order-info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border);
    }

    .order-info-row:last-child {
        border-bottom: none;
    }

    .order-info-row.status-row {
        padding-bottom: 0;
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .info-label svg {
        opacity: 0.7;
    }

    .order-number {
        font-weight: 600;
        font-family: monospace;
        color: var(--accent);
        font-size: 0.95rem;
    }

    .order-amount {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--text);
    }

    .qr-status {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* QR Code Card */
    .qr-code-card {
        background: var(--bg-card);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 1.5rem;
        animation: fadeInUp 0.4s ease;
    }

    .qr-card-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1.25rem;
        background: linear-gradient(180deg, var(--primary), var(--primary-light));
        color: #fff;
    }

    .qr-card-header svg {
        opacity: 0.9;
    }

    .qr-card-header h2 {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 600;
    }

    .qr-code-wrap {
        padding: 2rem;
        text-align: center;
    }

    .qr-placeholder {
        display: inline-block;
        padding: 2rem;
        background: var(--bg);
        border-radius: var(--radius);
        border: 2px dashed var(--border);
    }

    .qr-loading {
        color: var(--text-muted);
        opacity: 0.4;
        margin-bottom: 1rem;
    }

    .scan-text {
        margin: 0 0 0.75rem;
        font-weight: 500;
        color: var(--text);
    }

    .qr-data-text {
        margin: 0;
        font-size: 0.75rem;
        font-family: monospace;
        color: var(--text-muted);
        word-break: break-all;
        max-width: 250px;
    }

    /* QR Instructions */
    .qr-instructions {
        display: flex;
        justify-content: center;
        gap: 2rem;
        padding: 1.25rem;
        background: var(--bg);
        border-top: 1px solid var(--border);
    }

    .instruction-step {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .step-number {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--accent);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* QR Notice */
    .qr-notice {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        background: var(--accent-soft);
        border-radius: var(--radius-sm);
        margin-bottom: 1.5rem;
    }

    .qr-notice svg {
        color: var(--accent);
        flex-shrink: 0;
        margin-top: 0.15rem;
    }

    .qr-notice p {
        margin: 0;
        font-size: 0.9rem;
        color: var(--text);
        line-height: 1.5;
    }

    /* QR Actions */
    .qr-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .qr-actions .btn {
        min-width: 160px;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .qr-payment-header {
            flex-direction: column;
            text-align: center;
        }

        .qr-instructions {
            flex-direction: column;
            gap: 0.75rem;
            align-items: flex-start;
            padding-left: 1.5rem;
        }

        .qr-actions {
            flex-direction: column;
        }

        .qr-actions .btn {
            width: 100%;
        }
    }
</style>