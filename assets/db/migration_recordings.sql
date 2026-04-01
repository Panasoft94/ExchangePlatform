-- =====================================================
-- Migration: Enregistrements vidéo des réunions
-- Exécuter ce script sur la base db_chat
-- =====================================================

CREATE TABLE IF NOT EXISTS `reunions_recordings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `reunion_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL COMMENT 'Utilisateur qui a lancé l''enregistrement',
  `filename` VARCHAR(255) NOT NULL COMMENT 'Nom du fichier sur le serveur',
  `original_name` VARCHAR(255) NOT NULL COMMENT 'Nom original affiché',
  `file_size` BIGINT UNSIGNED DEFAULT 0 COMMENT 'Taille en octets',
  `duration` INT UNSIGNED DEFAULT 0 COMMENT 'Durée en secondes',
  `mime_type` VARCHAR(100) DEFAULT 'video/webm',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_reunion_id` (`reunion_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
