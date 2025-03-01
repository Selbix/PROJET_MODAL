<script>
    
    // Fonction qui crée et renvoie des notifications
    function showNotification(message, type = 'error') {
        // Crée un conteneur pour les notifications s'il n'existe pas
        let container = document.querySelector('.notification-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'notification-container';
            document.body.appendChild(container);
        }
        
        // Créer la notification
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        notification.textContent = message;
        
        // Ajout au conteneur
        container.appendChild(notification);
        
        // Retirer la notifiaction après affichage
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    // Fonction qui vérifie les paramètres URL, et affiche des notifications en conséquence
    function checkForErrors() {
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');
        
        const errorMessages = {
            'empty_fields': 'Veuillez remplir tous les champs.',
            'login_failed': 'Email ou mot de passe incorrect.',
            'system_error': 'Une erreur système est survenue. Veuillez réessayer.',
            'login_success': 'Connexion réussie !',
            'registration_completed': 'Inscription réussie !',
            'old_password_incorrect': 'Ancien mot de passe incorrect.',
            'new_password_different': 'Le nouveau mot de passe et la confirmation ne correspondent pas.',
            'same_password': 'L\'ancien mot de passe et le nouveau sont identiques.',
            'change_success' : 'Mot de passe changé avec succès.',
            'pseudo_long' : "Le pseudo est trop long",
        };
        
        if (error && errorMessages[error]) {
            const isSuccess = error.includes('success');
            showNotification(errorMessages[error], isSuccess ? 'success' : 'error');
            
        }
    }

    document.addEventListener('DOMContentLoaded', checkForErrors);

//Affiche ou de masque le formulaire de changement de mot de passe
function toggleChangePassword() {
    var form = document.getElementById("change-password-form");
    form.style.display = (form.style.display === "none") ? "block" : "none";
}

//Vérifie le type et la taille du fichier sélectionné par l'utilisateur
function validateAndShowStatus(input) {
const file = input.files[0];
const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
const maxSize = 20 * 1024 * 1024; // 5MB

if (!file) {
showToast("Aucun fichier choisi", "error");
return;
}

if (!allowedTypes.includes(file.type)) {
showToast("Seuls les fichiers JPEG, PNG et GIF sont autorisés.", "error");
input.value = ''; 
return;
}

if (file.size > maxSize) {
showToast("La taille du fichier ne doit pas dépasser 5 Mo.", "error");
input.value = ''; 
return;
}

showToast("Fichier prêt à être soumis", "success");
}

//Affiche des notifications de type "toast"
function showToast(message, type = "info") {
let toastContainer = document.getElementById("toast-container");

// Créer le conteneur toast s'il n'existe pas
if (!toastContainer) {
console.log("Creating toast container...");
toastContainer = document.createElement("div");
toastContainer.id = "toast-container";
document.body.appendChild(toastContainer);
}

console.log("Adding toast:", message); // Débogage

const toast = document.createElement("div");
toast.className = `toast ${type}`;
toast.innerText = message;

toastContainer.appendChild(toast);
console.log("Toast added:", toast);
toast.style.opacity = "1";
toast.style.display = "block";
setTimeout(() => {
toast.style.opacity = "0";
setTimeout(() => {
    console.log("Removing toast...");
    toast.remove();
}, 500);
}, 5000);
}

function showNotification(message, type = 'error') {
// Créer le conteneur s'il n'existe pas
let container = document.querySelector('.notification-container');
if (!container) {
container = document.createElement('div');
container.className = 'notification-container';
document.body.appendChild(container);
}

// Créer la notification
const notification = document.createElement('div');
notification.className = 'notification \${type}';

notification.textContent = message;

// Ajout au conteneur
container.appendChild(notification);

// Retirer la notification après l'animation
setTimeout(() => {
notification.remove();
}, 5000);
}





</script>

    <?php
    
    //L'utilisateur doit se connecter pour accéder à la page
if (!isset($_SESSION['loggedIn'])) {
    echo '
    <style>
        /* Ensure the body background is blurred */
        body {
            background-color: #263248;
            background-size: cover;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Create an overlay that blurs the entire page */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        /* Blurred background without affecting text */
        .blurred-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(10px);
            z-index: -1; /* Ensure it stays behind the message */
        }

        /* Message box (kept clear) */
        .message {
            background: rgba(0, 0, 0, 0.85); /* Dark box for contrast */
            color: white;
            padding: 20px 40px;
            border-radius: 10px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.5);
            z-index: 1000; /* Keep it above everything */
        }
            
    </style>
    <div class="overlay">
        <div class="blurred-background"></div> <!-- Background blur -->
        <a href="index.php?page=connexion"><div class="message">Il faut être connecté pour pouvoir accéder aux livres. <br>
        Cliquez pour rejoindre la page de connexion.</div></a> <!-- Clear Message -->
    </div>';
    exit();
}
// Récupération des informations de l'utilisateur
$user = $_SESSION['user'];

