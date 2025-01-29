<?php

if (isset($_GET['id'])) {
    $askedLivre = $_GET["id"];
    $askedLivre = intval($askedLivre); // Sanitizing the input
    $query = "SELECT * FROM Livres WHERE id = :id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':id', $askedLivre, PDO::PARAM_INT);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($books) > 0) {
        $book = $books[0];
    } else {
        echo "Book not found.";
        exit;
    }
} else {
    echo '<div class = "invalid"><h1 >Invalid book ID. </h1></div>';
    exit;
}

echo generateHTMLHeader($books[$askedLivre]["titre"], "styles.css");
//var_dump($books);
//echo "<h1>". $book['titre']. "</h1>";
?>

<div class="book-page">
    <h1><?php echo htmlspecialchars($book["titre"]); ?></h1>
    <p><strong>Author:</strong> <?php echo htmlspecialchars($book["auteur"]); ?></p>
    <p><strong>Publication Date:</strong> <?php echo htmlspecialchars($book["date_publication"]); ?></p>
    <p><?php echo htmlspecialchars($book["description"]); ?></p>
    <div class="book-actions">
        <button><a href="download.php?id=<?php echo $book['id']; ?>">Download</a></button>
        <button><a href="read.php?id=<?php echo $book['id']; ?>">Read</a></button>
    </div>
</div>

<?php
echo "</body>";
?>