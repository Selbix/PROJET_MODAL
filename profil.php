
<style>
.review-carousel-wrapper {
    position: relative;
}
a {
    text-decoration : none;
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
.liked-books h3 {
    margin-bottom: -50px;
    margin-top : 40px;
    font-family : 'Courier New', Courier, monospace;
}
.left2 {
    left: -20px;
}

.right2 {
    right: -20px;
}
.review-carousel-btn.left2 { margin-left: 40px; }
.review-carousel-btn.right2 { margin-right: 40px; }

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
    font-family : 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
}
p{
    font-family : 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
}

.star-rating {
    display: inline-block;
    font-size: 24px;
    color: gold;
}

.title-margin {

    margin-left: 150px;
    font-family : 'Courier New', Courier, monospace;
    font-weight: bold;
    z-index: 1000;
    
}
</style>

<style>
    .titre-livre {
        font-family : 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
    }
    .auteur-livre{
        font-family : 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
    }
        header h1 {
            margin-left: 20px;
            margin-right: 20px;
        }
        h2{
            color:rgba(207, 207, 207, 0.91);
        }
        
        .header{
            display : flex;
            background :rgb(125, 125, 125);
            opacity : 0.7;
            padding-top : 10px;
            padding-bottom : 10px;
            font-family : sans-serif;
            font: white;
            align-items : center;
        }
        .setts{
            display : flex;
            position : absolute;
            align-items : center;
            justify-content : center;
            height : 100%;
            right : 0;
            margin-right : 30px;
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
            color: rgb(98, 98, 98);

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
        /* From Uiverse.io by andrew-demchenk0 */ 
.button {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 6px 12px;
  gap: 8px;
  height: 36px;
  width: 220px;
  border: none;
  background: #5e41de33;
  border-radius: 20px;
  cursor: pointer;
}

.lable {
  line-height: 20px;
  font-size: 17px;
  color: #5D41DE;
  font-family: sans-serif;
  letter-spacing: 1px;
  width: 100%;
}

.button:hover {
  background: #5e41de4d;
}

.button:hover .svg-icon {
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

/* From Uiverse.io by vinodjangid07 */ 
.Btn-prof {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  width: 45px;
  height: 45px;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  margin-left : 15px;
  transition-duration: .3s;
  box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
  background-color: rgb(255, 65, 65);
}

/* plus sign */
.sign {
  width: 100%;
  transition-duration: .3s;
  display: flex;
  align-items: center;
  justify-content: center;

}

.sign svg {
  width: 17px;
}

.sign svg path {
  fill: white;
}
/* text */
.text {
  position: absolute;
  right: 0%;
  width: 0%;
  opacity: 0;
  color: white;
  font-size: 1.2em;
  font-weight: 600;
  transition-duration: .3s;
  margin-right : 10px;
}
/* hover effect on button width */
.Btn-prof:hover {
  width: 225px;
  border-radius: 40px;
  transition-duration: .3s;
}

.Btn-prof:hover .sign {
  width: 30%;
  transition-duration: .3s;
  padding-left: 20px;
  transform : translateX(-10px);
}
/* hover effect button's text */
.Btn-prof:hover .text {
  opacity: 1;
  width: 70%;
  transition-duration: .3s;
  padding-right: 10px;
}
/* button click effect*/
.Btn-prof:active {
  transform: translate(2px ,2px);
}

/* From Uiverse.io by eslam-hany */ 
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

.book {
  position: relative;
  border-radius: 10px;
  width: 50%;
  
  background-color: whitesmoke;
  -webkit-box-shadow: 1px 1px 12px #000;
  box-shadow: 1px 1px 12px #000;
  -webkit-transform: preserve-3d;
  -ms-transform: preserve-3d;
  transform: preserve-3d;
  -webkit-perspective: 2000px;
  perspective: 2000px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  color: #000;
}

.cover {
  top: 0;
  position: absolute;
  background-color: lightgray;
  width: 100%;
  height: 100%;
  border-radius: 10px;
  cursor: pointer;
  -webkit-transition: all 0.5s;
  transition: all 0.5s;
  -webkit-transform-origin: 0;
  -ms-transform-origin: 0;
  transform-origin: 0;
  -webkit-box-shadow: 1px 1px 12px #000;
  box-shadow: 1px 1px 12px #000;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
}

.book:hover .cover {
  -webkit-transition: all 0.5s;
  transition: all 0.5s;
  -webkit-transform: rotatey(-80deg);
  -ms-transform: rotatey(-80deg);
  transform: rotatey(-80deg);
}

p {
  font-size: 20px;
  font-weight: bolder;
}
.book p {
    margin-left : 10%;
}
.cover p{
    margin-left : 0;
}
    </style>


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
if(isset($_GET['id'])){
    $sth = $dbh->prepare("SELECT * FROM utilisateurs WHERE id = ?");
    $sth->execute([$_GET['id']]);
    $user = $sth->fetchAll(PDO::FETCH_ASSOC);
    $user = $user[0];
}
if (empty($user)) {
    echo 'Erreur : aucune information utilisateur disponible.';
    exit();
}

$user['image'] = 'uploads/' . $user['id'] . '.jpg';
if (!file_exists($user['image'])) {
    $user['image'] = 'uploads/default_profile.jpg';
}
//var_dump($user);
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
    <header class = "header">
        <h1 class="title-margin">Mon Profil</h1>
        <?php if (!isset($_GET["id"])){
            echo <<<FIN
            <div class="setts">
            <a href="index.php?page=modif-profile">
            <button class="button">
  <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 20 20" height="20" fill="none" class="svg-icon"><g stroke-width="1.5" stroke-linecap="round" stroke="#5d41de"><circle r="2.5" cy="10" cx="10"></circle><path fill-rule="evenodd" d="m8.39079 2.80235c.53842-1.51424 2.67991-1.51424 3.21831-.00001.3392.95358 1.4284 1.40477 2.3425.97027 1.4514-.68995 2.9657.82427 2.2758 2.27575-.4345.91407.0166 2.00334.9702 2.34248 1.5143.53842 1.5143 2.67996 0 3.21836-.9536.3391-1.4047 1.4284-.9702 2.3425.6899 1.4514-.8244 2.9656-2.2758 2.2757-.9141-.4345-2.0033.0167-2.3425.9703-.5384 1.5142-2.67989 1.5142-3.21831 0-.33914-.9536-1.4284-1.4048-2.34247-.9703-1.45148.6899-2.96571-.8243-2.27575-2.2757.43449-.9141-.01669-2.0034-.97028-2.3425-1.51422-.5384-1.51422-2.67994.00001-3.21836.95358-.33914 1.40476-1.42841.97027-2.34248-.68996-1.45148.82427-2.9657 2.27575-2.27575.91407.4345 2.00333-.01669 2.34247-.97026z" clip-rule="evenodd"></path></g></svg>
  <span class="lable">Modifier mon profil</span>
</button> </a>
<!-- From Uiverse.io by vinodjangid07 --> 
<a href="index.php?todo=logout">
<button class="Btn-prof">
  
  <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
  
  <div class="text">Se déconnecter</div>
</button>
</a>

</div>
FIN;
        } ?>
    </header>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-picture-container">
                <div class="profile-picture">
                    <img src="<?php echo htmlspecialchars($user['image']); ?>?v=<?php echo time(); ?>" alt="Photo de profil">
                </div>
            </div>
            <div class="profile-details">
                <h2><?php echo htmlspecialchars($user['username'] ?? $user['nom_utilisateur'] ?? 'Nom utilisateur'); ?></h2>
            </div>
        </div>
        <!-- <div class="quote-container"> -->
        <div class="book">
    <p><?php echo htmlspecialchars($user['quote'] ?? 'Aucune citation disponible :('); ?></p>
    <div class="cover">
        <p>Citations Préférée </p>
    </div>
   </div>
        <!--</div>-->
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
        <button class="review-carousel-btn left2" onclick="scrollReviewCarousel(-1)">&#8249;</button>
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
        <button class="review-carousel-btn right2" onclick="scrollReviewCarousel(1)">&#8250;</button>
    </div>
</div>

<script>
function scrollReviewCarousel(direction) {
    let carousel = document.querySelector(".review-carousel");
    carousel.scrollBy({ left: direction * 400, behavior: 'smooth' });
}
</script>
</div>
