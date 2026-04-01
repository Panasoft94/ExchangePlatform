-- ============================================================
-- Migration ACL : Ajout des nouvelles colonnes de permissions
-- dans la table `group` pour le système d'habilitations étendu
-- ============================================================

-- Administration Générale
ALTER TABLE `group` ADD COLUMN IF NOT EXISTS `super_admin` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `group` ADD COLUMN IF NOT EXISTS `lock_user` TINYINT(1) NOT NULL DEFAULT 0;

-- Réunions
ALTER TABLE `group` ADD COLUMN IF NOT EXISTS `view_reunions` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `group` ADD COLUMN IF NOT EXISTS `create_reunion` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `group` ADD COLUMN IF NOT EXISTS `manage_reunions` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `group` ADD COLUMN IF NOT EXISTS `join_reunion` TINYINT(1) NOT NULL DEFAULT 0;

-- Documents
ALTER TABLE `group` ADD COLUMN IF NOT EXISTS `view_documents` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `group` ADD COLUMN IF NOT EXISTS `upload_document` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `group` ADD COLUMN IF NOT EXISTS `manage_documents` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `group` ADD COLUMN IF NOT EXISTS `manage_categories` TINYINT(1) NOT NULL DEFAULT 0;

-- Messagerie
ALTER TABLE `group` ADD COLUMN IF NOT EXISTS `view_chat` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `group` ADD COLUMN IF NOT EXISTS `create_chat_group` TINYINT(1) NOT NULL DEFAULT 0;

-- Enregistrements
ALTER TABLE `group` ADD COLUMN IF NOT EXISTS `view_recordings` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `group` ADD COLUMN IF NOT EXISTS `record_reunion` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `group` ADD COLUMN IF NOT EXISTS `manage_recordings` TINYINT(1) NOT NULL DEFAULT 0;

-- Visioconférence
ALTER TABLE `group` ADD COLUMN IF NOT EXISTS `manage_visio_config` TINYINT(1) NOT NULL DEFAULT 0;

-- ============================================================
-- Mettre à jour le groupe Administrateur (group_id = 2)
-- pour lui accorder toutes les nouvelles permissions
-- ============================================================
UPDATE `group` SET
    `super_admin` = 1,
    `lock_user` = 1,
    `view_reunions` = 1,
    `create_reunion` = 1,
    `manage_reunions` = 1,
    `join_reunion` = 1,
    `view_documents` = 1,
    `upload_document` = 1,
    `manage_documents` = 1,
    `manage_categories` = 1,
    `view_chat` = 1,
    `create_chat_group` = 1,
    `view_recordings` = 1,
    `record_reunion` = 1,
    `manage_recordings` = 1,
    `manage_visio_config` = 1
WHERE `group_id` = 2;

-- ============================================================
-- Mettre à jour le groupe Default (group_id = 1)
-- pour lui accorder les permissions de base (utilisateur normal)
-- ============================================================
UPDATE `group` SET
    `edit_user` = 1,
    `view_reunions` = 1,
    `create_reunion` = 1,
    `join_reunion` = 1,
    `view_documents` = 1,
    `upload_document` = 1,
    `view_chat` = 1,
    `envoyer_message` = 1,
    `repondre_message` = 1,
    `boite_reception` = 1,
    `view_recordings` = 1,
    `record_reunion` = 1,
    `create_chat_group` = 1
WHERE `group_id` = 1;
