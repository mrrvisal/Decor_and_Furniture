<?php require __DIR__ . '/../helpers.php'; ?>
<section class="auth-section">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 3rem; margin-bottom: 0.75rem;">🔐</div>
            <h1 style="margin: 0 0 0.5rem;">Enter verification code</h1>
            <p class="muted" style="margin: 0;">We sent a 6-digit code to your email.</p>
        </div>
        <form method="post" action="<?= base_url('auth/forgot/verify') ?>">
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
            <div class="form-group">
                <label for="otp">Verification code</label>
                <input type="text" id="otp" name="otp" maxlength="6" pattern="[0-9]{6}" required
                    placeholder="Enter 6-digit code" autocomplete="one-time-code"
                    style="letter-spacing: 0.5em; font-size: 1.5rem; text-align: center; font-weight: 600;">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Verify Code</button>
        </form>
        <p style="text-align: center; margin-top: 1.25rem;">
            <a href="<?= base_url('auth/forgot') ?>" class="btn btn-ghost" style="text-decoration: none;">← Back</a>
        </p>
    </div>
</section>