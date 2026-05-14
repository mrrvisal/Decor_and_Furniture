<?php
/**
 * PHPMailer / SMTP configuration
 * Configure for your SMTP provider (Gmail, SendGrid, etc.)
 */
return [
    'smtp_host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
    'smtp_port' => getenv('SMTP_PORT') ?: 587,
    'smtp_username' => getenv('SMTP_USERNAME') ?: 'mrrvisal617@gmail.com', // Your email
    'smtp_password' => getenv('SMTP_PASSWORD') ?: 'tkqd qsks kujx wauw', // App password for Gmail
    'smtp_secure' => getenv('SMTP_SECURE') ?: 'tls',
    'from_email' => getenv('MAIL_FROM_EMAIL') ?: 'mrrvisal617@gmail.com', // Must match smtp_username for Gmail
    'from_name' => getenv('MAIL_FROM_NAME') ?: 'Decor & Furniture Store',
];
