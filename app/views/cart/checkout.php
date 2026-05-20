<?php require __DIR__ . '/../helpers.php'; ?>
<section class="checkout-section">
    <h1 style="margin: 0 0 1.5rem; font-size: 1.75rem; font-weight: 700;">Checkout</h1>
    <div class="checkout-grid">
        <div class="checkout-form-wrap">
            <form method="post" action="<?= base_url('order/place') ?>" class="needs-validation" novalidate>
                <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                    <span style="font-size: 1.5rem;">📦</span>
                    <h2 style="margin: 0; font-size: 1.15rem; font-weight: 600;">Shipping Address</h2>
                </div>
                <div class="form-row">
                    <div class="form-group" style="flex: 1 1 220px;">
                        <label for="shipping_name">Full Name *</label>
                        <input type="text" id="shipping_name" name="shipping_name" required
                            value="<?= e($_SESSION['user_name'] ?? '') ?>" placeholder="Your full name">
                        <div class="invalid-feedback">Please enter your full name</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="flex: 1 1 220px;">
                        <label for="shipping_email">Email *</label>
                        <input type="email" id="shipping_email" name="shipping_email" required
                            value="<?= e($_SESSION['user_email'] ?? '') ?>" placeholder="you@example.com">
                        <div class="invalid-feedback">Please enter a valid email address</div>
                    </div>
                    <div class="form-group" style="flex: 1 1 220px;">
                        <label for="shipping_phone">Phone *</label>
                        <input type="text" id="shipping_phone" name="shipping_phone" required
                            placeholder="Phone number">
                        <div class="invalid-feedback">Please enter your phone number</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="shipping_address">Address *</label>
                    <textarea id="shipping_address" name="shipping_address" required rows="3"
                        placeholder="Street address"></textarea>
                    <div class="invalid-feedback">Please enter your shipping address</div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="flex: 1 1 200px;">
                        <label for="shipping_city">City</label>
                        <input type="text" id="shipping_city" name="shipping_city" placeholder="City">
                    </div>
                    <div class="form-group" style="flex: 1 1 150px;">
                        <label for="shipping_postcode">Postcode</label>
                        <input type="text" id="shipping_postcode" name="shipping_postcode" placeholder="Postcode">
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes">Notes <span class="text-muted">(optional)</span></label>
                    <textarea id="notes" name="notes" rows="2" placeholder="Order notes (optional)"></textarea>
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem; margin: 1.5rem 0 1rem;">
                    <span style="font-size: 1.5rem;">💳</span>
                    <h2 style="margin: 0; font-size: 1.15rem; font-weight: 600;">Payment Method</h2>
                </div>
                <div class="payment-method-options">
                    <label class="payment-option"
                        style="padding: 0.5rem; border-radius: var(--radius-sm); transition: background 0.2s;">
                        <input type="radio" name="payment_method" value="qr_code">
                        <span style="margin-left: 0.5rem;">📱 QR Code Payment (pay now)</span>
                    </label>
                    <label class="payment-option"
                        style="padding: 0.5rem; border-radius: var(--radius-sm); transition: background 0.2s;">
                        <input type="radio" name="payment_method" value="pay_later" checked>
                        <span style="margin-left: 0.5rem;">🕐 Order now & Pay later</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-place-order"
                    style="padding: 0.9rem; font-size: 1.05rem;">✓ Place Order</button>
            </form>
        </div>
        <div class="checkout-summary">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                <span style="font-size: 1.25rem;">📋</span>
                <h2 style="margin: 0; font-size: 1.15rem; font-weight: 600;">Order Summary</h2>
            </div>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <?php foreach ($items as $item):
                    $qty = min((int) $item['quantity'], (int) $item['stock']);
                    $subtotal = $item['price'] * $qty;
                    ?>
                <li
                    style="padding: 0.65rem 0; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between;">
                    <span style="flex: 1;"><?= e($item['name']) ?> × <?= $qty ?></span>
                    <span style="font-weight: 500; margin-left: 1rem;">$<?= number_format($subtotal, 2) ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
            <p class="order-total" style="border: none;">
                <strong>Total: $<?= number_format($total, 2) ?></strong>
            </p>
        </div>
    </div>
</section>