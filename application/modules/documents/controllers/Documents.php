<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Documents extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Documents_model');
        $this->load->model('Users_model');
        $this->load->model('History_model');
    }

    // ── Index (list with filters) ────────────────────────

    public function index()
    {
        check();
        has_access('view_documents');
        $session = $this->session->userdata('users');
        $user_id = (int) $session->users_id;

        $filters = array(
            'search'      => $this->input->get('q', TRUE),
            'visibility'  => $this->input->get('visibility', TRUE),
            'category_id' => $this->input->get('category', TRUE),
            'file_type'   => $this->input->get('type', TRUE),
        );

        $tab = $this->input->get('tab', TRUE);
        if ($tab === 'mine') {
            $filters['uploaded_by'] = $user_id;
        }

        $documents = $this->Documents_model->get_accessible($user_id, $filters);
        $categories = $this->Documents_model->get_categories();

        $data = array(
            'documents'    => $documents,
            'categories'   => $categories,
            'filters'      => $filters,
            'tab'          => $tab ? $tab : 'all',
            'stats'        => array(
                'total'   => $this->Documents_model->count_all(),
                'public'  => $this->Documents_model->count_by_visibility('public'),
                'private' => $this->Documents_model->count_by_visibility('private'),
                'mine'    => $this->Documents_model->count_by_user($user_id),
                'size'    => $this->Documents_model->total_size(),
            ),
            'current_user_id' => $user_id,
            'all_users'       => $this->Users_model->get_all(),
        );

        $this->load->view('header');
        $this->load->view('index', $data);
        $this->load->view('footer');
    }

    // ── View Document Detail ──────────────────────────────

    public function view($id = NULL)
    {
        check();
        $session = $this->session->userdata('users');
        $user_id = (int) $session->users_id;

        $document = $this->_get_accessible_document($id, $user_id);
        if (!$document) return;

        $shares = $this->Documents_model->get_shares($id);
        $is_owner = (int) $document->uploaded_by === $user_id;

        $data = array(
            'document'        => $document,
            'shares'          => $shares,
            'is_owner'        => $is_owner,
            'categories'      => $this->Documents_model->get_categories(),
            'all_users'       => $this->Users_model->get_all(),
            'current_user_id' => $user_id,
        );

        $this->load->view('header');
        $this->load->view('view', $data);
        $this->load->view('footer');
    }

    // ── Upload (POST) ─────────────────────────────────────

    public function upload()
    {
        check();
        has_access('upload_document');
        $session = $this->session->userdata('users');

        if ($this->input->method() !== 'post') {
            redirect('documents');
            return;
        }

        $config['upload_path']   = './assets/uploads/documents/';
        $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|ppt|pptx|txt|csv|zip|rar|png|jpg|jpeg|gif';
        $config['max_size']      = 10240;
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('document')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            redirect('documents');
            return;
        }

        $upload_data = $this->upload->data();
        $ext = strtolower(pathinfo($upload_data['client_name'], PATHINFO_EXTENSION));
        $visibility = $this->input->post('visibility') === 'private' ? 'private' : 'public';
        $category_id = (int) $this->input->post('category_id');
        $description = trim($this->input->post('description', TRUE));

        $doc_data = array(
            'filename'    => $upload_data['client_name'],
            'filepath'    => 'assets/uploads/documents/' . $upload_data['file_name'],
            'description' => $description !== '' ? $description : NULL,
            'visibility'  => $visibility,
            'category_id' => $category_id > 0 ? $category_id : NULL,
            'file_size'   => (int) $upload_data['file_size'] * 1024,
            'file_type'   => $ext,
            'uploaded_by' => $session->users_id,
        );

        $doc_id = $this->Documents_model->create($doc_data);

        // Handle shares for private documents
        if ($visibility === 'private' && $doc_id) {
            $shared_users = $this->input->post('shared_users');
            if (!empty($shared_users) && is_array($shared_users)) {
                foreach ($shared_users as $uid) {
                    $uid = (int) $uid;
                    if ($uid > 0 && $uid !== (int) $session->users_id) {
                        $this->Documents_model->add_share($doc_id, $uid, $session->users_id);
                    }
                }
            }
        }

        log_history('Partage du document : ' . $upload_data['client_name'] . ' (' . $visibility . ')', $session);

        $this->session->set_flashdata('success', 'Document partagé avec succès.');
        redirect('documents');
    }

    // ── Update Document (POST) ────────────────────────────

    public function update($id = NULL)
    {
        check();
        $session = $this->session->userdata('users');

        if ($this->input->method() !== 'post' || !$id) {
            redirect('documents');
            return;
        }

        $document = $this->Documents_model->get($id);
        if (!$document || !can_access_or_owner('manage_documents', $document->uploaded_by)) {
            $this->session->set_flashdata('error', 'Vous ne pouvez modifier que vos propres documents.');
            redirect('documents');
            return;
        }

        $visibility = $this->input->post('visibility') === 'private' ? 'private' : 'public';
        $category_id = (int) $this->input->post('category_id');
        $description = trim($this->input->post('description', TRUE));

        $update = array(
            'description' => $description !== '' ? $description : NULL,
            'visibility'  => $visibility,
            'category_id' => $category_id > 0 ? $category_id : NULL,
        );

        $this->Documents_model->update($id, $update);

        log_history('Modification du document : ' . $document->filename, $session);

        $this->session->set_flashdata('success', 'Document mis à jour.');
        redirect('documents/view/' . (int) $id);
    }

    // ── Share / Unshare ───────────────────────────────────

    public function add_share($id = NULL)
    {
        check();
        $session = $this->session->userdata('users');

        if ($this->input->method() !== 'post' || !$id) {
            redirect('documents');
            return;
        }

        $document = $this->Documents_model->get($id);
        if (!$document || (int) $document->uploaded_by !== (int) $session->users_id) {
            $this->session->set_flashdata('error', 'Seul le propriétaire peut partager ce document.');
            redirect('documents');
            return;
        }

        $user_ids = $this->input->post('shared_users');
        if (!empty($user_ids) && is_array($user_ids)) {
            foreach ($user_ids as $uid) {
                $uid = (int) $uid;
                if ($uid > 0 && $uid !== (int) $session->users_id) {
                    $this->Documents_model->add_share($id, $uid, $session->users_id);
                }
            }
        }

        $this->session->set_flashdata('success', 'Partage mis à jour.');
        redirect('documents/view/' . (int) $id);
    }

    public function remove_share($doc_id = NULL, $user_id = NULL)
    {
        check();
        $session = $this->session->userdata('users');

        if (!$doc_id || !$user_id) {
            redirect('documents');
            return;
        }

        $document = $this->Documents_model->get($doc_id);
        if (!$document || (int) $document->uploaded_by !== (int) $session->users_id) {
            $this->session->set_flashdata('error', 'Seul le propriétaire peut modifier le partage.');
            redirect('documents');
            return;
        }

        $this->Documents_model->remove_share($doc_id, $user_id);
        $this->session->set_flashdata('success', 'Accès retiré.');
        redirect('documents/view/' . (int) $doc_id);
    }

    // ── Download ──────────────────────────────────────────

    public function download($id = NULL)
    {
        check();
        $session = $this->session->userdata('users');
        $user_id = (int) $session->users_id;

        $document = $this->_get_accessible_document($id, $user_id);
        if (!$document) return;

        $file_path = FCPATH . $document->filepath;
        if (!file_exists($file_path)) {
            $this->session->set_flashdata('error', 'Le fichier n\'existe plus sur le serveur.');
            redirect('documents');
            return;
        }

        $this->Documents_model->increment_downloads($id);

        $this->load->helper('download');
        force_download($document->filename, file_get_contents($file_path));
    }

    // ── Preview ───────────────────────────────────────────

    public function preview($id = NULL)
    {
        check();
        $session = $this->session->userdata('users');
        $user_id = (int) $session->users_id;

        $document = $this->_get_accessible_document($id, $user_id);
        if (!$document) return;

        $file_path = FCPATH . $document->filepath;
        if (!file_exists($file_path)) {
            $this->session->set_flashdata('error', 'Le fichier n\'existe plus sur le serveur.');
            redirect('documents');
            return;
        }

        $ext = strtolower(pathinfo($document->filename, PATHINFO_EXTENSION));
        $mime_types = array(
            'pdf'  => 'application/pdf',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            'txt'  => 'text/plain',
            'csv'  => 'text/csv',
        );

        if (isset($mime_types[$ext])) {
            header('Content-Type: ' . $mime_types[$ext]);
            header('Content-Disposition: inline; filename="' . basename($document->filename) . '"');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit;
        }

        $this->load->helper('download');
        force_download($document->filename, file_get_contents($file_path));
    }

    // ── Delete (POST) ─────────────────────────────────────

    public function delete($id = NULL)
    {
        check();
        $session = $this->session->userdata('users');

        if ($this->input->method() !== 'post' || !$id) {
            redirect('documents');
            return;
        }

        $document = $this->Documents_model->get($id);
        if (!$document) {
            $this->session->set_flashdata('error', 'Document introuvable.');
            redirect('documents');
            return;
        }

        // Only owner or manage_documents permission can delete
        if (!can_access_or_owner('manage_documents', $document->uploaded_by)) {
            $this->session->set_flashdata('error', 'Vous ne pouvez supprimer que vos propres documents.');
            redirect('documents');
            return;
        }

        $file_path = FCPATH . $document->filepath;
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        $this->Documents_model->delete($id);
        log_history('Suppression du document : ' . $document->filename, $session);

        $this->session->set_flashdata('success', 'Document supprimé avec succès.');
        redirect('documents');
    }

    // ── Category CRUD ─────────────────────────────────────

    public function create_category()
    {
        check();
        has_access('manage_categories');
        $session = $this->session->userdata('users');

        if ($this->input->method() !== 'post') {
            redirect('documents');
            return;
        }

        $name = trim($this->input->post('name', TRUE));
        $color = trim($this->input->post('color', TRUE));

        if ($name === '') {
            $this->session->set_flashdata('error', 'Le nom de la catégorie est requis.');
            redirect('documents');
            return;
        }

        $this->Documents_model->create_category(array(
            'name'       => $name,
            'color'      => $color !== '' ? $color : '#1a73e8',
            'icon'       => 'fas fa-folder',
            'created_by' => $session->users_id,
        ));

        $this->session->set_flashdata('success', 'Catégorie créée.');
        redirect('documents');
    }

    public function delete_category($id = NULL)
    {
        check();
        has_access('manage_categories');
        if ($this->input->method() !== 'post' || !$id) {
            redirect('documents');
            return;
        }

        $this->Documents_model->delete_category($id);
        $this->session->set_flashdata('success', 'Catégorie supprimée.');
        redirect('documents');
    }

    // ── Private Helpers ───────────────────────────────────

    private function _get_accessible_document($id, $user_id)
    {
        if (!$id) {
            $this->session->set_flashdata('error', 'Document introuvable.');
            redirect('documents');
            return NULL;
        }

        $document = $this->Documents_model->get($id);
        if (!$document) {
            $this->session->set_flashdata('error', 'Document introuvable.');
            redirect('documents');
            return NULL;
        }

        if (!$this->Documents_model->can_user_access($id, $user_id)) {
            $this->session->set_flashdata('error', 'Vous n\'avez pas accès à ce document.');
            redirect('documents');
            return NULL;
        }

        return $document;
    }
}
