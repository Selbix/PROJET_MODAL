<?php
function changePassword($dbh) {
    //var_dump($_POST);
    $newPassword = $_POST["newPassword"];
    $confirmNewPassword = $_POST["confirmNewPassword"];
    $oldPassword = $_POST["oldPassword"];
    if (empty($newPassword) || empty($confirmNewPassword) || empty($oldPassword)) {
        //header("Location: " . $_SERVER['HTTP_REFERER'] . "&error=empty_fields");
        header("Location: index.php?page=modif-profile&error=empty_fields");
        exit();
    }

    if($_POST["newPassword"] != $_POST["confirmNewPassword"]){
        header("Location: index.php?page=modif-profile&error=new_password_different");
        exit();
    }
    if($_POST["oldPassword"] == $_POST["newPassword"]) {
        header("Location: index.php?page=modif-profile&error=same_password");
        exit();
    }
    
    if(Utilisateur::getUtilisateur($dbh, $_SESSION['user']['id'])!= NULL){

        if(Utilisateur::testerMDP($dbh, $_SESSION['user']['email'], $_POST["oldPassword"])){
            $query = "UPDATE Utilisateurs SET password = ? WHERE id = ?";
            $sth = $dbh->prepare($query);
            $sth->execute(array(password_hash($_POST["newPassword"], PASSWORD_DEFAULT), $_SESSION['user']['id']));
            header("Location: index.php?page=modif-profile&error=change_success");
            exit();
            //echo $sth->rowCount();
        }
        else{
            header("Location: index.php?page=modif-profile&error=old_password_incorrect");
            exit();
        }
    }
}
    function displayLoginError2() {
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
            $messages = [
                'empty_fields' => 'Veuillez remplir tous les champs.',
                'login_failed' => 'Email ou mot de passe incorrect.',
                'system_error' => 'Une erreur système est survenue. Veuillez réessayer.',
                'debug_max' => 'sa march pa',
                'old_password_incorrect' => 'Old password is incorrect.',
                'new_password_different' => 'New password and confirm new password are different.',
                'same_password' => 'Old password and new password are the same.'
            ];
            
            if (isset($messages[$error])) {
                return "<div class='error-message'>" . htmlspecialchars($messages[$error]) . "</div>";
            }
        }
        return '';
    }



?>