<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->ensure_read_state_table();
        $this->ensure_attachment_table();
    }

    protected function ensure_read_state_table() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `chat_read_states` (
            `user_id` int(11) NOT NULL,
            `chat_type` varchar(20) NOT NULL,
            `target_id` int(11) NOT NULL,
            `last_read_message_id` int(11) NOT NULL DEFAULT 0,
            `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`user_id`, `chat_type`, `target_id`),
            KEY `idx_chat_type_target` (`chat_type`, `target_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    protected function ensure_attachment_table() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `chat_message_attachments` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `message_id` int(11) NOT NULL,
            `original_name` varchar(255) NOT NULL,
            `stored_name` varchar(255) NOT NULL,
            `filepath` varchar(255) NOT NULL,
            `mime_type` varchar(100) DEFAULT NULL,
            `file_ext` varchar(20) DEFAULT NULL,
            `file_size` int(11) NOT NULL DEFAULT 0,
            `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_message_id` (`message_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    // =========== CHAT PRIVÉ ===========
    public function get_users($current_user_id) {
        $this->db->where('users_id !=', $current_user_id);
        $this->db->order_by('users_nom', 'ASC');
        $users = $this->db->get('users')->result();

        return $this->attach_unread_counts($users, $this->get_private_unread_counts($current_user_id, $users), 'users_id');
    }

    public function get_private_messages($user1_id, $user2_id) {
        $this->db->group_start();
            $this->db->where('sender_id', $user1_id);
            $this->db->where('receiver_id', $user2_id);
            $this->db->where('group_id IS NULL');
        $this->db->group_end();
        $this->db->or_group_start();
            $this->db->where('sender_id', $user2_id);
            $this->db->where('receiver_id', $user1_id);
            $this->db->where('group_id IS NULL');
        $this->db->group_end();
        $this->db->order_by('created_at', 'ASC');
        $messages = $this->db->get('messages')->result();

        return $this->attach_message_attachments($messages);
    }

    public function send_private_message($data, $attachments = array()) {
        return $this->create_message($data, $attachments);
    }

    // =========== CHAT PUBLIC (GROUPES / WHATSAPP) ===========
    
    // Créer un nouveau groupe (Chat public)
    public function create_group($name, $creator_id, $members_ids) {
        $this->db->trans_start();

        // 1. Créer le groupe
        $group_data = array('name' => $name, 'created_by' => $creator_id);
        $this->db->insert('chat_groups', $group_data);
        $group_id = $this->db->insert_id();

        // 2. Ajouter les membres
        $members = array();
        // Le créateur fait aussi partie du groupe
        $members[] = array('group_id' => $group_id, 'user_id' => $creator_id);
        
        foreach($members_ids as $user_id) {
            if($user_id != $creator_id) {
                $members[] = array('group_id' => $group_id, 'user_id' => $user_id);
            }
        }
        
        if(!empty($members)) {
            $this->db->insert_batch('chat_group_members', $members);
        }

        $this->db->trans_complete();
        return $group_id;
    }

    // Récupérer les groupes auxquels l'utilisateur appartient
    public function get_user_groups($user_id) {
        $this->db->select('g.id, g.name, g.created_at');
        $this->db->from('chat_groups g');
        $this->db->join('chat_group_members gm', 'g.id = gm.group_id');
        $this->db->where('gm.user_id', $user_id);
        $this->db->order_by('g.name', 'ASC');
        $groups = $this->db->get()->result();

        return $this->attach_unread_counts($groups, $this->get_group_unread_counts($user_id, $groups), 'id');
    }

    // Récupérer les infos d'un groupe précis
    public function get_group_info($group_id) {
        $this->db->where('id', $group_id);
        return $this->db->get('chat_groups')->row();
    }

    public function get_group_members($group_id) {
        $this->db->select('u.users_id, u.users_nom, u.users_prenom, u.users_username, u.users_email, u.etat_online, u.photo_profil');
        $this->db->from('chat_group_members gm');
        $this->db->join('users u', 'u.users_id = gm.user_id');
        $this->db->where('gm.group_id', $group_id);
        $this->db->order_by('u.users_nom', 'ASC');
        $this->db->order_by('u.users_prenom', 'ASC');

        return $this->db->get()->result();
    }

    // Récupérer les messages d'un groupe spécifique
    public function get_group_messages($group_id) {
        $this->db->select('m.*, u.users_nom, u.users_prenom');
        $this->db->from('messages m');
        $this->db->join('users u', 'm.sender_id = u.users_id');
        $this->db->where('m.group_id', $group_id);
        $this->db->order_by('m.created_at', 'ASC');
        $messages = $this->db->get()->result();

        return $this->attach_message_attachments($messages);
    }

    // Envoyer un message dans un groupe
    public function send_group_message($data, $attachments = array()) {
        return $this->create_message($data, $attachments);
    }

    public function get_attachment($attachment_id) {
        $this->db->select('a.*, m.sender_id, m.receiver_id, m.group_id');
        $this->db->from('chat_message_attachments a');
        $this->db->join('messages m', 'm.id = a.message_id');
        $this->db->where('a.id', (int) $attachment_id);

        return $this->db->get()->row();
    }

    public function user_has_attachment_access($user_id, $attachment) {
        if (!$attachment) {
            return FALSE;
        }

        $user_id = (int) $user_id;

        if (!empty($attachment->group_id)) {
            return $this->is_user_in_group($user_id, (int) $attachment->group_id);
        }

        return ((int) $attachment->sender_id === $user_id || (int) $attachment->receiver_id === $user_id);
    }

    public function is_user_in_group($user_id, $group_id) {
        $this->db->where('group_id', (int) $group_id);
        $this->db->where('user_id', (int) $user_id);

        return $this->db->count_all_results('chat_group_members') > 0;
    }

    // Récupérer les utilisateurs qui ne sont PAS dans le groupe spécifié
    public function get_users_not_in_group($group_id) {
        // Sous-requête pour obtenir les IDs des membres actuels
        $this->db->select('user_id');
        $this->db->from('chat_group_members');
        $this->db->where('group_id', $group_id);
        $subquery = $this->db->get_compiled_select();

        // Requête principale : récupérer les utilisateurs dont l'ID n'est pas dans la sous-requête
        $this->db->where("users_id NOT IN ($subquery)", NULL, FALSE);
        $this->db->order_by('users_nom', 'ASC');
        return $this->db->get('users')->result();
    }

    // Ajouter de nouveaux membres à un groupe existant
    public function add_group_members($group_id, $members_ids) {
        $members = array();
        foreach($members_ids as $user_id) {
            $members[] = array('group_id' => $group_id, 'user_id' => $user_id);
        }
        
        if(!empty($members)) {
            $this->db->insert_batch('chat_group_members', $members);
            return true;
        }
        return false;
    }

    public function mark_thread_as_read($user_id, $chat_type, $target_id) {
        $user_id = (int) $user_id;
        $target_id = (int) $target_id;
        $normalized_type = ($chat_type === 'group') ? 'group' : 'private';
        $last_message_id = $this->get_last_thread_message_id($user_id, $normalized_type, $target_id);

        $sql = "INSERT INTO `chat_read_states` (`user_id`, `chat_type`, `target_id`, `last_read_message_id`, `updated_at`)
                VALUES (?, ?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE `last_read_message_id` = VALUES(`last_read_message_id`), `updated_at` = NOW()";

        return $this->db->query($sql, array($user_id, $normalized_type, $target_id, $last_message_id));
    }

    public function get_unread_summary($user_id) {
        $user_id = (int) $user_id;
        $private_total = $this->get_private_unread_total($user_id);
        $group_total = $this->get_group_unread_total($user_id);

        return array(
            'private_total' => $private_total,
            'group_total' => $group_total,
            'total' => $private_total + $group_total
        );
    }

    protected function get_last_thread_message_id($user_id, $chat_type, $target_id) {
        if ($chat_type === 'group') {
            $this->db->select_max('id', 'last_message_id');
            $this->db->where('group_id', $target_id);
            $row = $this->db->get('messages')->row();

            return $row && $row->last_message_id ? (int) $row->last_message_id : 0;
        }

        $this->db->select_max('id', 'last_message_id');
        $this->db->from('messages');
        $this->db->group_start();
            $this->db->where('sender_id', $user_id);
            $this->db->where('receiver_id', $target_id);
            $this->db->where('group_id IS NULL');
        $this->db->group_end();
        $this->db->or_group_start();
            $this->db->where('sender_id', $target_id);
            $this->db->where('receiver_id', $user_id);
            $this->db->where('group_id IS NULL');
        $this->db->group_end();
        $row = $this->db->get()->row();

        return $row && $row->last_message_id ? (int) $row->last_message_id : 0;
    }

    protected function get_private_unread_counts($current_user_id, $users) {
        $user_ids = $this->extract_ids($users, 'users_id');
        if (empty($user_ids)) {
            return array();
        }

        $sql = "SELECT m.sender_id AS target_id, COUNT(*) AS unread_count
                FROM messages m
                LEFT JOIN chat_read_states s
                    ON s.user_id = ?
                   AND s.chat_type = 'private'
                   AND s.target_id = m.sender_id
                WHERE m.receiver_id = ?
                  AND m.group_id IS NULL
                  AND m.sender_id IN (" . implode(',', $user_ids) . ")
                  AND m.id > IFNULL(s.last_read_message_id, 0)
                GROUP BY m.sender_id";

        return $this->map_unread_counts($this->db->query($sql, array($current_user_id, $current_user_id))->result());
    }

    protected function get_private_unread_total($current_user_id) {
        $sql = "SELECT COUNT(*) AS unread_total
                FROM messages m
                LEFT JOIN chat_read_states s
                    ON s.user_id = ?
                   AND s.chat_type = 'private'
                   AND s.target_id = m.sender_id
                WHERE m.receiver_id = ?
                  AND m.group_id IS NULL
                  AND m.id > IFNULL(s.last_read_message_id, 0)";

        $row = $this->db->query($sql, array($current_user_id, $current_user_id))->row();

        return $row ? (int) $row->unread_total : 0;
    }

    protected function get_group_unread_counts($current_user_id, $groups) {
        $group_ids = $this->extract_ids($groups, 'id');
        if (empty($group_ids)) {
            return array();
        }

        $sql = "SELECT m.group_id AS target_id, COUNT(*) AS unread_count
                FROM messages m
                LEFT JOIN chat_read_states s
                    ON s.user_id = ?
                   AND s.chat_type = 'group'
                   AND s.target_id = m.group_id
                WHERE m.group_id IN (" . implode(',', $group_ids) . ")
                  AND m.sender_id != ?
                  AND m.id > IFNULL(s.last_read_message_id, 0)
                GROUP BY m.group_id";

        return $this->map_unread_counts($this->db->query($sql, array($current_user_id, $current_user_id))->result());
    }

    protected function get_group_unread_total($current_user_id) {
        $sql = "SELECT COUNT(*) AS unread_total
                FROM messages m
                INNER JOIN chat_group_members gm
                    ON gm.group_id = m.group_id
                   AND gm.user_id = ?
                LEFT JOIN chat_read_states s
                    ON s.user_id = ?
                   AND s.chat_type = 'group'
                   AND s.target_id = m.group_id
                WHERE m.sender_id != ?
                  AND m.group_id IS NOT NULL
                  AND m.id > IFNULL(s.last_read_message_id, 0)";

        $row = $this->db->query($sql, array($current_user_id, $current_user_id, $current_user_id))->row();

        return $row ? (int) $row->unread_total : 0;
    }

    protected function extract_ids($items, $field_name) {
        $ids = array();

        foreach ($items as $item) {
            if (isset($item->{$field_name})) {
                $ids[] = (int) $item->{$field_name};
            }
        }

        return array_values(array_unique(array_filter($ids)));
    }

    protected function map_unread_counts($rows) {
        $counts = array();

        foreach ($rows as $row) {
            $counts[(int) $row->target_id] = (int) $row->unread_count;
        }

        return $counts;
    }

    protected function attach_unread_counts($items, $counts, $field_name) {
        foreach ($items as $item) {
            $target_id = isset($item->{$field_name}) ? (int) $item->{$field_name} : 0;
            $item->unread_count = isset($counts[$target_id]) ? (int) $counts[$target_id] : 0;
        }

        return $items;
    }

    protected function create_message($data, $attachments) {
        $this->db->trans_start();
        $this->db->insert('messages', $data);
        $message_id = $this->db->insert_id();

        if ($message_id && !empty($attachments)) {
            $rows = array();
            foreach ($attachments as $attachment) {
                $rows[] = array(
                    'message_id' => $message_id,
                    'original_name' => $attachment['original_name'],
                    'stored_name' => $attachment['stored_name'],
                    'filepath' => $attachment['filepath'],
                    'mime_type' => isset($attachment['mime_type']) ? $attachment['mime_type'] : NULL,
                    'file_ext' => isset($attachment['file_ext']) ? $attachment['file_ext'] : NULL,
                    'file_size' => isset($attachment['file_size']) ? (int) $attachment['file_size'] : 0
                );
            }

            if (!empty($rows)) {
                $this->db->insert_batch('chat_message_attachments', $rows);
            }
        }

        $this->db->trans_complete();

        return $message_id;
    }

    protected function attach_message_attachments($messages) {
        $message_ids = $this->extract_ids($messages, 'id');
        $attachments_by_message = $this->get_attachments_by_message_ids($message_ids);

        foreach ($messages as $message) {
            $message_id = isset($message->id) ? (int) $message->id : 0;
            $message->attachments = isset($attachments_by_message[$message_id]) ? $attachments_by_message[$message_id] : array();
        }

        return $messages;
    }

    protected function get_attachments_by_message_ids($message_ids) {
        if (empty($message_ids)) {
            return array();
        }

        $this->db->from('chat_message_attachments');
        $this->db->where_in('message_id', $message_ids);
        $this->db->order_by('id', 'ASC');
        $rows = $this->db->get()->result();
        $grouped = array();

        foreach ($rows as $row) {
            $message_id = (int) $row->message_id;
            if (!isset($grouped[$message_id])) {
                $grouped[$message_id] = array();
            }
            $grouped[$message_id][] = $row;
        }

        return $grouped;
    }
}
