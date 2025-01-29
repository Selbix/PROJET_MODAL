<?php
$to = "samy.el-bakouri@polytechnique.edu"; // Change to your real email
$subject = "Test Email";
$message = "This is a test email from PHP.";
$headers = "From: no-reply@yourwebsite.com\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "✅ Email sent successfully!";
} else {
    echo "❌ Failed to send email.";
}
?>