<?php
/**
 * Decor & Furniture E-Commerce - Front controller
 * Point document root to /decor/public or use this as entry
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone to match MySQL server (Cambodia/Asia/Bangkok UTC+7)
date_default_timezone_set('Asia/Phnom_Penh');

session_start();

define('BASE_PATH', dirname(__DIR__));

if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require BASE_PATH . '/vendor/autoload.php';
}

// Autoload App & Core (if not using Composer autoload for them)
spl_autoload_register(function ($class) {
    $prefixes = ['App\\' => BASE_PATH . '/app/', 'Core\\' => BASE_PATH . '/core/'];
    foreach ($prefixes as $prefix => $base) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0)
            continue;
        $rel = str_replace('\\', '/', substr($class, $len));
        $file = $base . $rel . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

// Base path from current script (works for any folder name, e.g. /decor/public or /myproject/public)
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$router = new Core\Router($basePath);

// Home & Products
$router->get('/', 'PageController@home');
$router->get('/products', 'ProductController@index');
$router->get('/product/{slug}', 'ProductController@show');

// Pages
$router->get('/about', 'PageController@about');
$router->get('/faq', 'PageController@faq');
$router->get('/shipping', 'PageController@shipping');
$router->get('/returns', 'PageController@returns');
$router->get('/contact', 'PageController@contact');
$router->post('/contact', 'PageController@contactSubmit');
$router->get('/profile', 'PageController@profile');
$router->get('/settings', 'PageController@settings');
$router->post('/settings', 'PageController@settingsUpdate');

// Auth (user)
$router->get('/auth/login', 'AuthController@loginForm');
$router->post('/auth/login', 'AuthController@login');
$router->get('/auth/register', 'AuthController@registerForm');
$router->post('/auth/register/send-otp', 'AuthController@registerSendOtp');
$router->get('/auth/register/verify', 'AuthController@registerVerifyForm');
$router->post('/auth/register/verify', 'AuthController@registerVerify');
$router->get('/auth/forgot', 'AuthController@forgotForm');
$router->post('/auth/forgot/send-otp', 'AuthController@forgotSendOtp');
$router->get('/auth/forgot/verify-form', 'AuthController@forgotVerifyForm');
$router->post('/auth/forgot/verify', 'AuthController@forgotVerify');
$router->get('/auth/reset-password', 'AuthController@resetPasswordForm');
$router->post('/auth/reset-password', 'AuthController@resetPassword');
$router->get('/auth/logout', 'AuthController@logout');

// Admin auth
$router->get('/admin/login', 'AuthController@adminLoginForm');
$router->post('/admin/login', 'AuthController@adminLogin');
$router->get('/auth/admin-logout', 'AuthController@adminLogout');

// Cart
$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/update', 'CartController@update');
$router->post('/cart/remove', 'CartController@remove');

// Checkout & Orders
$router->get('/cart/checkout', 'OrderController@checkout');
$router->post('/order/place', 'OrderController@place');
$router->get('/order/qr/{orderNumber}', 'OrderController@qr');
$router->get('/order/my-orders', 'OrderController@myOrders');
$router->get('/order/{orderNumber}', 'OrderController@show');

// Admin (require admin session)
$router->get('/admin', 'AdminController@index');
$router->get('/admin/profile', 'AdminController@profile');
$router->get('/admin/products', 'AdminController@products');
$router->get('/admin/product/add', 'AdminController@productAdd');
$router->post('/admin/product/store', 'AdminController@productStore');
$router->get('/admin/product/edit/{id}', 'AdminController@productEdit');
$router->post('/admin/product/update/{id}', 'AdminController@productUpdate');
$router->post('/admin/product/delete/{id}', 'AdminController@productDelete');
$router->post('/admin/product/toggle-status/{id}', 'AdminController@productToggleStatus');
$router->get('/admin/orders', 'AdminController@orders');
$router->get('/admin/order/{id}', 'AdminController@orderShow');
$router->post('/admin/order/update-status/{id}', 'AdminController@orderUpdateStatus');

// User Management
$router->get('/admin/users', 'AdminController@users');
// Specific routes must come before parameterized routes to avoid conflicts
$router->get('/admin/user/add', 'AdminController@userAdd');
$router->post('/admin/user/store', 'AdminController@userStore');
$router->get('/admin/user/edit/{id}', 'AdminController@userEdit');
$router->post('/admin/user/update/{id}', 'AdminController@userUpdate');
$router->get('/admin/user/{id}', 'AdminController@userShow');
$router->post('/admin/user/delete/{id}', 'AdminController@userDelete');
$router->post('/admin/user/toggle-status/{id}', 'AdminController@userToggleStatus');

$router->dispatch();