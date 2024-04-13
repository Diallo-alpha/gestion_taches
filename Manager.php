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
            $sql = $this->connexion->prepare("INSERT INTO tache(libelle, description, date_livrable, priorite, etat, id_utilisateur) VALUES(:libelle, :description, :date_livrable, :priorite, :etat, :id_utilisateur)");
            $sql->bindValue(':libelle', $tache->getLibelle());
            $sql->bindValue(':description', $tache->getDescription());
            $sql->bindValue(':date_livrable', $tache->getDate());
            $sql->bindValue(':priorite', $tache->getPriorite());
            $sql->bindValue(':etat', $tache->getEtat());
            $sql->bindValue(':id_utilisateur', $tache->getIdUser());
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
            $sql = $this->connexion->prepare("UPDATE tache SET libelle = :libelle, description = :description, date_livrable = :date_livrable, priorite = :priorite, etat = :etat, id_utilisateur = :id_utilisateur WHERE id = :id");
            $sql->bindValue(':id', $tache->getId());
            $sql->bindValue(':libelle', $tache->getLibelle());
            $sql->bindValue(':description', $tache->getDescription());
            $sql->bindValue(':date_livrable', $tache->getDate());
            $sql->bindValue(':priorite', $tache->getPriorite());
            $sql->bindValue(':etat', $tache->getEtat());
            $sql->bindValue(':id_utilisateur', $tache->getIdUser());
            $sql->execute();
    
            // Vérifier si la requête a réussi
            if ($sql->rowCount() === 0) {
                throw new Exception("La mise à jour de la tâche a échoué.");
            }
        } catch (PDOException $e) {
            // En cas d'erreur PDO
            $this->connexion->rollBack(); // Annuler la transaction en cas d'erreur
            throw new Exception("Erreur de mise à jour dans la base de données: " . $e->getMessage());
        } catch (Exception $e) {
            // Enregistrer l'erreur et envoyer un message à l'utilisateur
            error_log("Erreur lors de la mise à jour de la tâche: " . $e->getMessage());
            echo "Une erreur inattendue est survenue. Veuillez réessayer ultérieurement.";
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
        $sqlUtilisateur = $this->connexion->prepare("SELECT id FROM utilisateur WHERE id = :id");
        $sqlUtilisateur->bindParam(':id', $idUtilisateur);
        $sqlUtilisateur->execute();
        $utilisateur = $sqlUtilisateur->fetch();

        if ($utilisateur) {
            // Commencer une transaction
            $this->connexion->beginTransaction();

            // Mettre à jour l'attribut utilisateur_id de la tâche pour l'assigner à l'utilisateur
            $sqlTache = $this->connexion->prepare("UPDATE tache SET id_utilisateur = :idUtilisateur WHERE id = :idTache");
            $sqlTache->bindValue(':idTache', $idTache);
            $sqlTache->bindValue(':idUtilisateur', $idUtilisateur);
            $sqlTache->execute();

            // Vérifier si la requête a réussi
            $nbLignesAffectees = $sqlTache->rowCount();
            if ($nbLignesAffectees === 0) {
                // Annuler la transaction en cas d'échec
                $this->connexion->rollBack();
                throw new Exception("L'assignation de la tâche à l'utilisateur a échoué.");
            } else {
                // Valider la transaction en cas de succès
                $this->connexion->commit();
                echo "La tâche a été assignée à l'utilisateur avec succès.";
            }
        } else {
            throw new Exception("L'utilisateur avec l'identifiant $idUtilisateur n'existe pas.");
        }
    } catch (PDOException $e) {
        // Annuler la transaction en cas d'erreur PDO
        $this->connexion->rollBack();
        throw new Exception("Erreur lors de l'assignation de la tâche à l'utilisateur: " . $e->getMessage());
    } catch (Exception $e) {
        // Enregistrer l'erreur et envoyer un message à l'utilisateur
        error_log("Erreur lors de l'assignation de la tâche à l'utilisateur: " . $e->getMessage());
        echo "Une erreur inattendue est survenue. Veuillez réessayer ultérieurement.";
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
public function recupererTache($idTache) {
    try {
        $sql = $this->connexion->prepare("SELECT * FROM tache WHERE id = :id");
        $sql->bindValue(':id', $idTache);
        $sql->execute();
        $tache = $sql->fetch(PDO::FETCH_ASSOC);
        return $tache;
    } catch (PDOException $e) {
        // En cas d'erreur PDO
        throw new Exception("Erreur de récupération de la tâche depuis la base de données: " . $e->getMessage());
    }
}
  ///
  public function recupererTacheAvecNomUtilisateur($idTache) {
    try {
        // Récupérer les informations de la tâche
        $tache = $this->recupererTache($idTache);

        // Si la tâche existe
        if ($tache) {
            // Récupérer le nom de l'utilisateur associé à la tâche
            $sqlUtilisateur = $this->connexion->prepare("SELECT nom, prenom FROM utilisateur WHERE id = :idUtilisateur");
            $sqlUtilisateur->bindValue(':idUtilisateur', $tache['id_utilisateur']);
            $sqlUtilisateur->execute();
            $utilisateur = $sqlUtilisateur->fetch();

            // Si l'utilisateur existe
            if ($utilisateur) {
                // Combiner les informations de la tâche et du nom d'utilisateur
                $tache['nom_utilisateur'] = $utilisateur['nom'] . ' ' . $utilisateur['prenom'];
            } else {
                // Définir une valeur par défaut si l'utilisateur n'est pas trouvé
                $tache['nom_utilisateur'] = "Utilisateur inconnu";
            }

            // Renvoyer la tâche avec le nom d'utilisateur ajouté
            return $tache;
        } else {
            // Renvoyer null si la tâche n'existe pas
            return null;
        }
    } catch (PDOException $e) {
        throw new Exception("Erreur lors de la récupération de la tâche avec le nom d'utilisateur: " . $e->getMessage());
    } catch (Exception $e) {
        throw $e; // Laisser les autres exceptions non traitées remonter
    }
}


  
}
?>