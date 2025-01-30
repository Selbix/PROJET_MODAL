<?php
require "utils.php";
require "logInOut.php";
require "database.php";
require "utilisateur.php";
require "printForms.php";
require "livre.php";
require "register.php";

$dbh = Database::connect();

// Ensure book_id and user_id are received
if (isset($_POST['book_id']) && isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']); // Get user ID from frontend
    $bookId = intval($_POST['book_id']); // Get book ID safely

    // Check if the book is already liked
    $checkQuery = "SELECT * FROM Enregistrement_Livres WHERE id_utilisateur = ? AND id_titre = ?";
    $stmt = $dbh->prepare($checkQuery);
    $stmt->execute([$userId, $bookId]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "error", "message" => "Already liked"]);
        exit();
    }

    // Insert into "Enregistrement_Livres" table
    $query = "INSERT INTO Enregistrement_Livres (id_titre, id_utilisateur) VALUES (?, ?)";
    $stmt = $dbh->prepare($query);

    if ($stmt->execute([$bookId, $userId])) {
        echo json_encode(["status" => "success", "message" => "Book liked successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
