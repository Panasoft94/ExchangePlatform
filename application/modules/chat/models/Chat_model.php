<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // =========== CHAT PRIVÉ ===========
    public function get_users($current_user_id) {
        $this->db->where('users_id !=', $current_user_id);
        $this->db->order_by('users_nom', 'ASC');
        return $this->db->get('users')->result();
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
        return $this->db->get('messages')->result();
    }

    public function send_private_message($data) {
        return $this->db->insert('messages', $data);
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
        return $this->db->get()->result();
    }

    // Récupérer les infos d'un groupe précis
    public function get_group_info($group_id) {
        $this->db->where('id', $group_id);
        return $this->db->get('chat_groups')->row();
    }

    // Récupérer les messages d'un groupe spécifique
    public function get_group_messages($group_id) {
        $this->db->select('m.*, u.users_nom, u.users_prenom');
        $this->db->from('messages m');
        $this->db->join('users u', 'm.sender_id = u.users_id');
        $this->db->where('m.group_id', $group_id);
        $this->db->order_by('m.created_at', 'ASC');
        return $this->db->get()->result();
    }

    // Envoyer un message dans un groupe
    public function send_group_message($data) {
        return $this->db->insert('messages', $data);
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
}
