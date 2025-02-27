<?php
require "utils.php";
require "logInOut.php";
require "database.php";
require "utilisateur.php";
require "printForms.php";
require "livre.php";
require "register.php";
require "changePassword.php";
session_name("Session_utilisateur");
session_start();
if (!isset($_SESSION['initiated'])){
    session_regenerate_id();
    $_SESSION['initiated']= true;
}

$dbh = Database::connect();
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=connexion');
    exit();
}

// Vérifie si l'utilisateur est un admin
$user_id = $_SESSION['user']['id'];
$stmt = $dbh->prepare("SELECT id FROM administateurs WHERE id_utilisateurs = ?");
$stmt->execute([$user_id]);
$admin = $stmt->fetch();

if (!$admin) {
    header('Location: index.php?page=accueil');
    exit();
}

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// Gestion des privilèges de l'admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_admin'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $stmt = $dbh->prepare("SELECT id FROM administateurs WHERE id_utilisateurs = ?");
    $stmt->execute([$user_id]);
    $is_admin = $stmt->fetch();

    if ($is_admin) {
        $stmt = $dbh->prepare("DELETE FROM administateurs WHERE id_utilisateurs = ?");
        $_SESSION['message'] = "Privilèges administrateur retirés";
    } else {
        $stmt = $dbh->prepare("INSERT INTO administateurs (id_utilisateurs) VALUES (?)");
        $_SESSION['message'] = "Utilisateur promu au rang d'administrateur";
    }
    $stmt->execute([$user_id]);
}


// Gestion de la suppression de livres
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_book'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }
    $book_id = filter_input(INPUT_POST, 'book_id', FILTER_SANITIZE_NUMBER_INT);
    $stmt = $dbh->prepare("DELETE FROM Livres WHERE id = ?");
    $stmt->execute([$book_id]);
    $_SESSION['message'] = "Livre supprimé avec succès";
}

// Gestion de la suppression d'avis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_review'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }
    $review_id = filter_input(INPUT_POST, 'review_id', FILTER_SANITIZE_NUMBER_INT);
    $stmt = $dbh->prepare("DELETE FROM rating_livre WHERE id = ?");
    $stmt->execute([$review_id]);
    $_SESSION['message'] = "Avis supprimé avec succès";
}

// Gestion de la suppression d'utilisateurs
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $stmt = $dbh->prepare("DELETE FROM Utilisateurs WHERE id = ?");
    $stmt->execute([$user_id]);
    $_SESSION['message'] = "Utilisateur supprimé avec succès";
}

// Chercher les livres, avis, utilisateurs pour les rassembler dans un tableau
$books = $dbh->query("SELECT id, titre, auteur, date_parution, nombre_notes FROM Livres ORDER BY id DESC")->fetchAll();
$reviews = $dbh->query("SELECT rating_livre.id, Livres.titre, Utilisateurs.nom_utilisateur, rating_livre.note, rating_livre.avis FROM rating_livre INNER JOIN Livres ON rating_livre.id_titre = Livres.id INNER JOIN Utilisateurs ON rating_livre.id_utilisateur = Utilisateurs.id ORDER BY rating_livre.id DESC")->fetchAll();
$users = $dbh->query("SELECT u.id, u.nom_utilisateur, u.email, u.quote, a.id as admin_id FROM Utilisateurs u LEFT JOIN administateurs a ON u.id = a.id_utilisateurs ORDER BY u.id DESC")->fetchAll();
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <a href="index.php?page=accueil" class="btn btn-primary mb-3">Revenir à la page d'accueil</a>
    <h2 class="mb-4">Panneau Adminisateur</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    
    <h3>Gestion des livres</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Publication Date</th>
                <th>Number of Reviews</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['id']); ?></td>
                <td><?= htmlspecialchars($book['titre']); ?></td>
                <td><?= htmlspecialchars($book['auteur']); ?></td>
                <td><?= htmlspecialchars($book['date_parution']); ?></td>
                <td><?= htmlspecialchars($book['nombre_notes']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="book_id" value="<?= $book['id']; ?>">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                        <button type="submit" name="delete_book" class="btn btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <h3>Gestion des avis laissés</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Review ID</th>
                <th>Book Title</th>
                <th>User</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $review): ?>
            <tr>
                <td><?= htmlspecialchars($review['id']); ?></td>
                <td><?= htmlspecialchars($review['titre']); ?></td>
                <td><?= htmlspecialchars($review['nom_utilisateur']); ?></td>
                <td><?= htmlspecialchars($review['note']); ?></td>
                <td><?= htmlspecialchars($review['avis']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="review_id" value="<?= $review['id']; ?>">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                        <button type="submit" name="delete_review" class="btn btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Gestion des utilisateurs</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Photo Profile</th>
                <th>ID utilisateur</th>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Citation</th>
                <th>Admin Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                
            <tr>
            <?php 
            //var_dump($user);
if(file_exists("uploads/" . $user['id'] . ".jpg")) {
    $user["profile_pic"] = "uploads/" . $user['id'] . ".jpg";
} else {
    $user["profile_pic"] = "uploads/default_profile.jpg";
}
?>
                <td><img src="<?= htmlspecialchars($user["profile_pic"]); ?>" alt="Profile" width="50" height="50"></td>
                <td><?= htmlspecialchars($user['id']); ?></td>
                <td><?= htmlspecialchars($user['nom_utilisateur']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td><?= htmlspecialchars($user['quote']); ?></td>
                <td><?= $user['admin_id'] ? 'Admin' : 'User'; ?></td>
                <td>
    <form method="POST" style="display:inline-block;">
        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        <button type="submit" name="toggle_admin" class="btn btn-warning">
            <?= $user['admin_id'] ? 'Retirer Admin' : 'Ajouter Admin'; ?>
        </button>
    </form>
    <form method="POST" style="display:inline-block;">
        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        <button type="submit" name="delete_user" class="btn btn-danger">Supprimer</button>
    </form>
</td>

        </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>