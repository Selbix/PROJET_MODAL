<?php
class Database {
    public static function connect() {
        // Informations de connexion à la base de données
        $dsn = 'mysql:dbname=PROJET_SUCRE-SALE;host=127.0.0.1';
        $user = 'root';
        $password = '';
        $dbh = null;
        try {
            $dbh = new PDO($dsn, $user, $password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            // Configuration de PDO pour utiliser des exceptions en cas d'erreur
            $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
            exit(0);
        }
        return $dbh;
    }
}
 
 






$dbh = null; // Déconnexion de MySQL
?>