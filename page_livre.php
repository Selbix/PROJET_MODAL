<?php
if (!isset($_SESSION['loggedIn'])) {
    echo '
    <style>
        /* Ensure the body background is blurred */
        body {
            background-color: #263248;
            background-size: cover;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Create an overlay that blurs the entire page */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        /* Blurred background without affecting text */
        .blurred-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(10px);
            z-index: -1; /* Ensure it stays behind the message */
        }

        /* Message box (kept clear) */
        .message {
            background: rgba(0, 0, 0, 0.85); /* Dark box for contrast */
            color: white;
            padding: 20px 40px;
            border-radius: 10px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.5);
            z-index: 1000; /* Keep it above everything */
        }
    </style>
    <div class="overlay">
        <div class="blurred-background"></div> <!-- Background blur -->
        <a href="index.php?page=connexion"><div class="message">Il faut être connecté pour pouvoir accéder aux livres. <br>
        Cliquez pour rejoindre la page de connexion.</div></a> <!-- Clear Message -->
    </div>';
    exit();
}


if (isset($_GET['id'])) {
    $askedLivre = $_GET["id"];
    $askedLivre = intval($askedLivre); // Sanitizing the input

    $query = "SELECT * FROM Livres WHERE id = ?;";
    $stmt = $dbh->prepare($query);
   //$stmt->bindParam(':id', $askedLivre, PDO::PARAM_INT);
    $stmt->execute(array($askedLivre));


    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.pathname);
        }
    </script>";
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
                <a target = "_blank" href="books/<?php echo $books[0]['id']; ?>.pdf" class="btn2 btn-read2">Read</a>
                <button class="btn2 btn-like2" onclick="likeBook(<?php echo $books[0]['id']; ?>, <?php echo $_SESSION['user']['id']; ?>)">👍</button>
                </div>
            <div class="rating2">
                <div class="stars2">★★★★★</div>
                <div>Nombre de votants</div>
            </div>
        </div>
    </div>
</body>
<script>
function likeBook(bookId, userId) {
    let formData = new FormData();
    formData.append("book_id", bookId);
    formData.append("user_id", userId); // Send user ID from frontend

    fetch("like.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Debugging
        if (data.status === "success") {
            alert("Book liked successfully! ✅");
        } else if (data.message === "Already liked") {
            alert("You have already liked this book. ❤️");
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => console.error("Error:", error));
}
</script>
<script>
function sendEmail(bookId, bookTitle, userEmail) {
    let formData = new FormData();
    formData.append("book_id", bookId);
    formData.append("book_title", bookTitle);
    formData.append("user_email", userEmail);

    fetch("send_email.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("📩 Email sent successfully to " + userEmail);
        } else {
            alert("❌ Error sending email: " + data.message);
        }
    })
    .catch(error => console.error("Error:", error));
}
</script>