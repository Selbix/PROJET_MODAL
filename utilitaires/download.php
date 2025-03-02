
<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $bookId = intval($_GET['id']);
    $filePath = $_SERVER['DOCUMENT_ROOT'] . "/PROJET_MODAL/BDD-gestion/books/" . $bookId . ".pdf";

    if (file_exists($filePath)) {
        // Set headers to force download
        header('Content-Type: /application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        die("Error: File not found.");
    }
} else {
    die("Invalid book ID.");
}
?>