<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reunions_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // ─── Core CRUD ───────────────────────────────────────

    public function get_all()
    {
        $this->db->select('reunions.*, users.users_nom, users.users_prenom, users.users_username')
                 ->from('reunions')
                 ->join('users', 'users.users_id = reunions.created_by', 'left')
                 ->order_by('reunions.scheduled_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_upcoming()
    {
        $this->db->select('reunions.*, users.users_nom, users.users_prenom, users.users_username')
                 ->from('reunions')
                 ->join('users', 'users.users_id = reunions.created_by', 'left')
                 ->where('reunions.scheduled_at >=', date('Y-m-d H:i:s'))
                 ->where_in('reunions.status', array('planned', 'in_progress'))
                 ->order_by('reunions.scheduled_at', 'ASC');
        return $this->db->get()->result();
    }

    public function get_past()
    {
        $this->db->select('reunions.*, users.users_nom, users.users_prenom, users.users_username')
                 ->from('reunions')
                 ->join('users', 'users.users_id = reunions.created_by', 'left')
                 ->group_start()
                     ->where('reunions.scheduled_at <', date('Y-m-d H:i:s'))
                     ->or_where('reunions.status', 'completed')
                     ->or_where('reunions.status', 'cancelled')
                 ->group_end()
                 ->order_by('reunions.scheduled_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_today()
    {
        $today = date('Y-m-d');
        $this->db->select('reunions.*, users.users_nom, users.users_prenom, users.users_username')
                 ->from('reunions')
                 ->join('users', 'users.users_id = reunions.created_by', 'left')
                 ->where('DATE(reunions.scheduled_at)', $today)
                 ->order_by('reunions.scheduled_at', 'ASC');
        return $this->db->get()->result();
    }

    public function get_cancelled()
    {
        $this->db->select('reunions.*, users.users_nom, users.users_prenom, users.users_username')
                 ->from('reunions')
                 ->join('users', 'users.users_id = reunions.created_by', 'left')
                 ->where('reunions.status', 'cancelled')
                 ->order_by('reunions.scheduled_at', 'DESC');
        return $this->db->get()->result();
    }

    public function search($keyword)
    {
        $this->db->select('reunions.*, users.users_nom, users.users_prenom, users.users_username')
                 ->from('reunions')
                 ->join('users', 'users.users_id = reunions.created_by', 'left')
                 ->group_start()
                     ->like('reunions.title', $keyword)
                     ->or_like('reunions.description', $keyword)
                     ->or_like('reunions.location', $keyword)
                 ->group_end()
                 ->order_by('reunions.scheduled_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get($id)
    {
        $this->db->select('reunions.*, users.users_nom, users.users_prenom, users.users_username')
                 ->from('reunions')
                 ->join('users', 'users.users_id = reunions.created_by', 'left')
                 ->where('reunions.id', (int) $id);
        return $this->db->get()->row();
    }

    public function create($data)
    {
        $this->db->insert('reunions', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', (int) $id);
        return $this->db->update('reunions', $data);
    }

    public function update_status($id, $status)
    {
        $allowed = array('planned', 'in_progress', 'completed', 'cancelled');
        if (!in_array($status, $allowed)) {
            return false;
        }
        return $this->update($id, array('status' => $status));
    }

    public function delete($id)
    {
        $id = (int) $id;
        $this->db->where('reunion_id', $id)->delete('reunions_agenda');
        $this->db->where('reunion_id', $id)->delete('reunions_notes');
        $this->remove_all_participants($id);
        $this->db->where('id', $id);
        return $this->db->delete('reunions');
    }

    // ─── Participants ────────────────────────────────────

    public function get_participants($reunion_id)
    {
        $this->db->select('reunions_participants.*, users.users_id, users.users_nom, users.users_prenom, users.users_username, users.users_email, users.users_role, users.photo_profil')
                 ->from('reunions_participants')
                 ->join('users', 'users.users_id = reunions_participants.user_id', 'left')
                 ->where('reunions_participants.reunion_id', (int) $reunion_id);
        return $this->db->get()->result();
    }

    public function add_participants($reunion_id, $user_ids)
    {
        if (empty($user_ids) || !is_array($user_ids)) {
            return false;
        }

        $normalized_ids = array();
        foreach ($user_ids as $user_id) {
            $user_id = (int) $user_id;
            if ($user_id > 0) {
                $normalized_ids[$user_id] = $user_id;
            }
        }

        if (empty($normalized_ids)) {
            return false;
        }

        $existing_rows = $this->db->select('user_id')
                                  ->from('reunions_participants')
                                  ->where('reunion_id', (int) $reunion_id)
                                  ->where_in('user_id', array_values($normalized_ids))
                                  ->get()
                                  ->result();

        $existing_ids = array();
        foreach ($existing_rows as $row) {
            $existing_ids[(int) $row->user_id] = true;
        }

        $batch = array();
        foreach ($normalized_ids as $user_id) {
            if (isset($existing_ids[$user_id])) {
                continue;
            }
            $batch[] = array(
                'reunion_id' => (int) $reunion_id,
                'user_id'    => (int) $user_id,
                'status'     => 'invited'
            );
        }

        if (empty($batch)) {
            return 0;
        }

        return $this->db->insert_batch('reunions_participants', $batch);
    }

    public function remove_participant($reunion_id, $user_id)
    {
        $this->db->where('reunion_id', (int) $reunion_id)
                 ->where('user_id', (int) $user_id);
        return $this->db->delete('reunions_participants');
    }

    public function update_participant_status($reunion_id, $user_id, $status)
    {
        $allowed = array('invited', 'accepted', 'declined');
        if (!in_array($status, $allowed)) {
            return false;
        }
        $this->db->where('reunion_id', (int) $reunion_id)
                 ->where('user_id', (int) $user_id);
        return $this->db->update('reunions_participants', array('status' => $status));
    }

    public function is_user_participant($reunion_id, $user_id)
    {
        return $this->db->where('reunion_id', (int) $reunion_id)
                        ->where('user_id', (int) $user_id)
                        ->count_all_results('reunions_participants') > 0;
    }

    public function remove_all_participants($reunion_id)
    {
        $this->db->where('reunion_id', (int) $reunion_id);
        return $this->db->delete('reunions_participants');
    }

    public function count_rsvp($reunion_id, $status)
    {
        return $this->db->where('reunion_id', (int) $reunion_id)
                        ->where('status', $status)
                        ->count_all_results('reunions_participants');
    }

    // ─── Agenda Items ────────────────────────────────────

    public function get_agenda($reunion_id)
    {
        $this->db->where('reunion_id', (int) $reunion_id)
                 ->order_by('sort_order', 'ASC')
                 ->order_by('id', 'ASC');
        return $this->db->get('reunions_agenda')->result();
    }

    public function add_agenda_item($data)
    {
        $this->db->insert('reunions_agenda', $data);
        return $this->db->insert_id();
    }

    public function update_agenda_item($id, $data)
    {
        $this->db->where('id', (int) $id);
        return $this->db->update('reunions_agenda', $data);
    }

    public function delete_agenda_item($id)
    {
        $this->db->where('id', (int) $id);
        return $this->db->delete('reunions_agenda');
    }

    public function get_agenda_item($id)
    {
        return $this->db->where('id', (int) $id)->get('reunions_agenda')->row();
    }

    public function toggle_agenda_item($id)
    {
        $item = $this->get_agenda_item($id);
        if (!$item) return false;
        $new_state = $item->completed ? 0 : 1;
        return $this->update_agenda_item($id, array('completed' => $new_state));
    }

    // ─── Notes / PV ──────────────────────────────────────

    public function get_notes($reunion_id)
    {
        $this->db->select('reunions_notes.*, users.users_nom, users.users_prenom, users.photo_profil')
                 ->from('reunions_notes')
                 ->join('users', 'users.users_id = reunions_notes.user_id', 'left')
                 ->where('reunions_notes.reunion_id', (int) $reunion_id)
                 ->order_by('reunions_notes.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function add_note($data)
    {
        $this->db->insert('reunions_notes', $data);
        return $this->db->insert_id();
    }

    public function update_note($id, $data)
    {
        $this->db->where('id', (int) $id);
        return $this->db->update('reunions_notes', $data);
    }

    public function delete_note($id)
    {
        $this->db->where('id', (int) $id);
        return $this->db->delete('reunions_notes');
    }

    public function get_note($id)
    {
        $this->db->select('reunions_notes.*, users.users_nom, users.users_prenom')
                 ->from('reunions_notes')
                 ->join('users', 'users.users_id = reunions_notes.user_id', 'left')
                 ->where('reunions_notes.id', (int) $id);
        return $this->db->get()->row();
    }

    // ─── Statistics ──────────────────────────────────────

    public function get_next_reunion()
    {
        $this->db->select('reunions.*, users.users_nom, users.users_prenom, users.users_username')
                 ->from('reunions')
                 ->join('users', 'users.users_id = reunions.created_by', 'left')
                 ->where('reunions.scheduled_at >=', date('Y-m-d H:i:s'))
                 ->where_in('reunions.status', array('planned', 'in_progress'))
                 ->order_by('reunions.scheduled_at', 'ASC')
                 ->limit(1);
        return $this->db->get()->row();
    }

    public function count_today()
    {
        $today = date('Y-m-d');
        $this->db->where('DATE(scheduled_at)', $today);
        return $this->db->count_all_results('reunions');
    }

    public function count_upcoming()
    {
        $this->db->where('scheduled_at >=', date('Y-m-d H:i:s'))
                 ->where_in('status', array('planned', 'in_progress'));
        return $this->db->count_all_results('reunions');
    }

    public function count_total()
    {
        return $this->db->count_all_results('reunions');
    }

    public function count_by_status($status)
    {
        $this->db->where('status', $status);
        return $this->db->count_all_results('reunions');
    }

    public function get_user_reunions($user_id)
    {
        $this->db->select('reunions.*, users.users_nom, users.users_prenom, users.users_username')
                 ->from('reunions')
                 ->join('users', 'users.users_id = reunions.created_by', 'left')
                 ->join('reunions_participants', 'reunions_participants.reunion_id = reunions.id AND reunions_participants.user_id = ' . (int) $user_id, 'inner')
                 ->order_by('reunions.scheduled_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_my_reunions($user_id)
    {
        $this->db->select('reunions.*, users.users_nom, users.users_prenom, users.users_username')
                 ->from('reunions')
                 ->join('users', 'users.users_id = reunions.created_by', 'left')
                 ->group_start()
                     ->where('reunions.created_by', (int) $user_id)
                     ->or_where('reunions.id IN (SELECT reunion_id FROM reunions_participants WHERE user_id = ' . (int) $user_id . ')', NULL, false)
                 ->group_end()
                 ->order_by('reunions.scheduled_at', 'DESC');
        return $this->db->get()->result();
    }
}
