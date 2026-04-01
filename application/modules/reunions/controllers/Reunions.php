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

    // ─── List / Search ───────────────────────────────────

    public function index()
    {
        has_access('view_reunions');
        $filter = $this->input->get('filter');
        $search = trim($this->input->get('q'));

        if (!in_array($filter, array('all', 'today', 'upcoming', 'past', 'mine', 'cancelled'))) {
            $filter = 'all';
        }

        if (!empty($search)) {
            $data['reunions'] = $this->Reunions_model->search($search);
        } else {
            $session = $this->session->userdata('users');
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
                case 'mine':
                    $data['reunions'] = $this->Reunions_model->get_my_reunions($session->users_id);
                    break;
                case 'cancelled':
                    $data['reunions'] = $this->Reunions_model->get_cancelled();
                    break;
                default:
                    $data['reunions'] = $this->Reunions_model->get_all();
                    break;
            }
        }

        foreach ($data['reunions'] as &$reunion) {
            $reunion->participants = $this->Reunions_model->get_participants($reunion->id);
        }

        $data['next_reunion'] = $this->Reunions_model->get_next_reunion();
        if ($data['next_reunion']) {
            $data['next_reunion']->participants = $this->Reunions_model->get_participants($data['next_reunion']->id);
        }

        $data['users']  = $this->Users_model->get_all();
        $data['filter'] = $filter;
        $data['search'] = $search;

        // Stats
        $data['stats'] = array(
            'total'     => $this->Reunions_model->count_total(),
            'today'     => $this->Reunions_model->count_today(),
            'upcoming'  => $this->Reunions_model->count_upcoming(),
            'completed' => $this->Reunions_model->count_by_status('completed'),
            'cancelled' => $this->Reunions_model->count_by_status('cancelled')
        );

        $this->load->view('header');
        $this->load->view('index', $data);
        $this->load->view('footer');
    }

    // ─── Create ──────────────────────────────────────────

    public function create()
    {
        has_access('create_reunion');
        if ($this->input->method() !== 'post') {
            redirect('reunions');
        }

        $title        = trim($this->input->post('title'));
        $description  = trim($this->input->post('description'));
        $scheduled_at = trim($this->input->post('scheduled_at'));
        $location     = trim($this->input->post('location'));
        $duration     = (int) $this->input->post('duration');
        $priority     = trim($this->input->post('priority'));
        $participants = $this->input->post('participants');

        if (empty($title) || empty($scheduled_at)) {
            $this->session->set_flashdata('error', 'Le titre et la date sont obligatoires.');
            redirect('reunions');
        }

        if (!in_array($priority, array('low', 'normal', 'high', 'urgent'))) {
            $priority = 'normal';
        }

        $session = $this->session->userdata('users');

        $reunion_data = array(
            'title'        => $title,
            'description'  => $description,
            'location'     => $location ?: null,
            'duration'     => $duration > 0 ? $duration : null,
            'priority'     => $priority,
            'status'       => 'planned',
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

    // ─── Update ──────────────────────────────────────────

    public function update($id)
    {
        $reunion = $this->Reunions_model->get($id);
        if (!$reunion) {
            $this->session->set_flashdata('error', 'Cette réunion n\'existe pas.');
            redirect('reunions');
        }
        require_access_or_owner('manage_reunions', $reunion->created_by, 'Seul le créateur ou un administrateur peut modifier cette réunion.');

        if ($this->input->method() === 'post') {
            $title        = trim($this->input->post('title'));
            $description  = trim($this->input->post('description'));
            $scheduled_at = trim($this->input->post('scheduled_at'));
            $location     = trim($this->input->post('location'));
            $duration     = (int) $this->input->post('duration');
            $priority     = trim($this->input->post('priority'));
            $participants = $this->input->post('participants');

            if (empty($title) || empty($scheduled_at)) {
                $this->session->set_flashdata('error', 'Le titre et la date sont obligatoires.');
                redirect('reunions/update/' . $id);
            }

            if (!in_array($priority, array('low', 'normal', 'high', 'urgent'))) {
                $priority = 'normal';
            }

            $reunion_data = array(
                'title'        => $title,
                'description'  => $description,
                'location'     => $location ?: null,
                'duration'     => $duration > 0 ? $duration : null,
                'priority'     => $priority,
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
            redirect('reunions/view/' . $id);
        }

        $data['reunion']      = $reunion;
        $data['participants'] = $this->Reunions_model->get_participants($id);
        $data['users']        = $this->Users_model->get_all();

        $this->load->view('header');
        $this->load->view('edit', $data);
        $this->load->view('footer');
    }

    // ─── Status Management ───────────────────────────────

    public function update_status($id)
    {
        if ($this->input->method() !== 'post') {
            redirect('reunions');
        }

        $reunion = $this->Reunions_model->get($id);
        if (!$reunion) {
            $this->session->set_flashdata('error', 'Cette réunion n\'existe pas.');
            redirect('reunions');
        }
        require_access_or_owner('manage_reunions', $reunion->created_by, 'Seul le créateur ou un administrateur peut changer le statut.');

        $status = trim($this->input->post('status'));
        if ($this->Reunions_model->update_status($id, $status)) {
            $labels = array(
                'planned' => 'Planifiée', 'in_progress' => 'En cours',
                'completed' => 'Terminée', 'cancelled' => 'Annulée'
            );
            $session = $this->session->userdata('users');
            log_history('Changement de statut de la réunion "' . $reunion->title . '" → ' . (isset($labels[$status]) ? $labels[$status] : $status), $session);
            $this->session->set_flashdata('success', 'Statut mis à jour.');
        } else {
            $this->session->set_flashdata('error', 'Statut invalide.');
        }

        redirect('reunions/view/' . (int) $id);
    }

    // ─── Participants ────────────────────────────────────

    public function add_participants($id)
    {
        if ($this->input->method() !== 'post') {
            redirect('reunions/view/' . (int) $id);
        }

        $reunion = $this->Reunions_model->get($id);
        if (!$reunion) {
            $this->session->set_flashdata('error', 'Cette réunion n\'existe pas.');
            redirect('reunions');
        }

        $session = $this->session->userdata('users');
        if (!$session || (int) $session->users_id !== (int) $reunion->created_by) {
            $this->session->set_flashdata('error', 'Seul le créateur de la réunion peut ajouter des participants.');
            redirect('reunions/view/' . (int) $id);
        }

        $participants = $this->input->post('participants');
        if (empty($participants) || !is_array($participants)) {
            $this->session->set_flashdata('error', 'Sélectionnez au moins un participant à ajouter.');
            redirect('reunions/view/' . (int) $id);
        }

        $added_count = $this->Reunions_model->add_participants($id, $participants);

        if ($added_count > 0) {
            log_history('Ajout de participants à la réunion : ' . $reunion->title, $session);
            $this->session->set_flashdata('success', $added_count . ' participant' . ($added_count > 1 ? 's ont été ajoutés.' : ' a été ajouté.'));
        } elseif ($added_count === 0) {
            $this->session->set_flashdata('info', 'Les utilisateurs sélectionnés participent déjà à cette réunion.');
        } else {
            $this->session->set_flashdata('error', 'Impossible d\'ajouter les participants pour le moment.');
        }

        redirect('reunions/view/' . (int) $id);
    }

    public function remove_participant($id, $user_id)
    {
        $reunion = $this->Reunions_model->get($id);
        if (!$reunion) {
            $this->session->set_flashdata('error', 'Cette réunion n\'existe pas.');
            redirect('reunions');
        }

        $session = $this->session->userdata('users');
        if (!$session || (int) $session->users_id !== (int) $reunion->created_by) {
            $this->session->set_flashdata('error', 'Seul le créateur peut retirer un participant.');
            redirect('reunions/view/' . (int) $id);
        }

        $this->Reunions_model->remove_participant($id, $user_id);
        $this->session->set_flashdata('success', 'Participant retiré.');
        redirect('reunions/view/' . (int) $id);
    }

    // ─── RSVP ────────────────────────────────────────────

    public function rsvp($id)
    {
        if ($this->input->method() !== 'post') {
            redirect('reunions/view/' . (int) $id);
        }

        $session = $this->session->userdata('users');
        if (!$session) {
            redirect('reunions');
        }

        $status = trim($this->input->post('rsvp'));
        if (!in_array($status, array('accepted', 'declined'))) {
            $this->session->set_flashdata('error', 'Réponse invalide.');
            redirect('reunions/view/' . (int) $id);
        }

        $is_participant = $this->Reunions_model->is_user_participant($id, $session->users_id);
        if (!$is_participant) {
            $this->session->set_flashdata('error', 'Vous n\'êtes pas invité à cette réunion.');
            redirect('reunions/view/' . (int) $id);
        }

        $this->Reunions_model->update_participant_status($id, $session->users_id, $status);
        $label = $status === 'accepted' ? 'acceptée' : 'déclinée';
        $this->session->set_flashdata('success', 'Invitation ' . $label . '.');
        redirect('reunions/view/' . (int) $id);
    }

    // ─── Agenda ──────────────────────────────────────────

    public function add_agenda($id)
    {
        if ($this->input->method() !== 'post') {
            redirect('reunions/view/' . (int) $id);
        }

        $reunion = $this->Reunions_model->get($id);
        if (!$reunion) {
            $this->session->set_flashdata('error', 'Réunion introuvable.');
            redirect('reunions');
        }
        require_access_or_owner('manage_reunions', $reunion->created_by, 'Seul le créateur ou un administrateur peut modifier l\'ordre du jour.');

        $title = trim($this->input->post('agenda_title'));
        $description = trim($this->input->post('agenda_description'));

        if (empty($title)) {
            $this->session->set_flashdata('error', 'Le titre du point est obligatoire.');
            redirect('reunions/view/' . (int) $id . '#agenda');
        }

        $existing = $this->Reunions_model->get_agenda($id);
        $order = count($existing);

        $this->Reunions_model->add_agenda_item(array(
            'reunion_id'  => (int) $id,
            'title'       => $title,
            'description' => $description ?: null,
            'sort_order'  => $order
        ));

        $this->session->set_flashdata('success', 'Point ajouté à l\'ordre du jour.');
        redirect('reunions/view/' . (int) $id . '#agenda');
    }

    public function delete_agenda($id, $agenda_id)
    {
        $item = $this->Reunions_model->get_agenda_item($agenda_id);
        if (!$item || (int) $item->reunion_id !== (int) $id) {
            $this->session->set_flashdata('error', 'Point introuvable.');
            redirect('reunions/view/' . (int) $id);
        }

        $reunion = $this->Reunions_model->get($id);
        require_access_or_owner('manage_reunions', $reunion ? $reunion->created_by : 0);

        $this->Reunions_model->delete_agenda_item($agenda_id);
        $this->session->set_flashdata('success', 'Point supprimé de l\'ordre du jour.');
        redirect('reunions/view/' . (int) $id . '#agenda');
    }

    public function toggle_agenda($id, $agenda_id)
    {
        $item = $this->Reunions_model->get_agenda_item($agenda_id);
        if (!$item || (int) $item->reunion_id !== (int) $id) {
            $this->session->set_flashdata('error', 'Point introuvable.');
            redirect('reunions/view/' . (int) $id);
        }

        $reunion = $this->Reunions_model->get($id);
        require_access_or_owner('manage_reunions', $reunion ? $reunion->created_by : 0);

        $this->Reunions_model->toggle_agenda_item($agenda_id);
        redirect('reunions/view/' . (int) $id . '#agenda');
    }

    // ─── Notes ───────────────────────────────────────────

    public function add_note($id)
    {
        if ($this->input->method() !== 'post') {
            redirect('reunions/view/' . (int) $id);
        }

        $reunion = $this->Reunions_model->get($id);
        if (!$reunion) {
            $this->session->set_flashdata('error', 'Réunion introuvable.');
            redirect('reunions');
        }

        $session = $this->session->userdata('users');
        $content = trim($this->input->post('note_content'));

        if (empty($content)) {
            $this->session->set_flashdata('error', 'Le contenu de la note ne peut pas être vide.');
            redirect('reunions/view/' . (int) $id . '#notes');
        }

        $this->Reunions_model->add_note(array(
            'reunion_id' => (int) $id,
            'user_id'    => (int) $session->users_id,
            'content'    => $content
        ));

        $this->session->set_flashdata('success', 'Note ajoutée.');
        redirect('reunions/view/' . (int) $id . '#notes');
    }

    public function delete_note($id, $note_id)
    {
        $note = $this->Reunions_model->get_note($note_id);
        if (!$note || (int) $note->reunion_id !== (int) $id) {
            $this->session->set_flashdata('error', 'Note introuvable.');
            redirect('reunions/view/' . (int) $id);
        }

        $session = $this->session->userdata('users');
        if ((int) $note->user_id !== (int) $session->users_id) {
            $this->session->set_flashdata('error', 'Vous ne pouvez supprimer que vos propres notes.');
            redirect('reunions/view/' . (int) $id . '#notes');
        }

        $this->Reunions_model->delete_note($note_id);
        $this->session->set_flashdata('success', 'Note supprimée.');
        redirect('reunions/view/' . (int) $id . '#notes');
    }

    // ─── Delete ──────────────────────────────────────────

    public function delete($id)
    {
        $reunion = $this->Reunions_model->get($id);
        if (!$reunion) {
            $this->session->set_flashdata('error', 'Cette réunion n\'existe pas.');
            redirect('reunions');
        }

        require_access_or_owner('manage_reunions', $reunion->created_by);

        $this->Reunions_model->delete($id);

        $session = $this->session->userdata('users');
        log_history('Suppression de la réunion : ' . $reunion->title, $session);
        $this->session->set_flashdata('success', 'La réunion a été supprimée avec succès.');
        redirect('reunions');
    }

    // ─── View Detail ─────────────────────────────────────

    public function view($id)
    {
        $reunion = $this->Reunions_model->get($id);
        if (!$reunion) {
            $this->session->set_flashdata('error', 'Cette réunion n\'existe pas.');
            redirect('reunions');
        }

        $session = $this->session->userdata('users');
        $participants = $this->Reunions_model->get_participants($id);

        $is_reunion_host = $session && (int) $session->users_id === (int) $reunion->created_by;

        $data['reunion'] = $reunion;
        $data['participants'] = $participants;
        $data['available_users'] = $this->get_available_users_for_reunion($participants);
        $data['can_manage_participants'] = $is_reunion_host;
        $data['can_join_visio'] = $session && $this->can_access_reunion_room($reunion, $session->users_id);
        $data['is_reunion_host'] = $is_reunion_host;
        $data['visio_url'] = site_url('visioconference/reunion/' . (int) $reunion->id);
        $data['can_manage_visio_settings'] = $this->can_manage_visio_settings($session);
        $data['visio_configuration_url'] = site_url('visioconference/configuration');

        // Agenda
        $data['agenda'] = $this->Reunions_model->get_agenda($id);

        // Notes
        $data['notes'] = $this->Reunions_model->get_notes($id);

        // RSVP counts
        $data['rsvp_accepted'] = $this->Reunions_model->count_rsvp($id, 'accepted');
        $data['rsvp_declined'] = $this->Reunions_model->count_rsvp($id, 'declined');
        $data['rsvp_pending']  = $this->Reunions_model->count_rsvp($id, 'invited');

        // Current user RSVP status
        $data['my_rsvp'] = null;
        if ($session) {
            foreach ($participants as $p) {
                if ((int) $p->users_id === (int) $session->users_id) {
                    $data['my_rsvp'] = $p->status;
                    break;
                }
            }
        }

        $this->load->view('header');
        $this->load->view('view', $data);
        $this->load->view('footer');
    }

    // ─── Helpers (private) ───────────────────────────────

    private function get_available_users_for_reunion($participants)
    {
        $users = $this->Users_model->get_all();
        $participant_ids = array();

        foreach ($participants as $participant) {
            $participant_ids[(int) $participant->users_id] = true;
        }

        $available_users = array();
        foreach ($users as $user) {
            if (!isset($participant_ids[(int) $user->users_id])) {
                $available_users[] = $user;
            }
        }

        return $available_users;
    }

    private function can_access_reunion_room($reunion, $user_id)
    {
        $user_id = (int) $user_id;
        if ($user_id <= 0 || empty($reunion)) {
            return false;
        }

        if ((int) $reunion->created_by === $user_id) {
            return true;
        }

        return $this->Reunions_model->is_user_participant($reunion->id, $user_id);
    }

    private function can_manage_visio_settings($session)
    {
        if (empty($session)) {
            return false;
        }

        if (function_exists('is_allowed') && is_allowed('manage_visio_config')) {
            return true;
        }

        return false;
    }
}
