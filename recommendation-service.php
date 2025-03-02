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

    if (empty($userInteractions)) {
        usort($books, function($a, $b) {
            return $b['id'] - $a['id'];
        });
        return array_slice($books, 0, $numRecommendations);
    }

    $recommendedBooks = array_slice($books, 0, $numRecommendations);
    return $recommendedBooks;
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