-- =====================================================
-- Migration: Reunions Module V2 — Plateforme complète
-- Exécuter ce script sur la base db_chat
-- =====================================================

-- 1. Ajouter les colonnes manquantes à la table reunions
ALTER TABLE `reunions`
  ADD COLUMN `location` VARCHAR(255) DEFAULT NULL AFTER `description`,
  ADD COLUMN `duration` INT DEFAULT NULL COMMENT 'Durée en minutes' AFTER `location`,
  ADD COLUMN `status` ENUM('planned','in_progress','completed','cancelled') DEFAULT 'planned' AFTER `duration`,
  ADD COLUMN `priority` ENUM('low','normal','high','urgent') DEFAULT 'normal' AFTER `status`;

-- 2. Table Ordre du jour (Agenda)
CREATE TABLE IF NOT EXISTS `reunions_agenda` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `reunion_id` INT(11) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `sort_order` INT(11) DEFAULT 0,
  `completed` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_reunion_id` (`reunion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Table Notes / Procès-verbal
CREATE TABLE IF NOT EXISTS `reunions_notes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `reunion_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_reunion_id` (`reunion_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
