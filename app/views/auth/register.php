<?php require __DIR__ . '/../helpers.php'; ?>
<section class="auth-section">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 3rem; margin-bottom: 0.75rem;">✨</div>
            <h1 style="margin: 0 0 0.5rem;">Create Account</h1>
            <p class="muted" style="margin: 0;">Join us and start shopping today</p>
        </div>
        <form id="register-form" method="post" action="<?= base_url('auth/register/send-otp') ?>"
            class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required value="<?= e($_POST['name'] ?? '') ?>"
                    placeholder="Your name">
                <div class="invalid-feedback">Please enter your name</div>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"
                    placeholder="you@example.com">
                <div class="invalid-feedback">Please enter a valid email address</div>
            </div>
            <div class="form-group">
                <label for="phone">Phone <span class="text-muted">(optional)</span></label>
                <input type="text" id="phone" name="phone" value="<?= e($_POST['phone'] ?? '') ?>"
                    placeholder="Phone number">
            </div>
            <div class="form-group">
                <label for="password">Password <span class="text-muted">(min 6 characters)</span></label>
                <input type="password" id="password" name="password" required minlength="6" placeholder="••••••••">
                <div class="invalid-feedback">Password must be at least 6 characters</div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Send OTP to Email</button>
        </form>
        <p id="register-message" style="text-align: center;"></p>
        <p style="text-align: center;">
            <a href="<?= base_url('auth/login') ?>" style="color: var(--accent);">Already have an account? </a>
            <span class="muted" style="margin: 0 0.5rem;">·</span>
            <a href="<?= base_url() ?>" style="color: var(--accent);">Back to store</a>
        </p>
    </div>
</section>
<script>
document.getElementById('register-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = this;
    var msg = document.getElementById('register-message');
    msg.textContent = '';
    msg.className = '';
    var fd = new FormData(form);
    fetch(form.action, {
            method: 'POST',
            body: fd
        })
        .then(r => r.json())
        .then(data => {
            msg.textContent = data.message;
            msg.className = data.success ? 'success' : 'error';
            if (data.success) window.location = '<?= base_url('auth/register/verify') ?>';
        })
        .catch(function() {
            msg.textContent = 'Something went wrong.';
            msg.className = 'error';
        });
});
</script>