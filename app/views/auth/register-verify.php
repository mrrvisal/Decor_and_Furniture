<?php require __DIR__ . '/../helpers.php'; ?>
<section class="auth-section">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 3rem; margin-bottom: 0.75rem;">📧</div>
            <h1 style="margin: 0 0 0.5rem;">Verify your email</h1>
            <p class="muted" style="margin: 0;">We sent a 6-digit code to <strong><?= e($email) ?></strong></p>
        </div>
        <form method="post" action="<?= base_url('auth/register/verify') ?>" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
            <div class="form-group">
                <label for="otp" style="text-align: center; margin-bottom: 0.75rem;">Enter verification code</label>
                <input type="text" id="otp" name="otp" maxlength="6" pattern="[0-9]{6}" required placeholder="000000"
                    autocomplete="one-time-code" class="otp-input"
                    style="letter-spacing: 0.5em; font-size: 1.5rem; text-align: center; padding: 0.85rem 1rem;">
                <div class="invalid-feedback" style="text-align: center;">Please enter the 6-digit verification code
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Verify & Register</button>
        </form>
        <p style="text-align: center; margin-top: 1.25rem;">
            <a href="<?= base_url('auth/register') ?>" style="color: var(--accent);">Back to Register</a>
            <span class="muted" style="margin: 0 0.5rem;">·</span>
            <a href="<?= base_url() ?>" style="color: var(--accent);">Back to store</a>
        </p>
    </div>
</section>