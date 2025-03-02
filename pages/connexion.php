<div class="container"> 
    <div class="form_area inscription">
        <h2 class="title">INSCRIPTION</h2>
        <form method="post" action="index.php?todo=register">
            <div class="form_group">
                <label for="reg_username">Nom d'utilisateur :</label>
                <input class="form_style" type="text" id="reg_username" name="username" maxlength ="10" required>
            </div>
            <div class="form_group">
                <label for="reg_fullname">Nom complet :</label>
                <input class="form_style" type="text" id="reg_fullname" name="fullname" required>
            </div>
            <div class="form_group">
                <label for="reg_email">Email :</label>
                <input class="form_style" type="email" id="reg_email" name="email" required>
            </div>
            <div class="form_group">
                <label for="reg_password">Mot de passe :</label>
                <input class="form_style" type="password" id="reg_password" name="password" required>
            </div>
            <input type="hidden" name="form_type" value="register">
            <button class="btn" type="submit" name="todo" value="register">S'INSCRIRE</button>
        </form>
    </div>
    <div class="form_area connexion">
        <h2 class="title">CONNEXION</h2>
        <form method="post" action="index.php?todo=login">
            <div class="form_group">
                <label for="login">Email :</label>
                <input class="form_style" type="text" id="login" name="login" required>
            </div>
            <div class="form_group">
                <label for="login_password">Mot de passe :</label>
                <input class="form_style" type="password" id="login_password" name="password" required>
            </div>
            <button class="btn" type="submit" name="todo" value="login">SE CONNECTER</button>
        </form>
    </div>
</div>