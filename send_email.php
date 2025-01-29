<?php
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';
require 'libs/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');
echo json_encode(["received_data" => $_POST]);
exit();

// Check if email and book details are received
if (!isset($_POST['user_email']) || !isset($_POST['book_id']) || !isset($_POST['book_title'])) {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit();
}

// Get user email and sanitize
$userEmail = filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL);
if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email address"]);
    exit();
}

// Get book details
$bookId = intval($_POST['book_id']);
$bookTitle = htmlspecialchars($_POST['book_title']);
$filePath = "books/" . $bookId . ".pdf"; // Path to the book PDF

// Check if file exists
if (!file_exists($filePath)) {
    echo json_encode(["status" => "error", "message" => "File not found"]);
    exit();
}

$mail = new PHPMailer(true);
try {
    // Enable SMTP debugging (0 = off, 1 = client messages, 2 = full debug)
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = 'smtp.sendgrid.net';
    $mail->SMTPAuth = true;
    $mail->Username = 'apikey'; // Do not change
    $mail->Password = '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'; // Replace with new API Key
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email settings
    $mail->setFrom('modalsamybuen@gmail.com', 'Sucré-Salé');
    $mail->addAddress($userEmail);
    $mail->Subject = "Le PDF que vous avez demandé";
    $mail->Body = "Bonjour,\n\nVoici le PDF que vous avez demandé : '$bookTitle'.\nBonne lecture !\n";

    // Attach PDF
    $mail->addAttachment($filePath);

    // Send email
    if ($mail->send()) {
        echo json_encode(["status" => "success", "message" => "Email sent successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to send email"]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Mailer Error: " . $mail->ErrorInfo]);
}
?>
