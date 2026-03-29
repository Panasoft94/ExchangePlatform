<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Documents_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all documents joined with users for uploader name, ordered by created_at DESC
     */
    public function get_all()
    {
        $this->db->select('documents.*, users.users_nom, users.users_prenom')
                 ->from('documents')
                 ->join('users', 'users.users_id = documents.uploaded_by', 'left')
                 ->order_by('documents.created_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Get single document by ID with uploader info
     */
    public function get($id)
    {
        $this->db->select('documents.*, users.users_nom, users.users_prenom')
                 ->from('documents')
                 ->join('users', 'users.users_id = documents.uploaded_by', 'left')
                 ->where('documents.id', $id);
        return $this->db->get()->row();
    }

    /**
     * Get documents uploaded by specific user
     */
    public function get_by_user($user_id)
    {
        $this->db->select('documents.*, users.users_nom, users.users_prenom')
                 ->from('documents')
                 ->join('users', 'users.users_id = documents.uploaded_by', 'left')
                 ->where('documents.uploaded_by', $user_id)
                 ->order_by('documents.created_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Insert document, return insert_id
     */
    public function create($data)
    {
        $this->db->insert('documents', $data);
        return $this->db->insert_id();
    }

    /**
     * Delete document record
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('documents');
    }

    /**
     * Count total documents
     */
    public function count_all()
    {
        return $this->db->count_all('documents');
    }

    /**
     * Search documents by filename (LIKE query)
     */
    public function search($keyword)
    {
        $this->db->select('documents.*, users.users_nom, users.users_prenom')
                 ->from('documents')
                 ->join('users', 'users.users_id = documents.uploaded_by', 'left')
                 ->like('documents.filename', $keyword)
                 ->order_by('documents.created_at', 'DESC');
        return $this->db->get()->result();
    }
}
