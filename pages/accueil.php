<?php
// Vérification si les paramètres 'search' ou 'genre' sont passés dans l'URL
if (isset($_GET['search']) || isset($_GET['genre'])) {
    $searchTerm = $_GET['search'];
    $selectedGenre = $_GET['genre'];

    // Construire la requête SQL avec le filtre de genre
    $sql = "SELECT id, titre, auteur FROM Livres WHERE titre LIKE :searchTerm";
    if (!empty($selectedGenre)) {
        $sql .= " AND genre = :selectedGenre";
    }

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
    if (!empty($selectedGenre)) {
        $stmt->bindValue(':selectedGenre', $selectedGenre, PDO::PARAM_STR);
    }

    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $numBooks = count($books);
    
    // Ajout de la condition pour afficher le message si aucun résultat
    if ($numBooks == 0) {
        echo '<div class="container-ajouts">';
        echo '<h2>Aucun élément ne correspond à votre recherche</h2>';
        echo '</div>';
        exit(); // On arrête l'exécution pour ne pas afficher le carrousel vide
    }
} else {
    // Si aucun paramètre de recherche n'est passé, récupérer tous les livres triés par ID décroissant
    $query = "SELECT id, titre, auteur FROM Livres ORDER BY id DESC";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $numBooks = count($books);
}

// Inclusion des scripts nécessaires pour le carrousel

echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>';
echo '<script>
        $(document).ready(function() {
            $(".carousel").slick({
                infinite: true,
                slidesToShow: 5,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                arrows: true,
                dots: true,
            });
        });
      </script>';

// Affichage de la section des ajouts récents

echo '<div class="container-ajouts">';
echo '<h2> Ajouts récents </h2>';
echo '</div>';
echo '<div class="carousel">';
for ($i = 0; $i < $numBooks; $i++) {
   $url = "index.php?id=" . $books[$i]["id"];
   $thumbnailPath = "thumbnail/" . $books[$i]["id"] . ".jpg";
   echo '<div class="carousel-item">';
   echo "<a href='$url' target=_blank>";
   echo '<div class="book-cover" style="background: url(' . htmlspecialchars($thumbnailPath) . ') center/cover no-repeat;">';
   echo '</div>';
   echo '</a>';
   echo '<div class="book-title"> <p class="titre-livre">' . htmlspecialchars($books[$i]['titre']) . '</p></div>';
   echo '</div>';
}
echo '</div>';

// Affichage des livres les plus populaires

$query = "SELECT 
    L.id,
    L.titre,
    L.auteur,
    COUNT(R.id) AS nombre_notes,
    AVG(R.note) AS moyenne_notes
FROM Livres L
LEFT JOIN rating_livre R ON L.id = R.id_titre
GROUP BY L.id, L.titre, L.auteur
ORDER BY nombre_notes DESC, moyenne_notes DESC;";
$stmt = $dbh->prepare($query);
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
$numBooks = count($books);

echo '<div class="container-ajouts">';
echo '<h2>Les plus populaires</h2>';
echo '</div>';
echo '<div class="carousel">';

// Boucle pour afficher les livres populaires
for ($i = 0; $i < $numBooks; $i++) {
   $url = "index.php?id=" . $books[$i]["id"];
   $thumbnailPath = "thumbnail/" . $books[$i]["id"] . ".jpg";
   echo '<div class="carousel-item">';
   echo "<a href='$url' target=_blank>";
   echo '<div class="book-cover" style="background: url(' . htmlspecialchars($thumbnailPath) . ') center/cover no-repeat;">';
   echo '</div>';
   echo '</a>';
   echo '<div class="book-title"> <p class="titre-livre">' . htmlspecialchars($books[$i]['titre']) . '</p></div>';
   echo '</div>';
}
echo '</div>';

    // Sélection d'un genre aléatoire et affichage des livres associés
    $genreQuery = "SELECT genre FROM Livres ORDER BY RAND() LIMIT 1";
    $genreStmt = $dbh->prepare($genreQuery);
    $genreStmt->execute();
    $randomGenre = $genreStmt->fetchColumn();

    // Chercher les livres du genre choisi
    $query = "SELECT 
        L.id,
        L.titre,
        L.auteur,
        COUNT(R.id) AS nombre_notes,
        AVG(R.note) AS moyenne_notes
    FROM Livres L
    LEFT JOIN rating_livre R ON L.id = R.id_titre
    WHERE L.genre = :genre
    GROUP BY L.id, L.titre, L.auteur
    ORDER BY nombre_notes DESC, moyenne_notes DESC
    LIMIT 10";

    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':genre', $randomGenre, PDO::PARAM_STR);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $numBooks = count($books);

    echo '<div class="container-ajouts">';
    echo "<h2>Sélection aléatoire du genre : <span class='selected-genre'>" . htmlspecialchars($randomGenre) . "</span></h2>";
    echo '</div>';
    
    echo '<div class="carousel">';
    for ($i = 0; $i < $numBooks; $i++) {
        $url = "index.php?id=" . $books[$i]["id"];
        $thumbnailPath = "thumbnail/" . $books[$i]["id"] . ".jpg";
        
        echo '<div class="carousel-item">';
        echo "<a href='$url' target=_blank>";
        echo '<div class="book-cover" style="background: url(' . htmlspecialchars($thumbnailPath) . ') center/cover no-repeat;">';
        echo '</div>';
        echo '</a>';
        echo '<div class="book-title"> <p class="titre-livre">' . htmlspecialchars($books[$i]['titre']) . '</p></div>';
        echo '</div>';
    }
    echo '</div>';
    displayRecommendations($_SESSION["user"]["id"], $dbh, $numRecommendations = 10);
?>



