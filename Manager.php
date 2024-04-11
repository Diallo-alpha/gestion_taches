<?php
require_once "config.php";
require_once "inclureClasse.php";

class Manager extends User {
    protected $connexion;

    // Constructeur de la classe Manager
    public function __construct(PDO $connexion, $donnees = []) {
        // Appel du constructeur de la classe parent (User)
        parent::__construct($donnees);
        $this->connexion = $connexion;
    }

    // Fonction pour créer une tâche
    public function creerTache(Tache $tache) {
        try {
            $sql = $this->connexion->prepare("INSERT INTO tache(libelle, description, date_livrable, priorite, etat) VALUES(:libelle, :description, :date_livrable, :priorite, :etat)");
            $sql->bindValue(':libelle', $tache->getLibelle());
            $sql->bindValue(':description', $tache->getDescription());
            $sql->bindValue(':date_livrable', $tache->getDate());
            $sql->bindValue(':priorite', $tache->getPriorite());
            $sql->bindValue(':etat', $tache->getEtat());
            $sql->execute();

            // Vérifier si la requête a réussi
            if ($sql->rowCount() === 0) {
                throw new Exception("La création de la tâche a échoué.");
            }
        } catch (PDOException $e) {
            // En cas d'erreur PDO
            throw new Exception("Erreur d'insertion dans la base de données: " . $e->getMessage());
        }
    }

    // Fonction pour mettre à jour une tâche
    public function updateTache(Tache $tache) {
        try {
            $sql = $this->connexion->prepare("UPDATE tache SET libelle = :libelle, description = :description, date_livrable = :date_livrable, priorite = :priorite, etat = :etat WHERE id = :id");
            $sql->bindValue(':id', $tache->getId());
            $sql->bindValue(':libelle', $tache->getLibelle());
            $sql->bindValue(':description', $tache->getDescription());
            $sql->bindValue(':date_livrable', $tache->getDate());
            $sql->bindValue(':priorite', $tache->getPriorite());
            $sql->bindValue(':etat', $tache->getEtat());
            $sql->execute();

            // Vérifier si la requête a réussi
            if ($sql->rowCount() === 0) {
                throw new Exception("La mise à jour de la tâche a échoué.");
            }
        } catch (PDOException $e) {
            // En cas d'erreur PDO
            throw new Exception("Erreur de mise à jour dans la base de données: " . $e->getMessage());
        }
    }

    // Fonction pour supprimer une tâche
    public function deleteTache($id) {
        try {
            $sql = $this->connexion->prepare("DELETE FROM tache WHERE id = :id");
            $sql->bindValue(':id', $id);
            $sql->execute();

            // Vérifier si la requête a réussi
            if ($sql->rowCount() === 0) {
                throw new Exception("La suppression de la tâche a échoué.");
            }
        } catch (PDOException $e) {
            // En cas d'erreur PDO
            throw new Exception("Erreur de suppression dans la base de données: " . $e->getMessage());
        }
    }
    //pour l'affichage 
    public function getTaches() {
        try {
            $sql = $this->connexion->prepare("SELECT * FROM tache");
            $sql->execute();
            $resultat = $sql->fetchAll(PDO::FETCH_ASSOC);
            $taches = [];
    
            foreach ($resultat as $row) {
                $tache = new Tache();
                $tache->setId($row['id']);
                $tache->setLibelle($row['libelle']);
                $tache->setDescription($row['description']);
                $tache->setDate($row['date_livrable']);
                $tache->setPriorite($row['priorite']);
                $tache->setEtat($row['etat']);
                $taches[] = $tache;
            }
    
            return $taches;
        } catch (PDOException $e) {
            // En cas d'erreur PDO
            throw new Exception("Erreur de récupération des tâches depuis la base de données: " . $e->getMessage());
        }
    }
    public function getTacheById($id) {
        try {
            $sql = $this->connexion->prepare("SELECT * FROM tache WHERE id = :id");
            $sql->bindParam(':id', $id);
            $sql->execute();
            $tache = $sql->fetch(PDO::FETCH_ASSOC);
            return $tache;
        } catch (PDOException $e) {
            // En cas d'erreur PDO
            throw new Exception("Erreur de récupération de la tâche depuis la base de données: " . $e->getMessage());
        }
    }

// Fonction pour assigner une tâche à un utilisateur
public function assignerTacheAUtilisateur($idTache, $idUtilisateur) {
    try {
        // Vérifier si l'utilisateur existe
        $sql = $this->connexion->prepare("SELECT COUNT(*) FROM utilisateur WHERE id = :id");
        $sql->bindParam(':id', $idUtilisateur);
        $sql->execute();
        $utilisateurExiste = $sql->fetchColumn();

        if ($utilisateurExiste) {
            // Mettre à jour l'attribut employe_id de la tâche pour l'assigner à l'utilisateur
            $sql = $this->connexion->prepare("UPDATE tache SET employe_id = :idUtilisateur WHERE id = :idTache");
            $sql->bindValue(':idTache', $idTache);
            $sql->bindValue(':idUtilisateur', $idUtilisateur);
            $sql->execute();

            // Vérifier si la requête a réussi
            if ($sql->rowCount() === 0) {
                throw new Exception("L'assignation de la tâche à l'utilisateur a échoué.");
            } else {
                echo "La tâche a été assignée à l'utilisateur avec succès.";
            }
        } else {
            throw new Exception("L'utilisateur avec l'identifiant $idUtilisateur n'existe pas.");
        }
    } catch (PDOException $e) {
        // En cas d'erreur PDO
        throw new Exception("Erreur lors de l'assignation de la tâche à l'utilisateur: " . $e->getMessage());
    }
}
//tous les utilisateur 
public function allUser()
{
    try{
        $sql =  $this->connexion->prepare("SELECT * FROM utilisateur");
        $sql->execute();
        return $sql->fetchAll();
    }catch(PDOException $e)
        {
            die("erreur pour récupération list users " .$e->getMessage());
        }
}
  ///
  public function getNomUtilisateurById($idUtilisateur) {
    try {
        $sql = $this->connexion->prepare("SELECT u.nom, u.prenom FROM utilisateur u JOIN tache t ON u.id = t.id_utilisateur WHERE u.id = :id");
        $sql->bindParam(':id', $idUtilisateur);
        $sql->execute();
        $utilisateur = $sql->fetch(PDO::FETCH_ASSOC);
        if ($utilisateur) {
            return $utilisateur['nom'] . ' ' . $utilisateur['prenom'];
        } else {
            return "Utilisateur inconnu";
        }
    } catch (PDOException $e) {
        throw new Exception("Erreur lors de la récupération de l'utilisateur depuis la base de données: " . $e->getMessage());
    }
}
  
}
?>