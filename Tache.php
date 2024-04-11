<?php
class Tache {
    private $erreurs = [];
    private $id;
    private $libelle;
    private $description;
    private $date_livrable;
    private $priorite;
    private $etat;
    private $utilisateur; // Ajout de l'attribut utilisateur

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
            if ($attribut === 'utilisateur') {
                $this->setUtilisateur($valeur); // Utilisateur associé à la tâche
            } else {
                $methodeSeters = "set" . ucfirst($attribut);
                if(method_exists($this, $methodeSeters)) {
                    $this->$methodeSeters($valeur);
                }
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

    public function getUtilisateurId() {
        return $this->utilisateur;
    }

    // Méthodes setter pour définir les attributs de l'objet
    public function setId($id) {
        if(!empty($id)) {
            $this->id = (int) $id;
        }
    }

    public function setLibelle($libelle) {
        if(!is_string($libelle) || empty($libelle)) {
            $this->erreurs[] = self::LIBELLE_INVALIDE;
        } else {
            $this->libelle = $libelle;
        }
    }

    public function setDescription($desc) {
        if(!is_string($desc) || empty($desc)) {
            $this->erreurs[] = self::DESCRIPTION_INVALIDE;
        } else {
            $this->description = $desc;
        }
    }

    public function setDate($date) {
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
            $this->erreurs[] = self::DATE_LIVRABLE_INVALIDE;
        } else {
            $this->date_livrable = $date;
        }
    }

    public function setPriorite($priorite) {
        if(!is_string($priorite) || empty($priorite)) {
            $this->erreurs[] = self::PRIORITE_INVALIDE;
        } else {
            $this->priorite = $priorite;
        }
    }

    public function setEtat($etat) {
        if(!is_string($etat) || empty($etat)) {
            $this->erreurs[] = self::ETAT_INVALIDE;
        } else {
            $this->etat = $etat;
        }
    }

    public function setUtilisateurId($utilisateur) {
        $this->utilisateur = $utilisateur;
    }
}
?>
