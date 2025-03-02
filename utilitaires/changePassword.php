<?php

// Fonction pour gérer le changement de mot de passe de l'utilisateur
function changePassword($dbh) {
    
    // Récupération des mots de passe depuis le formulaire
    $newPassword = $_POST["newPassword"]; 
    $confirmNewPassword = $_POST["confirmNewPassword"]; 
    $oldPassword = $_POST["oldPassword"]; 
    
    // Vérification que les champs ne sont pas vides
    if (empty($newPassword) || empty($confirmNewPassword) || empty($oldPassword)) {
        
        // header("Location: " . $_SERVER['HTTP_REFERER'] . "&error=empty_fields");
        header("Location: index.php?page=modif-profile&error=empty_fields");
        exit(); 
    }

    // Vérification que le nouveau mot de passe et la confirmation sont identiques
    if($_POST["newPassword"] != $_POST["confirmNewPassword"]){
        
        header("Location: index.php?page=modif-profile&error=new_password_different");
        exit(); 
    }

    // Vérification que l'ancien mot de passe est différent du nouveau mot de passe
    if($_POST["oldPassword"] == $_POST["newPassword"]) {
        
        header("Location: index.php?page=modif-profile&error=same_password");
        exit(); 
    }
    
    // Vérification si l'utilisateur existe dans la base de données (basé sur l'ID de l'utilisateur)
    if(Utilisateur::getUtilisateur($dbh, $_SESSION['user']['id']) != NULL){
        // Si l'utilisateur existe, on vérifie que l'ancien mot de passe est correct
        if(Utilisateur::testerMDP($dbh, $_SESSION['user']['email'], $_POST["oldPassword"])){
            // Si l'ancien mot de passe est correct, on met à jour le mot de passe dans la base de données
            $query = "UPDATE Utilisateurs SET password = ? WHERE id = ?";
            $sth = $dbh->prepare($query);
            $sth->execute(array(password_hash($_POST["newPassword"], PASSWORD_DEFAULT), $_SESSION['user']['id']));
            header("Location: index.php?page=modif-profile&error=change_success");
            exit(); 
            
        }
        else{
            // Si l'ancien mot de passe est incorrect, on redirige avec un message d'erreur
            header("Location: index.php?page=modif-profile&error=old_password_incorrect");
            exit(); 
        }
    }
}
?>
