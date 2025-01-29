<?php
function printLoginForm($askedPage) {
    echo "<form method = 'post' action = 'index.php?todo=login&page=$askedPage'>";
    echo '<p>Login : <input type = "text" name = "login" required placeholder = "Utilisateur"/></p>';
    echo '<p>Email : <input type = "email" name = "email" required placeholder = "E-mail"/></p>';
    echo '<p>Password : <input type = "password" name = "password" required placeholder = "Mot de passe" /></p>';
    echo '<p><input type="submit" value="Valider" /></p>';
    echo '</form>';
}
function printLogoutForm($askedPage) {
echo "<form method = 'post' action = 'index.php?todo=logout&page=$askedPage'>";
echo '<p><input type="submit" value="Se déconnecter" /></p>';
echo '</form>';
}
?>