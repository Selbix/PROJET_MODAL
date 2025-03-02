<?php
require $_SERVER['DOCUMENT_ROOT'] . "/PROJET_MODAL/utilitaires/utils.php";
require $_SERVER['DOCUMENT_ROOT'] . "/PROJET_MODAL/utilitaires/logInOut.php";
require $_SERVER['DOCUMENT_ROOT'] . "/PROJET_MODAL/BDD-gestion/database.php";
require $_SERVER['DOCUMENT_ROOT'] . "/PROJET_MODAL/BDD-gestion/utilisateur.php";
require $_SERVER['DOCUMENT_ROOT'] . "/PROJET_MODAL/BDD-gestion/livre.php";
require $_SERVER['DOCUMENT_ROOT'] . "/PROJET_MODAL/utilitaires/register.php";



session_name("Session_utilisateur");
session_start();
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}
$dbh = Database::connect();

// Récupération des données de recherche
$searchTerm = !empty($_POST['search']) ? '%' . $_POST['search'] . '%' : null;
$searchField = !empty($_POST['field']) ? $_POST['field'] : 'titre';
$selectedGenre = !empty($_POST['genre']) ? $_POST['genre'] : null;

$results = [];

if ($searchTerm !== null || $selectedGenre !== null) {
    $sql = "SELECT * FROM Livres WHERE ($searchField LIKE ?)";
    $params = [$searchTerm ?: '%'];

    if ($selectedGenre) {
        $sql .= " AND genre = ?";
        $params[] = $selectedGenre;
    }

    $stmt = $dbh->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

echo generateHTMLHeader("Recherche de Livres", "styles/styles.css");
?>
<div class="container-fluid ontop">
    <?php echo generateMenu(); ?>
</div>

<div id="content" class="content">
    <!-- Search form section -->
    <div class="book-search-form-container">

         <form method="POST" >

            <input type="text" 
                   name="search" 
                   class="book-search-input" 
                   placeholder="Rechercher un livre..." 
                   value="<?php echo isset($_POST['search']) ? htmlspecialchars(trim($_POST['search'], '%')) : ''; ?>">

            <div class="book-search-radio-group">
                <div class="radio-input">
                    <label>
                        <input type="radio" id="titre" name="field" value="titre" 
                               <?php echo ($searchField === 'titre' || empty($searchField)) ? 'checked' : ''; ?> />
                        <span>Rechercher par titre</span>
                    </label>
                    <label>
                        <input type="radio" id="auteur" name="field" value="auteur" 
                               <?php echo ($searchField === 'auteur') ? 'checked' : ''; ?> />
                        <span>Rechercher par auteur</span>
                    </label>
                </div>
            </div>

            <!-- Genre filter dropdown -->
            <div class="filter-dropdown">
                <select name="genre" class="book-genre-filter">
                    <option value="">Tous les genres</option>
                    <option value="Roman" <?php echo ($selectedGenre === 'Roman') ? 'selected' : ''; ?>>Roman</option>
                    <option value="Policier" <?php echo ($selectedGenre === 'Policier') ? 'selected' : ''; ?>>Policier</option>
                    <option value="Romance" <?php echo ($selectedGenre === 'Romance') ? 'selected' : ''; ?>>Romance</option>
                    <option value="Science-fiction" <?php echo ($selectedGenre === 'Science-fiction') ? 'selected' : ''; ?>>Science-fiction</option>
                    <option value="Scolaire" <?php echo ($selectedGenre === 'Scolaire') ? 'selected' : ''; ?>>Scolaire</option>
                    <option value="Théâtre" <?php echo ($selectedGenre === 'Théâtre') ? 'selected' : ''; ?>>Théâtre</option>
                </select>
            </div>

            <button type="submit" class="book-search-submit">Rechercher</button>
        </form>
    </div>

    <!-- Results section -->
    <div class="book-search-container">
    <?php if (!empty($_POST['search']) && empty($results)): ?>
    <div class="book-search-no-results">
        Aucun résultat trouvé pour votre recherche. <br>
        Recherche sur Internet Archive...
    </div>
    <?php
    $searchQuery = urlencode(trim($_POST['search'], '%'));
    $apiUrl = "https://archive.org/advancedsearch.php?q=title:$searchQuery&fl[]=identifier,title,creator&output=json";
    $archiveResponse = file_get_contents($apiUrl);
    $archiveData = json_decode($archiveResponse, true);

    if (!empty($archiveData['response']['docs'])) {
        echo "<div class='book-search-results-grid'>";
        foreach ($archiveData['response']['docs'] as $book) {
            $identifier = htmlspecialchars($book['identifier']);
            $coverUrl = "https://archive.org/services/img/$identifier"; // Cover URL

            echo "<div class='book-search-result-card'>";
            echo "<img src='$coverUrl' alt='Book Cover' class='book-cover' onerror=\"this.src='default-cover.jpg';\">"; // Display cover with fallback image
            echo "<h2 class='book-search-result-title'>" . htmlspecialchars($book['title']) . "</h2>";
            echo "<div class='book-search-result-author'>par " . htmlspecialchars($book['creator'] ?? 'Auteur inconnu') . "</div>";
            echo "<a href='https://archive.org/details/$identifier' class='book-search-result-link' target='_blank'>Lire sur Internet Archive</a>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<div class='book-search-no-results'>Aucun livre trouvé sur Internet Archive.</div>";
    }
    ?>
        <?php elseif (!empty($results)): ?>
            <div class="book-search-results-grid">
                <?php foreach ($results as $book): ?>
                    <div class="book-search-result-card">
                        <?php
                        $coverPath = "thumbnail/" . $book['id'] . ".jpg";
                        $pdfPath = "BDD-gestion/books/" . $book['id'] . ".pdf";
                        ?>
                        
                        <?php if (file_exists($coverPath)): ?>
                            <img src="<?php echo htmlspecialchars($coverPath); ?>" 
                                 alt="Couverture de <?php echo htmlspecialchars($book['titre']); ?>"
                                 class="book-search-result-cover">
                        <?php endif; ?>

                        <h2 class="book-search-result-title"><?php echo htmlspecialchars($book['titre']); ?></h2>
                        <div class="book-search-result-author">par <?php echo htmlspecialchars($book['auteur']); ?></div>
                        <div class="book-search-result-genre">Genre : <?php echo htmlspecialchars($book['genre']); ?></div>
                        <div class="book-search-result-description">
                            <?php echo htmlspecialchars(substr($book['description'], 0, 150)) . '...'; ?>
                        </div>

                        <?php if (file_exists($pdfPath)): ?>
                            <a href="index.php?id=<?php echo $book['id']; ?>" 
                               class="book-search-result-link" 
                               target="_blank">
                                Lire le livre
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
