<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');
	
	class Message_model extends MY_Model{
		
		protected $_table = 'message';
		
		protected $primary_key = 'message_code';
		
		public function __construct(){
			
			parent::__construct();
			
		}
		
		
		
		public function les_messages_utilisateur($users_email){
				 $this->db->select('*');
				 $this->db->from('message');
				 $this->db->join('users','users.users_id=message.users_id');
				 $this->db->where('message.email_expeditaire',$users_email);
			return $this->db->get()->result(); 
		}
		
		
	
 }


