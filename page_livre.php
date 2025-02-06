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
$user = $_SESSION['user'];

if (isset($_GET['id'])) {
    $askedLivre = $_GET["id"];
    $askedLivre = intval($askedLivre); // Sanitizing the input

    $query = "SELECT * FROM Livres WHERE id = ?;";
    $stmt = $dbh->prepare($query);
   //$stmt->bindParam(':id', $askedLivre, PDO::PARAM_INT);
    $stmt->execute(array($askedLivre));


    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    /*echo "<script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.pathname);
        }
    </script>";*/
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
//var_dump($books);
?>

<?php
$dbh = Database::connect();
//var_dump($user);
// Fetch the latest reviews with user names
$query = "SELECT u.id AS user_id, u.nom_utilisateur AS user_name, r.note, r.avis 
          FROM rating_livre r
          JOIN Utilisateurs u ON r.id_utilisateur = u.id
          WHERE r.id_titre = ?  -- Filter by book ID
          AND r.avis IS NOT NULL 
          AND r.avis != ''
          ORDER BY r.id DESC
          LIMIT 10";  // Adjust the limit as needed

$stmt = $dbh->prepare($query);
$stmt->execute([$books[0]['id']]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
var_dump($reviews);
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
                <a href="download.php?id=<?php echo $books[0]['id']; ?>" class="btn2 btn-download2">Télécharger</a>
                <a target = "_blank" href="books/<?php echo $books[0]['id']; ?>.pdf" class="btn2 btn-read2">Lire</a>
                <button class="btn2 btn-like2" onclick="likeBook(<?php echo $books[0]['id']; ?>, <?php echo $_SESSION['user']['id']; ?>)">👍</button>
                </div>
                <div class="rating2">
                <div class="rating2">
    <div class="star-rating">
        <?php
        // Get the average rating for this book (you'll need to implement this)
        $averageRating = 0; // Replace with actual average rating
        
        for ($i = 1; $i <= 5; $i++) {
            echo "<span class='star' data-rating='$i'>★</span>";
        }
        ?>
    </div>
    <div>Nombre de votants</div>
</div>
<footer class="review-footer">
<div class="review-carousel-container">
    <h2>Avis des lecteurs</h2>
    <div class="review-wrapper">
        <button class="review-carousel-btn left" onclick="scrollReviewCarousel(-1)">❮</button>
        <div class="review-carousel">
            <?php if (empty($reviews)): ?>
                <!-- Show message when no reviews exist -->
                <div class="no-reviews-card">
                    Aucun avis pour l'instant... Soyez le premier !
                </div>
            <?php else: ?>
                <!-- Display reviews -->
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <img class="image-review" alt="user-pfp" src=<?php echo "uploads/".$review['user_id'].".jpg"; ?>>
                            <strong><?= htmlspecialchars($review['user_name']); ?></strong>
                            <div class="review-rating">
                                <?php
                                $rating = intval($review['note']);
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $rating) {
                                        echo '<span class="review-star review-filled">★</span>';
                                    } else {
                                        echo '<span class="review-star review-empty">★</span>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="review-content">
                            <?= htmlspecialchars($review['avis']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button class="review-carousel-btn right" onclick="scrollReviewCarousel(1)">❯</button>
    </div>
</div>
</footer>

<style>
.review-carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.6);
    color: white;
    border: none;
    font-size: 24px;
    cursor: pointer;
    padding: 10px;
    border-radius: 5px;
}

.left { left: 10px; }
.right { right: 10px; }
</style>

<script>
function scrollReviewCarousel(direction) {
    let carousel = document.querySelector(".review-carousel");
    carousel.scrollBy({ left: direction * 400, behavior: 'smooth' });
}
</script>





        </div>
    </div>

    <div id="reviewModal" class="review-modal">
    <div class="review-modal-content">
        <span class="review-close">&times;</span>
        <h2>Ecrire un avis (Optionel)</h2>
        <textarea id="reviewText" class="review-textarea" placeholder="Écrivez votre avis ici..."></textarea>
        <button id="submitReview" class="review-submit-btn">Publier</button>
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
            alert("Livre ajouté à vos livres likés! ✅");
        } else if (data.message === "Already liked") {
            alert("Vous avez déjà liké ce livre. ❤️");
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => console.error("Error:", error));
}
</script>



<script>
function submitReview(bookId, userId, rating) {
    let reviewText = document.getElementById("reviewText").value.trim(); // Get review text

    let formData = new FormData();
    formData.append("book_id", bookId);
    formData.append("user_id", userId);
    formData.append("rating", rating);
    formData.append("review", reviewText);

    fetch("submit_review.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Debugging
        if (data.status === "success") {
            alert("Avis publié! ⭐");
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => console.error("Error submitting review:", error));
}
document.addEventListener("DOMContentLoaded", function () {
    const stars = document.querySelectorAll(".star-rating .star");
    let selectedRating = 0;
    const modal = document.getElementById("reviewModal");
    const closeModal = document.querySelector(".review-close");
    const submitButton = document.getElementById("submitReview");
    const reviewText = document.getElementById("reviewText");

    // ⭐ Handle Star Click Event (Open Modal)
    stars.forEach((star) => {
        star.addEventListener("click", function () {
            selectedRating = this.getAttribute("data-rating");
            modal.style.display = "flex"; // Show modal
        });
    });

    // ❌ Close Modal When Clicking 'X'
    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // 📩 Submit Rating & Review
    submitButton.addEventListener("click", function () {
        if (!loggedInUser || !loggedInUser.id) {
            alert("Vous devez être connecté pour laisser un avis.");
            return;
        }
        submitReview(<?= $books[0]['id']; ?>, loggedInUser.id, selectedRating);
        modal.style.display = "none";
        reviewText.value = ""; // Clear the input
    });
});

</script>