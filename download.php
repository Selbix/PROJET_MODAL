<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $bookId = intval($_GET['id']);
    $filePath = "books/" . $bookId . ".pdf"; // Définir le chemin vers le pdf

    if (file_exists($filePath)) { //Vérifie si le fichier existe sur le serveur
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"'); //Force le téléchargement avec un nom défini pour le fichier
        header('Content-Length: ' . filesize($filePath)); //Indique la taille du fichier
        readfile($filePath);
        exit;
    } else {
        die("Error: File not found.");
    }
} else {
    die("Invalid book ID.");
}
?>