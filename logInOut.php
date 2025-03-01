<?php

// Fonction pour gérer la connexion des utilisateurs
function logIn($dbh) {
    // Vérification si la méthode de la requête est POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return false; 
    }

    // Récupération des données du formulaire, en les nettoyant
    $login = filter_var(trim($_POST['login'] ?? ''), FILTER_SANITIZE_EMAIL); 
    $password = trim($_POST['password'] ?? ''); 

    // Si le login ou le mot de passe sont vides, on redirige avec un message d'erreur
    if (empty($login) || empty($password)) {
        header("Location: index.php?page=connexion&error=empty_fields");
        exit(); 
    }

    try {
        // Recherche de l'utilisateur dans la base de données en utilisant l'email
        $utilisateur = Utilisateur::getUtilisateurParEmail($dbh, $login);

        // Si l'utilisateur existe et que le mot de passe est correct, on connecte l'utilisateur
        if ($utilisateur !== null && Utilisateur::testerMDP($dbh, $login, $password)) {
            // On marque l'utilisateur comme connecté en créant une session
            $_SESSION['loggedIn'] = true; 
            $_SESSION['user'] = [ // Informations sur l'utilisateur stockées dans la session
                'id' => $utilisateur->id,                 
                'username' => $utilisateur->nom_utilisateur, 
                'email' => $utilisateur->email,          
                'nom-complet' => $utilisateur->nom_complet, 
                'quote' => $utilisateur->quote,          // Citation (si présente)
            ];
            
            // Redirection vers la page d'accueil après une connexion réussie
            
            header("Location: index.php?page=accueil");
            exit(); 
        } else {
            // Si l'utilisateur n'existe pas ou si le mot de passe est incorrect
            header("Location: index.php?page=connexion&error=login_failed");
            exit(); 
        }
    } catch (PDOException $e) {
        // En cas d'erreur de base de données, on capture l'exception et on log l'erreur
        error_log("Database error during login: " . $e->getMessage());
        header("Location: index.php?page=connexion&error=system_error");
        exit(); 
    }
    
    return false; 
}

// Fonction pour gérer la déconnexion de l'utilisateur
function logOut() {
    // On supprime les informations de session liées à la connexion
    unset($_SESSION['loggedIn']); 
    unset($_SESSION['user']);     
}
?>
