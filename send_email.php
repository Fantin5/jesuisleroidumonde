<?php
// send_email.php

// Include PHPMailer classes
require_once 'PHPMailer/Exception.php';
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Check if form was submitted
if ($_POST) {
    // Get form data
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = strip_tags(trim($_POST["message"]));

    // Validate form data
    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: indextest.php?status=error");
        exit;
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // Set the SMTP server (Gmail)
        $mail->SMTPAuth   = true;
        $mail->Username   = 'deschaumeselodie@gmail.com';     // Your email
        $mail->Password   = 'qghypbtuftgqqzuw';              // Your new app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('deschaumeselodie@gmail.com', 'Contact Form');
        $mail->addAddress('deschaumeselodie@gmail.com', 'Lodie');
        $mail->addReplyTo($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Contact Form: ' . $subject;
        
        // Create HTML email body
        $emailBody = "
        <html>
        <head>
            <title>Contact Form Submission</title>
        </head>
        <body>
            <h2>New Contact Form Submission</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Subject:</strong> {$subject}</p>
            <p><strong>Message:</strong></p>
            <p>{$message}</p>
            <hr>
            <p><em>This email was sent from your contact form.</em></p>
        </body>
        </html>
        ";
        
        $mail->Body = $emailBody;
        $mail->AltBody = "Name: {$name}\nEmail: {$email}\nSubject: {$subject}\nMessage: {$message}";

        // Send the email
        $mail->send();
        header("Location: indextest.php?status=success");
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        header("Location: indextest.php?status=error");
    }
} else {
    header("Location: indextest.php");
}
exit;
?>