<?php require __DIR__ . '/../helpers.php'; ?>
<section class="cart-section">
    <!-- Cart Header -->
    <div class="cart-header">
        <div class="cart-header-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
        </div>
        <div class="cart-header-content">
            <h1>Your Cart</h1>
            <span class="cart-count"><?= count($items) ?> <?= count($items) === 1 ? 'item' : 'items' ?></span>
        </div>
    </div>

    <?php if (empty($items)): ?>
        <!-- Empty Cart State -->
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
            </div>
            <h2>Your cart is empty</h2>
            <p>Looks like you haven't added anything to your cart yet.</p>
            <a href="<?= base_url() ?>" class="btn a btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span class="span">Continue Shopping</span>
            </a>
        </div>
    <?php else: ?>
        <!-- Cart Table -->
        <div class="cart-table-wrapper">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th class="product-col">Product</th>
                        <th>Price</th>
                        <th class="qty-col">Quantity</th>
                        <th class="subtotal-col">Subtotal</th>
                        <th class="action-col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item):
                        $qty = min((int) $item['quantity'], (int) $item['stock']);
                        $subtotal = $item['price'] * $qty;
                        ?>
                        <tr>
                            <td data-label="Product">
                                <div class="product-cell">
                                    <img src="<?= $item['image'] ? asset('images/products/' . $item['image']) : placeholder_image() ?>"
                                        alt="<?= e($item['name']) ?>" class="cart-thumb"
                                        onerror="this.src='<?= placeholder_image() ?>'">
                                    <div class="product-info">
                                        <strong><?= e($item['name']) ?></strong>
                                        <span
                                            class="product-stock-info"><?= $item['stock'] > 0 ? 'In Stock' : 'Out of Stock' ?></span>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Price">
                                <span class="price">$<?= number_format($item['price'], 2) ?></span>
                            </td>
                            <td data-label="Quantity">
                                <form method="post" action="<?= base_url('cart/update') ?>" class="cart-qty-form">
                                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                    <div class="qty-controls">
                                        <button type="button" class="qty-btn qty-decrease"
                                            onclick="updateQty(this, -1)">−</button>
                                        <input type="number" name="quantity" value="<?= $qty ?>" min="1"
                                            max="<?= $item['stock'] ?>" class="qty-input" readonly>
                                        <button type="button" class="qty-btn qty-increase"
                                            onclick="updateQty(this, 1)">+</button>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-ghost">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                        </svg>
                                        Update
                                    </button>
                                </form>
                            </td>
                            <td data-label="Subtotal">
                                <span class="subtotal">$<?= number_format($subtotal, 2) ?></span>
                            </td>
                            <td data-label="">
                                <form method="post" action="<?= base_url('cart/remove') ?>" class="remove-form"
                                    data-product-id="<?= $item['product_id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                    <button type="button" class="btn-remove btn-remove-item" title="Remove item"
                                        data-product-id="<?= $item['product_id'] ?>"
                                        data-product-name="<?= e($item['name']) ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path
                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                            </path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Cart Summary -->
        <div class="cart-summary">
            <div class="cart-summary-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
                <h2>Order Summary</h2>
            </div>
            <div class="cart-summary-details">
                <div class="summary-row">
                    <span>Subtotal (<?= count($items) ?> items)</span>
                    <span>$<?= number_format($total, 2) ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span class="shipping-free">FREE</span>
                </div>
                <div class="summary-row total-row">
                    <span>Total</span>
                    <span class="total-amount">$<?= number_format($total, 2) ?></span>
                </div>
            </div>
            <a href="<?= base_url('cart/checkout') ?>" class="btn btn-primary checkout-btn">
                Proceed to Checkout
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
            <a href="<?= base_url() ?>" class="continue-shopping-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Continue Shopping
            </a>
        </div>
    <?php endif; ?>
</section>

