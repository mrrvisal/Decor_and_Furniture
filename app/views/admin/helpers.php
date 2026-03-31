<?php
/**
 * Admin View Helpers
 */
if (!function_exists('admin_base_url')) {
    function admin_base_url(string $path = ''): string
    {
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
        return $base . '/admin' . ($path ? '/' . ltrim($path, '/') : '');
    }
}
if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
        return $base . ($path ? '/' . ltrim($path, '/') : '');
    }
}
if (!function_exists('e')) {
    function e(?string $s): string
    {
        return $s === null ? '' : htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }
}
if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return base_url('assets/' . ltrim($path, '/'));
    }
}
if (!function_exists('placeholder_image')) {
    function placeholder_image(): string
    {
        return 'data:image/svg+xml,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="300" height="200" viewBox="0 0 300 200"><rect fill="#eee" width="300" height="200"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#999" font-family="sans-serif" font-size="14">No image</text></svg>');
    }
}
if (!function_exists('user_status_badge')) {
    function user_status_badge(int $isActive): string
    {
        if ($isActive) {
            return '<span class="status-badge status-active">Active</span>';
        }
        return '<span class="status-badge status-inactive">Inactive</span>';
    }
}
if (!function_exists('email_verified_badge')) {
    function email_verified_badge(?string $verifiedAt): string
    {
        if ($verifiedAt) {
            return '<span class="verified-badge" title="Verified">✅ Verified</span>';
        }
        return '<span class="unverified-badge" title="Not verified">❌ Unverified</span>';
    }
}