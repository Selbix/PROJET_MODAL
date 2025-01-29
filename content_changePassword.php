<?php

if($_SESSION["loggedIn"] != true){
    echo "You must be logged in to change your password.";
    return;
}

else {
    #echo password_hash("secret");
    echo <<<FIN
<form method="post" action = 'index.php?page=content_changePassword'>
    <p>Login : <input type ="text" name="login" required /></p>
    <p>Old Password : <input type="password" name="oldPassword" required /></p>
    <p>New Password : <input type="password" name="newPassword" required /></p>
    <p>Confirm New Password : <input type="password" name="confirmNewPassword" required /></p>
    <p><input type="submit" value="Valider" /></p>
</form>
FIN;

    var_dump($_POST);
    if($_POST["newPassword"] != $_POST["confirmNewPassword"]){
        echo "New password and confirm new password are different.";
        return;
    }
    if($_POST["oldPassword"] == $_POST["newPassword"]) {
        echo "Old password and new password are the same.";
        return;
    }
    
    if(Utilisateur::getUtilisateur($dbh, $_POST["login"])!= NULL){
        if($_POST["newPassword"] != $_POST["confirmNewPassword"]){
            echo "New password and confirm new password are different.";
            return;
        }
        if(Utilisateur::testerMDP($dbh, $_POST["login"], $_POST["oldPassword"])){
            $query = "UPDATE utilisateurs SET mdp = ? WHERE login = ?";
            $sth = $dbh->prepare($query);
            $sth->execute(array(password_hash($_POST["newPassword"], PASSWORD_DEFAULT), $_POST["login"]));
            echo $sth->rowCount();
        }
        else{
            echo "Old password is incorrect.";
            return;
        }
    } 
}



?>