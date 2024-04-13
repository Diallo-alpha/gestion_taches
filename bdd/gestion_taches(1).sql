-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : sam. 13 avr. 2024 à 13:16
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_taches`
--

-- --------------------------------------------------------

--
-- Structure de la table `chef_projet`
--

CREATE TABLE `chef_projet` (
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

CREATE TABLE `employe` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tache`
--

CREATE TABLE `tache` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_livrable` date NOT NULL,
  `priorite` enum('faible','moyenne','elevee') NOT NULL,
  `etat` enum('A faire','En cours','Terminée') NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tache`
--

INSERT INTO `tache` (`id`, `libelle`, `description`, `date_livrable`, `priorite`, `etat`, `id_utilisateur`) VALUES
(3, 'mettre a jours l\'aplication', 'vous devez mzttre a jour cette aapplication le plus rapidement possible ', '2024-04-12', 'faible', 'En cours', 1),
(6, 'implement la function qui permet d\'asigner une tache à un employer', 'dans cette tâche vous devez implement une function qui permet de aux cef de projet de assigner une tâche à un employéé', '2024-04-12', 'elevee', 'Terminée', 2),
(9, 'darrra', 'sxsbnxns cjjcd ', '2024-04-12', 'faible', 'En cours', 1),
(11, 'mega tache', 'réaliseé tous les tâches encore des tache', '2024-04-12', 'elevee', 'Terminée', 6),
(12, 'demba dhh', 'der fer sdf sfds gsg', '2024-04-11', 'faible', 'En cours', 5),
(13, 'une nouvelle tâche ', 'des tâches que vous avez faire ', '2024-04-18', 'moyenne', 'En cours', 2),
(14, 'zee', 'zoro20', '2024-04-01', 'moyenne', 'A faire', 1),
(15, 'demba dhh788', 'test pour assignation', '2024-04-02', 'elevee', 'En cours', 6),
(27, 'test12', 'test assignation12', '2024-04-13', 'faible', 'Terminée', 6);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telephone` int(11) NOT NULL,
  `mot_de_passe` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `email`, `telephone`, `mot_de_passe`) VALUES
(1, 'Diop', 'Amadou', 'amadou.diop@example.com', 0, 'motdepasse1'),
(2, 'Sow', 'Aïssatou', 'aissatou.sow@example.com', 0, 'motdepasse2'),
(3, 'Ndiaye', 'Mamadou', 'mamadou.ndiaye@example.com', 0, 'motdepasse3'),
(4, 'BA', 'CELINA', 'ibou.ndiaye@exemple.com', 781041321, '123ml'),
(5, 'BA', 'karaaaa', 'celine.diallo@exemple.com', 775452526, '$2y$10$uKpvjAGftI6OCarP/wfV4uZNH5krAURETfXnJZ591AnQAZZrzMDYq'),
(6, 'Diallo', 'ALPHA', 'alpha.diallo@exemple.com', 784563212, '1234');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `chef_projet`
--
ALTER TABLE `chef_projet`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- Index pour la table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tache`
--
ALTER TABLE `tache`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `tache`
--
ALTER TABLE `tache`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chef_projet`
--
ALTER TABLE `chef_projet`
  ADD CONSTRAINT `chef_projet_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `tache`
--
ALTER TABLE `tache`
  ADD CONSTRAINT `fk_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
