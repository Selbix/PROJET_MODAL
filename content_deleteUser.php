<?php
echo <<<FIN
<form method="post">
    <p>Login : <input type ="text" name="login" required /></p>
    <p>Old Password : <input type="password" name="password" required /></p>
    <p><input type="submit" value="Valider" /></p>
</form>
FIN;

if(Utilisateur::getUtilisateur($dbh, $_POST["login"])!= NULL){
    if(Utilisateur::testerMDP($dbh, $_POST["login"], $_POST["password"])){
        $query = "DELETE FROM utilisateurs WHERE login = {$_POST["login"]}";
        $sth = $dbh->prepare($query);
        $request_succeeded = $sth->execute();
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
        $sth->execute();
    }
    else{
        echo "Password is incorrect.";
    }
}


?>