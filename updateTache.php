<?php
// Inclure le fichier de connexion à la base de données et les classes nécessaires
require_once "inclureClasse.php";
require_once "config.php";

// Vérifier si l'identifiant de la tâche à mettre à jour est passé en paramètre
if (isset($_GET['id'])) {
    // Récupérer l'identifiant de la tâche depuis l'URL
    $idTache = $_GET['id'];

    // Récupérer les informations de la tâche à mettre à jour depuis la base de données
    $manager = new Manager($connexion);
    $tache = $manager->getTacheById($idTache);
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Créer une instance de la classe Tache
    $tache = new Tache();

    // Définir les valeurs des attributs de l'objet Tache avec les nouvelles données
    $tache->setId($idTache);
    $tache->setLibelle($_POST['libelle']);
    $tache->setDescription($_POST['description']);
    $tache->setDate($_POST['date_livrable']);
    $tache->setPriorite($_POST['priorite']);
    $tache->setEtat($_POST['etat']);

    // Vérifier s'il y a des erreurs de validation
    if (empty($tache->getErr())) {
        // Créer une instance de la classe Manager
        $manager = new Manager($connexion);

        // Mettre à jour les données dans la base de données en utilisant la méthode updateTache
        try {
            $manager->updateTache($tache);
            echo "La tâche a été mise à jour avec succès.";
            header("Location: index.php");
            exit();
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
    <title>Modifier une tâche</title>
    <link rel="stylesheet" href="style/tache.css">
</head>
<body>
    <div class="container">
        <h2>Modifier la tâche</h2>
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?id=$idTache"; ?>" method="POST">
            <label for="libelle">Libellé de la tâche :</label>
            <input type="text" id="libelle" name="libelle" value="<?php echo $tache['libelle']; ?>" required>

            <label for="description">Description :</label>
            <textarea id="description" name="description" rows="4" required><?php echo $tache['description']; ?></textarea>

            <label for="date_livrable">Date livrable :</label>
            <input type="date" id="date_livrable" name="date_livrable" value="<?php echo $tache['date_livrable']; ?>" required>

            <label for="priorite">Priorité :</label>
            <select id="priorite" name="priorite" required>
                <option value="faible" <?php if ($tache['priorite'] == 'faible') echo 'selected'; ?>>Basse</option>
                <option value="Moyenne" <?php if ($tache['priorite'] == 'Moyenne') echo 'selected'; ?>>Moyenne</option>
                <option value="elevee" <?php if ($tache['priorite'] == 'elevee') echo 'selected'; ?>>Haute</option>
            </select>

            <label for="etat">État :</label>
            <select id="etat" name="etat" required>
                 <option value='A faire' <?php if ($tache['etat'] == 'A faire') echo 'selected'; ?>>A faire</option>
                 <option value='En cours'<?php if ($tache['etat'] == 'En cours') echo 'selected'; ?>>En cours</option>
                 <option value='Terminée'<?php if ($tache['etat'] == 'Terminée') echo 'selected'; ?>>Terminée</option>
            </select>

            <button type="submit">Modifier la tâche</button>
        </form>
    </div>
</body>
</html>
