<?php
function logIn($dbh) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return false;
    }

    $login = filter_var(trim($_POST['login'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password'] ?? '');
    
    if (empty($login) || empty($password)) {
        //header("Location: " . $_SERVER['HTTP_REFERER'] . "&error=empty_fields");
        header("Location: index.php?page=connexion&error=empty_fields");
        exit();
    }

    try {
        $utilisateur = Utilisateur::getUtilisateurParEmail($dbh, $login);

        if ($utilisateur !== null && Utilisateur::testerMDP($dbh, $login, $password)) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['user'] = [
                'id' => $utilisateur->id,                // Use -> to access properties
                'username' => $utilisateur->nom_utilisateur, // Map correctly
                'email' => $utilisateur->email,
                'nom-complet' => $utilisateur->nom_complet,
                'quote' => $utilisateur->quote,
            ];
            
            //header("Location: index.php?page=connexion&error=login_success");
            header("Location: index.php?page=accueil");
            exit();
        } else {
            //header("Location: " . $_SERVER['HTTP_REFERER'] . "&error=login_failed");
            header("Location: index.php?page=connexion&error=login_failed");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Database error during login: " . $e->getMessage());
        //header("Location: " . $_SERVER['HTTP_REFERER'] . "&error=system_error");
        header("Location: index.php?page=connexion&error=system_error");
        exit();
    }
    
    return false;
}


function logOut() {
    // Destruction de la variable de session loggedIn
    unset($_SESSION['loggedIn']);
    unset($_SESSION['user']);
}
?>
