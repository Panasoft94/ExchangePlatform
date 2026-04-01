<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Recordings_model extends CI_Model
{
    protected $table = 'reunions_recordings';

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get($id)
    {
        return $this->db->where('id', (int) $id)->get($this->table)->row();
    }

    public function get_by_reunion($reunion_id)
    {
        return $this->db
            ->select('reunions_recordings.*, CONCAT(u.users_prenom, " ", u.users_nom) AS recorder_name')
            ->from($this->table)
            ->join('users u', 'u.users_id = reunions_recordings.user_id', 'left')
            ->where('reunions_recordings.reunion_id', (int) $reunion_id)
            ->order_by('reunions_recordings.created_at', 'DESC')
            ->get()
            ->result();
    }

    public function get_user_recordings($user_id, $limit = 50)
    {
        return $this->db
            ->select('reunions_recordings.*, r.title AS reunion_title, r.scheduled_at AS reunion_date, CONCAT(u.users_prenom, " ", u.users_nom) AS recorder_name')
            ->from($this->table)
            ->join('reunions r', 'r.id = reunions_recordings.reunion_id', 'left')
            ->join('users u', 'u.users_id = reunions_recordings.user_id', 'left')
            ->where('reunions_recordings.reunion_id IN (
                SELECT rp.reunion_id FROM reunions_participants rp WHERE rp.user_id = ' . (int) $user_id . '
                UNION
                SELECT r2.id FROM reunions r2 WHERE r2.created_by = ' . (int) $user_id . '
            )', NULL, FALSE)
            ->order_by('reunions_recordings.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }

    public function delete($id)
    {
        $recording = $this->get($id);
        if ($recording) {
            $filepath = FCPATH . 'assets/uploads/recordings/' . $recording->filename;
            if (file_exists($filepath)) {
                @unlink($filepath);
            }
            $this->db->where('id', (int) $id)->delete($this->table);
            return true;
        }
        return false;
    }

    public function format_file_size($bytes)
    {
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' Go';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 1) . ' Mo';
        if ($bytes >= 1024) return number_format($bytes / 1024, 0) . ' Ko';
        return $bytes . ' o';
    }

    public function format_duration($seconds)
    {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds % 60;
        if ($h > 0) return sprintf('%dh %02dmin %02ds', $h, $m, $s);
        if ($m > 0) return sprintf('%dmin %02ds', $m, $s);
        return sprintf('%ds', $s);
    }
}
