<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('chat_model');
        $this->load->library('upload');
        $this->load->helper(array('chat_view', 'download'));
        // Vérification de connexion
        if (!$this->session->userdata('users')) {
            redirect('users/login');
        }
    }

    public function index($type = 'private', $partner_or_group_id = null) {
        has_access('view_chat');
        $data = $this->build_view_data($type, $partner_or_group_id);
        $data['title'] = 'Messagerie & Chat';

        $this->load->view('header');
        $this->load->view('index', $data);
        $this->load->view('footer');
    }

    public function thread_state($type = 'private', $partner_or_group_id = null) {
        $data = $this->build_view_data($type, $partner_or_group_id);

        $this->render_thread_state($data);
    }

    public function unread_summary() {
        $session = $this->session->userdata('users');
        $summary = $this->chat_model->get_unread_summary($session->users_id);

        $this->json_response(array(
            'success' => TRUE,
            'unread_summary' => $summary
        ));
    }

    public function send_message() {
        has_access('envoyer_message');
        $session = $this->session->userdata('users');
        $sender_id = $session->users_id;
        
        $type = $this->input->post('type'); // 'private' ou 'group'
        $target_id = $this->input->post('target_id');
        $content = $this->input->post('content');
        $trimmed_content = trim($content);
        $attachments = $this->upload_chat_attachments();

        if (isset($attachments['error'])) {
            if ($this->input->is_ajax_request()) {
                $this->json_response(array(
                    'success' => FALSE,
                    'message' => $attachments['error']
                ), 422);
                return;
            }

            $this->session->set_flashdata('error', $attachments['error']);
            if ($target_id) {
                redirect('chat/index/' . (($type === 'group') ? 'group' : 'private') . '/' . (int) $target_id);
                return;
            }
            redirect('chat');
            return;
        }

        if ($target_id && ($trimmed_content !== '' || !empty($attachments))) {
            if ($type == 'private') {
                $data = array(
                    'sender_id' => $sender_id,
                    'receiver_id' => $target_id,
                    'content' => $trimmed_content
                );
                $this->chat_model->send_private_message($data, $attachments);
                if ($this->input->is_ajax_request()) {
                    $this->render_thread_state($this->build_view_data('private', $target_id), array('message_sent' => TRUE));
                    return;
                }
                redirect('chat/index/private/' . $target_id);
            } elseif ($type == 'group') {
                $data = array(
                    'sender_id' => $sender_id,
                    'group_id' => $target_id,
                    'content' => $trimmed_content
                );
                if (!$this->chat_model->is_user_in_group($sender_id, $target_id)) {
                    if ($this->input->is_ajax_request()) {
                        $this->json_response(array('success' => FALSE, 'message' => 'Accès refusé à ce groupe.'), 403);
                        return;
                    }
                    show_error('Accès refusé.', 403);
                    return;
                }
                $this->chat_model->send_group_message($data, $attachments);
                if ($this->input->is_ajax_request()) {
                    $this->render_thread_state($this->build_view_data('group', $target_id), array('message_sent' => TRUE));
                    return;
                }
                redirect('chat/index/group/' . $target_id);
            }
        }

        if ($this->input->is_ajax_request()) {
            $this->json_response(array(
                'success' => FALSE,
                'message' => 'Impossible d\'envoyer un message vide.'
            ), 422);
            return;
        }

        redirect('chat');
    }

    public function download_attachment($attachment_id = NULL) {
        $session = $this->session->userdata('users');

        if (!$attachment_id) {
            show_404();
            return;
        }

        $attachment = $this->chat_model->get_attachment($attachment_id);
        if (!$attachment || !$this->chat_model->user_has_attachment_access($session->users_id, $attachment)) {
            show_error('Accès refusé.', 403);
            return;
        }

        $absolute_path = FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $attachment->filepath);
        if (!file_exists($absolute_path)) {
            show_404();
            return;
        }

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        force_download($attachment->original_name, file_get_contents($absolute_path));
    }

    // Créer un groupe public 
    public function create_group() {
        has_access('create_chat_group');
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

    protected function build_view_data($type = 'private', $partner_or_group_id = null) {
        $session = $this->session->userdata('users');
        $current_user_id = $session->users_id;
        $normalized_type = ($type === 'group') ? 'group' : 'private';
        $normalized_id = $partner_or_group_id ? (int) $partner_or_group_id : null;

        $data = array(
            'users' => array(),
            'groups' => array(),
            'active_type' => $normalized_type,
            'active_id' => $normalized_id,
            'messages' => array(),
            'active_partner' => null,
            'active_group' => null,
            'group_members' => array(),
            'users_not_in_group' => array(),
            'unread_summary' => array('private_total' => 0, 'group_total' => 0, 'total' => 0)
        );

        if ($normalized_type === 'private' && $normalized_id) {
            $data['messages'] = $this->chat_model->get_private_messages($current_user_id, $normalized_id);
            $this->db->where('users_id', $normalized_id);
            $data['active_partner'] = $this->db->get('users')->row();

            if ( ! $data['active_partner']) {
                $data['active_id'] = null;
                $data['messages'] = array();
            } else {
                $this->chat_model->mark_thread_as_read($current_user_id, 'private', $normalized_id);
            }
        } elseif ($normalized_type === 'group' && $normalized_id) {
            $data['active_group'] = $this->chat_model->get_group_info($normalized_id);

            if ($data['active_group']) {
                $data['messages'] = $this->chat_model->get_group_messages($normalized_id);
                $data['group_members'] = $this->chat_model->get_group_members($normalized_id);
                $data['users_not_in_group'] = $this->chat_model->get_users_not_in_group($normalized_id);
                $this->chat_model->mark_thread_as_read($current_user_id, 'group', $normalized_id);
            } else {
                $data['active_id'] = null;
            }
        } else {
            $data['active_id'] = null;
        }

        $data['users'] = $this->chat_model->get_users($current_user_id);
        $data['groups'] = $this->chat_model->get_user_groups($current_user_id);
        $data['unread_summary'] = $this->chat_model->get_unread_summary($current_user_id);

        return $data;
    }

    protected function render_thread_state($data, $extra = array()) {
        $active_url = site_url('chat');

        if ($data['active_id']) {
            $active_url = site_url('chat/index/' . $data['active_type'] . '/' . $data['active_id']);
        }

        $payload = array_merge(array(
            'success' => TRUE,
            'active_type' => $data['active_type'],
            'active_id' => $data['active_id'],
            'page_url' => $active_url,
            'unread_summary' => $data['unread_summary'],
            'sidebar_html' => $this->load->view('_sidebar', $data, TRUE),
            'main_html' => $this->load->view('_main', $data, TRUE),
            'group_modal_html' => $this->load->view('_add_group_members_modal', $data, TRUE)
        ), $extra);

        $this->json_response($payload);
    }

    protected function json_response($payload, $status_code = 200) {
        $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($payload));
    }

    protected function upload_chat_attachments() {
        if (empty($_FILES['chat_files']) || empty($_FILES['chat_files']['name'])) {
            return array();
        }

        $this->ensure_chat_upload_directory();

        $files = $_FILES['chat_files'];
        $uploaded = array();
        $file_count = is_array($files['name']) ? count($files['name']) : 0;
        $config = array(
            'upload_path' => './assets/uploads/chat/',
            'allowed_types' => 'pdf|doc|docx|xls|xlsx|ppt|pptx|txt|csv|zip|rar|png|jpg|jpeg|gif',
            'max_size' => 10240,
            'encrypt_name' => TRUE
        );

        for ($index = 0; $index < $file_count; $index++) {
            if (empty($files['name'][$index])) {
                continue;
            }

            $_FILES['chat_file_single'] = array(
                'name' => $files['name'][$index],
                'type' => $files['type'][$index],
                'tmp_name' => $files['tmp_name'][$index],
                'error' => $files['error'][$index],
                'size' => $files['size'][$index]
            );

            $this->upload->initialize($config, TRUE);
            if (!$this->upload->do_upload('chat_file_single')) {
                foreach ($uploaded as $existing_file) {
                    $existing_path = FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $existing_file['filepath']);
                    if (file_exists($existing_path)) {
                        @unlink($existing_path);
                    }
                }
                return array('error' => $this->upload->display_errors('', ''));
            }

            $file_data = $this->upload->data();
            $uploaded[] = array(
                'original_name' => $file_data['client_name'],
                'stored_name' => $file_data['file_name'],
                'filepath' => 'assets/uploads/chat/' . $file_data['file_name'],
                'mime_type' => isset($file_data['file_type']) ? $file_data['file_type'] : NULL,
                'file_ext' => isset($file_data['file_ext']) ? $file_data['file_ext'] : NULL,
                'file_size' => isset($file_data['file_size']) ? (int) round($file_data['file_size'] * 1024) : 0
            );
        }

        return $uploaded;
    }

    protected function ensure_chat_upload_directory() {
        $directory = FCPATH . 'assets/uploads/chat/';

        if (!is_dir($directory)) {
            mkdir($directory, 0777, TRUE);
        }
    }
}
