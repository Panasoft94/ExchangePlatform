<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Documents_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // ── Core Queries ──────────────────────────────────────

    private function _base_select()
    {
        $this->db->select('documents.*, users.users_nom, users.users_prenom, users.photo_profil, document_categories.name as category_name, document_categories.color as category_color, document_categories.icon as category_icon')
                 ->from('documents')
                 ->join('users', 'users.users_id = documents.uploaded_by', 'left')
                 ->join('document_categories', 'document_categories.id = documents.category_id', 'left');
        return $this;
    }

    public function get_all($filters = array())
    {
        $this->_base_select();
        $this->_apply_filters($filters);
        $this->db->order_by('documents.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_accessible($user_id, $filters = array())
    {
        $this->_base_select();

        // Public documents OR owned by user OR shared with user
        $this->db->group_start()
                 ->where('documents.visibility', 'public')
                 ->or_where('documents.uploaded_by', (int) $user_id)
                 ->or_where('documents.id IN (SELECT document_id FROM document_shares WHERE shared_with = ' . (int) $user_id . ')', NULL, FALSE)
                 ->group_end();

        $this->_apply_filters($filters);
        $this->db->order_by('documents.created_at', 'DESC');
        return $this->db->get()->result();
    }

    private function _apply_filters($filters)
    {
        if (!empty($filters['search'])) {
            $this->db->group_start()
                     ->like('documents.filename', $filters['search'])
                     ->or_like('documents.description', $filters['search'])
                     ->group_end();
        }
        if (!empty($filters['visibility'])) {
            $this->db->where('documents.visibility', $filters['visibility']);
        }
        if (!empty($filters['category_id'])) {
            $this->db->where('documents.category_id', (int) $filters['category_id']);
        }
        if (!empty($filters['uploaded_by'])) {
            $this->db->where('documents.uploaded_by', (int) $filters['uploaded_by']);
        }
        if (!empty($filters['file_type'])) {
            $this->db->where('documents.file_type', $filters['file_type']);
        }
    }

    public function get($id)
    {
        $this->_base_select();
        $this->db->where('documents.id', (int) $id);
        return $this->db->get()->row();
    }

    public function get_by_user($user_id)
    {
        $this->_base_select();
        $this->db->where('documents.uploaded_by', (int) $user_id)
                 ->order_by('documents.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function create($data)
    {
        $this->db->insert('documents', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', (int) $id);
        return $this->db->update('documents', $data);
    }

    public function delete($id)
    {
        // Also delete shares
        $this->db->where('document_id', (int) $id)->delete('document_shares');
        $this->db->where('id', (int) $id);
        return $this->db->delete('documents');
    }

    public function increment_downloads($id)
    {
        $this->db->set('download_count', 'download_count + 1', FALSE);
        $this->db->where('id', (int) $id);
        return $this->db->update('documents');
    }

    // ── Statistics ───────────────────────────────────────

    public function count_all()
    {
        return $this->db->count_all('documents');
    }

    public function count_by_visibility($visibility)
    {
        return $this->db->where('visibility', $visibility)->count_all_results('documents');
    }

    public function count_by_user($user_id)
    {
        return $this->db->where('uploaded_by', (int) $user_id)->count_all_results('documents');
    }

    public function total_size()
    {
        $this->db->select_sum('file_size');
        $row = $this->db->get('documents')->row();
        return $row ? (int) $row->file_size : 0;
    }

    public function stats_by_type()
    {
        $this->db->select('file_type, COUNT(*) as count')
                 ->from('documents')
                 ->where('file_type IS NOT NULL', NULL, FALSE)
                 ->group_by('file_type')
                 ->order_by('count', 'DESC');
        return $this->db->get()->result();
    }

    // ── Categories ──────────────────────────────────────

    public function get_categories()
    {
        return $this->db->order_by('name', 'ASC')->get('document_categories')->result();
    }

    public function get_category($id)
    {
        return $this->db->where('id', (int) $id)->get('document_categories')->row();
    }

    public function create_category($data)
    {
        $this->db->insert('document_categories', $data);
        return $this->db->insert_id();
    }

    public function update_category($id, $data)
    {
        $this->db->where('id', (int) $id);
        return $this->db->update('document_categories', $data);
    }

    public function delete_category($id)
    {
        // Set documents in this category to NULL
        $this->db->where('category_id', (int) $id)->update('documents', array('category_id' => NULL));
        $this->db->where('id', (int) $id);
        return $this->db->delete('document_categories');
    }

    public function count_by_category($category_id)
    {
        return $this->db->where('category_id', (int) $category_id)->count_all_results('documents');
    }

    // ── Shares ──────────────────────────────────────────

    public function get_shares($document_id)
    {
        $this->db->select('document_shares.*, users.users_nom, users.users_prenom, users.photo_profil')
                 ->from('document_shares')
                 ->join('users', 'users.users_id = document_shares.shared_with', 'left')
                 ->where('document_shares.document_id', (int) $document_id)
                 ->order_by('document_shares.created_at', 'ASC');
        return $this->db->get()->result();
    }

    public function add_share($document_id, $user_id, $shared_by, $can_download = 1)
    {
        $exists = $this->db->where('document_id', (int) $document_id)
                           ->where('shared_with', (int) $user_id)
                           ->count_all_results('document_shares');
        if ($exists > 0) return false;

        $this->db->insert('document_shares', array(
            'document_id' => (int) $document_id,
            'shared_with' => (int) $user_id,
            'shared_by'   => (int) $shared_by,
            'can_download' => $can_download ? 1 : 0
        ));
        return $this->db->insert_id();
    }

    public function remove_share($document_id, $user_id)
    {
        $this->db->where('document_id', (int) $document_id)
                 ->where('shared_with', (int) $user_id);
        return $this->db->delete('document_shares');
    }

    public function is_shared_with($document_id, $user_id)
    {
        return $this->db->where('document_id', (int) $document_id)
                        ->where('shared_with', (int) $user_id)
                        ->count_all_results('document_shares') > 0;
    }

    public function can_user_access($document_id, $user_id)
    {
        $doc = $this->db->select('uploaded_by, visibility')
                        ->where('id', (int) $document_id)
                        ->get('documents')->row();
        if (!$doc) return false;
        if ($doc->visibility === 'public') return true;
        if ((int) $doc->uploaded_by === (int) $user_id) return true;
        return $this->is_shared_with($document_id, $user_id);
    }

    // ── Recent Documents ────────────────────────────────

    public function get_recent($user_id, $limit = 5)
    {
        $this->_base_select();
        $this->db->group_start()
                 ->where('documents.visibility', 'public')
                 ->or_where('documents.uploaded_by', (int) $user_id)
                 ->or_where('documents.id IN (SELECT document_id FROM document_shares WHERE shared_with = ' . (int) $user_id . ')', NULL, FALSE)
                 ->group_end();
        $this->db->order_by('documents.created_at', 'DESC')
                 ->limit((int) $limit);
        return $this->db->get()->result();
    }
}
