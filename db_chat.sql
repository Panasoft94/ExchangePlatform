-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 29 mars 2026 à 15:41
-- Version du serveur :  5.7.26
-- Version de PHP :  5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `db_chat`
--
CREATE DATABASE IF NOT EXISTS `db_chat` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `db_chat`;

-- --------------------------------------------------------

--
-- Structure de la table `chat_groups`
--

DROP TABLE IF EXISTS `chat_groups`;
CREATE TABLE IF NOT EXISTS `chat_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `chat_groups`
--

TRUNCATE TABLE `chat_groups`;
--
-- Déchargement des données de la table `chat_groups`
--

INSERT INTO `chat_groups` (`id`, `name`, `created_by`, `created_at`) VALUES
(1, 'IT-Dévéloppement', 1, '2026-03-29 15:08:47'),
(2, 'Core Senior', 1, '2026-03-29 15:10:08');

-- --------------------------------------------------------

--
-- Structure de la table `chat_group_members`
--

DROP TABLE IF EXISTS `chat_group_members`;
CREATE TABLE IF NOT EXISTS `chat_group_members` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`group_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `chat_group_members`
--

TRUNCATE TABLE `chat_group_members`;
--
-- Déchargement des données de la table `chat_group_members`
--

INSERT INTO `chat_group_members` (`group_id`, `user_id`, `joined_at`) VALUES
(1, 1, '2026-03-29 15:08:47'),
(1, 2, '2026-03-29 16:41:44'),
(1, 3, '2026-03-29 15:08:47'),
(2, 1, '2026-03-29 15:10:08'),
(2, 2, '2026-03-29 15:10:08');

-- --------------------------------------------------------

--
-- Structure de la table `chat_read_states`
--

