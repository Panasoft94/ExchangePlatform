<?php
	class History_model extends MY_Model
	{
		protected $_table = 'history';
		protected $primary_key = 'history_id';

		public function __construct()
		{
			parent::__construct();
		}

		public function getAllHistories($date, $str_utilisateur = null, $limit = null, $offset = null){
			$this->db->select('*')
					 ->limit($limit, $offset)
					 ->order_by('history_date', 'desc')
					 ->like('history.history_date', $date, 'after');
			if($str_utilisateur!= null){
				$this->db->where('history.history_users',$str_utilisateur);
			}
			return $this->db->get($this->_table)->result();
		}


	public function getAllHistories_download($date, $str_utilisateur = null, $limit = null, $offset = null){
			$this->db->select('*')
					 ->limit($limit, $offset)
					 ->order_by('history_date', 'desc')
					 ->like('history.history_date', $date, 'after');
			if($str_utilisateur!= null){
				$this->db->where('history.history_users',$str_utilisateur);
			}
			return $this->db->get($this->_table)->result();
	}

		public function getTotalHistories($date){
			$this->db->select('history_id')
					 ->like('history.history_date', $date, 'after');
			return $this->db->get($this->_table)->num_rows();
		}
	}
?>
