<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');
	
	class Group_model extends MY_Model{
		
		protected $_table = 'group';
		
		protected $primary_key = 'group_id';
		
		
		public function __construct(){
			parent::__construct();
		}
		
	}


