<?php
// Inclure le fichier de connexion à la base de données et les classes nécessaires
require_once "inclureClasse.php";
require_once "config.php";

// Vérifier si l'identifiant de la tâche à supprimer est passé en paramètre
if (isset($_GET['id'])) {
    // Récupérer l'identifiant de la tâche depuis l'URL
    $idTache = $_GET['id'];

    // Créer une instance de la classe Manager
    $manager = new Manager($connexion);

    // Supprimer la tâche de la base de données en utilisant la méthode deleteTache
    try {
        $manager->deleteTache($idTache);
        echo "La tâche a été supprimée avec succès.";
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Identifiant de tâche manquant.";
    exit; // Arrêter le script
}
?>
