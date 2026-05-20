<?php
/**
 * Mail helper using PHPMailer (install via: composer require phpmailer/phpmailer)
 */
namespace Core;

class Mail
{
    private static array $config;
    private static string $lastError = '';

    /**
     * Get the last error message
     */
    public static function getLastError(): string
    {
        return self::$lastError;
    }

    /**
     * Test SMTP connection without sending email
     */
    public static function testSmtpConnection(): array
    {
        self::$config = require dirname(__DIR__) . '/config/mail.php';

        if (!class_exists(\PHPMailer\PHPMailer\PHPMailer::class)) {
            return ['success' => false, 'error' => 'PHPMailer not installed. Run: composer require phpmailer/phpmailer'];
        }

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = self::$config['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = self::$config['smtp_username'];
            $mail->Password = self::$config['smtp_password'];
            $mail->SMTPSecure = self::$config['smtp_secure'];
            $mail->Port = self::$config['smtp_port'];

            // Don't try to connect yet, just validate config
            return [
                'success' => true,
                'host' => self::$config['smtp_host'],
                'port' => self::$config['smtp_port'],
                'username' => self::$config['smtp_username'],
                'secure' => self::$config['smtp_secure'],
                'from' => self::$config['from_email']
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send email with detailed error handling
     */
    public static function send(string $to, string $subject, string $bodyHtml, string $bodyText = ''): bool
    {
        self::$lastError = '';

        if (!class_exists(\PHPMailer\PHPMailer\PHPMailer::class)) {
            self::$lastError = 'PHPMailer not installed. Run: composer require phpmailer/phpmailer';
            error_log('Mail error: ' . self::$lastError);
            return false;
        }

        self::$config = require dirname(__DIR__) . '/config/mail.php';
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = self::$config['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = self::$config['smtp_username'];
            $mail->Password = self::$config['smtp_password'];
            $mail->SMTPSecure = self::$config['smtp_secure'];
            $mail->Port = self::$config['smtp_port'];

            // Debug mode - uncomment for troubleshooting
            // $mail->SMTPDebug  = 2;

            $mail->setFrom(self::$config['from_email'], self::$config['from_name']);
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $bodyHtml;
            $mail->AltBody = $bodyText ?: strip_tags($bodyHtml);

            $mail->send();
            return true;
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            self::$lastError = 'Email configuration error: ' . $mail->ErrorInfo;
            error_log('Mail error: ' . self::$lastError);
            return false;
        } catch (\Throwable $e) {
            self::$lastError = 'Unexpected error: ' . $e->getMessage();
            error_log('Mail error: ' . self::$lastError);
            return false;
        }
    }

    /**
     * Send OTP email with better error handling
     */
    public static function sendOtp(string $to, string $otp, string $type): bool
    {
        $subject = $type === 'register' ? 'Verify your email - Luxery' : 'Reset your password - Luxery';
        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #333;'>Your verification code</h2>
            <p>Use this code to complete your request:</p>
            <div style='background: #f5f5f5; padding: 20px; text-align: center; margin: 20px 0;'>
                <p style='font-size: 32px; letter-spacing: 8px; font-weight: bold; margin: 0; color: #2196F3;'>{$otp}</p>
            </div>
            <p style='color: #666;'>This code expires in 10 minutes.</p>
            <p style='color: #999; font-size: 12px;'>If you didn't request this, please ignore this email.</p>
        </div>
        ";

        $result = self::send($to, $subject, $body);

        if (!$result) {
            // Store error in session for user feedback
            $_SESSION['email_error'] = self::$lastError;
        }

        return $result;
    }
}