<?php require __DIR__ . '/../helpers.php'; ?>
<section class="auth-section">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 3rem; margin-bottom: 0.75rem;">👋</div>
            <h1 style="margin: 0 0 0.5rem;">Welcome Back</h1>
            <p class="muted" style="margin: 0;">Sign in to your account</p>
        </div>
        <form method="post" action="<?= base_url('auth/login') ?>">
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"
                    placeholder="you@example.com" autocomplete="email">
            </div>
            <div class="form-group">
                <div class="form-group-header">
                    <label for="password">Password</label>
                    <a href="<?= base_url('auth/forgot') ?>" style="color: var(--accent);">Forgot password?</a>
                </div>
                <input type="password" id="password" name="password" required placeholder="Enter your password"
                    autocomplete="current-password">
            </div>
            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </form>
        <p style="text-align: center; margin-top: 1.25rem;">
            <a href="<?= base_url('auth/register') ?>" style="color: var(--accent);">Create account</a>
            <span class="muted" style="margin: 0 0.5rem;">·</span>
            <a href="<?= base_url() ?>" style="color: var(--accent);">Back to store</a>
        </p>
    </div>
</section>
<style>
.form-group-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.35rem;
}
</style>