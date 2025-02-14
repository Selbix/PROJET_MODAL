<?php
$page_list = [
    'accueil' => 'Accueil',
    'connexion' => 'Connexion',
    'profil' => 'Mon profil',
    'ajouter' => 'Ajouter',
    'modif-profile' => 'Modifier mon profil',
];

function generateHTMLFooter()
{
    echo "</body>";
    echo "<footer>";
    echo "</footer>";
}
function generateHTMLHeader($titre, $link)
{
    if($link != "styles-loginout.css"){
    echo "<!DOCTYPE html>";
    echo "<html lang='fr'>";
    echo <<<FIN
            <head>
            
                <title>$titre</title>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <!-- Bootstrap CSS -->
                <link href="css/bootstrap.min.css" rel="stylesheet">
                <!-- jQuery first, then Bootstrap JavaScript -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="js/bootstrap.bundle.min.js"></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
                
                <!-- Mon CSS Perso -->
                <link href=$link rel="stylesheet">

            
            </head>
            <body>
FIN;}
    else{
        echo "<!DOCTYPE html>";
        echo "<html lang='fr'>";
    echo <<<FIN
            <head>
                
                <title>$titre</title>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <!-- Bootstrap CSS -->
                <link href="css/bootstrap.min.css" rel="stylesheet">
                <!-- jQuery first, then Bootstrap JavaScript -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="js/bootstrap.bundle.min.js"></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
                <script>
// Function to create and show notifications
function showNotification(message, type = 'error') {
    // Create container if it doesn't exist
    let container = document.querySelector('.notification-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'notification-container';
        document.body.appendChild(container);
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification \${type}';
    
    notification.textContent = message;
    
    // Add to container
    container.appendChild(notification);
    
    // Remove notification after animation completes
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Function to check URL parameters and show notification
function checkForErrors() {
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    
    const errorMessages = {
        'empty_fields': 'Veuillez remplir tous les champs.',
        'login_failed': 'Email ou mot de passe incorrect.',
        'system_error': 'Une erreur système est survenue. Veuillez réessayer.',
        'login_success': 'Connexion réussie !',
        'registration_completed': 'Inscription réussie !'
    };
    
    if (error && errorMessages[error]) {
        const isSuccess = error.includes('success');
        showNotification(errorMessages[error], isSuccess ? 'success' : 'error');
        
        // Clean up URL without reloading page
        const newUrl = window.location.pathname;
        window.history.pushState({}, '', newUrl);
    }
}

// Run when page loads
document.addEventListener('DOMContentLoaded', checkForErrors);
</script>
                <!-- Mon CSS Perso -->
                <link href=$link rel="stylesheet">

            
            </head>
            <body>
FIN;
    }
}
function checkPage($askedPage): bool{
    global $page_list;
    foreach($page_list as $page => $valeur){
        if($page == $askedPage){
            return true;
        }
    }
    return false;
}
function getPageTitle(string $page): string{
    global $page_list;
    if(checkPage($page)) {
        return $page_list[$page];
    } 
    else{
        return "Erreur : cette page n'existe pas";
    }
}

function generateMenu() {
    global $page_list;
    $html = '<nav class="navbar navbar-expand-lg custom-nav">';
    $html .= '<div class="container-fluid">';
    
    // Logo
    $html .= '<a class="navbar-brand" href="index.php?page=accueil">';
    $html .= '<img src="logo(1).png" alt="Sucré-Salé" class="nav-logo">';
    $html .= '</a>';
    
    // Toggler for mobile
    $html .= '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
              data-bs-target="#navbarContent" aria-controls="navbarContent" 
              aria-expanded="false" aria-label="Toggle navigation">';
    $html .= '<span class="navbar-toggler-icon"></span>';
    $html .= '</button>';
    
    // Main navigation content
    $html .= '<div class="collapse navbar-collapse" id="navbarContent">';
    $html .= '<ul class="navbar-nav me-auto mb-2 mb-lg-0">';
    
    // Generate menu items
    foreach($page_list as $key => $page) {
        $html .= '<li class="nav-item">';
        $html .= '<a class="nav-link" href="index.php?page=' . $key . '">' . $page . '</a>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    
    // Search form
    $html .= '<form class="d-flex searchBox" role="search" method="GET" action="index.php">';
    $html .= '<input type="hidden" name="page" value="accueil">'; // Pour rester sur la page d'accueil
    $html .= '<input class="form-control searchInput" type="search" name="search" placeholder="Search something..." aria-label="Search">';
    
    // Genre filter dropdown button with style
    $html .= '<button type="button" class="btn btn-filter me-2" onclick="toggleGenreDropdown()">';
    $html .= '<svg viewBox="0 0 24 24"><path d="M3 18h6v-2H3v2zm8 0h10v-2H11v2zm-8-5h14v-2H3v2zm8-7v2h10V6H11z"/></svg>';
    $html .= '</button>';

    $html .= '<select class="form-control" name="genre" id="genreDropdown" style="display: none;">'; // Hide select box but keep it for functionality
    $html .= '<option value=""></option>'; // Empty option to replace "Tous les genres"
    $html .= '<option value="Roman">Roman</option>';
    $html .= '<option value="Scolaire">Scolaire</option>';
    $html .= '<option value="Théâtre">Théâtre</option>';
    $html .= '<option value="Policier">Policier</option>';
    $html .= '<option value="Science-fiction">Science-fiction</option>';
    $html .= '</select>';

    $html .= '</form>';
    
    $html .= '</div></div></nav>';
    
    // Ajoutez la fonction JavaScript ici, dans la sortie HTML générée
    $html .= '<script>
    function toggleGenreDropdown() {
        var genreDropdown = document.getElementById("genreDropdown");
        if (genreDropdown.style.display === "none" || genreDropdown.style.display === "") {
            genreDropdown.style.display = "block";
        } else {
            genreDropdown.style.display = "none";
        }
    }
    </script>';
    
    return $html;
}





/*function generateMenu(){
    global $page_list;
    echo '<div class="container-fluid">';
    echo '<a class="navbar-brand" href="#">Mon site</a>';
    echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">';
    echo '<span class="navbar-toggler-icon"></span>';
    echo "</button>";
    echo '<div class="collapse navbar-collapse" id="navbarSupportedContent">';
    echo '<ul class="navbar-nav me-auto mb-2 mb-lg-0">';
    if(!$_SESSION["loggedIn"]){
        echo <<<FIN
        <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="content_register.php">S'inscrire</a>
                    </li>
FIN;    
                    }

    
    foreach($page_list as $cle => $page){
        echo <<<FIN
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php?page=$cle">$page</a>
                    </li>

FIN;
        }
        echo '</ul></div></div>';
    }

?>*/
?>