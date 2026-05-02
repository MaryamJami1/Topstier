<?php
// email_helper.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Attempt to load Composer's autoloader if present (adjust path if needed)
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
}

/**
 * Sends a shipment notification email to the customer using Hostinger SMTP
 *
 * @param string $customerEmail
 * @param string $trackingNumber
 * @return bool True if sent, False otherwise
 */
function sendShipmentEmail($customerEmail, $trackingNumber) {
    if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        error_log("PHPMailer is not installed. Please install it via composer: composer require phpmailer/phpmailer");
        return false;
    }

    $mail = new PHPMailer(true);

    try {
        // Hostinger SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@yourdomain.com'; // TODO: Replace with real email
        $mail->Password   = 'your-email-password';       // TODO: Replace with real password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('your-email@yourdomain.com', 'Your Store Name');
        $mail->addAddress($customerEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Order Has Shipped!';
        
        // FedEx standard tracking URL format
        $trackingUrl = "https://www.fedex.com/fedextrack/?trknbr=" . $trackingNumber;
        
        $mail->Body = "
            <h2>Great news! Your order is on the way.</h2>
            <p>Your FedEx tracking number is: <strong>{$trackingNumber}</strong></p>
            <p><a href='{$trackingUrl}' style='padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 10px;'>Track Your Package</a></p>
            <br>
            <p>Thank you for shopping with us!</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
