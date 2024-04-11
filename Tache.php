<?php
class Tache {
    private $erreurs = [];
    private $id;
    private $libelle;
    private $description;
    private $date_livrable;
    private $priorite;
    private $etat;

    // Constantes pour les erreurs de validation 
    const LIBELLE_INVALIDE = 1;
    const DESCRIPTION_INVALIDE = 2;
    const PRIORITE_INVALIDE = 3;
    const ETAT_INVALIDE = 4;
    const DATE_LIVRABLE_INVALIDE = 5;
    // Constructeur de la classe Tache
    public function __construct($donnee = []) {
        // Si des données sont fournies, hydrate l'objet avec ces données
        if(!empty($donnee)) {
            $this->hydrater($donnee);
        }
    }

    // Méthode pour hydrater l'objet avec des données 
    public function hydrater($donnee) {
        foreach($donnee as $attribut => $valeur) {
            // Construire le nom de la méthode setter à appeler
            $methodeSeters = "set" . ucfirst($attribut);
            // Vérifier si la méthode existe
            if(method_exists($this, $methodeSeters)) {
                $this->$methodeSeters($valeur);
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

    public function getLibelle() {
        return $this->libelle;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getDate() {
        return $this->date_livrable;
    }

    public function getPriorite() {
        return $this->priorite;
    }

    public function getEtat() {
        return $this->etat;
    }

    // Méthodes setter pour définir les attributs de l'objet
    public function setId($id) {
        if(!empty($id)) {
            $this->id = (int) $id;
        }
    }

    public function setLibelle($libelle) {
        // Vérifier si le nom est une chaîne non vide
        if(!is_string($libelle) || empty($libelle)) {
            $this->erreurs[] = self::LIBELLE_INVALIDE;
        } else {
            $this->libelle = $libelle;
        }
    }

    public function setDescription($desc) {
        // Vérifier si la description est une chaîne non vide
        if(!is_string($desc) || empty($desc)) {
            $this->erreurs[] = self::DESCRIPTION_INVALIDE;
        } else {
            $this->description = $desc;
        }
    }

    public function setDate($date) {
        // Vérifie si la date est au format YYYY-MM-DD
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
            $this->erreurs[] = self::DATE_LIVRABLE_INVALIDE;
        } else {
            $this->date_livrable = $date;
        }
    }

    public function setPriorite($priorite) {
        // Ajouter une validation pour la priorité (si nécessaire)
        if(!is_string($priorite) || empty($priorite)) {
            $this->erreurs[] = self::PRIORITE_INVALIDE;
        } else {
            $this->priorite = $priorite;
        }
    }

    public function setEtat($etat) {
        // Ajouter une validation pour l'état (si nécessaire)
        if(!is_string($etat) || empty($etat)) {
            $this->erreurs[] = self::ETAT_INVALIDE;
        } else {
            $this->etat = $etat;
        }
    }
}
?>