DROP TABLE IF EXISTS `chat_read_states`;
CREATE TABLE IF NOT EXISTS `chat_read_states` (
  `user_id` int(11) NOT NULL,
  `chat_type` varchar(20) NOT NULL,
  `target_id` int(11) NOT NULL,
  `last_read_message_id` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`chat_type`,`target_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `chat_read_states`
--

TRUNCATE TABLE `chat_read_states`;

-- --------------------------------------------------------

--
-- Structure de la table `chat_message_attachments`
--

DROP TABLE IF EXISTS `chat_message_attachments`;
CREATE TABLE IF NOT EXISTS `chat_message_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `file_ext` varchar(20) DEFAULT NULL,
  `file_size` int(11) NOT NULL DEFAULT '0',
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_message_id` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `chat_message_attachments`
--

TRUNCATE TABLE `chat_message_attachments`;

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uploaded_by` (`uploaded_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `documents`
--

TRUNCATE TABLE `documents`;
-- --------------------------------------------------------

--
-- Structure de la table `group`
--

DROP TABLE IF EXISTS `group`;
CREATE TABLE IF NOT EXISTS `group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  `is_system` tinyint(4) NOT NULL DEFAULT '0',
  `user` tinyint(4) NOT NULL DEFAULT '0',
  `view_history` tinyint(4) NOT NULL DEFAULT '0',
  `group` tinyint(4) NOT NULL DEFAULT '0',
  `admin` int(1) NOT NULL,
  `add_user` int(1) NOT NULL,
  `update_user` int(1) NOT NULL,
  `delete_user` int(1) NOT NULL,
  `edit_user` int(1) NOT NULL,
  `envoyer_message` int(1) NOT NULL,
  `repondre_message` int(1) NOT NULL,
  `boite_reception` int(1) NOT NULL,
  `delete_message` int(1) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Tronquer la table avant d'insérer `group`
--

TRUNCATE TABLE `group`;
--
-- Déchargement des données de la table `group`
--

INSERT INTO `group` (`group_id`, `group_name`, `is_system`, `user`, `view_history`, `group`, `admin`, `add_user`, `update_user`, `delete_user`, `edit_user`, `envoyer_message`, `repondre_message`, `boite_reception`, `delete_message`) VALUES
(1, 'default', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 'Administrateur', 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `history`
--

DROP TABLE IF EXISTS `history`;
CREATE TABLE IF NOT EXISTS `history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `history_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `history_action` varchar(255) NOT NULL,
  `history_users` varchar(255) NOT NULL,
  PRIMARY KEY (`history_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

--
-- Tronquer la table avant d'insérer `history`
--

TRUNCATE TABLE `history`;
--
-- Déchargement des données de la table `history`
--

INSERT INTO `history` (`history_id`, `history_date`, `history_action`, `history_users`) VALUES
(23, '2023-07-22 19:57:43', 'Connexion de l\'utilisateur admin admin', 'admin admin'),
(24, '2023-07-22 19:59:57', 'Déconnexion de l\'utilisateur  admin admin', 'admin admin'),
(25, '2023-07-22 20:13:24', 'Connexion de l\'utilisateur admin admin', 'admin admin'),
(26, '2023-07-22 20:13:54', 'Modification de compte de l\'utilisateur ADMIN Admin', 'ADMIN Admin'),
(27, '2024-06-21 10:26:55', 'Connexion de l\'utilisateur ADMIN Admin', 'ADMIN Admin'),
(28, '2024-06-21 10:41:14', 'Déconnexion de l\'utilisateur  ADMIN Admin', 'ADMIN Admin'),
(29, '2024-06-21 10:42:05', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(30, '2024-06-21 10:44:20', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(31, '2024-06-21 10:49:07', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(32, '2024-06-21 10:49:24', 'Modification du groupe  Administrateur', 'DJIMTOLOUMA Anicet'),
(33, '2024-06-21 10:51:05', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(34, '2024-06-21 10:51:09', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(35, '2024-06-21 10:53:08', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(36, '2024-06-21 10:53:12', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(37, '2024-06-21 10:56:24', 'Modification de compte de l\'utilisateur DOKOSSI Gomer', 'DJIMTOLOUMA Anicet'),
(38, '2024-06-21 10:56:30', 'Modification de compte de l\'utilisateur TOAPORO Anniel', 'DJIMTOLOUMA Anicet'),
(39, '2026-03-29 14:23:50', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(40, '2026-03-29 15:17:19', 'Modification de compte de l\'utilisateur DOKOSSI Gomer', 'DJIMTOLOUMA Anicet'),
(41, '2026-03-29 15:17:58', 'Connexion de l\'utilisateur DOKOSSI Gomer', 'DOKOSSI Gomer'),
(42, '2026-03-29 15:22:24', 'Déconnexion de l\'utilisateur  DOKOSSI Gomer', 'DOKOSSI Gomer'),
(43, '2026-03-29 15:24:00', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(44, '2026-03-29 15:25:55', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(45, '2026-03-29 15:29:42', 'Création de l\'utilisateur DECKORO Fred', 'DJIMTOLOUMA Anicet'),
(46, '2026-03-29 15:53:43', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(47, '2026-03-29 16:55:35', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(48, '2026-03-29 16:55:45', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(49, '2026-03-29 17:10:58', 'Création de la réunion : Réunion de planification', 'DJIMTOLOUMA Anicet'),
(50, '2026-03-29 17:11:15', 'Suppression de la réunion : Réunion de planification', 'DJIMTOLOUMA Anicet'),
(51, '2026-03-29 17:37:12', 'Connexion de l\'utilisateur DECKORO Fred', 'DECKORO Fred'),
(52, '2026-03-29 17:40:17', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `messages`
--

TRUNCATE TABLE `messages`;
--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `group_id`, `content`, `created_at`) VALUES
(1, 1, NULL, 1, 'Bonjour dev, c\'est comment', '2026-03-29 15:09:13'),
(2, 3, NULL, 1, 'Oui bonjour , ça va bien', '2026-03-29 15:18:33'),
(3, 3, NULL, 1, 'Et de ton coté ?', '2026-03-29 15:18:43'),
(4, 1, NULL, 1, 'Je me porte bien par la grace de Dieu', '2026-03-29 15:20:12'),
(5, 1, NULL, 1, 'Je suis entrain de developper un nouveau systeme de traitement des données des FAE', '2026-03-29 17:05:50'),
(6, 1, NULL, 1, 'OK', '2026-03-29 17:06:13'),
(7, 1, NULL, 1, 'C\'est bon', '2026-03-29 17:31:16');

-- --------------------------------------------------------

--
-- Structure de la table `reunions`
--

DROP TABLE IF EXISTS `reunions`;
CREATE TABLE IF NOT EXISTS `reunions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `scheduled_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `reunions`
--

TRUNCATE TABLE `reunions`;
--
-- Déchargement des données de la table `reunions`
--

INSERT INTO `reunions` (`id`, `title`, `description`, `scheduled_at`, `created_by`, `created_at`) VALUES
(2, 'Réunion de planification', 'La réunion permet de definir le plan d\'action de la Direction du système d\'information', '2026-03-30 19:13:00', 1, '2026-03-29 17:10:58');

-- --------------------------------------------------------

--
-- Structure de la table `reunions_participants`
--

DROP TABLE IF EXISTS `reunions_participants`;
CREATE TABLE IF NOT EXISTS `reunions_participants` (
  `reunion_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('invited','accepted','declined') DEFAULT 'invited',
  PRIMARY KEY (`reunion_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `reunions_participants`
--

TRUNCATE TABLE `reunions_participants`;
--
-- Déchargement des données de la table `reunions_participants`
--

INSERT INTO `reunions_participants` (`reunion_id`, `user_id`, `status`) VALUES
(2, 1, 'invited'),
(2, 2, 'invited'),
(2, 3, 'invited');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `users_id` int(11) NOT NULL AUTO_INCREMENT,
  `users_username` varchar(255) NOT NULL,
  `users_password` varchar(255) NOT NULL,
  `users_nom` varchar(255) NOT NULL,
  `users_prenom` varchar(255) NOT NULL,
  `users_email` varchar(255) NOT NULL,
  `users_role` varchar(255) DEFAULT NULL,
  `etat_compte` int(4) NOT NULL,
  `etat_online` int(1) NOT NULL,
  `photo_profil` varchar(230) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`users_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Tronquer la table avant d'insérer `users`
--

TRUNCATE TABLE `users`;
--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`users_id`, `users_username`, `users_password`, `users_nom`, `users_prenom`, `users_email`, `users_role`, `etat_compte`, `etat_online`, `photo_profil`, `create_at`) VALUES
(1, 'Admin', '21232f297a57a5a743894a0e4a801fc3', 'DJIMTOLOUMA', 'Anicet', 'webmasterdjim@gmail.com', 'Developpeur', 1, 0, 'user.jpg', '2016-11-11 07:54:38'),
(2, 'Anniel', 'ab4f63f9ac65152575886860dde480a1', 'TOAPORO', 'Anniel', 'anniel@gmail.com', 'PDG ENTREPRISE ACM', 1, 0, '2_070620241046.png', '2024-06-03 21:08:50'),
(3, 'Gomer', 'e10adc3949ba59abbe56e057f20f883e', 'DOKOSSI', 'Gomer', 'gomer@gmail.com', 'Administrateur BDD', 1, 0, '3_060620240850.png', '2024-06-05 13:28:34'),
(4, 'Fred', 'e7247759c1633c0f9f1485f3690294a9', 'DECKORO', 'Fred', 'freddeckoro@gmail.com', 'Administrateur Réseaux & Systèmes', 1, 1, 'user.jpg', '2026-03-29 15:29:42');

-- --------------------------------------------------------

--
-- Structure de la table `users_group`
--

DROP TABLE IF EXISTS `users_group`;
CREATE TABLE IF NOT EXISTS `users_group` (
  `users_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`users_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Tronquer la table avant d'insérer `users_group`
--

TRUNCATE TABLE `users_group`;
--
-- Déchargement des données de la table `users_group`
--

INSERT INTO `users_group` (`users_group_id`, `users_id`, `group_id`) VALUES
(1, 1, 2),
(3, 2, 1),
(4, 3, 1),
(5, 4, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
