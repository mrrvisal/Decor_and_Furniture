<?php require __DIR__ . '/../helpers.php'; ?>
<section class="contact-section container">
    <div class="contact-hero">
        <div class="contact-hero-content">
            <div class="contact-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                    </path>
                </svg>
                Get in Touch
            </div>
            <h1 class="contact-hero-title">Contact Us</h1>
            <p class="contact-hero-subtitle">Have questions? We'd love to hear from you. Our team is here to help you
                with any inquiries about our products or services.</p>
        </div>
    </div>

    <div class="contact-grid">
        <div class="contact-info">
            <h2>Get in Touch</h2>
            <div class="info-item">
                <div class="info-icon-wrap">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <div class="info-content">
                    <h3>Address</h3>
                    <p>123 Street Name, Phnom Penh<br>Cambodia</p>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon-wrap">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                        </path>
                    </svg>
                </div>
                <div class="info-content">
                    <h3>Phone</h3>
                    <p>+855 12 345 678</p>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon-wrap">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </div>
                <div class="info-content">
                    <h3>Email</h3>
                    <p>info@decor-furniture.com</p>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon-wrap">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="info-content">
                    <h3>Business Hours</h3>
                    <p>Monday - Saturday: 9:00 AM - 7:00 PM<br>Sunday: 10:00 AM - 5:00 PM</p>
                </div>
            </div>
        </div>

        <div class="contact-form-wrapper">
            <h2>Send us a Message</h2>
            <form method="post" action="<?= base_url('contact') ?>" class="contact-form needs-validation" novalidate>
                <input type="hidden" name="csrf_token" value="<?= e($csrf_token ?? '') ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Your Name *</label>
                        <input type="text" id="name" name="name" required value="<?= e($_POST['name'] ?? '') ?>">
                        <div class="invalid-feedback">Please enter your name</div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
                        <div class="invalid-feedback">Please enter a valid email address</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <select id="subject" name="subject">
                        <option value="">Select a subject</option>
                        <option value="general" <?= (($_POST['subject'] ?? '') == 'general') ? 'selected' : '' ?>>
                            General Inquiry</option>
                        <option value="order" <?= (($_POST['subject'] ?? '') == 'order') ? 'selected' : '' ?>>Order
                            Status</option>
                        <option value="product" <?= (($_POST['subject'] ?? '') == 'product') ? 'selected' : '' ?>>
                            Product Question</option>
                        <option value="support" <?= (($_POST['subject'] ?? '') == 'support') ? 'selected' : '' ?>>
                            Customer Support</option>
                        <option value="other" <?= (($_POST['subject'] ?? '') == 'other') ? 'selected' : '' ?>>Other
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" rows="5" required><?= e($_POST['message'] ?? '') ?></textarea>
                    <div class="invalid-feedback">Please enter your message</div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                    Send Message
                </button>
            </form>
        </div>
    </div>

    <div class="contact-map-section">
        <h2>Find Us</h2>

        <div class="map-placeholder">
            <iframe src="https://www.google.com/maps?q=Norton+University,+Phnom+Penh&output=embed" width="100%"
                height="500" style="border:0; border-radius:12px;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>

</section>

<style>
/* Contact Hero */
.contact-hero {
    position: relative;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 2rem;
    padding: 2.5rem 2rem;
    margin-bottom: 2.5rem;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 50%, #0f3460 100%);
    border-radius: var(--radius);
    overflow: hidden;
    background-image: url('https://cdn.pixabay.com/photo/2017/06/19/15/57/new-home-2419869_1280.jpg');
    background-position: center;
    background-size: cover;
    text-shadow: 2px 2px 5px rgb(150, 150, 150);
    text-align: center;
}

.contact-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.5;
}

.contact-hero-content {
    position: relative;
    z-index: 1;
    flex: 1;
}

.contact-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    margin-bottom: 1rem;
    backdrop-filter: blur(8px);
}

.contact-badge svg {
    color: var(--accent);
}

.contact-hero-title {
    margin: 0 0 1rem;
    font-size: 48px;
    font-weight: 800;
    color: #fff;
    letter-spacing: -0.02em;
    line-height: 1.5;
}

.contact-hero-subtitle {
    margin: 0;
    font-size: 18px;
    color: rgba(255, 255, 255, 0.85);
    line-height: 1.65;
    max-width: 700px;
    text-align: center;
    margin: auto;
}

.contact-hero-decoration {
    position: absolute;
    right: 2rem;
    top: 50%;
    transform: translateY(-50%);
    z-index: 0;
}

