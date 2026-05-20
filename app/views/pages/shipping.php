<?php require __DIR__ . '/../helpers.php'; ?>
<section class="shipping-section container">
    <div class="page-hero">
        <div class="page-hero-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="3" width="15" height="13"></rect>
                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                <circle cx="18.5" cy="18.5" r="2.5"></circle>
            </svg>
        </div>
        <div class="page-hero-content">
            <h1 class="page-title">Shipping Information</h1>
            <p class="page-subtitle">Everything you need to know about delivery options, timeframes, and costs.</p>
        </div>
    </div>

    <div class="shipping-grid">
        <div class="shipping-card">
            <div class="shipping-icon local">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
            </div>
            <h3>Phnom Penh</h3>
            <div class="shipping-details">
                <div class="shipping-row">
                    <span>Delivery Time</span>
                    <strong>1-2 business days</strong>
                </div>
                <div class="shipping-row">
                    <span>Shipping Fee</span>
                    <strong class="free">FREE</strong>
                </div>
                <div class="shipping-row">
                    <span>Order Over</span>
                    <strong>$50</strong>
                </div>
            </div>
            <p class="shipping-note">Free delivery for orders above $50</p>
        </div>

        <div class="shipping-card">
            <div class="shipping-icon province">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                </svg>
            </div>
            <h3>Provinces</h3>
            <div class="shipping-details">
                <div class="shipping-row">
                    <span>Delivery Time</span>
                    <strong>3-5 business days</strong>
                </div>
                <div class="shipping-row">
                    <span>Shipping Fee</span>
                    <strong>$5</strong>
                </div>
                <div class="shipping-row">
                    <span>Order Over</span>
                    <strong>$100</strong>
                </div>
            </div>
            <p class="shipping-note">Free delivery for orders above $100</p>
        </div>

        <div class="shipping-card">
            <div class="shipping-icon international">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="2" y1="12" x2="22" y2="12"></line>
                    <path
                        d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                    </path>
                </svg>
            </div>
            <h3>International</h3>
            <div class="shipping-details">
                <div class="shipping-row">
                    <span>Delivery Time</span>
                    <strong>7-14 business days</strong>
                </div>
                <div class="shipping-row">
                    <span>Shipping Fee</span>
                    <strong>Calculated at checkout</strong>
                </div>
                <div class="shipping-row">
                    <span>Countries</span>
                    <strong>Worldwide</strong>
                </div>
            </div>
            <p class="shipping-note">Duties and taxes may apply</p>
        </div>
    </div>

    <div class="shipping-process">
        <h2>Shipping Process</h2>
        <div class="process-steps">
            <div class="process-step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h4>Order Confirmation</h4>
                    <p>You'll receive an email confirmation once your order is placed.</p>
                </div>
            </div>
            <div class="process-step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h4>Processing</h4>
                    <p>We process your order within 24-48 hours (excluding weekends).</p>
                </div>
            </div>
            <div class="process-step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h4>Shipped</h4>
                    <p>You'll receive tracking info via email and SMS.</p>
                </div>
            </div>
            <div class="process-step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h4>Delivered</h4>
                    <p>Our delivery team will contact you to schedule delivery.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="shipping-info-cards">
        <div class="info-card">
            <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div class="info-content">
                <h4>Tracking Your Order</h4>
                <p>Track your order anytime by logging into your account or using the tracking link sent to your email.
                </p>
            </div>
        </div>

        <div class="info-card">
            <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <div class="info-content">
                <h4>Secure Delivery</h4>
                <p>All shipments are insured. Signature may be required upon delivery for security.</p>
            </div>
        </div>

        <div class="info-card">
            <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                </svg>
            </div>
            <div class="info-content">
                <h4>Delivery Assistance</h4>
                <p>Our team will help you bring items inside and set them up in your desired location.</p>
            </div>
        </div>

        <div class="info-card">
            <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                    <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                </svg>
            </div>
            <div class="info-content">
                <h4>Delivery Hours</h4>
                <p>Deliveries are available Monday-Saturday, 9:00 AM - 6:00 PM.</p>
            </div>
        </div>
    </div>

    <div class="shipping-cta">
        <h3>Have questions about shipping?</h3>
        <p>Our customer service team is ready to help you with any inquiries.</p>
        <a href="<?= base_url('contact') ?>" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                </path>
            </svg>
            Contact Us
        </a>
    </div>
