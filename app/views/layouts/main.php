<?php
if (!function_exists('base_url'))
    require __DIR__ . '/../helpers.php';
if (!function_exists('isActive'))
    require __DIR__ . '/../helpers.php';
$pageTitle = $pageTitle ?? 'Decor & Furniture';
$bodyClass = $bodyClass ?? '';

// Helper function to check if nav link is active
$navLinks = [
    'Home' => base_url(),
    'Products' => base_url('products'),
    'About' => base_url('about'),
    'Contact Us' => base_url('contact'),
    'Cart' => base_url('cart'),
    'Login' => base_url('auth/login'),
    'Register' => base_url('auth/register'),
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?>Luxery</title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>

<body class="<?= e($bodyClass) ?>">
    <header class="site-header">
        <div class="container header-inner">
            <a href="<?= base_url() ?>" class="logo"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                    <path
                        d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z" />
                </svg> Luxery</a>
            <button type="button" class="nav-toggle" id="nav-toggle" aria-label="Toggle menu">☰</button>
            <nav class="nav-main" id="nav-main">
                <?php
                // Get current path for comparison
                $currentPath = $_SERVER['REQUEST_URI'] ?? '/';
                $currentPath = str_replace('/decor', '', $currentPath); // Remove base path if present
                $currentPath = parse_url($currentPath, PHP_URL_PATH);

                function isNavActive($link, $currentPath)
                {
                    // Exact match for home
                    if ($link === base_url() || $link === base_url('/')) {
                        return $currentPath === '/' || $currentPath === '';
                    }
                    // Check if current path starts with the link path
                    $linkPath = parse_url($link, PHP_URL_PATH);
                    return strpos($currentPath, $linkPath) === 0;
                }
                ?>
                <a href="<?= base_url() ?>"
                    class="<?= isNavActive(base_url(), $currentPath) ? 'active' : '' ?>">Home</a>
                <a href="<?= base_url('products') ?>"
                    class="<?= isNavActive(base_url('products'), $currentPath) ? 'active' : '' ?>">Products</a>
                <a href="<?= base_url('about') ?>"
                    class="<?= isNavActive(base_url('about'), $currentPath) ? 'active' : '' ?>">About</a>
                <a href="<?= base_url('contact') ?>"
                    class="<?= isNavActive(base_url('contact'), $currentPath) ? 'active' : '' ?>">Contact Us</a>
                <?php if (!empty($_SESSION['admin_id'])): ?>
                <a href="<?= base_url('cart') ?>"
                    class="<?= isNavActive(base_url('cart'), $currentPath) ? 'active' : '' ?>">Cart (<span
                        id="cart-count"><?= (int) ($cartCount ?? 0) ?></span>)</a>
                <div class="user-dropdown">
                    <button class="user-dropdown-btn">
                        <img src="<?= e(avatar($_SESSION['admin_avatar'] ?? null, 'admins')) ?>" alt="Profile"
                            class="user-avatar">
                        <span class="user-name"><?= e($_SESSION['admin_name'] ?? 'Admin') ?></span>
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    <div class="user-dropdown-menu">
                        <a href="<?= base_url('order/my-orders') ?>">📦 My Orders</a>
                        <a href="<?= base_url('profile') ?>">👤 My Profile</a>
                        <a href="#" class="logout-trigger" data-logout-url="<?= base_url('auth/admin-logout') ?>">🚪
                            Logout</a>
                    </div>
                </div>
                <?php elseif (!empty($_SESSION['user_id'])): ?>
                <a href="<?= base_url('cart') ?>"
                    class="<?= isNavActive(base_url('cart'), $currentPath) ? 'active' : '' ?>">Cart (<span
                        id="cart-count"><?= (int) ($cartCount ?? 0) ?></span>)</a>
                <div class="user-dropdown">
                    <button class="user-dropdown-btn">
                        <img src="<?= e(avatar($_SESSION['user_avatar'] ?? null, 'users')) ?>" alt="Profile"
                            class="user-avatar">
                        <span class="user-name"><?= e($_SESSION['user_name'] ?? 'User') ?></span>
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    <div class="user-dropdown-menu">
                        <a href="<?= base_url('order/my-orders') ?>">📦 My Orders</a>
                        <a href="<?= base_url('profile') ?>">👤 My Profile</a>
                        <a href="#" class="logout-trigger" data-logout-url="<?= base_url('auth/logout') ?>">🚪
                            Logout</a>
                    </div>
                </div>
                <?php else: ?>
                <a href="<?= base_url('auth/login') ?>"
                    class="<?= isNavActive(base_url('auth/login'), $currentPath) ? 'active' : '' ?>">Login</a>
                <a href="<?= base_url('auth/register') ?>"
                    class="<?= isNavActive(base_url('auth/register'), $currentPath) ? 'active' : '' ?>">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="main-content">
        <div class="container page-wrap">
            <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= e($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= e($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <?= $content ?? '' ?>
        </div>
    </main>
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3 class="footer-brand"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                            <path
                                d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z" />
                        </svg> Luxery</h3>
                    <p class="footer-desc">
                        Your destination for beautiful home decor and quality furniture. Transform your space with our
                        curated collection.
                    </p>
                </div>
                <div class="footer-col">
                    <h4 class="footer-title">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="<?= base_url() ?>">Home</a></li>
                        <li><a href="<?= base_url('/products') ?>">Products</a></li>
                        <li><a href="<?= base_url('/about') ?>">About </a></li>
                        <li><a href="<?= base_url('/contact') ?>">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-title">Customer Service</h4>
                    <ul class="footer-links">
                        <li><a href="<?= base_url('order/my-orders') ?>">My Orders</a></li>
                        <li><a href="<?= base_url('faq') ?>">FAQ</a></li>
                        <li><a href="<?= base_url('shipping') ?>">Shipping Info</a></li>
                        <li><a href="<?= base_url('returns') ?>">Returns Policy</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-title">Contact Us</h4>
                    <ul class="footer-contact">
                        <li>📧 info@luxery.com</li>
                        <li>📞 +855 12 345 678</li>
                        <li>📍 Phnom Penh, Cambodia</li>
                    </ul>
                </div>
            </div>
            <hr class="footer-divider">
            <p class="footer-copyright">&copy; <?= date('Y') ?> Luxery. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- Logout Confirmation Modal -->
    <div class="modal-overlay" id="logout-modal">
        <div class="modal">
            <div class="modal-header">
                <h3>Logout Confirmation</h3>
                <button class="modal-close" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to logout?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-ghost modal-cancel">Cancel</button>
                <a href="#" class="btn btn-primary modal-confirm" id="logout-confirm-btn">Logout</a>
            </div>
        </div>
    </div>

    <script src="<?= asset('js/app.js') ?>"></script>
    <?= $scripts ?? '' ?>
</body>

</html>