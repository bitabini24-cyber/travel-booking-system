<?php
/**
 * Email Service
 * Uses PHP mail() by default. Swap sendMail() body for PHPMailer/SMTP in production.
 */
class EmailService {
    private static string $from    = 'noreply@travellux.com';
    private static string $fromName = 'TravelLux';

    /**
     * Send booking confirmation email
     */
    public static function sendBookingConfirmation(array $booking): bool {
        $subject = "Booking Confirmed - {$booking['hotel_name']}";
        $body = self::wrap("
            <h2>Your booking is confirmed! 🎉</h2>
            <p>Hi {$booking['user_name']},</p>
            <p>Your reservation at <strong>{$booking['hotel_name']}</strong> has been confirmed.</p>
            <table style='width:100%;border-collapse:collapse;margin:20px 0;'>
                <tr><td style='padding:8px;color:#6b7280;'>Check In</td><td style='padding:8px;font-weight:600;'>{$booking['check_in']}</td></tr>
                <tr><td style='padding:8px;color:#6b7280;'>Check Out</td><td style='padding:8px;font-weight:600;'>{$booking['check_out']}</td></tr>
                <tr><td style='padding:8px;color:#6b7280;'>Total</td><td style='padding:8px;font-weight:700;color:#6C63FF;'>\${$booking['total_price']}</td></tr>
            </table>
            <p>We look forward to welcoming you!</p>
        ");
        return self::sendMail($booking['user_email'], $subject, $body);
    }

    /**
     * Send password reset email
     */
    public static function sendReset(string $email, string $resetUrl): bool {
        $subject = 'Reset Your TravelLux Password';
        $body = self::wrap("
            <h2>Password Reset Request</h2>
            <p>Click the button below to reset your password. This link expires in 1 hour.</p>
            <p style='text-align:center;margin:32px 0;'>
                <a href='{$resetUrl}' style='background:linear-gradient(135deg,#6C63FF,#FF6584);color:white;padding:14px 32px;border-radius:10px;text-decoration:none;font-weight:700;'>Reset Password</a>
            </p>
            <p style='color:#9ca3af;font-size:0.85rem;'>If you didn't request this, ignore this email.</p>
        ");
        return self::sendMail($email, $subject, $body);
    }

    /**
     * Send welcome email after registration
     */
    public static function sendWelcome(string $email, string $name): bool {
        $subject = "Welcome to TravelLux, {$name}!";
        $body = self::wrap("
            <h2>Welcome aboard, {$name}! ✈️</h2>
            <p>Your account has been created. Start exploring thousands of hotels worldwide.</p>
            <p style='text-align:center;margin:32px 0;'>
                <a href='" . APP_URL . "/pages/search.php' style='background:linear-gradient(135deg,#6C63FF,#FF6584);color:white;padding:14px 32px;border-radius:10px;text-decoration:none;font-weight:700;'>Explore Hotels</a>
            </p>
        ");
        return self::sendMail($email, $subject, $body);
    }

    /**
     * Core mail sender
     */
    private static function sendMail(string $to, string $subject, string $body): bool {
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . self::$fromName . " <" . self::$from . ">\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        return mail($to, $subject, $body, $headers);
    }

    /**
     * HTML email wrapper template
     */
    private static function wrap(string $content): string {
        return "<!DOCTYPE html><html><body style='font-family:system-ui,sans-serif;background:#f8f9ff;margin:0;padding:40px 20px;'>
            <div style='max-width:600px;margin:0 auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.1);'>
                <div style='background:linear-gradient(135deg,#6C63FF,#FF6584);padding:32px;text-align:center;'>
                    <h1 style='color:white;margin:0;font-size:1.8rem;'>✈ TravelLux</h1>
                </div>
                <div style='padding:40px;color:#2d2d2d;line-height:1.6;'>{$content}</div>
                <div style='background:#f8f9ff;padding:20px;text-align:center;color:#9ca3af;font-size:0.8rem;'>
                    © " . date('Y') . " TravelLux. All rights reserved.
                </div>
            </div>
        </body></html>";
    }
}
