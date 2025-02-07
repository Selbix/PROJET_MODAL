<?php


if (!isset($_SESSION['loggedIn'])) {
    echo '
    <style>
        body {
            background-color: #263248;
            background-size: cover;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

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

        .blurred-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(10px);
            z-index: -1;
        }

        .message {
            background: rgba(0, 0, 0, 0.85);
            color: white;
            padding: 20px 40px;
            border-radius: 10px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.5);
            z-index: 1000;
        }
    </style>
    <div class="overlay">
        <div class="blurred-background"></div>
        <a href="index.php?page=connexion"><div class="message">Il faut être connecté pour pouvoir accéder aux livres. <br>Cliquez pour rejoindre la page de connexion.</div></a>
    </div>';
    exit();
}

$user = $_SESSION['user'] ?? [];
if (empty($user)) {
    echo 'Erreur : aucune information utilisateur disponible.';
    exit();
}

$user['image'] = 'uploads/' . $user['id'] . '.jpg';
if (!file_exists($user['image'])) {
    $user['image'] = 'uploads/default_profile.jpg';
}

// Connexion à la base de données
try {
    $dbh = new PDO('mysql:host=localhost;dbname=projet_sucre-sale', 'root', '');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
    exit();
}

// Récupérer les livres likés par l'utilisateur
$likedBooks = [];
try {
    $sth = $dbh->prepare("SELECT l.id, l.titre, l.auteur, l.extension AS cover FROM livres l INNER JOIN enregistrement_livres e ON l.id = e.id_titre WHERE e.id_utilisateur = ?");
    $sth->execute([$user['id']]);
    $likedBooks = $sth->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erreur lors de la récupération des livres : ' . $e->getMessage();
    exit();
}
?>
<div class="stockage">
    <header>
        <h1 class="title-margin">Mon Profil</h1>
    </header>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-picture-container">
                <div class="profile-picture">
                    <img src="<?php echo htmlspecialchars($user['image']); ?>?v=<?php echo time(); ?>" alt="Photo de profil">
                </div>
            </div>
            <div class="profile-details">
                <h2><?php echo htmlspecialchars($user['username'] ?? 'Nom utilisateur'); ?></h2>
            </div>
        </div>
        <div class="quote-container">
            <p><?php echo htmlspecialchars($user['quote'] ?? 'Aucune citation disponible.'); ?></p>
        </div>
    </div>
    <div class="liked-books">
        <h3 class="title-margin">Livres likés</h3>
        <div class="carousel">
            <?php foreach ($likedBooks as $book): ?>
            <div class="carousel-item">
                <a href="index.php?id=<?php echo htmlspecialchars($book['id']); ?>" target="_blank">
                    <div class="book-cover" style="background: url('thumbnail/<?php echo htmlspecialchars($book['id']); ?>.jpg' ) center/cover no-repeat;"></div>
                </a>
                <div class="book-title">
                    <p class="titre-livre"><?php echo htmlspecialchars($book['titre']); ?></p>
                    <p class="auteur-livre"><?php echo htmlspecialchars($book['auteur']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
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
    </script>


<?php
// Connexion à la base de données
try {
    $dbh = new PDO('mysql:host=localhost;dbname=projet_sucre-sale', 'root', '');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
    exit();
}

// Récupérer les avis de l'utilisateur
$userReviews = [];
try {
    $sth = $dbh->prepare("SELECT l.titre, r.avis, r.note FROM rating_livre r INNER JOIN livres l ON r.id_titre = l.id WHERE r.id_utilisateur = ?");
    $sth->execute([$user['id']]);
    $userReviews = $sth->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erreur lors de la récupération des avis : ' . $e->getMessage();
    exit();
}
?>

<div class="user-reviews">
    <h3 class="title-margin">Avis laissés</h3>
    <div class="review-carousel-wrapper">
        <button class="review-carousel-btn left" onclick="scrollReviewCarousel(-1)">&#8249;</button>
        <div class="review-carousel">
            <?php foreach ($userReviews as $review): ?>
            <div class="review-item">
                <p class="review-title"><?php echo htmlspecialchars($review['titre']); ?></p>
                <div class="review-content">
                    <p><?php echo htmlspecialchars($review['avis']); ?></p>
                    <div class="star-rating">
                        <?php
                        $note = $review['note'];
                        for ($i = 1; $i <= 5; $i++) {
                            echo "<span class='star' data-rating='$i'>" . ($i <= $note ? "★" : "☆") . "</span>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <button class="review-carousel-btn right" onclick="scrollReviewCarousel(1)">&#8250;</button>
    </div>
</div>

<script>
function scrollReviewCarousel(direction) {
    let carousel = document.querySelector(".review-carousel");
    carousel.scrollBy({ left: direction * 400, behavior: 'smooth' });
}
</script>
</div>

<style>
.review-carousel-wrapper {
    position: relative;
}

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

.left {
    left: -20px;
}

.right {
    right: -20px;
}

.review-carousel {
    display: flex;
    overflow-x: auto;
    scroll-behavior: smooth;
    padding: 10px;
}

.review-item {
    min-width: 300px;
    margin: 10px;
    padding: 20px;
    background-color: #444;
    color: white;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.review-title {
    font-weight: bold;
    margin-bottom: 10px;
}

.star-rating {
    display: inline-block;
    font-size: 24px;
    color: gold;
}

.title-margin {
    margin-left: 20px;
    margin-right: 20px;
}
</style>

<style>
        header h1 {
            margin-left: 20px;
            margin-right: 20px;
        }
        h2{
            color: #2d4b2d;
        }

        .profile-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color:rgb(212, 212, 212);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .profile-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            width: 40%;
        }

        .profile-picture img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-details h2 {
            text-align: center;
        }

        .quote-container {
            width: 50%;
            background-color: #333;
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-left: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .liked-books {
            width: 100%;
            margin-top: 20px;
        }

        .carousel-item {
            text-align: center;
            margin: 10px;
        }

        .book-cover {
    width: 100px;
    height: 150px;
    background-size: contain;  /* Ensures the whole image fits */
    background-position: center;
    background-repeat: no-repeat;  /* Prevents repeating */
    margin: 0 auto;
    border: 1px solid #ddd; /* Optional: adds a subtle border */
}

        .book-title {
            font-size: 14px;
            margin-top: 10px;
        }

        .auteur-livre {
            font-size: 12px;
            color: #555;
        }

        .title-margin {
            margin-left: 20px;
            margin-right: 20px;
        }
    </style>


