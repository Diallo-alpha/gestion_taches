<?php
// Inclure le fichier de connexion à la base de données et les classes nécessaires
require_once "config.php";
require_once "inclureClasse.php";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Créer une instance de la classe Tache
    $tache = new Tache();

    // Définir les valeurs des attributs de l'objet Tache
    $tache->setLibelle($_POST['libelle']);
    $tache->setDescription($_POST['description']);
    $tache->setDate($_POST['date_livrable']);
    $tache->setPriorite($_POST['priorite']);
    $tache->setEtat($_POST['etat']);
    $tache->setIdUser($_POST['assigner_a']);

    // Vérifier s'il y a des erreurs de validation
    if (empty($tache->getErr())) {
        // Créer une instance de la classe Manager
        $manager = new Manager($connexion);

        // Insérer les données dans la base de données en utilisant la méthode creerTache
        try {
            $manager->creerTache($tache);

            // Récupérer l'ID de l'utilisateur sélectionné dans le formulaire
            $idUtilisateur = $_POST['assigner_a'];

            // Assigner la tâche à l'utilisateur
            $manager->assignerTacheAUtilisateur($tache->getId(), $idUtilisateur);

            header("location: index.php");
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        // Afficher les erreurs de validation
        echo "Erreurs de validation : " . implode(", ", $tache->getErr());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une tâche</title>
    <link rel="stylesheet" href="styles/addT.css">
</head>
<body>
    <div class="container">
        <h2>Ajouter une nouvelle tâche</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="libelle">Libellé de la tâche :</label>
            <input type="text" id="libelle" name="libelle" required>

            <label for="description">Description :</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="date_livrable">Date livrable :</label>
            <input type="date" id="date_livrable" name="date_livrable" required>

            <label for="priorite">Priorité :</label>
            <select id="priorite" name="priorite" required>
                <option value="faible">faible</option>
                <option value="Moyenne">Moyenne</option>
                <option value="elevee">Difficile</option>
            </select>
            
            <label for="etat">État :</label>
            <select id="etat" name="etat" required>
                <option value='A faire'>A faire</option>
                <option value='En cours'>En cours</option>
                <option value='Terminée'>Terminée</option>
            </select>

            <label for="assigner_a">Assigner à :</label>
            <select id="assigner_a" name="assigner_a" required>
                <option value="">Sélectionner un utilisateur</option>
                <?php
                $manager = new Manager($connexion);
                $listeUtilisateurs = $manager->allUser();
                foreach ($listeUtilisateurs as $utilisateur) {
                    echo "<option value='".$utilisateur['id']."'>".$utilisateur['nom']." ".$utilisateur['prenom']."</option>";
                }
                ?>
            </select>

            <button type="submit">Ajouter la tâche</button>
        </form>
    </div>
</body>
</html>
