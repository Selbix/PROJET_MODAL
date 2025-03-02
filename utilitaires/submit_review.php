<?php
require $_SERVER['DOCUMENT_ROOT'] . "/PROJET_MODAL/utilitaires/utils.php";
require $_SERVER['DOCUMENT_ROOT'] . "/PROJET_MODAL/utilitaires/logInOut.php";
require $_SERVER['DOCUMENT_ROOT'] . "/PROJET_MODAL/BDD-gestion/database.php";
require $_SERVER['DOCUMENT_ROOT'] . "/PROJET_MODAL/BDD-gestion/utilisateur.php";
require $_SERVER['DOCUMENT_ROOT'] . "/PROJET_MODAL/BDD-gestion/livre.php";
require $_SERVER['DOCUMENT_ROOT'] . "/PROJET_MODAL/utilitaires/register.php"; 


$dbh = Database::connect();

header('Content-Type: application/json');

// Ensure book_id, user_id, and rating are received
if (isset($_POST['book_id'], $_POST['user_id'], $_POST['rating'])) {
    $userId = intval($_POST['user_id']); // Get user ID from frontend
    $bookId = intval($_POST['book_id']); // Get book ID safely
    $rating = intval($_POST['rating']); // Get rating
    $rating = 5 - $rating + 1; // Invert rating (1 star = 5, 2 stars = 4, etc.)
    $review = isset($_POST['review']) ? trim($_POST['review']) : ""; // Get review text (optional)

    // Check if the user already submitted a review
    $checkQuery = "SELECT * FROM rating_livre WHERE id_utilisateur = ? AND id_titre = ?";
    $stmt = $dbh->prepare($checkQuery);
    $stmt->execute([$userId, $bookId]);

    if ($stmt->rowCount() > 0) {
        // If the review already exists, update it
        $query = "UPDATE rating_livre SET note = ?, avis = ? WHERE id_utilisateur = ? AND id_titre = ?";
        $stmt = $dbh->prepare($query);
        $result = $stmt->execute([$rating, $review, $userId, $bookId]);

        if ($result) {
            echo json_encode(["status" => "success", "message" => "Review updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database update error"]);
        }
    } else {
        // If no previous review, insert new one
        $query = "INSERT INTO rating_livre (id_titre, id_utilisateur, note, avis) VALUES (?, ?, ?, ?)";
        $stmt = $dbh->prepare($query);
        $result = $stmt->execute([$bookId, $userId, $rating, $review]);

        if ($result) {
            echo json_encode(["status" => "success", "message" => "Review submitted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database insert error"]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
