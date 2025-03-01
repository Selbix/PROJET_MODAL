<?php
require "utils.php";
require "logInOut.php";
require "database.php";
require "utilisateur.php";
require "printForms.php";
require "livre.php";
require "register.php";

$dbh = Database::connect();

header('Content-Type: application/json');

// S'assurer que les identifiants ont été reçus
if (isset($_POST['book_id'], $_POST['user_id'], $_POST['rating'])) {
    $userId = intval($_POST['user_id']); 
    $bookId = intval($_POST['book_id']); 
    $rating = intval($_POST['rating']); 
    $rating = 5 - $rating + 1; 
    $review = isset($_POST['review']) ? trim($_POST['review']) : ""; // Get review text (optional)

    // Vérifie si l'utilisateur a déjà soumis un avis
    $checkQuery = "SELECT * FROM rating_livre WHERE id_utilisateur = ? AND id_titre = ?";
    $stmt = $dbh->prepare($checkQuery);
    $stmt->execute([$userId, $bookId]);

    if ($stmt->rowCount() > 0) {
        // Si l'avis existe déjà, le mettre à jour
        $query = "UPDATE rating_livre SET note = ?, avis = ? WHERE id_utilisateur = ? AND id_titre = ?";
        $stmt = $dbh->prepare($query);
        $result = $stmt->execute([$rating, $review, $userId, $bookId]);

        if ($result) {
            echo json_encode(["status" => "success", "message" => "Review updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database update error"]);
        }
    } else {
        // Si pas d'ancien avis, insérer le nouveau
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