.contact-decor-circle {
    width: 160px;
    height: 160px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(255, 107, 53, 0.2), rgba(255, 107, 53, 0.05));
    border: 1px solid rgba(255, 107, 53, 0.15);
}

.contact-decor-dots {
    position: absolute;
    top: -30px;
    right: -20px;
    width: 60px;
    height: 60px;
    background-image: radial-gradient(rgba(255, 255, 255, 0.15) 2px, transparent 2px);
    background-size: 12px 12px;
}

/* Contact Section */
.contact-section {
    padding: 0 0 4rem;
}

/* Contact Grid */
.contact-grid {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 2rem;
    margin-bottom: 2.5rem;
}

@media (max-width: 900px) {
    .contact-grid {
        grid-template-columns: 1fr;
    }
}

/* Contact Info Card */
.contact-info {
    background: var(--bg-card);
    border-radius: var(--radius);
    padding: 2rem;
    box-shadow: var(--shadow);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.contact-info h2 {
    margin: 0 0 1.5rem;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text);
}

/* Info Items */
.info-item {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.25rem;
    padding: 1rem;
    background: var(--bg);
    border-radius: var(--radius-sm);
    transition: all var(--transition);
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-item:hover {
    transform: translateX(4px);
    box-shadow: var(--shadow);
}

.info-icon-wrap {
    width: 44px;
    height: 44px;
    background: var(--accent-soft);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent);
    flex-shrink: 0;
}

.info-item:hover .info-icon-wrap {
    background: var(--accent);
    color: #fff;
}

.info-content h3 {
    margin: 0 0 0.35rem;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text);
}

.info-content p {
    margin: 0;
    font-size: 0.85rem;
    color: var(--text-muted);
    line-height: 1.5;
}

/* Contact Form Wrapper */
.contact-form-wrapper {
    background: var(--bg-card);
    border-radius: var(--radius);
    padding: 2rem;
    box-shadow: var(--shadow);
}

.contact-form-wrapper h2 {
    margin: 0 0 1.5rem;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text);
}

/* Contact Form */
.contact-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.contact-form .form-group {
    margin-bottom: 0;
}

.contact-form label {
    display: block;
    margin-bottom: 0.4rem;
    font-weight: 500;
    font-size: 0.9rem;
    color: var(--text);
}

.contact-form input,
.contact-form select,
.contact-form textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    font-family: inherit;
    font-size: 0.95rem;
    background: var(--bg);
    transition: all var(--transition);
}

.contact-form input:focus,
.contact-form select:focus,
.contact-form textarea:focus {
    outline: none;
    border-color: var(--border-focus);
    box-shadow: 0 0 0 3px var(--accent-soft);
    transform: translateY(-1px);
    background: #fff;
}

.contact-form textarea {
    min-height: 140px;
    resize: vertical;
}

/* Contact Form Row */
.contact-form .form-row {
    display: flex;
    gap: 1rem;
}

.contact-form .form-row .form-group {
    flex: 1;
}

@media (max-width: 500px) {
    .contact-form .form-row {
        flex-direction: column;
    }
}

/* Submit Button */
.contact-form .btn-block {
    width: 100%;
    margin-top: 0.5rem;
    padding: 0.9rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

/* Contact Map Section */
.contact-map-section {
    background: var(--bg-card);
    border-radius: var(--radius);
    padding: 2rem;
    box-shadow: var(--shadow);
}

.contact-map-section h2 {
    margin: 0 0 1.5rem;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text);
}

/* Map Placeholder */
.map-placeholder {
    border-radius: var(--radius-sm);
    overflow: hidden;
}

/* Responsive */
@media (max-width: 900px) {
    .contact-hero {
        padding: 2rem;
    }

    .contact-hero-title {
        font-size: 2rem;
    }

    .contact-hero-decoration {
        display: none;
    }
}

@media (max-width: 768px) {
    .contact-section {
        padding: 0 0 3rem;
    }

    .contact-hero {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 2rem 1.5rem;
    }

    .contact-hero-content {
        max-width: 100%;
    }

    .contact-badge {
        justify-content: center;
    }

    .contact-hero-title {
        font-size: 1.75rem;
    }

    .contact-hero-subtitle {
        font-size: 1rem;
    }

    .contact-hero-decoration {
        display: none;
    }

    .contact-info,
    .contact-form-wrapper,
    .contact-map-section {
        padding: 1.5rem;
    }

    .contact-info {
        position: static;
    }

    .info-item {
        padding: 0.85rem;
    }
}
</style>