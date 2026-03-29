<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('chat_model');
        // Vérification de connexion
        if (!$this->session->userdata('users')) {
            redirect('users/login');
        }
    }

    public function index($type = 'private', $partner_or_group_id = null) {
        $data['title'] = 'Messagerie & Chat';
        $session = $this->session->userdata('users');
        $current_user_id = $session->users_id;

        // Données communes : Listes pour la barre latérale
        $data['users'] = $this->chat_model->get_users($current_user_id);
        $data['groups'] = $this->chat_model->get_user_groups($current_user_id);

        $data['active_type'] = $type; // 'private' ou 'group'
        $data['active_id'] = $partner_or_group_id;

        if ($type == 'private' && $partner_or_group_id) {
            $data['messages'] = $this->chat_model->get_private_messages($current_user_id, $partner_or_group_id);
            $this->db->where('users_id', $partner_or_group_id);
            $data['active_partner'] = $this->db->get('users')->row();
            $data['active_group'] = null;
        } elseif ($type == 'group' && $partner_or_group_id) {
            $data['messages'] = $this->chat_model->get_group_messages($partner_or_group_id);
            $data['active_group'] = $this->chat_model->get_group_info($partner_or_group_id);
            $data['active_partner'] = null;
            // Utilisateurs qui ne sont pas dans ce groupe (pour l'ajout)
            $data['users_not_in_group'] = $this->chat_model->get_users_not_in_group($partner_or_group_id);
        } else {
            $data['messages'] = array();
            $data['active_partner'] = null;
            $data['active_group'] = null;
        }

        $this->load->view('header');
        $this->load->view('index', $data);
        $this->load->view('footer');
    }

    public function send_message() {
        $session = $this->session->userdata('users');
        $sender_id = $session->users_id;
        
        $type = $this->input->post('type'); // 'private' ou 'group'
        $target_id = $this->input->post('target_id');
        $content = $this->input->post('content');

        if ($target_id && !empty($content)) {
            if ($type == 'private') {
                $data = array(
                    'sender_id' => $sender_id,
                    'receiver_id' => $target_id,
                    'content' => $content
                );
                $this->chat_model->send_private_message($data);
                redirect('chat/index/private/' . $target_id);
            } elseif ($type == 'group') {
                $data = array(
                    'sender_id' => $sender_id,
                    'group_id' => $target_id,
                    'content' => $content
                );
                $this->chat_model->send_group_message($data);
                redirect('chat/index/group/' . $target_id);
            }
        }
        redirect('chat');
    }

    // Créer un groupe public (WhatsApp style)
    public function create_group() {
        $session = $this->session->userdata('users');
        $creator_id = $session->users_id;
        
        $group_name = $this->input->post('group_name');
        $members = $this->input->post('members'); // Array of user IDs

        if (!empty($group_name) && !empty($members)) {
            $group_id = $this->chat_model->create_group($group_name, $creator_id, $members);
            $this->session->set_flashdata('success', 'Nouveau Chat Public créé avec succès !');
            redirect('chat/index/group/' . $group_id);
        } else {
            $this->session->set_flashdata('error', 'Veuillez renseigner un nom et sélectionner des membres.');
            redirect('chat');
        }
    }

    // Ajouter des membres à un groupe public
    public function add_members() {
        $group_id = $this->input->post('group_id');
        $members = $this->input->post('new_members'); // Array of user IDs

        if (!empty($group_id) && !empty($members)) {
            $this->chat_model->add_group_members($group_id, $members);
            $this->session->set_flashdata('success', 'Membres ajoutés avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Veuillez sélectionner au moins un membre à ajouter.');
        }
        redirect('chat/index/group/' . $group_id);
    }
}
