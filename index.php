<?php
// Inclusion des fichiers nécessaires 
require "utils.php";        
require "logInOut.php";     
require "database.php";     
require "utilisateur.php";  
require "printForms.php";   
require "livre.php";        
require "register.php";     
require "changePassword.php"; 
require "recommendation-service.php";

// Initialisation de la session avec un nom personnalisé
session_name("Session_utilisateur");
session_start();

// Si la session n'est pas encore initialisée, on régénère l'ID de session
if (!isset($_SESSION['initiated'])){
    session_regenerate_id();
    $_SESSION['initiated']= true;
}

// Connexion à la base de données
$dbh = Database::connect();

// Gestion de la recherche d'un livre
$searchResults = null;
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']); // Sécurisation du terme de recherche
    try {
        // Modification de la requête pour chercher spécifiquement dans le titre des livres
        $query = "SELECT id, titre, auteur FROM Livres WHERE titre LIKE :search";
        $stmt = $dbh->prepare($query);
        $stmt->execute(['search' => '%' . $searchTerm . '%']);
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Si des résultats sont trouvés, on les stocke dans la session
        if ($searchResults) {
            $_SESSION['search_results'] = $searchResults;
        } else {
            // Si aucun résultat, on stocke un tableau vide
            $_SESSION['search_results'] = [];
        }
    } catch(PDOException $e) {
        error_log("Erreur de recherche : " . $e->getMessage());
        $_SESSION['search_results'] = []; // Si une erreur survient, tableau vide
    }
}

// Gestion des actions spécifiques 
if (isset($_GET['todo'])) {
    switch ($_GET['todo']) {
        case 'login':
            // Si l'action est 'login', on appelle la fonction logIn
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                //var_dump($_POST);
                logIn($dbh);
            }
            break;
            
        case 'register':
            // Si l'action est 'register', on appelle la fonction handleRegistration
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                handleRegistration($dbh);
            }
            break;
            
        case 'logout':
            // Si l'action est 'logout', on appelle logOut et redirige vers la page de connexion
            logOut();
            header("Location: index.php?page=connexion");
            exit();
            break;

        case 'changePassword':
            // Si l'action est 'changePassword', on appelle la fonction changePassword
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                changePassword($dbh);
            }
            break;
    }
}

// Vérification de la page demandée via le paramètre 'page'
if(isset($_GET['page'])){
    $askedPage = $_GET["page"];
    $authorized = checkPage($askedPage); // Vérifie si la page est autorisée
    $titre = "Passeur de temps"; // Titre par défaut
    $lien = "styles.css"; // Fichier CSS par défaut
    $pageTitle = getPageTitle($askedPage); // Récupère le titre de la page demandée
    if($askedPage == "connexion"){ // Si la page est "connexion", on change le fichier CSS
        $lien = "styles-loginout.css";
    }
}
else if(isset($_GET['id'])){
    // Si un ID de livre est passé dans l'URL, on charge la page du livre
    $askedPage = "page_livre";
    $pageTitle = "Livre";
    $lien = "styles.css";
    $authorized = checkPage($askedPage); // Vérifie si la page est autorisée
}

// Affichage de l'en-tête HTML avec le titre et le fichier CSS approprié
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
// Vérification si l'utilisateur connecté est un administrateur
$user_id = $_SESSION['user']['id'];
$stmt = $dbh->prepare("SELECT id FROM administateurs WHERE id_utilisateurs = ?");
$stmt->execute([$user_id]);
$admin = $stmt->fetch();
if($admin){
    // Si l'utilisateur est administrateur, afficher un lien vers le panneau d'administration
    echo '<a href="admin.php" class="floating-admin-button">Admin Panel</a>';
}

// Redirection si l'utilisateur est déjà connecté et essaie d'accéder à la page de connexion
if(isset($_GET['page']) && $_GET['page'] === 'connexion' && isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
    header("Location: index.php?page=accueil");
    exit();
}
?>

<!-- Début du contenu principal de la page -->
<div class="container-fluid ontop">
    <?php echo generateMenu(); ?>
</div>
<div id="content" class="content">
    <?php
    // Inclus dynamiquement la page demandée par l'utilisateur
    require "{$askedPage}.php";
    ?>
</div>

</html>

