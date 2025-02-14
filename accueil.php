<?php
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
    $query = "SELECT id, titre, auteur FROM Livres";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $numBooks = count($books);
}



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
?>



