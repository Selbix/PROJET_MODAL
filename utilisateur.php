<?php
class Utilisateur {
    public $id;
    public $nom_utilisateur;
    public $nom_complet;
    public $email;
    public $password;
    public $quote;
    public $image;

    // Constructeur de la classe avec valeurs par défaut
    /*public function __construct($nom_utilisateur = null, $nom_complet = null, $email = null, $password = null, $quote = null) {
        $this->nom_utilisateur = $nom_utilisateur;
        $this->nom_complet = $nom_complet;
        $this->email = $email;
        $this->password = $password;
        $this->quote = $quote;
    }*/
    public function __construct($data = null) {
        if ($data) {
            // Si un tableau de données est passé, configure les propriétés
            $this->nom_utilisateur = $data['nom_utilisateur'] ?? null;
            $this->nom_complet = $data['nom_complet'] ?? null;
            $this->email = $data['email'] ?? null;
            $this->password = $data['password'] ?? null;
            $this->quote = $data['quote'] ?? null;
            $this->image = $data['image'] ?? null;
        }
    }

    // Méthode pour récupérer un utilisateur par ID
    public static function getUtilisateur($dsn, $id) {
        $query = "SELECT * FROM Utilisateurs WHERE id = ?";
        $sth = $dsn->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
        $sth->execute(array($id));
        $user = $sth->fetch();
        $sth->closeCursor();
        return $user;
    }

    // Méthode pour récupérer un utilisateur par email
    public static function getUtilisateurParEmail($dsn, $email) {
        $query = "SELECT * FROM Utilisateurs WHERE email = ?";
        $sth = $dsn->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
        $sth->execute(array($email));
        $user = $sth->fetch();
        $sth->closeCursor();
        return $user ? $user : null; // Retourne null si aucun utilisateur n'est trouvé
    }

    // Méthode pour ajouter ou insérer un utilisateur à la base de données
    public function save($dsn) {
        try {
            // Vérifier si l'utilisateur existe déjà
            $existingUser = Utilisateur::getUtilisateurParEmail($dsn, $this->email);
            if ($existingUser !== null) {
                error_log("Utilisateur déjà existant: " . $this->email);
                return false;
            }

            // Préparer la requête SQL
            $sth = $dsn->prepare(
                'INSERT INTO Utilisateurs (nom_utilisateur, nom_complet, email, password, quote, image) 
                 VALUES (?, ?, ?, ?, ?, ?)'
            );

            // Hasher le mot de passe
            $mdp_hashed = password_hash($this->password, PASSWORD_DEFAULT);

            // Forcer NULL pour les quotes et images vides
            $quoteValue = empty($this->quote) ? null : $this->quote;
            $imageValue = empty($this->image) ? null : $this->image;

            // Logger les valeurs pour le débogage
            error_log("Saving user: nom_utilisateur={$this->nom_utilisateur}, nom_complet={$this->nom_complet}, email={$this->email}, quote=" . ($quoteValue ?? 'NULL') . ", image=" . ($imageValue ?? 'NULL'));

            // Exécuter la requête
            return $sth->execute([
                $this->nom_utilisateur,
                $this->nom_complet,
                $this->email,
                $mdp_hashed,
                $quoteValue,
                $imageValue
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de l'enregistrement: " . $e->getMessage());
            return false;
        }
    }

    // Méthode pour vérifier le mot de passe
    public static function testerMDP($dsn, $email, $password) {
        $user = Utilisateur::getUtilisateurParEmail($dsn, $email);
        if (!$user) {
            return false; // Retourne false si l'utilisateur n'est pas trouvé
        }
        return password_verify($password, $user->password);
    }
}
?>
