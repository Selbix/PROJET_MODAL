<?php
// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    echo '
    <style>
        /* ... (code CSS précédent) ... */
    </style>
    <div class="overlay">
        <div class="blurred-background"></div>
        <a href="index.php?page=connexion">
            <div class="message">
                Il faut être connecté pour pouvoir accéder au profil.<br>
                Cliquez pour rejoindre la page de connexion.
            </div>
        </a>
    </div>';
    exit();
}

// Récupération des informations de l'utilisateur depuis la session
$user = $_SESSION['user'];

// Traitement du formulaire de mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quote = $_POST['quote'];
    
    // Traitement de l'upload de l'image de profil
    if (!empty($_FILES['image']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);
        if (in_array($fileType, $allowedTypes)) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            $image = $target_file;
        } else {
            echo '<p>Type de fichier non autorisé.</p>';
            $image = $user['image'] ?? 'default_profile.png';
        }
    } else {
        $image = $user['image'] ?? 'default_profile.png';
    }
    
    // Mise à jour des informations dans la base de données
    $sth = $dbh->prepare('UPDATE utilisateurs SET quote = ?, image = ? WHERE id = ?');
    $sth->execute(array($quote, $image, $user['id']));

    // Mettre à jour les informations de l'utilisateur dans la session
    $_SESSION['user']['quote'] = $quote;
    $_SESSION['user']['image'] = $image;

    echo '<p>Profil mis à jour avec succès !</p>';
}

// Affichage du profil de l'utilisateur
$user = $_SESSION['user']; // Recharge les données de l'utilisateur après mise à jour
echo generateHTMLHeader("Profil de " . htmlspecialchars($user['nom_utilisateur'] ?? ''), "styles.css");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?php echo htmlspecialchars($user['nom_utilisateur'] ?? ''); ?></title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function toggleChangePassword() {
            var form = document.getElementById("change-password-form");
            if (form.style.display === "none") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }
    </script>
</head>
<body>
<div class="profile-container">
    <div class="profile-header">
        <div class="profile-picture">
            <img src="<?php echo htmlspecialchars($user['image'] ?? 'default_profile.png'); ?>" alt="Photo de profil">
            <label for="image" class="profile-picture-label">
                <input type="file" name="image" id="image" onchange="this.form.submit()">
                <i class="fas fa-camera"></i>
            </label>
        </div>
        <div class="profile-details">
            <h2><?php echo htmlspecialchars($user['nom_complet'] ?? ''); ?></h2>
            <p>Nom d'utilisateur : <?php echo htmlspecialchars($user['nom_utilisateur'] ?? ''); ?></p>
            <p>Email : <?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
        </div>
    </div>
    <form method="post" action="" enctype="multipart/form-data" class="profile-update-form">
        <div class="form-group">
            <label for="quote">Citation préférée :</label>
            <textarea id="quote" name="quote" placeholder="Ajoutez votre citation préférée..."><?php echo htmlspecialchars($user['quote'] ?? ''); ?></textarea>
        </div>
        <button type="submit" class="btn">Mettre à jour</button>
    </form>
    <a href="#" onclick="toggleChangePassword()">Changer de mot de passe</a>
    <form method="post" action="index.php?page=content_changePassword" id="change-password-form" style="display:none;">
        <div class="form-group">
            <label for="oldPassword">Ancien mot de passe :</label>
            <input type="password" id="oldPassword" name="oldPassword" required>
        </div>
        <div class="form-group">
            <label for="newPassword">Nouveau mot de passe :</label>
            <input type="password" id="newPassword" name="newPassword" required>
        </div>
        <div class="form-group">
            <label for="confirmNewPassword">Confirmer le nouveau mot de passe :</label>
            <input type="password" id="confirmNewPassword" name="confirmNewPassword" required>
        </div>
        <button type="submit" class="btn">Valider</button>
    </form>
</div>
</body>
</html>

<?php
echo generateHTMLFooter();
?>
