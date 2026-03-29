<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reunions_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all reunions ordered by scheduled_at DESC, joined with users for creator name
     */
    public function get_all()
    {
        $this->db->select('reunions.*, users.users_nom, users.users_prenom, users.users_username')
                 ->from('reunions')
                 ->join('users', 'users.users_id = reunions.created_by', 'left')
                 ->order_by('reunions.scheduled_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Get reunions where scheduled_at >= NOW(), ordered ASC
     */
    public function get_upcoming()
    {
        $this->db->select('reunions.*, users.users_nom, users.users_prenom, users.users_username')
                 ->from('reunions')
                 ->join('users', 'users.users_id = reunions.created_by', 'left')
                 ->where('reunions.scheduled_at >=', date('Y-m-d H:i:s'))
                 ->order_by('reunions.scheduled_at', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Get reunions where scheduled_at < NOW(), ordered DESC
     */
    public function get_past()
    {
        $this->db->select('reunions.*, users.users_nom, users.users_prenom, users.users_username')
                 ->from('reunions')
                 ->join('users', 'users.users_id = reunions.created_by', 'left')
                 ->where('reunions.scheduled_at <', date('Y-m-d H:i:s'))
                 ->order_by('reunions.scheduled_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Get reunions scheduled today
     */
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

    /**
     * Get single reunion by ID, joined with users for creator name
     */
    public function get($id)
    {
        $this->db->select('reunions.*, users.users_nom, users.users_prenom, users.users_username')
                 ->from('reunions')
                 ->join('users', 'users.users_id = reunions.created_by', 'left')
                 ->where('reunions.id', $id);
        return $this->db->get()->row();
    }

    /**
     * Insert reunion, return insert_id
     */
    public function create($data)
    {
        $this->db->insert('reunions', $data);
        return $this->db->insert_id();
    }

    /**
     * Update reunion
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('reunions', $data);
    }

    /**
     * Delete reunion + its participants
     */
    public function delete($id)
    {
        $this->remove_all_participants($id);
        $this->db->where('id', $id);
        return $this->db->delete('reunions');
    }

    /**
     * Get participants with user info (joined with users table)
     */
    public function get_participants($reunion_id)
    {
        $this->db->select('reunions_participants.*, users.users_id, users.users_nom, users.users_prenom, users.users_username, users.users_email, users.users_role, users.photo_profil')
                 ->from('reunions_participants')
                 ->join('users', 'users.users_id = reunions_participants.user_id', 'left')
                 ->where('reunions_participants.reunion_id', $reunion_id);
        return $this->db->get()->result();
    }

    /**
     * Batch insert participants
     */
    public function add_participants($reunion_id, $user_ids)
    {
        if (empty($user_ids) || !is_array($user_ids)) {
            return false;
        }
        $batch = array();
        foreach ($user_ids as $user_id) {
            $batch[] = array(
                'reunion_id' => (int) $reunion_id,
                'user_id'    => (int) $user_id,
                'status'     => 'invited'
            );
        }
        return $this->db->insert_batch('reunions_participants', $batch);
    }

    /**
     * Delete all participants for a reunion
     */
    public function remove_all_participants($reunion_id)
    {
        $this->db->where('reunion_id', $reunion_id);
        return $this->db->delete('reunions_participants');
    }

    /**
     * Get the next upcoming reunion (single row)
     */
    public function get_next_reunion()
    {
        $this->db->select('reunions.*, users.users_nom, users.users_prenom, users.users_username')
                 ->from('reunions')
                 ->join('users', 'users.users_id = reunions.created_by', 'left')
                 ->where('reunions.scheduled_at >=', date('Y-m-d H:i:s'))
                 ->order_by('reunions.scheduled_at', 'ASC')
                 ->limit(1);
        return $this->db->get()->row();
    }

    /**
     * Count today's reunions
     */
    public function count_today()
    {
        $today = date('Y-m-d');
        $this->db->where('DATE(scheduled_at)', $today);
        return $this->db->count_all_results('reunions');
    }

    /**
     * Count upcoming reunions
     */
    public function count_upcoming()
    {
        $this->db->where('scheduled_at >=', date('Y-m-d H:i:s'));
        return $this->db->count_all_results('reunions');
    }
}
