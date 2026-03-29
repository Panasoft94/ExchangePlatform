<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');
	
	class Users_group_model extends MY_Model{
		
		protected $_table = 'users_group';
		
		protected $primary_key = 'users_group_id';
		protected $has_many = array('users', 'group');
		
		
		public function __construct(){
			parent::__construct();
		}
		
	}


