<?php
require "utils.php";
require "logInOut.php";
require "database.php";
require "utilisateur.php";
require "printForms.php";
require "livre.php";
require "register.php";
require "changePassword.php";
session_name("Session_utilisateur");
session_start();
if (!isset($_SESSION['initiated'])){
    session_regenerate_id();
    $_SESSION['initiated']= true;
}

$dbh = Database::connect();

// Gestion de la recherche
$searchResults = null;
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']);
    try {
        // Modification de la requête pour chercher spécifiquement dans le titre
        $query = "SELECT id, titre, auteur FROM Livres WHERE titre LIKE :search";
        $stmt = $dbh->prepare($query);
        $stmt->execute(['search' => '%' . $searchTerm . '%']);
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Si on a des résultats, on les stocke
        if ($searchResults) {
            $_SESSION['search_results'] = $searchResults;
        } else {
            // Si aucun résultat, tableau vide
            $_SESSION['search_results'] = [];
        }
    } catch(PDOException $e) {
        error_log("Erreur de recherche : " . $e->getMessage());
        $_SESSION['search_results'] = [];
    }
}

if (isset($_GET['todo'])) {
    switch ($_GET['todo']) {
        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                //var_dump($_POST);
                logIn($dbh);
            }
            break;
            
        case 'register':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                handleRegistration($dbh);
            }
            break;
            
        case 'logout':
            logOut();
            header("Location: index.php?page=connexion");
            exit();
            break;
        case 'changePassword':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                changePassword($dbh);
            }
            break;
    }
}


if(isset($_GET['page'])){
    $askedPage = $_GET["page"];
    $authorized = checkPage($askedPage);
$titre = "Passeur de temps";
$lien = "styles.css";
$pageTitle = getPageTitle($askedPage);
if($askedPage == "connexion"){
    $lien = "styles-loginout.css";
}
}
else if(isset($_GET['id'])){
    $askedPage = "page_livre";
    $pageTitle = "Livre";
    $lien = "styles.css";
    $authorized = checkPage($askedPage);
}

echo generateHTMLHeader($pageTitle, $lien);
## ADAPTER CODE LOGIN/OUT POUR LA NOUVELLE BDD
/*
$totaux = (Utilisateur::getUtilisateur($dbh, "barry.allen"));
// traitement des contenus de formulaires
if(isset($_GET["todo"]) && $_GET["todo"] == "login") {
    //echo "ICI";
    logIn($dbh);
}

//var_dump($_SESSION);
echo "<br>";*/
//var_dump(password_verify("secret", '$2y$10$jFPYCoqfq5IrY1I0xASPX.OVLJxDHUR0G2S5OunyeI5LsnIwuvo8m'));
// affichage de formulaires

// code de sélection des pages, comme précédemment

?>

<script>
var loggedInUser = <?= json_encode($_SESSION['user'] ?? null) ?>;
console.log("Logged in user:", loggedInUser);
</script>

<?php
$user_id = $_SESSION['user']['id'];
$stmt = $dbh->prepare("SELECT id FROM administateurs WHERE id_utilisateurs = ?");
$stmt->execute([$user_id]);
$admin = $stmt->fetch();
if($admin){
    echo '<a href="admin.php" class="floating-admin-button">Admin Panel</a>';
}
if(isset($_GET['page']) && $_GET['page'] === 'connexion' && isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
    header("Location: index.php?page=accueil");
    exit();
}
?>

<div class="container-fluid ontop">
    <?php echo generateMenu(); ?>
</div>
    <div id="content" class="content">
    <?php require "{$askedPage}.php";
    //var_dump($_SESSION);
    ?>
    </div>

</html>

