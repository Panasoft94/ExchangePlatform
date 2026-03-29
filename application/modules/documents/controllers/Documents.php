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

    /**
     * List all documents, with optional search
     */
    public function index()
    {
        check();

        $search = $this->input->get('search', TRUE);
        if ($search) {
            $data['documents'] = $this->Documents_model->search($search);
        } else {
            $data['documents'] = $this->Documents_model->get_all();
        }
        $data['search'] = $search ? $search : '';

        $this->load->view('header');
        $this->load->view('index', $data);
        $this->load->view('footer');
    }

    /**
     * Handle file upload (POST)
     */
    public function upload()
    {
        check();
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

        $doc_data = array(
            'filename'    => $upload_data['client_name'],
            'filepath'    => 'assets/uploads/documents/' . $upload_data['file_name'],
            'uploaded_by' => $session->users_id
        );

        $this->Documents_model->create($doc_data);

        log_history('Partage du document : ' . $upload_data['client_name'], $session);

        $this->session->set_flashdata('success', 'Document partagé avec succès.');
        redirect('documents');
    }

    /**
     * Force download a document
     */
    public function download($id = NULL)
    {
        check();

        if (!$id) {
            $this->session->set_flashdata('error', 'Document introuvable.');
            redirect('documents');
            return;
        }

        $document = $this->Documents_model->get($id);

        if (!$document) {
            $this->session->set_flashdata('error', 'Document introuvable.');
            redirect('documents');
            return;
        }

        $file_path = FCPATH . $document->filepath;

        if (!file_exists($file_path)) {
            $this->session->set_flashdata('error', 'Le fichier n\'existe plus sur le serveur.');
            redirect('documents');
            return;
        }

        $this->load->helper('download');
        force_download($document->filename, file_get_contents($file_path));
    }

    /**
     * Delete a document
     */
    public function delete($id = NULL)
    {
        check();
        $session = $this->session->userdata('users');

        if (!$id) {
            $this->session->set_flashdata('error', 'Document introuvable.');
            redirect('documents');
            return;
        }

        $document = $this->Documents_model->get($id);

        if (!$document) {
            $this->session->set_flashdata('error', 'Document introuvable.');
            redirect('documents');
            return;
        }

        // Delete file from disk
        $file_path = FCPATH . $document->filepath;
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Delete record from DB
        $this->Documents_model->delete($id);

        log_history('Suppression du document : ' . $document->filename, $session);

        $this->session->set_flashdata('success', 'Document supprimé avec succès.');
        redirect('documents');
    }

    /**
     * Preview a document (inline display for images/PDFs)
     */
    public function preview($id = NULL)
    {
        check();

        if (!$id) {
            $this->session->set_flashdata('error', 'Document introuvable.');
            redirect('documents');
            return;
        }

        $document = $this->Documents_model->get($id);

        if (!$document) {
            $this->session->set_flashdata('error', 'Document introuvable.');
            redirect('documents');
            return;
        }

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
            header('Content-Disposition: inline; filename="' . $document->filename . '"');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit;
        }

        // Non-previewable files: force download instead
        $this->load->helper('download');
        force_download($document->filename, file_get_contents($file_path));
    }
}
