-- ============================================================
-- Documents V2 Migration
-- Adds: description, visibility, category, file_size, file_type,
--        download_count, document categories table, document shares table
-- ============================================================

-- Add new columns to documents table
ALTER TABLE `documents`
  ADD COLUMN `description` TEXT NULL AFTER `filepath`,
  ADD COLUMN `visibility` ENUM('public','private') NOT NULL DEFAULT 'public' AFTER `description`,
  ADD COLUMN `category_id` INT(11) NULL AFTER `visibility`,
  ADD COLUMN `file_size` BIGINT(20) NOT NULL DEFAULT 0 AFTER `category_id`,
  ADD COLUMN `file_type` VARCHAR(50) NULL AFTER `file_size`,
  ADD COLUMN `download_count` INT(11) NOT NULL DEFAULT 0 AFTER `file_type`,
  ADD COLUMN `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- Categories table
CREATE TABLE IF NOT EXISTS `document_categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `color` VARCHAR(20) NOT NULL DEFAULT '#1a73e8',
  `icon` VARCHAR(50) NOT NULL DEFAULT 'fas fa-folder',
  `created_by` INT(11) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Shares table: who can access private documents
CREATE TABLE IF NOT EXISTS `document_shares` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `document_id` INT(11) NOT NULL,
  `shared_with` INT(11) NOT NULL,
  `shared_by` INT(11) NOT NULL,
  `can_download` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_doc_share_doc` (`document_id`),
  KEY `idx_doc_share_user` (`shared_with`),
  UNIQUE KEY `uq_doc_user` (`document_id`, `shared_with`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed default categories
INSERT INTO `document_categories` (`name`, `color`, `icon`, `created_by`) VALUES
  ('Général', '#1a73e8', 'fas fa-folder', 1),
  ('Rapports', '#34a853', 'fas fa-chart-bar', 1),
  ('Présentations', '#fa7b17', 'fas fa-presentation-screen', 1),
  ('Procès-verbaux', '#a142f4', 'fas fa-gavel', 1),
  ('Formulaires', '#ea4335', 'fas fa-file-lines', 1),
  ('Images & Médias', '#f9ab00', 'fas fa-images', 1);

-- Add index for visibility and category filtering
ALTER TABLE `documents`
  ADD KEY `idx_doc_visibility` (`visibility`),
  ADD KEY `idx_doc_category` (`category_id`);
