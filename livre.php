<?php
class Livres {
    public $id;
    public $titre;
    public $auteur;
    public $genre;
    public $date_parution;
    public $description;
    public $extension;
    public $note_moyenne;
    public function __toString() {
        

        return $this->titre ." de". $this->auteur . ", paru en" . $this->date_parution . ". Genre " . $this->genre;
    }
    
    //Récupère un livre en fonction de son titre
    public static function getLivre($dbh, $titre){
        $query = "SELECT * FROM Livres WHERE titre = ?";
    $sth = $dbh->prepare($query);
    //$request_succeeded = $sth->execute();
    $sth->setFetchMode(PDO::FETCH_CLASS, 'Livres');
    $sth->execute(array($titre));
    $livre = $sth->fetch();
    $sth->closeCursor();
    //var_dump($user);
    return $livre;
    }
    
    //Génère une miniature pour le livre à partir du pdf, à l'aide de Python
    public static function generateThumbnail($numero) {
        $pdfPath = __DIR__ . '/books/' . $numero . '.pdf';
        $outputPath = __DIR__ . '/thumbnail/' . $numero . '.jpg';
        $pythonVersion = shell_exec("/Users/samyelbakouri/opt/anaconda3/bin/python3 --version 2>&1");
        error_log("Python Version: " . $pythonVersion);
        
        // Gestion de la sécurité
        $pdfPathEscaped = escapeshellarg($pdfPath);
        $outputPathEscaped = escapeshellarg($outputPath);
        
        // Chemin vers le fichier Python
        $command = "/Users/samyelbakouri/opt/anaconda3/bin/python3 convert.py $pdfPathEscaped $outputPathEscaped 2>&1";
        $output = shell_exec($command);
        
        // Débogage
        error_log("PDF Conversion Output: " . $output);
        
        return file_exists($outputPath);
    }
    
    public static function insererLivre($dbh, $titre,$auteur,$genre,$date_parution,$description,$extension,$filepath){
        echo "insererLivre";
        $sth = $dbh->prepare('INSERT INTO `Livres` (`titre`, `auteur`, `genre`, `date_parution`, `description`, `extension`) VALUES(?,?,?,?,?,?)');
        $sth->execute(array($titre,$auteur,$genre,$date_parution,$description,$extension));
        $numero = $dbh->lastInsertId();
        move_uploaded_file($filepath, "books/$numero.pdf");
                // Génère la miniature en utilisant Python
                if (self::generateThumbnail($numero)) {
                    echo "Thumbnail successfully generated for book ID: $numero\n";
                } else {
                    echo "Failed to generate thumbnail for book ID: $numero\n";
                }
        //self::generateMissingThumbnails();
    }
    
    //Génère les miniatures de livres qui n'en ont pas dans la base de donnée
    public static function generateMissingThumbnails() {
        $booksDir = __DIR__ . '/books/';
        $thumbnailsDir = __DIR__ . '/thumbnail/';
        $pdfFiles = glob($booksDir . '*.pdf');
    
        foreach ($pdfFiles as $pdfPath) {
            // Extrait le numéro du livre à partir du nom
            $numero = pathinfo($pdfPath, PATHINFO_FILENAME);
    
            // Vérifie si la miniature existe déjà
            $thumbnailPath = $thumbnailsDir . $numero . '.jpg';
            if (!file_exists($thumbnailPath)) {
                echo "Generating thumbnail for book: $numero\n";
                self::generateThumbnail($numero);
            } else {
                echo "Thumbnail already exists for book: $numero\n";
            }
        }
    }
    
    //Vérifie si un mot de passe correspond à un utilisateur dans la base de données
    public static function testerMDP($dbh, $login, $mdp){
        $user = Livres::getLivre($dbh, $login);
        
        return password_verify( $mdp,$user->mdp);
    }
}   
?>