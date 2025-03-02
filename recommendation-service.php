<?php
require_once 'database.php';

function getUserInteractions($userId, $conn) {
    $stmt = $conn->prepare("SELECT id_titre, note FROM rating_livre WHERE id_utilisateur = :userId");
    $stmt->bindValue(":userId", $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getBookMetadata($conn) {
    $stmt = $conn->prepare("SELECT id, titre, auteur, genre, description FROM Livres");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function recommendBooks($userId, $conn, $numRecommendations = 5) {
    $userInteractions = getUserInteractions($userId, $conn);
    $books = getBookMetadata($conn);

    // Si l'utilisateur n'a noté aucun livre, recommander les derniers livres ajoutés
    if (empty($userInteractions)) {
        usort($books, function($a, $b) {
            return $b['id'] - $a['id'];
        });
        return array_slice($books, 0, $numRecommendations);
    }

    // Récupère la liste des ID des livres notés par l'utilisateur
    $ratedBookIds = array_column($userInteractions, 'id_titre');

    // Trouver des utilisateurs ayant noté les mêmes livres que l'utilisateur actuel
    $stmt = $conn->prepare("
        SELECT DISTINCT id_utilisateur 
        FROM rating_livre 
        WHERE id_titre IN (" . implode(',', $ratedBookIds) . ") 
        AND id_utilisateur != :userId
    ");
    $stmt->bindValue(":userId", $userId, PDO::PARAM_INT);
    $stmt->execute();
    $similarUsers = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($similarUsers)) {
        return array_slice($books, 0, $numRecommendations);
    }

    // Trouver les livres les mieux notés par ces utilisateurs similaires - recommandation collaborative
    $stmt = $conn->prepare("
        SELECT id_titre, AVG(note) as avg_rating 
        FROM rating_livre 
        WHERE id_utilisateur IN (" . implode(',', $similarUsers) . ") 
        AND id_titre NOT IN (" . implode(',', $ratedBookIds) . ") 
        GROUP BY id_titre 
        ORDER BY avg_rating DESC 
        LIMIT :num
    ");
    $stmt->bindValue(":num", $numRecommendations, PDO::PARAM_INT);
    $stmt->execute();
    $recommendedBookIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($recommendedBookIds)) {
        return array_slice($books, 0, $numRecommendations);
    }

    // Filtrer et récupérer les métadonnées des livres recommandés
    $recommendedBooks = array_filter($books, function ($book) use ($recommendedBookIds) {
        return in_array($book['id'], $recommendedBookIds);
    });

    return array_values($recommendedBooks);
}



function displayRecommendations($userId, $conn, $numRecommendations = 5) {
    $recommendations = recommendBooks($userId, $conn, $numRecommendations);
    
    echo '<div class="container-ajouts">';
    echo "<h2>Recommandations personnalisées</h2>";
    echo '</div>';
    
    echo '<div class="carousel">';
    foreach ($recommendations as $book) {
        $url = "index.php?id=" . htmlspecialchars($book['id']);
        $thumbnailPath = "thumbnail/" . htmlspecialchars($book['id']) . ".jpg";
        
        echo '<div class="carousel-item">';
        echo "<a href='$url' target=_blank>";
        echo '<div class="book-cover" style="background: url(' . htmlspecialchars($thumbnailPath) . ') center/cover no-repeat;">';
        echo '</div>';
        echo '</a>';
        echo '<div class="book-title"> <p class="titre-livre">' . htmlspecialchars($book['titre']) . '</p></div>';
        echo '</div>';
    }
    echo '</div>';
}
/*
try {
    $dbh = Database::connect();
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $userId = $_SESSION['user']['id'] ?? 1; 
    displayRecommendations($userId, $dbh);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}*/
?>