</section>

<style>
/* Page Hero */
.page-hero {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 2.5rem 2rem;
    margin-bottom: 2.5rem;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 50%, #0f3460 100%);
    border-radius: var(--radius);
    position: relative;
    overflow: hidden;
}

.page-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.5;
}

.page-hero-icon {
    width: 72px;
    height: 72px;
    background: linear-gradient(135deg, var(--accent), var(--accent-hover));
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    box-shadow: 0 10px 30px rgba(255, 107, 53, 0.3);
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}

.page-hero-content {
    position: relative;
    z-index: 1;
    flex: 1;
}

.page-title {
    margin: 0 0 0.5rem;
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: -0.02em;
}

.page-subtitle {
    margin: 0;
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.5;
}

/* Shipping Section */
.shipping-section {
    padding: 0 0 4rem;
}

/* Shipping Grid */
.shipping-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 3rem;
}

@media (max-width: 900px) {
    .shipping-grid {
        grid-template-columns: 1fr;
    }
}

.shipping-card {
    background: var(--bg-card);
    border-radius: var(--radius);
    padding: 2rem;
    box-shadow: var(--shadow);
    text-align: center;
}

.shipping-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
}

.shipping-icon.local {
    background: rgba(255, 107, 53, 0.12);
    color: var(--accent);
}

.shipping-icon.province {
    background: rgba(14, 165, 233, 0.12);
    color: #0ea5e9;
}

.shipping-icon.international {
    background: rgba(139, 92, 246, 0.12);
    color: #8b5cf6;
}

.shipping-card h3 {
    margin: 0 0 1.25rem;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text);
}

.shipping-details {
    margin-bottom: 1rem;
}

.shipping-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border);
}

.shipping-row:last-child {
    border-bottom: none;
}

.shipping-row span {
    color: var(--text-muted);
    font-size: 0.9rem;
}

.shipping-row strong {
    font-weight: 600;
    color: var(--text);
}

.shipping-row strong.free {
    color: var(--success);
}

.shipping-note {
    margin: 0;
    font-size: 0.85rem;
    color: var(--text-muted);
    font-style: italic;
}

/* Shipping Process */
.shipping-process {
    margin-bottom: 3rem;
}

.shipping-process h2 {
    margin: 0 0 1.5rem;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text);
}

.process-steps {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    position: relative;
}

.process-steps::before {
    content: "";
    position: absolute;
    top: 28px;
    left: 56px;
    right: 56px;
    height: 2px;
    background: var(--border);
}

@media (max-width: 768px) {
    .process-steps {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .process-steps::before {
        display: none;
    }
}

.process-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
}

.step-number {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--accent), var(--accent-hover));
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1rem;
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
    position: relative;
    z-index: 1;
}

@media (max-width: 768px) {
    .process-step {
        flex-direction: row;
        text-align: left;
    }

    .step-number {
        margin-bottom: 0;
        margin-right: 1rem;
        width: 48px;
        height: 48px;
        font-size: 1rem;
    }
}

.step-content h4 {
    margin: 0 0 0.5rem;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text);
}

.step-content p {
    margin: 0;
    font-size: 0.9rem;
    color: var(--text-muted);
    line-height: 1.5;
}

/* Info Cards */
.shipping-info-cards {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 3rem;
}

@media (max-width: 768px) {
    .shipping-info-cards {
        grid-template-columns: 1fr;
    }
}

.info-card {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.info-icon {
    width: 48px;
    height: 48px;
    background: var(--accent-soft);
    color: var(--accent);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.info-content h4 {
    margin: 0 0 0.5rem;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text);
}

.info-content p {
    margin: 0;
    font-size: 0.9rem;
    color: var(--text-muted);
    line-height: 1.5;
}

/* Shipping CTA */
.shipping-cta {
    padding: 3rem 2rem;
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    text-align: center;
}

.shipping-cta h3 {
    margin: 0 0 0.5rem;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text);
}

.shipping-cta p {
    margin: 0 0 1.5rem;
    color: var(--text-muted);
}

.shipping-cta .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-hero {
        flex-direction: column;
        text-align: center;
        padding: 2rem 1.5rem;
    }

    .page-title {
        font-size: 1.65rem;
    }
}
</style>