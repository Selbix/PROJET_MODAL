<?php

if (isset($_GET['id'])) {
    $askedLivre = $_GET["id"];
    $askedLivre = intval($askedLivre); // Sanitizing the input

    $query = "SELECT * FROM Livres WHERE id = ?;";
    $stmt = $dbh->prepare($query);
   //$stmt->bindParam(':id', $askedLivre, PDO::PARAM_INT);
    $stmt->execute(array($askedLivre));


    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debugging output (Optional)
    if (!$books) {
        echo "No book found.";
    }
} else {
    echo "Invalid book ID.";
}


echo generateHTMLHeader($books[0]["titre"], "styles.css");
//var_dump($books);
//echo "<h1>". $book['titre']. "</h1>";
var_dump($books);
?>

<div class="book-container2">
        <div class="book-image2">
            <img src="thumbnail/<?php echo $books[0]['id']; ?>.jpg" alt="<?php echo htmlspecialchars($books[0]['titre']); ?>">
        </div>
        <div class="book-details2">
            <h1 class="book-title2"><?php echo htmlspecialchars($books[0]['titre']); ?></h1>
            <div class="book-subtitle2">
                <?php echo htmlspecialchars($books[0]['auteur']); ?> - 
                <?php echo htmlspecialchars($books[0]['date_parution']); ?> - 
                <?php echo htmlspecialchars($books[0]['genre']); ?>
            </div>
            <div class="book-description2">
                <?php echo htmlspecialchars($books[0]['description']); ?>
            </div>
            <div class="action-buttons2">
                <a href="download.php?id=<?php echo $books[0]['id']; ?>" class="btn2 btn-download2">Download</a>
                <a href="read.php?id=<?php echo $books[0]['id']; ?>" class="btn2 btn-read2">Read</a>
                <button class="btn2 btn-like2">👍</button>
            </div>
            <div class="rating2">
                <div class="stars2">★★★★★</div>
                <div>Nombre de votants</div>
            </div>
        </div>
    </div>
</body>