var_dump($user);
// Traitement du formulaire de mise à jour
/*if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Form submitted!";

    if (!empty($_FILES)) {
        echo "<pre>";
        print_r($_FILES);
        echo "</pre>";
    } else {
        echo "No file uploaded!";
    }
}*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification et traitement de l'upload de l'image de profil
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);

        if (in_array($fileType, $allowedTypes)) {
            // Créer le dossier uploads s'il n'existe pas
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            // Générer un nom de fichier unique
            $fileExtension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            $fileName = $_SESSION['user']['id'] . "." . $fileExtension;
            $target_file = $target_dir . $fileName;
            $user['image'] = $target_file;
            // Déplacer le fichier uploadé
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo '<div class="alert alert-success">Profil mis à jour avec succès !</div>';
            } else {
                echo '<div class="alert alert-error">Il y a eu une erreur lors de la mise à jour de votre profil.</div>';
            }
        } else {
            echo '<div class="alert alert-error">Type de fichier non autorisé. Utilisez JPEG, PNG ou GIF.</div>';
            $image = $user['image'] ?? 'default_profile.jpg';
        }
    }
}
//var_dump($user['image']);
/*if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['image'])) {
        echo "<pre>";
        print_r($_FILES);
        echo "</pre>";
    }
}*/
if ($_SERVER["REQUEST_METHOD"] == "POST" & ISSET($_POST['quote'])){
    $citation = trim($_POST['quote']);
    $sth = $dbh->prepare("UPDATE Utilisateurs SET quote = ? WHERE id = ?");
    $sth->execute(array($citation, $user['id']));
    $_SESSION['user']['quote'] = $citation;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" & ISSET($_POST['username'])){
    $username = trim($_POST['username']);
    $sth = $dbh->prepare("UPDATE Utilisateurs SET nom_utilisateur = ? WHERE id = ?");
    $sth->execute(array($username, $user['id']));
    $_SESSION['user']['username'] = $username;
}
$user = $_SESSION['user'];
$user['image'] = 'uploads/' . $user['id'] . '.jpg';
if(!file_exists($user['image'])){
    $user['image'] = 'uploads/default_profile.jpg';
}
?>


<div class="profile-container"> 
<div class="profile-header">
    <div class="profile-picture-container">
        <div class="profile-picture">
            <img src="<?php echo htmlspecialchars($user['image']); ?>?v=<?php echo time(); ?>" alt="Photo de profil">
        </div>
        <form method="post" action="index.php?page=modif-profile" enctype="multipart/form-data" class="profile-picture-upload">
    <label class="custum-file-upload" for="file">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="" viewBox="0 0 24 24">
                        <g stroke-width="0" id="SVGRepo_bgCarrier"></g>
                        <g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g>
                        <g id="SVGRepo_iconCarrier"> 
                            <path fill="" d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V9C19 9.55228 19.4477 10 20 10C20.5523 10 21 9.55228 21 9V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM14 15.5C14 14.1193 15.1193 13 16.5 13C17.8807 13 19 14.1193 19 15.5V16V17H20C21.1046 17 22 17.8954 22 19C22 20.1046 21.1046 21 20 21H13C11.8954 21 11 20.1046 11 19C11 17.8954 11.8954 17 13 17H14V16V15.5ZM16.5 11C14.142 11 12.2076 12.8136 12.0156 15.122C10.2825 15.5606 9 17.1305 9 19C9 21.2091 10.7909 23 13 23H20C22.2091 23 24 21.2091 24 19C24 17.1305 22.7175 15.5606 20.9844 15.122C20.7924 12.8136 18.858 11 16.5 11Z" clip-rule="evenodd" fill-rule="evenodd"></path> 
                        </g>
                        </svg>
</span>
        <span class="text">
            <span>Choisir une image</span>
</span>
        <input type="file" id="file" name="image" accept=".jpg,.jpeg,.png,.gif" onchange="validateAndShowStatus(this)">
        </label>
        <button type="submit" class="btn">Mettre à jour la photo de profil</button>
        </form>
    </div>

        <div class="profile-details">
            <h2><?php echo htmlspecialchars($user['nom-complet'] ?? ''); ?></h2> 
            <form method="post" action="index.php?page=modif-profile" enctype="multipart/form-data" class="form-username">

            <p>Modifier nom d'utilisateur :  <input type="text" id="username" name="username" maxlength ="10" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>">
            </p>
            <button type="submit" class="btn-username">Mettre à jour le nom d'utilisateur</button>
            </form>
            <p>Email : <?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
        </div>
    </div>
    <form method="post" action="index.php?page=modif-profile" enctype="multipart/form-data" class ="form-quote">
        <div class="form-group">
            <label for="quote">Citation préférée :</label>
            <textarea id="quote" name="quote" placeholder="Ajoutez votre citation préférée..."><?php echo htmlspecialchars($user['quote']); ?></textarea>
        </div>
        <button type="submit" class="btn">Mettre à jour la citation</button>
    </form>

    <div class ="btn"><a class="lienmdp" href="#" onclick="toggleChangePassword()">Changer de mot de passe</a></div>
    <form method="post" action="index.php?page=modif-profile&todo=changePassword" id="change-password-form" style="display:none;">
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

