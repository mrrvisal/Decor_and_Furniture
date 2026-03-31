<?php
/**
 * View helpers - use in views
 */
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
if (!function_exists('avatar')) {
    function avatar(?string $path = null, string $type = 'users'): string
    {
        if ($path) {
            return asset('images/' . $type . '/' . $path);
        }
        // Default SVG avatar
        return 'data:image/svg+xml,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50" fill="#e0e0e0"/><circle cx="50" cy="38" r="20" fill="#bdbdbd"/><path d="M15 100 Q50 65 85 100" fill="#bdbdbd"/></svg>');
    }
}
if (!function_exists('upload_avatar')) {
    function upload_avatar(array $file, int $userId, string $type = 'users'): ?string
    {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Upload error: ' . $file['error']);
        }
        // Validate image
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowed)) {
            throw new \Exception('Invalid file type. Allowed: JPG, PNG, GIF, WebP.');
        }
        // Max 2MB
        if ($file['size'] > 2 * 1024 * 1024) {
            throw new \Exception('File too large. Maximum size: 2MB.');
        }
        // Create upload directory
        $uploadDir = BASE_PATH . '/public/assets/images/' . $type;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        // Generate unique filename
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'jpg';
        $filename = $type . '_' . substr(md5($userId . time()), 0, 12) . '.' . $ext;
        $destination = $uploadDir . '/' . $filename;
        // Resize and save image
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($file['tmp_name']);
                break;
            case 'image/png':
                $image = imagecreatefrompng($file['tmp_name']);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($file['tmp_name']);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($file['tmp_name']);
                break;
            default:
                $image = null;
        }
        if (!$image) {
            throw new \Exception('Failed to process image.');
        }
        // Resize to 200x200
        $newWidth = 200;
        $newHeight = 200;
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($image), imagesy($image));
        // Save
        imagejpeg($resized, $destination, 90);
        imagedestroy($image);
        imagedestroy($resized);
        return $filename;
    }
}