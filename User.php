<?php
require_once "config.php";
require_once "inclureClasse.php";
class User {
   private $erreurs = [];
   private $id;
   private $connexion;
   private $name;
   private $firstName;
   private $email;
   private $password;
   private $tel;

   // Constantes pour les erreurs de validation
   const NOM_INVALIDE = 1;
   const PRENOM_INVALIDE = 2;
   const EMAIL_INVALIDE = 3;
   const TELEPHONE_INVALIDE = 4;
   const MOT_DE_PASSE_INVALIDE = 5;

// Constructeur de la classe User

    public function __construct($donnees = []) {
    // Si des données sont fournies, hydrate l'objet avec ces données
        if(!empty($donnees)) {
        $this->hydrater($donnees);
        }
        $this->erreurs = [];
    }

    // Méthode pour hydrater l'objet avec des données
    public function hydrater($donnees) {
        foreach($donnees as $attribut => $valeur) {
            // Construit le nom de la méthode setter à appeler
            $methodeSetters = "set" . ucfirst($attribut);
            // Vérifie si la méthode setter correspondante existe avant de l'appeler
            if(method_exists($this, $methodeSetters)) {
                $this->$methodeSetters($valeur);
            }
        }
    }

    // Méthodes getter pour récupérer les attributs de l'objet
    public function getErr() {
        return $this->erreurs;
    }

    public function getId() {
        return $this->id;
    }

    public function getConnexion() {
        return $this->connexion;
    }

    public function getName() {
        return $this->name;
    }

    public function getFirst_name() {
        return $this->firstName;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getTel() {
        return $this->tel;
    }

    // Méthodes setter pour définir les attributs de l'objet
    public function setErr($error) {
        $this->erreurs[] = $error;
    }
    public function setId($id) {
        if(!empty($id)) {
            $this->id = (int) $id;
        }
    }

    public function setConnexion($connexion) {
        $this->connexion = $connexion;
    }

    public function setName($name) {
        // Vérifie si le nom est une chaîne non vide
        if(!is_string($name) || empty($name)) {
            $this->erreurs[] = self::NOM_INVALIDE;
        } else {
            $this->name = $name;
        }
    }

    public function setFirst_name($firstName) {
        // Vérifie si le prénom est une chaîne non vide
        if(!is_string($firstName) || empty($firstName)) {
            $this->erreurs[] = self::PRENOM_INVALIDE;
        } else {
            $this->firstName = $firstName;
        }
    }

    public function setEmail($email) {
        // Vérifie si l'email est valide
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            $this->erreurs[] = self::EMAIL_INVALIDE;
        }
    }

    public function setPassword($password) {
        // Vérifie si le mot de passe est une chaîne non vide
        if(!is_string($password) || empty($password)) {
            $this->erreurs[] = self::MOT_DE_PASSE_INVALIDE;
        } else {
            $this->password = $password;
        }
    }

    public function setTel($tel) {

            $this->tel = $tel;
    }

    public function isUserValide() {
        // Vérifie s'il y a des erreurs de validation
        if (!empty($this->erreurs)) {
            return false;
        }

        // Vérifie si tous les attributs obligatoires sont définis
        if (empty($this->name) || empty($this->firstName) || empty($this->tel) || empty($this->email) || empty($this->password)) {
            return false;
        }

        return true;
    }
}

?>
