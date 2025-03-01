<?php


// Inclure la connexion à la base de données
include('database.php'); // Remplacez par le fichier contenant votre connexion PDO

try {
    // Requête pour tirer un livre au hasard
    $query = "SELECT id FROM Livres ORDER BY RAND() LIMIT 1";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $randomBook = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($randomBook) {
        // Rediriger vers la page du livre sélectionné
        header("Location: index.php?id=" . $randomBook['id']);
        exit();
    } else {
        echo "Aucun livre trouvé dans la base de données.";
    }
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
