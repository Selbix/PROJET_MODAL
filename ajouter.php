<?php
echo <<<FIN
<div class="container">
    <h2>Ajouter un livre/document</h2>
    <form action="index.php?page=ajouter" method="POST" enctype="multipart/form-data">
        <label for="titre">Titre</label>
        <input type="text" id="titre" name="titre" required>

        <label for="auteur">Auteur</label>
        <input type="text" id="auteur" name="auteur" required>

        <label for="date_sortie">Date de sortie</label>
        <input type="number" id="date_sortie" name="date_sortie" required>

        <label for="genre">Genre</label>
        <select id="genre" name="genre" required>
            <option value="">Choisir un genre</option>
            <option value="Roman">Roman</option>
            <option value="Théâtre">Théâtre</option>
            <option value="Scolaire">Scolaire</option>
            <option value="Science-fiction">Science-fiction</option>
            <option value="Policier">Policier</option>
            <option value="Romance">Romance</option>
        </select>

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4" required></textarea>

        <label for="file">Ajouter le document</label>
        <input type="file" id="file" name="file" required>

        <button type="submit">Ajouter</button>
    </form>
</div>
FIN;



var_dump($_FILES);
var_dump(!empty($_FILES['file']['tmp_name']));
var_dump(is_uploaded_file($_FILES['file']['tmp_name']));

    if (!empty($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']
['tmp_name'])) {
    //echo "ICI";
    Livres::insererLivre( $dbh, $_POST["titre"],  $_POST["auteur"], $_POST["genre"], $_POST["date_sortie"], $_POST["description"],  $_FILES["file"]["type"],  $_FILES["file"]["tmp_name"]);
    $form_values_valid = true;
  }
  else {
    echo "Erreur lors de l'upload du fichier";
  }


?>