-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 31 mars 2026 à 16:58
-- Version du serveur :  5.7.26
-- Version de PHP :  5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


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
(1, 'IT-Dévéloppement', 1, '2026-03-29 13:08:47'),
(2, 'Core Senior', 1, '2026-03-29 13:10:08');

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
(1, 1, '2026-03-29 13:08:47'),
(1, 2, '2026-03-29 14:41:44'),
(1, 3, '2026-03-29 13:08:47'),
(2, 1, '2026-03-29 13:10:08'),
(2, 2, '2026-03-29 13:10:08'),
(2, 4, '2026-03-31 08:26:04');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `chat_message_attachments`
--

TRUNCATE TABLE `chat_message_attachments`;
--
-- Déchargement des données de la table `chat_message_attachments`
--

INSERT INTO `chat_message_attachments` (`id`, `message_id`, `original_name`, `stored_name`, `filepath`, `mime_type`, `file_ext`, `file_size`, `uploaded_at`) VALUES
(1, 17, 'Temple_Facture_PANASOFT.docx', '11c207e1f52d1f0ffe6be48811292400.docx', 'assets/uploads/chat/11c207e1f52d1f0ffe6be48811292400.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '.docx', 76687, '2026-03-30 12:10:59'),
(2, 19, 'Temple_Facture_PANASOFT.docx', 'ddf37399c730c99dc77954ef72d93622.docx', 'assets/uploads/chat/ddf37399c730c99dc77954ef72d93622.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '.docx', 76687, '2026-03-30 12:15:19'),
(3, 26, 'Temple_Facture_PANASOFT.docx', '79ff480e2bd7ee6916583c427992d1e5.docx', 'assets/uploads/chat/79ff480e2bd7ee6916583c427992d1e5.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '.docx', 76687, '2026-03-31 08:27:10'),
(4, 38, 'IMG_20250325_140518.jpg', '69c9467a4f9838becd19df163d969986.jpg', 'assets/uploads/chat/69c9467a4f9838becd19df163d969986.jpg', 'image/jpeg', '.jpg', 3030118, '2026-03-31 13:21:15');

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
  PRIMARY KEY (`user_id`,`chat_type`,`target_id`),
  KEY `idx_chat_type_target` (`chat_type`,`target_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `chat_read_states`
--

TRUNCATE TABLE `chat_read_states`;
--
-- Déchargement des données de la table `chat_read_states`
--

INSERT INTO `chat_read_states` (`user_id`, `chat_type`, `target_id`, `last_read_message_id`, `updated_at`) VALUES
(1, 'group', 1, 42, '2026-03-31 14:08:15'),
(1, 'group', 2, 26, '2026-03-31 12:06:18'),
(1, 'private', 3, 23, '2026-03-31 08:25:49'),
(3, 'group', 1, 42, '2026-03-31 13:38:27'),
(3, 'private', 1, 23, '2026-03-31 08:26:22'),
(4, 'group', 2, 26, '2026-03-31 10:08:13'),
(4, 'private', 2, 0, '2026-03-31 10:08:10'),
(4, 'private', 3, 0, '2026-03-31 10:08:08');

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `description` text,
  `visibility` enum('public','private') NOT NULL DEFAULT 'public',
  `category_id` int(11) DEFAULT NULL,
  `file_size` bigint(20) NOT NULL DEFAULT '0',
  `file_type` varchar(50) DEFAULT NULL,
  `download_count` int(11) NOT NULL DEFAULT '0',
  `uploaded_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uploaded_by` (`uploaded_by`),
  KEY `idx_doc_visibility` (`visibility`),
  KEY `idx_doc_category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `documents`
--

TRUNCATE TABLE `documents`;
--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`id`, `filename`, `filepath`, `description`, `visibility`, `category_id`, `file_size`, `file_type`, `download_count`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(1, 'Temple_Facture_PANASOFT.docx', 'assets/uploads/documents/e189d57062943ba9ff59fe984a1364f9.docx', 'Ceci est un test lors du developpement', 'private', 4, 75776, 'docx', 0, 1, '2026-03-31 10:53:57', '2026-03-31 10:59:09');

-- --------------------------------------------------------

--
-- Structure de la table `document_categories`
--

DROP TABLE IF EXISTS `document_categories`;
CREATE TABLE IF NOT EXISTS `document_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `color` varchar(20) NOT NULL DEFAULT '#1a73e8',
  `icon` varchar(50) NOT NULL DEFAULT 'fas fa-folder',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `document_categories`
--

TRUNCATE TABLE `document_categories`;
--
-- Déchargement des données de la table `document_categories`
--

INSERT INTO `document_categories` (`id`, `name`, `color`, `icon`, `created_by`, `created_at`) VALUES
(1, 'Général', '#1a73e8', 'fas fa-folder', 1, '2026-03-31 10:37:07'),
(2, 'Rapports', '#34a853', 'fas fa-chart-bar', 1, '2026-03-31 10:37:07'),
(3, 'Présentations', '#fa7b17', 'fas fa-presentation-screen', 1, '2026-03-31 10:37:07'),
(4, 'Procès-verbaux', '#a142f4', 'fas fa-gavel', 1, '2026-03-31 10:37:07'),
(5, 'Formulaires', '#ea4335', 'fas fa-file-lines', 1, '2026-03-31 10:37:07'),
(6, 'Images & Médias', '#f9ab00', 'fas fa-images', 1, '2026-03-31 10:37:07'),
(7, 'Rapports mensuel', '#2b5a97', 'fas fa-folder', 3, '2026-03-31 10:52:56');

-- --------------------------------------------------------

--
-- Structure de la table `document_shares`
--

DROP TABLE IF EXISTS `document_shares`;
CREATE TABLE IF NOT EXISTS `document_shares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL,
  `shared_with` int(11) NOT NULL,
  `shared_by` int(11) NOT NULL,
  `can_download` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_doc_user` (`document_id`,`shared_with`),
  KEY `idx_doc_share_doc` (`document_id`),
  KEY `idx_doc_share_user` (`shared_with`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `document_shares`
--

TRUNCATE TABLE `document_shares`;
--
-- Déchargement des données de la table `document_shares`
--

INSERT INTO `document_shares` (`id`, `document_id`, `shared_with`, `shared_by`, `can_download`, `created_at`) VALUES
(1, 1, 3, 1, 1, '2026-03-31 10:59:22');

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
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8;

--
-- Tronquer la table avant d'insérer `history`
--

TRUNCATE TABLE `history`;
--
-- Déchargement des données de la table `history`
--

INSERT INTO `history` (`history_id`, `history_date`, `history_action`, `history_users`) VALUES
(23, '2023-07-22 17:57:43', 'Connexion de l\'utilisateur admin admin', 'admin admin'),
(24, '2023-07-22 17:59:57', 'Déconnexion de l\'utilisateur  admin admin', 'admin admin'),
(25, '2023-07-22 18:13:24', 'Connexion de l\'utilisateur admin admin', 'admin admin'),
(26, '2023-07-22 18:13:54', 'Modification de compte de l\'utilisateur ADMIN Admin', 'ADMIN Admin'),
(27, '2024-06-21 08:26:55', 'Connexion de l\'utilisateur ADMIN Admin', 'ADMIN Admin'),
(28, '2024-06-21 08:41:14', 'Déconnexion de l\'utilisateur  ADMIN Admin', 'ADMIN Admin'),
(29, '2024-06-21 08:42:05', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(30, '2024-06-21 08:44:20', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(31, '2024-06-21 08:49:07', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(32, '2024-06-21 08:49:24', 'Modification du groupe  Administrateur', 'DJIMTOLOUMA Anicet'),
(33, '2024-06-21 08:51:05', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(34, '2024-06-21 08:51:09', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(35, '2024-06-21 08:53:08', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(36, '2024-06-21 08:53:12', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(37, '2024-06-21 08:56:24', 'Modification de compte de l\'utilisateur DOKOSSI Gomer', 'DJIMTOLOUMA Anicet'),
(38, '2024-06-21 08:56:30', 'Modification de compte de l\'utilisateur TOAPORO Anniel', 'DJIMTOLOUMA Anicet'),
(39, '2026-03-29 12:23:50', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(40, '2026-03-29 13:17:19', 'Modification de compte de l\'utilisateur DOKOSSI Gomer', 'DJIMTOLOUMA Anicet'),
(41, '2026-03-29 13:17:58', 'Connexion de l\'utilisateur DOKOSSI Gomer', 'DOKOSSI Gomer'),
(42, '2026-03-29 13:22:24', 'Déconnexion de l\'utilisateur  DOKOSSI Gomer', 'DOKOSSI Gomer'),
(43, '2026-03-29 13:24:00', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(44, '2026-03-29 13:25:55', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(45, '2026-03-29 13:29:42', 'Création de l\'utilisateur DECKORO Fred', 'DJIMTOLOUMA Anicet'),
(46, '2026-03-29 13:53:43', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(47, '2026-03-29 14:55:35', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(48, '2026-03-29 14:55:45', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(49, '2026-03-29 15:10:58', 'Création de la réunion : Réunion de planification', 'DJIMTOLOUMA Anicet'),
(50, '2026-03-29 15:11:15', 'Suppression de la réunion : Réunion de planification', 'DJIMTOLOUMA Anicet'),
(51, '2026-03-29 15:37:12', 'Connexion de l\'utilisateur DECKORO Fred', 'DECKORO Fred'),
(52, '2026-03-29 15:40:17', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(53, '2026-03-29 15:45:23', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(54, '2026-03-29 15:55:43', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(55, '2026-03-29 15:56:05', 'Déconnexion de l\'utilisateur  DECKORO Fred', 'DECKORO Fred'),
(56, '2026-03-29 15:56:11', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(57, '2026-03-30 11:20:58', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(58, '2026-03-30 11:24:27', 'Connexion de l\'utilisateur DOKOSSI Gomer', 'DOKOSSI Gomer'),
(59, '2026-03-30 14:29:05', 'Déconnexion de l\'utilisateur  DOKOSSI Gomer', 'DOKOSSI Gomer'),
(60, '2026-03-30 14:29:15', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(61, '2026-03-31 07:33:23', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(62, '2026-03-31 08:01:56', 'Connexion de l\'utilisateur DECKORO Fred', 'DECKORO Fred'),
(63, '2026-03-31 08:02:40', 'Déconnexion de l\'utilisateur  DECKORO Fred', 'DECKORO Fred'),
(64, '2026-03-31 08:02:48', 'Connexion de l\'utilisateur DOKOSSI Gomer', 'DOKOSSI Gomer'),
(65, '2026-03-31 08:26:24', 'Déconnexion de l\'utilisateur  DOKOSSI Gomer', 'DOKOSSI Gomer'),
(66, '2026-03-31 08:26:31', 'Connexion de l\'utilisateur DECKORO Fred', 'DECKORO Fred'),
(67, '2026-03-31 08:28:23', 'Ajout de participants à la réunion : Réunion de planification', 'DJIMTOLOUMA Anicet'),
(68, '2026-03-31 08:52:29', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(69, '2026-03-31 08:53:01', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(70, '2026-03-31 10:19:36', 'Changement de statut de la réunion \"Réunion de planification\" → En cours', 'DJIMTOLOUMA Anicet'),
(71, '2026-03-31 10:44:19', 'Connexion de l\'utilisateur DOKOSSI Gomer', 'DOKOSSI Gomer'),
(72, '2026-03-31 10:53:57', 'Partage du document : Temple_Facture_PANASOFT.docx (public)', 'DJIMTOLOUMA Anicet'),
(73, '2026-03-31 10:59:09', 'Modification du document : Temple_Facture_PANASOFT.docx', 'DJIMTOLOUMA Anicet'),
(74, '2026-03-31 12:05:55', 'Modification de compte de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(75, '2026-03-31 12:18:54', 'Déconnexion de l\'utilisateur  DECKORO Fred', 'DECKORO Fred'),
(76, '2026-03-31 12:31:48', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(77, '2026-03-31 12:32:09', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(78, '2026-03-31 12:34:27', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(79, '2026-03-31 12:34:50', 'Connexion de l\'utilisateur DOKOSSI Gomer', 'DOKOSSI Gomer'),
(80, '2026-03-31 12:38:13', 'Déconnexion de l\'utilisateur  DOKOSSI Gomer', 'DOKOSSI Gomer'),
(81, '2026-03-31 12:38:21', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(82, '2026-03-31 12:48:05', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(83, '2026-03-31 12:48:12', 'Connexion de l\'utilisateur DOKOSSI Gomer', 'DOKOSSI Gomer'),
(84, '2026-03-31 13:26:53', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(85, '2026-03-31 13:36:38', 'Connexion de l\'utilisateur DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(86, '2026-03-31 14:08:19', 'Déconnexion de l\'utilisateur  DJIMTOLOUMA Anicet', 'DJIMTOLOUMA Anicet'),
(87, '2026-03-31 14:08:33', 'Déconnexion de l\'utilisateur  DOKOSSI Gomer', 'DOKOSSI Gomer');

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
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `messages`
--

TRUNCATE TABLE `messages`;
--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `group_id`, `content`, `created_at`) VALUES
(1, 1, NULL, 1, 'Bonjour dev, c\'est comment', '2026-03-29 13:09:13'),
(2, 3, NULL, 1, 'Oui bonjour , ça va bien', '2026-03-29 13:18:33'),
(3, 3, NULL, 1, 'Et de ton coté ?', '2026-03-29 13:18:43'),
(4, 1, NULL, 1, 'Je me porte bien par la grace de Dieu', '2026-03-29 13:20:12'),
(5, 1, NULL, 1, 'Je suis entrain de developper un nouveau systeme de traitement des données des FAE', '2026-03-29 15:05:50'),
(6, 1, NULL, 1, 'OK', '2026-03-29 15:06:13'),
(7, 1, NULL, 1, 'C\'est bon', '2026-03-29 15:31:16'),
(8, 1, NULL, 1, 'OK', '2026-03-29 15:45:37'),
(9, 1, NULL, 1, 'Je vois maintenant', '2026-03-29 15:45:59'),
(10, 1, NULL, 1, 'OK', '2026-03-29 15:46:33'),
(11, 1, NULL, 1, 'ok moi je suis la', '2026-03-29 15:49:13'),
(12, 3, NULL, 1, 'Ok je suis en route', '2026-03-30 11:25:05'),
(13, 1, NULL, 1, 'On doit echanger un peu', '2026-03-30 11:44:13'),
(14, 1, NULL, 1, 'sur le truc', '2026-03-30 11:44:32'),
(15, 1, NULL, 1, 'Ok,va voir le Directeur', '2026-03-30 11:45:08'),
(16, 3, NULL, 1, 'Par rapport à la réunion de planification', '2026-03-30 11:46:29'),
(17, 1, NULL, 1, 'Voici le document', '2026-03-30 12:10:59'),
(18, 1, 3, NULL, 'Bonjour', '2026-03-30 12:14:56'),
(19, 1, 3, NULL, 'Voici le fichier que je t\'ai promis d\'envoyer', '2026-03-30 12:15:19'),
(20, 1, 3, NULL, 'Ok j\'ai reçu ça dev', '2026-03-30 12:30:12'),
(21, 1, 3, NULL, 'Bonjour Dev', '2026-03-31 08:08:20'),
(22, 1, 3, NULL, 'C\'est comment ?', '2026-03-31 08:08:39'),
(23, 3, 1, NULL, 'ok c\'est bien', '2026-03-31 08:25:15'),
(24, 1, NULL, 2, 'Bonjour', '2026-03-31 08:26:12'),
(25, 4, NULL, 2, 'OK', '2026-03-31 08:26:44'),
(26, 1, NULL, 2, 'Voici', '2026-03-31 08:27:10'),
(27, 1, NULL, 1, 'Je suis en phase de test\r\nTu peux venir voir ça', '2026-03-31 12:47:42'),
(28, 1, NULL, 1, 'OK', '2026-03-31 12:48:45'),
(29, 1, NULL, 1, 'OK', '2026-03-31 13:01:09'),
(30, 1, NULL, 1, 'Je vois maintenant', '2026-03-31 13:03:58'),
(31, 3, NULL, 1, 'ok', '2026-03-31 13:04:40'),
(32, 1, NULL, 1, 'Moi Dev', '2026-03-31 13:05:08'),
(33, 1, NULL, 1, 'bonjour', '2026-03-31 13:13:21'),
(34, 3, NULL, 1, 'Tu ne vas pas venir ?', '2026-03-31 13:15:15'),
(35, 3, NULL, 1, 'Si c\'est vrais dit moi dev', '2026-03-31 13:17:19'),
(36, 1, NULL, 1, 'OK', '2026-03-31 13:18:00'),
(37, 1, NULL, 1, 'mais c\'est vrai', '2026-03-31 13:18:53'),
(38, 1, NULL, 1, 'voici ma photo', '2026-03-31 13:21:15'),
(39, 3, NULL, 1, 'Bonjour mon frere', '2026-03-31 13:36:28'),
(40, 1, NULL, 1, 'Oui bonjour', '2026-03-31 13:36:53'),
(41, 3, NULL, 1, 'tu ne vas pas venir aujourdhui ?', '2026-03-31 13:37:31'),
(42, 1, NULL, 1, 'oui', '2026-03-31 13:38:13');

-- --------------------------------------------------------

--
-- Structure de la table `reunions`
--

DROP TABLE IF EXISTS `reunions`;
CREATE TABLE IF NOT EXISTS `reunions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `location` varchar(255) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL COMMENT 'Durée en minutes',
  `status` enum('planned','in_progress','completed','cancelled') DEFAULT 'planned',
  `priority` enum('low','normal','high','urgent') DEFAULT 'normal',
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

INSERT INTO `reunions` (`id`, `title`, `description`, `location`, `duration`, `status`, `priority`, `scheduled_at`, `created_by`, `created_at`) VALUES
(2, 'Réunion de planification', 'La réunion permet de definir le plan d\'action de la Direction du système d\'information', NULL, NULL, 'in_progress', 'normal', '2026-03-30 19:13:00', 1, '2026-03-29 15:10:58');

-- --------------------------------------------------------

--
-- Structure de la table `reunions_agenda`
--

DROP TABLE IF EXISTS `reunions_agenda`;
CREATE TABLE IF NOT EXISTS `reunions_agenda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reunion_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `sort_order` int(11) DEFAULT '0',
  `completed` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_reunion_id` (`reunion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `reunions_agenda`
--

TRUNCATE TABLE `reunions_agenda`;
-- --------------------------------------------------------

--
-- Structure de la table `reunions_notes`
--

DROP TABLE IF EXISTS `reunions_notes`;
CREATE TABLE IF NOT EXISTS `reunions_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reunion_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_reunion_id` (`reunion_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `reunions_notes`
--

TRUNCATE TABLE `reunions_notes`;
--
-- Déchargement des données de la table `reunions_notes`
--

INSERT INTO `reunions_notes` (`id`, `reunion_id`, `user_id`, `content`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Je vois que la bonne methode c\'est de repartie les taches', '2026-03-31 10:41:38', NULL),
(2, 2, 4, 'On peut faire comme Djim à suggerer', '2026-03-31 10:42:46', NULL);

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
(2, 1, 'accepted'),
(2, 2, 'invited'),
(2, 3, 'accepted'),
(2, 4, 'accepted');

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
(1, 'Admin', '21232f297a57a5a743894a0e4a801fc3', 'DJIMTOLOUMA', 'Anicet', 'webmasterdjim@gmail.com', 'Developpeur', 1, 0, '1_310320261305.jpg', '2016-11-11 06:54:38'),
(2, 'Anniel', 'ab4f63f9ac65152575886860dde480a1', 'TOAPORO', 'Anniel', 'anniel@gmail.com', 'PDG ENTREPRISE ACM', 1, 0, '2_070620241046.png', '2024-06-03 19:08:50'),
(3, 'Gomer', 'e10adc3949ba59abbe56e057f20f883e', 'DOKOSSI', 'Gomer', 'gomer@gmail.com', 'Administrateur BDD', 1, 0, '3_060620240850.png', '2024-06-05 11:28:34'),
(4, 'Fred', 'e7247759c1633c0f9f1485f3690294a9', 'DECKORO', 'Fred', 'freddeckoro@gmail.com', 'Administrateur Réseaux & Systèmes', 1, 0, 'user.jpg', '2026-03-29 13:29:42');

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
