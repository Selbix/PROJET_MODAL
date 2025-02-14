<?php

function searchBooks($searchTerm) {
    try {
        $pdo = new PDO(
            "mysql:host=localhost;dbname=projet_sucre-sale;charset=utf8",
            "votre_utilisateur",
            "votre_mot_de_passe"
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT * FROM livres WHERE titre LIKE :search";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['search' => '%' . $searchTerm . '%']);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        // Log l'erreur et retourne un tableau vide
        error_log("Erreur de recherche : " . $e->getMessage());
        return [];
    }
}