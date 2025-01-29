<?php
$query = "SELECT id, titre, auteur FROM Livres";
$stmt = $dbh->prepare($query);
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
$numBooks = count($books);
//var_dump($page_list);
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
   //$url = "books/" . $books[$i]["id"] . ".pdf";
   $url = "index.php?id=" . $books[$i]["id"];
   $thumbnailPath = "thumbnail/" . $books[$i]["id"] . ".jpg"; // Path to the thumbnail image
   echo '<div class="carousel-item">';
   echo "<a href=$url target=_blank>";
   echo '<div class="book-cover" style="background: url(' . htmlspecialchars($thumbnailPath) . ') center/cover no-repeat;">';
   echo '</div>';
   echo '</a>';
   echo '<div class="book-title">' . htmlspecialchars($books[$i]['titre']) . '</div>';
   echo '</div>';
}
echo '</div>';
/* foreach ($books as $book) {
    $url = "books/" . $book["id"] . ".pdf";
    echo '<div class="carousel-item">';
    echo "<a href=$url target=_blank>";
    echo '<div class="book-cover">';
    echo htmlspecialchars($book["titre"]);
    echo '</div>';
    echo '</a>';
    echo '<div class="book-title">' . htmlspecialchars($book['titre']) . '</div>';
    echo '</div>';
} */


    
?>