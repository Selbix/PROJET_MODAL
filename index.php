<?php
require "utils.php";
require "logInOut.php";
require "database.php";
require "utilisateur.php";
require "printForms.php";
require "livre.php";
require "register.php";
session_name("Session_utilisateur");
session_start();
if (!isset($_SESSION['initiated'])){
    session_regenerate_id();
    $_SESSION['initiated']= true;
}

$dbh = Database::connect();
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
if(isset($_GET["todo"]) && $_GET["todo"] == "logout") {
    logOut();
}
//var_dump($_SESSION);
echo "<br>";*/
//var_dump(password_verify("secret", '$2y$10$jFPYCoqfq5IrY1I0xASPX.OVLJxDHUR0G2S5OunyeI5LsnIwuvo8m'));
// affichage de formulaires

// code de sélection des pages, comme précédemment

?>


<div class="container-fluid ontop">
    <?php echo generateMenu(); ?>
</div>

    <div id="content" class="content">
    <?php require "{$askedPage}.php";
    var_dump($_SESSION);
    echo "<h1>TEST GIT</h1>";
    echo "<h2> TEST BRANCHE </h2>";
    echo "<h3> last test </h3>";
    ?>
    </div>



