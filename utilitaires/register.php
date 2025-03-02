<?php
// Inclusion des fichiers nécessaires
require_once $_SERVER['DOCUMENT_ROOT'] . "/PROJET_MODAL/BDD-gestion/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/PROJET_MODAL/BDD-gestion/utilisateur.php";

//Fonction d'inscription
function handleRegistration($dbh) {
    if (!isset($_GET['todo']) || $_GET['todo'] !== 'register') {
        return false;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = isset($_POST['username']) ? trim(htmlspecialchars($_POST['username'])) : '';
        $fullname = isset($_POST['fullname']) ? trim(htmlspecialchars($_POST['fullname'])) : '';
        $email = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $quote = isset($_POST['quote']) ? trim(htmlspecialchars($_POST['quote'])) : null;
        $quote = "Cet utilisateur n'a pas encore de citation préférée.";
        if (empty($username) || empty($fullname) || empty($email) || empty($password)) {
            header("Location: index.php?page=connexion&error=empty_fields");
            exit();
        }

        try {
            // Utilisation du constructeur pour simplifier la création de l'utilisateur
            $nouvelUtilisateur = new Utilisateur([
                'nom_utilisateur' => $username,
                'nom_complet' => $fullname,
                'email' => $email,
                'password' => $password,
                'quote' => $quote
            ]);

            if ($nouvelUtilisateur->save($dbh)) {
                header("Location: index.php?page=connexion&success=registration_completed");
            } else {
                header("Location: index.php?page=connexion&error=registration_failed");
            }
        } catch (PDOException $e) {
            error_log("Erreur d'inscription: " . $e->getMessage());
            header("Location: index.php?page=connexion&error=registration_failed");
        }
        exit();
    }
    return true;
}
// Ne rien exécuter directement - le code sera appelé depuis index.php
?>