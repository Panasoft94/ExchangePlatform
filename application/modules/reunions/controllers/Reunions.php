<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reunions extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reunions_model');
        $this->load->model('Users_model');
        $this->load->model('History_model');
        check();
    }

    /**
     * List all reunions with optional filter (all/today/upcoming/past)
     */
    public function index()
    {
        $filter = $this->input->get('filter');
        if (!in_array($filter, array('all', 'today', 'upcoming', 'past'))) {
            $filter = 'all';
        }

        switch ($filter) {
            case 'today':
                $data['reunions'] = $this->Reunions_model->get_today();
                break;
            case 'upcoming':
                $data['reunions'] = $this->Reunions_model->get_upcoming();
                break;
            case 'past':
                $data['reunions'] = $this->Reunions_model->get_past();
                break;
            default:
                $data['reunions'] = $this->Reunions_model->get_all();
                break;
        }

        // Attach participant count to each reunion
        foreach ($data['reunions'] as &$reunion) {
            $reunion->participants = $this->Reunions_model->get_participants($reunion->id);
        }

        $data['next_reunion'] = $this->Reunions_model->get_next_reunion();
        if ($data['next_reunion']) {
            $data['next_reunion']->participants = $this->Reunions_model->get_participants($data['next_reunion']->id);
        }

        $data['users']  = $this->Users_model->get_all();
        $data['filter'] = $filter;

        $this->load->view('header');
        $this->load->view('index', $data);
        $this->load->view('footer');
    }

    /**
     * POST handler for creating a reunion
     */
    public function create()
    {
        if ($this->input->method() !== 'post') {
            redirect('reunions');
        }

        $title        = trim($this->input->post('title'));
        $description  = trim($this->input->post('description'));
        $scheduled_at = trim($this->input->post('scheduled_at'));
        $participants = $this->input->post('participants');

        if (empty($title) || empty($scheduled_at)) {
            $this->session->set_flashdata('error', 'Le titre et la date sont obligatoires.');
            redirect('reunions');
        }

        $session = $this->session->userdata('users');

        $reunion_data = array(
            'title'        => $title,
            'description'  => $description,
            'scheduled_at' => date('Y-m-d H:i:s', strtotime($scheduled_at)),
            'created_by'   => $session->users_id
        );

        $reunion_id = $this->Reunions_model->create($reunion_data);

        if ($reunion_id && !empty($participants) && is_array($participants)) {
            $this->Reunions_model->add_participants($reunion_id, $participants);
        }

        log_history('Création de la réunion : ' . $title, $session);
        $this->session->set_flashdata('success', 'La réunion a été créée avec succès.');
        redirect('reunions');
    }

    /**
     * GET: show edit form / POST: update reunion
     */
    public function update($id)
    {
        $reunion = $this->Reunions_model->get($id);
        if (!$reunion) {
            $this->session->set_flashdata('error', 'Cette réunion n\'existe pas.');
            redirect('reunions');
        }

        if ($this->input->method() === 'post') {
            $title        = trim($this->input->post('title'));
            $description  = trim($this->input->post('description'));
            $scheduled_at = trim($this->input->post('scheduled_at'));
            $participants = $this->input->post('participants');

            if (empty($title) || empty($scheduled_at)) {
                $this->session->set_flashdata('error', 'Le titre et la date sont obligatoires.');
                redirect('reunions/update/' . $id);
            }

            $reunion_data = array(
                'title'        => $title,
                'description'  => $description,
                'scheduled_at' => date('Y-m-d H:i:s', strtotime($scheduled_at))
            );

            $this->Reunions_model->update($id, $reunion_data);
            $this->Reunions_model->remove_all_participants($id);

            if (!empty($participants) && is_array($participants)) {
                $this->Reunions_model->add_participants($id, $participants);
            }

            $session = $this->session->userdata('users');
            log_history('Modification de la réunion : ' . $title, $session);
            $this->session->set_flashdata('success', 'La réunion a été modifiée avec succès.');
            redirect('reunions');
        }

        $data['reunion']      = $reunion;
        $data['participants'] = $this->Reunions_model->get_participants($id);
        $data['users']        = $this->Users_model->get_all();

        $this->load->view('header');
        $this->load->view('edit', $data);
        $this->load->view('footer');
    }

    /**
     * Delete reunion
     */
    public function delete($id)
    {
        $reunion = $this->Reunions_model->get($id);
        if (!$reunion) {
            $this->session->set_flashdata('error', 'Cette réunion n\'existe pas.');
            redirect('reunions');
        }

        $this->Reunions_model->delete($id);

        $session = $this->session->userdata('users');
        log_history('Suppression de la réunion : ' . $reunion->title, $session);
        $this->session->set_flashdata('success', 'La réunion a été supprimée avec succès.');
        redirect('reunions');
    }

    /**
     * View single reunion details with participant list
     */
    public function view($id)
    {
        $reunion = $this->Reunions_model->get($id);
        if (!$reunion) {
            $this->session->set_flashdata('error', 'Cette réunion n\'existe pas.');
            redirect('reunions');
        }

        $data['reunion']      = $reunion;
        $data['participants'] = $this->Reunions_model->get_participants($id);

        $this->load->view('header');
        $this->load->view('view', $data);
        $this->load->view('footer');
    }
}
