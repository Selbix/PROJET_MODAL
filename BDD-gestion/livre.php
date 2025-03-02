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
        #$date = explode('-',$this->naissance);

        return $this->titre ." de". $this->auteur . ", paru en" . $this->date_parution . ". Genre " . $this->genre;
    }
    public static function getLivre($dbh, $titre){
        $query = "SELECT * FROM Livres WHERE titre = ?";
    $sth = $dbh->prepare($query);
    //$request_succeeded = $sth->execute();
    $sth->setFetchMode(PDO::FETCH_CLASS, 'Livres');
    $sth->execute(array($titre));
    $livre = $sth->fetch();
    $sth->closeCursor();
    ////var_dump($user);
    return $livre;
    }
    public static function generateThumbnail($numero) {
        $pdfPath = __DIR__ . '/books/' . $numero . '.pdf';
        $outputPath = __DIR__ . '/thumbnail/' . $numero . '.jpg';
        
        // Check Python version
        $pythonVersion = shell_exec("/Users/samyelbakouri/opt/anaconda3/bin/python3 --version 2>&1");
        error_log("Python Version: " . $pythonVersion);
        
        // Remove the PYTHONPATH setting since it's undefined
        // putenv("PYTHONPATH=/Users/samyelbakouri/opt/anaconda3/lib/python3.8/site-packages:".$_ENV['PYTHONPATH']);
        
        // Escape paths for security
        $pdfPathEscaped = escapeshellarg($pdfPath);
        $outputPathEscaped = escapeshellarg($outputPath);
        
        $convertScript = __DIR__ . '/convert.py';
        $command = "/Users/samyelbakouri/opt/anaconda3/bin/python3 $convertScript $pdfPathEscaped $outputPathEscaped 2>&1";
        $output = shell_exec($command);
        
        // Logging for debugging
        error_log("PDF Conversion Output: " . $output);
        
        return file_exists($outputPath);
    }
    
    public static function insererLivre($dbh, $titre,$auteur,$genre,$date_parution,$description,$extension,$filepath){
        //echo "insererLivre";
        $sth = $dbh->prepare('INSERT INTO `Livres` (`titre`, `auteur`, `genre`, `date_parution`, `description`, `extension`) VALUES(?,?,?,?,?,?)');
        $sth->execute(array($titre,$auteur,$genre,$date_parution,$description,$extension));
        $numero = $dbh->lastInsertId();
        move_uploaded_file($filepath, "BDD-gestion/books/$numero.pdf");
                // Generate the thumbnail using Python
                if (self::generateThumbnail($numero)) {
                    //echo "Thumbnail successfully generated for book ID: $numero\n";
                } else {
                    //echo "Failed to generate thumbnail for book ID: $numero\n";
                }
        //self::generateMissingThumbnails();
    }
    public static function generateMissingThumbnails() {
        $booksDir = __DIR__ . '/books/';
        $thumbnailsDir = __DIR__ . '/thumbnail/';
    
        // Get all PDF files
        $pdfFiles = glob($booksDir . '*.pdf');
    
        foreach ($pdfFiles as $pdfPath) {
            // Extract the book number from the filename
            $numero = pathinfo($pdfPath, PATHINFO_FILENAME);
    
            // Check if thumbnail already exists
            $thumbnailPath = $thumbnailsDir . $numero . '.jpg';
            if (!file_exists($thumbnailPath)) {
                echo "Generating thumbnail for book: $numero\n";
                self::generateThumbnail($numero);
            } else {
                echo "Thumbnail already exists for book: $numero\n";
            }
        }
    }
    
    public static function testerMDP($dbh, $login, $mdp){
        $user = Livres::getLivre($dbh, $login);
        
        return password_verify( $mdp,$user->mdp);
    }
}   
?>