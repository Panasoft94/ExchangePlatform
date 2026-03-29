<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');
	
	class Users_model extends MY_Model{
		
		protected $_table = 'users';
		
		protected $primary_key = 'users_id';
		
		
		public function __construct(){
			parent::__construct();
		}
		
		
		public function get_all_permissions($users_id){
			$this->db->select('group.*')
					  ->join('users_group', 'users_group.users_id = users.users_id')
					  ->join('group', 'users_group.group_id = group.group_id');
			return $this->db->get_where('users', array('users.users_id' => $users_id))->result_array();
		}
	}


