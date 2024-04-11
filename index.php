<?php
require_once "config.php";
require_once "Manager.php";

try {
    $manager = new Manager($connexion);
    $taches = $manager->getTaches();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des tâches</title>
    <link rel="stylesheet" href="style/tache.css">
</head>
<body>
    <div class="container">
        <h2>Liste des tâches</h2>
        <div class="taches">
            <?php foreach ($taches as $tache) : ?>
                <div class="carte">
                    <h3><?= htmlspecialchars($tache->getLibelle()) ?></h3>
                    <p>Description : <?= htmlspecialchars($tache->getDescription()) ?></p>
                    <p>Date livrable : <?= htmlspecialchars($tache->getDate()) ?></p>
                    <p>Priorité : <?= htmlspecialchars($tache->getPriorite()) ?></p>
                    <p>État : <?= htmlspecialchars($tache->getEtat()) ?></p>
                    <p>Assignée à : <?= htmlspecialchars($manager->getNomUtilisateurById($tache->getUtilisateurId())) ?></p>
                    <div class="boutons">
                        <a href="updateTache.php?id=<?= $tache->getId() ?>">Modifier</a>
                        <a href="deleteTache.php?id=<?= $tache->getId() ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')">Supprimer</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