<style>
    /* Cart Header */
    .cart-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border);
    }

    .cart-header-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, var(--accent), var(--accent-hover));
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        box-shadow: 0 8px 24px rgba(255, 107, 53, 0.2);
    }

    .cart-header-content h1 {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text);
    }

    .cart-count {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
        display: block;
    }

    .a {
        color: white !important;
    }

    /* Empty Cart */
    .empty-cart {
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

    .empty-cart-icon {
        color: var(--text-muted);
        opacity: 0.4;
        margin-bottom: 1.5rem;
    }

    .empty-cart h2 {
        margin: 0 0 0.75rem;
        font-size: 1.35rem;
        font-weight: 600;
        color: var(--text);
    }

    .empty-cart p {
        margin: 0 0 2rem;
        color: var(--text-muted);
        font-size: 1rem;
    }

    .empty-cart .btn {
        padding: 0.85rem 2rem;
        font-size: 1rem;
    }

    /* Cart Table Wrapper */
    .cart-table-wrapper {
        background: var(--bg-card);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        animation: fadeInUp 0.4s ease;
    }

    .cart-table {
        width: 100%;
        border-collapse: collapse;
    }

    .cart-table th {
        background: linear-gradient(180deg, var(--primary), var(--primary-light));
        color: #fff;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 1rem 1.25rem;
        text-align: left;
    }

    .cart-table th.product-col {
        width: 42%;
    }

    .cart-table th.qty-col {
        width: 200px;
        text-align: center;
    }

    .cart-table th.subtotal-col {
        width: 140px;
    }

    .cart-table th.action-col {
        width: 80px;
        text-align: center;
    }

    .cart-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.2s ease;
    }

    .cart-table tbody tr:hover {
        background: rgba(255, 107, 53, 0.02);
    }

    .cart-table tbody tr:last-child {
        border-bottom: none;
    }

    .cart-table td {
        padding: 1.25rem;
        vertical-align: middle;
    }

    .cart-table td[data-label="Quantity"] {
        text-align: center;
    }

    .cart-table td[data-label]:last-child {
        text-align: center;
    }

    /* Product Cell */
    .product-cell {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .cart-thumb {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: var(--radius-sm);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        flex-shrink: 0;
    }

    .product-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .product-info strong {
        font-size: 1rem;
        color: var(--text);
        line-height: 1.4;
    }

    .product-stock-info {
        font-size: 0.8rem;
        color: var(--success);
    }

    /* Price & Subtotal */
    .price,
    .subtotal {
        font-weight: 600;
        color: var(--text);
        font-size: 0.95rem;
    }

    /* Quantity Controls */
    .cart-qty-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .qty-controls {
        display: flex;
        align-items: center;
        gap: 0;
        background: var(--bg);
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: transparent;
        color: var(--text);
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .qty-btn:hover {
        background: var(--accent-soft);
        color: var(--accent);
    }

    .qty-btn:active {
        background: var(--accent);
        color: #fff;
    }

    .qty-input {
        width: 48px;
        height: 32px;
        border: none;
        border-left: 1px solid var(--border);
        border-right: 1px solid var(--border);
        text-align: center;
        font-size: 0.9rem;
        font-weight: 600;
        background: var(--bg);
        color: var(--text);
        appearance: textfield;
        margin: 0;
        -moz-appearance: textfield;
    }

    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .cart-qty-form .btn-ghost {
        font-size: 0.8rem;
        padding: 0.35rem 0.75rem;
        opacity: 0;
        transform: translateY(-4px);
        transition: all 0.2s ease;
    }

    .cart-qty-form:hover .btn-ghost,
    .qty-input:focus+.btn-ghost {
        opacity: 1;
        transform: translateY(0);
    }

    /* Remove Button */
    .remove-form {
        display: inline-block;
    }

    .btn-remove {
        width: 36px;
        height: 36px;
        border: none;
        background: transparent;
        color: var(--text-muted);
        cursor: pointer;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .btn-remove:hover {
        background: var(--error-bg);
        color: var(--error);
        transform: scale(1.05);
    }

    .btn-remove:active {
        transform: scale(0.95);
    }

    /* Cart Summary */
    .cart-summary {
        margin-top: 1.5rem;
        background: var(--bg-card);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 1.5rem;
        animation: fadeInUp 0.4s ease;
    }

    .cart-summary-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .cart-summary-header svg {
        color: var(--accent);
    }

    .cart-summary-header h2 {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--text);
    }

    .cart-summary-details {
        margin-bottom: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.65rem 0;
        font-size: 0.95rem;
        color: var(--text-muted);
    }

    .summary-row.total-row {
        padding-top: 1rem;
        margin-top: 0.5rem;
        border-top: 2px solid var(--border);
        font-weight: 600;
    }

    .shipping-free {
        color: var(--success);
        font-weight: 500;
    }

    .total-amount {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--accent);
    }

    .checkout-btn {
        width: 100%;
        padding: 0.9rem 1.5rem;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .continue-shopping-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.2s ease;
    }

    .continue-shopping-link:hover {
        color: var(--accent);
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .cart-header {
            flex-direction: column;
            text-align: center;
        }

        .cart-table-wrapper {
            overflow-x: auto;
        }

        .cart-table {
            min-width: 600px;
        }

        .cart-table thead {
            display: none;
        }

        .cart-table tr {
            display: block;
            border-bottom: 1px solid var(--border);
            padding: 1rem;
        }

        .cart-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border: none;
        }

        .cart-table td::before {
            content: attr(data-label);
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .cart-table td[data-label="Product"] {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding-bottom: 1rem;
            margin-bottom: 0.5rem;
            border-bottom: 1px solid var(--border);
        }

        .cart-table td[data-label="Product"]::before {
            display: none;
        }

        .cart-qty-form {
            flex-direction: row;
            justify-content: flex-end;
        }

        .cart-qty-form .btn-ghost {
            opacity: 1;
            transform: translateY(0);
        }

        .cart-summary {
            padding: 1.25rem;
        }

        .empty-cart {
            padding: 3rem 1.5rem;
        }
    }
</style>

<script>
    function updateQty(btn, delta) {
        const input = btn.parentElement.querySelector('.qty-input');
        const currentVal = parseInt(input.value) || 1;
        const min = parseInt(input.min);
        const max = parseInt(input.max);
        const newVal = Math.max(min, Math.min(max, currentVal + delta));
        input.value = newVal;
    }

    // Remove item modal functionality
    document.addEventListener('DOMContentLoaded', function () {
        const removeModal = document.getElementById('removeItemModal');
        const removeForm = document.getElementById('removeItemForm');
        const modalProductName = document.getElementById('modalProductName');
        const modalProductImage = document.getElementById('modalProductImage');
        const modalProductPrice = document.getElementById('modalProductPrice');

        // Open modal when remove button is clicked
        document.querySelectorAll('.btn-remove-item').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const productId = this.dataset.productId;
                const productName = this.dataset.productName;
                const form = this.closest('form');
                const price = form.closest('tr').querySelector('.price').textContent;
                const img = form.closest('tr').querySelector('.cart-thumb').src;

                // Update modal content
                modalProductName.textContent = productName;
                modalProductPrice.textContent = price;
                modalProductImage.src = img;
                modalProductImage.onerror = function () {
                    this.src = '<?= placeholder_image() ?>';
                };

                // Store the form reference for submission
                removeModal.dataset.targetForm = productId;

                // Show modal
                removeModal.classList.add('active');
            });
        });

        // Confirm removal
        document.getElementById('confirmRemoveBtn').addEventListener('click', function () {
            // Find the form with the matching product ID
            const productId = removeModal.dataset.targetForm;
            const form = document.querySelector(`.remove-form[data-product-id="${productId}"]`);
            if (form) {
                form.submit();
            }
            removeModal.classList.remove('active');
        });

        // Cancel/close modal
        document.getElementById('cancelRemoveBtn').addEventListener('click', function () {
            removeModal.classList.remove('active');
        });

        // Close modal on close button click
        removeModal.querySelector('.modal-close').addEventListener('click', function () {
            removeModal.classList.remove('active');
        });

        // Close modal on overlay click
        removeModal.addEventListener('click', function (e) {
            if (e.target === removeModal) {
                removeModal.classList.remove('active');
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && removeModal.classList.contains('active')) {
                removeModal.classList.remove('active');
            }
        });
    });
</script>

<!-- Remove Item Modal -->
<div class="modal-overlay" id="removeItemModal">
    <div class="modal">
        <div class="modal-header">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    style="margin-right: 8px; color: var(--error);">
                    <path d="M3 6h18"></path>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
                Remove Item
            </h3>
            <button type="button" class="modal-close" aria-label="Close modal">&times;</button>
        </div>
        <div class="modal-body">
            <p style="margin-bottom: 1rem;">Are you sure you want to remove this item from your cart?</p>
            <div
                style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem; background: var(--bg); border-radius: var(--radius-sm);">
                <img id="modalProductImage" src="" alt=""
                    style="width: 56px; height: 56px; object-fit: cover; border-radius: var(--radius-sm);">
                <div>
                    <strong id="modalProductName" style="display: block; font-size: 0.95rem;"></strong>
                    <span id="modalProductPrice" style="color: var(--accent); font-weight: 600;"></span>
                </div>
            </div>
            <p style="margin-top: 1rem; font-size: 0.9rem; color: var(--text-muted);">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn modal-cancel" id="cancelRemoveBtn">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmRemoveBtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    style="margin-right: 4px;">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                Remove
            </button>
        </div>
    </div>
</div>