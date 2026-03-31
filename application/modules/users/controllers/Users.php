<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');

	

	class Users extends MX_Controller
	{

		/**
		* the class constructor
		*
		* @access public
		* @return void
		*/
		public function __construct(){

			//called the parent constructor
			parent::__construct();
			//load the users model class
			$this->load->model('Users_model');

			//load the group model class
			$this->load->model('Group_model');

			//load the users group model class
			$this->load->model('Users_group_model');
			$this->load->model('Message_model');
			$this->load->model('History_model');
		}


		/**
		* the default method called if no method is given
		*
		* @access public
		* @return void
		*/
		public function index(){
			has_access('user');

			$all_users = $this->Users_model->get_all();
			$data['liste_users'] = $all_users;

			// Stats for dashboard
			$total = is_array($all_users) ? count($all_users) : 0;
			$online = 0;
			$locked = 0;
			if($total > 0) {
				foreach($all_users as $u) {
					if($u->etat_online == 1) $online++;
					if($u->etat_compte == 0) $locked++;
				}
			}
			$data['stats'] = array(
				'total' => $total,
				'online' => $online,
				'locked' => $locked,
				'groups' => count($this->Group_model->get_all())
			);

			$this->load->view('header');
			$this->load->view('index', $data);
			$this->load->view('footer');
		}

		public function history(){
			has_access('view_history');
			$this->load->model('History_model');
			$date = date('Y-m-d', time());
			$critera = 0;
			$users_id = null; 
			if($jours = $this->input->get('jours')){
				$date = $jours;
			}
			else if($mois = $this->input->get('mois')){
				$date = $mois;
				$critera = 1;
			}
			else if($u = $this->input->get('utilisateur')){
					$users_id = $u;
					$annee = $this->input->get('annee');
					$date = $annee;
					$critera = 2;
		   }

			$tab = explode('-', $date);
			$tab = array_reverse($tab);
			$str = implode('/', $tab);
			$session = $this->session->userdata('users');

			$data['date'] = $str;
			
            $str_utilisateur = null;
		   if($users_id != null){
			$users = $this->Users_model->get($users_id);
				if($users){
					$str_utilisateur = $users->users_nom.' '.$users->users_prenom;
				}
				else{
					$str_utilisateur = null;
				}
		  }
		  
		  //var_dump($str_utilisateur);exit();
		  
		    $data['user_trace'] = $str_utilisateur;
		
		  if(!empty($users)){
		    $data['user'] = $users;
		   }
			$this->load->library('pagination');
			$config = array(
							'base_url'          => site_url('users/history'),
							'total_rows'        => $this->History_model->getTotalHistories($date),
							'per_page'          => 150,
							'num_links'         => 4,
							'uri_segment'       => 3,
							'reuse_query_string' => true,
							'query_string_segment' => 'page',
							'full_tag_open'     => '<ul class = "pagination">',
							'full_tag_close'     => '</ul>',
							'next_link'     => 'Suiv.',
							'prev_link'     => 'Préc.',
							'num_tag_open' => '<li>',
							'num_tag_close' => '</li>',
							'prev_tag_open' => '<li>',
							'prev_tag_close' => '</li>',
							'next_tag_open' => '<li>',
							'next_tag_close' => '</li>',
							'cur_tag_open' => '<li class = "active"><a href = "#">',
							'cur_tag_close' => '</a></li>',
							'last_link' => null,
							'first_link' => null
						);
			$this->pagination->initialize($config);
			 $data['utilisateurs'] = $this->Users_model->order_by('users_nom, users_prenom')->get_all();
			
		  if(!empty($users)){
		    $data['user'] = $users;
		   }
		   
			$data['liste_history'] = $this->History_model->getAllHistories($date,$str_utilisateur,$config['per_page'], $this->uri->segment(3));
			$this->load->view('header');
			$this->load->view('history', $data);
			$this->load->view('footer');
		}

		public function download_history($users_id){
			has_access('view_history');
			$this->load->model('History_model');
			$date = date('Y-m-d', time());
			$critera = 0;
			if($jours = $this->input->get('jours')){
				$date = $jours;
			}
			else if($mois = $this->input->get('mois')){
				$date = $mois;
				$critera = 1;
			}

			else if($annee = $this->input->get('annee')){
				$date = $annee;
				$critera = 2;
			}

			$tab = explode('-', $date);
			$tab = array_reverse($tab);
			$str = implode('/', $tab);
			$session = $this->session->userdata('users');

			$data['date'] = $str;

			$str_utilisateur = null;
			
		   if($users_id != null){
			$users = $this->Users_model->get($users_id);
				if($users){
					$str_utilisateur = $users->users_nom.' '.$users->users_prenom;
				}
				else{
					$str_utilisateur = null;
				}
		    }
			
			$data['liste_history'] = $this->History_model->getAllHistories_download($date,$str_utilisateur);
			$data['users'] = $users;
			
			/* hack php.ini pour des gros fichiers */
			set_time_limit(0); //pas de limite du temps d'execution
			/*******************************************************/
			$this->load->view('users/history_pdf', $data);
			// Get output html
			$html = $this->output->get_output();
			// Load library
			$this->load->library('dompdf_gen');
			$this->dompdf->set_paper("a4", "landscape");
			// Convert to PDF
			$this->dompdf->load_html($html);
			$this->dompdf->render();


			$this->dompdf->stream('journal_systeme_'.$str.'.pdf');
		}

   
		public function add(){
			has_access('user');
            
			check();
		
			$data['liste_group'] = $this->Group_model->get_all();
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<p class = "error">', '</p>');

			$this->form_validation->set_rules('users_username', 'nom d\'utilisateur', 'trim|required|min_length[3]|alpha_dash|is_unique[users.users_username]');
			$this->form_validation->set_rules('users_email', 'E-mail', 'trim|required|valid_email|is_unique[users.users_email]');
			$this->form_validation->set_rules('users_nom', 'nom', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('users_prenom', 'prénom', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('users_role', 'fonction', 'trim');


			if($this->form_validation->run() === FALSE)
			{
				$this->load->view('header');
				$this->load->view('add', $data);
				$this->load->view('footer');
			}
			else
			{
				$users_username = $this->input->post('users_username');
				$users_email =  $this->input->post('users_email');
				$users_role =  $this->input->post('users_role');
				$group_ids =  $this->input->post('group_ids');
				$users_password =  'passer';
				$users_nom =  strtoupper($this->input->post('users_nom'));
				$users_prenom =  ucfirst(strtolower($this->input->post('users_prenom')));
				$users_password_hach = md5($users_password);
                $photo_profil = 'user.jpg';
				
				if(empty($group_ids)){
					$this->session->set_flashdata('error', 'Vous devez selectionner au moins un groupe pour cet utilisateur');
					$this->load->view('header');
					$this->load->view('add', $data);
					$this->load->view('footer');
				}
				else{

					$params = array(
						'users_username' => $users_username,
						'users_email' => $users_email,
						'users_password' => $users_password_hach,
						'users_nom' => $users_nom,
						'users_prenom' => $users_prenom,
						'users_role' => $users_role,
						'etat_compte' => 1,
						'photo_profil' => $photo_profil,
					);

					$users_id = $this->Users_model->insert($params);

					if($users_id)
					{
						$this->Users_group_model->delete_by('users_id', $users_id);
						foreach($group_ids as $group_id){
							$this->Users_group_model->insert(array('users_id' => $users_id, 'group_id' => $group_id));
						}
						
                        /************************** log history *****************************************************************/
						$session = $this->session->userdata('users');
						log_history('Création de l\'utilisateur '.$users_nom.' '.$users_prenom, $session);
						/********************************************************************************************************/

						$this->session->set_flashdata('success', 'Le compte utilisateur a été créé avec succès le mot de passe par défaut est <b>'.$users_password.'</b>');
						redirect('users');
					}
					else
					{
						$this->session->set_flashdata('error', 'Une erreur est survenue lors de la création du compte');
						$this->load->view('header');
						$this->load->view('add', $data);
						$this->load->view('footer');
					}
				}

			}

		}

		/**
		* the authentification method to login the user
		*
		* @access public
		* @return void
		*/
		public function login(){
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<p class = "error">', '</p>');

			$this->form_validation->set_rules('users_username', 'nom d\'utilisateur ou ', 'trim|required');
			$this->form_validation->set_rules('users_password', 'mot de passe', 'required');
			if($this->form_validation->run() === FALSE)
			{
				$this->load->view('login');
			}
			else
			{
				$users_username = $this->input->post('users_username');
				$users_password =  $this->input->post('users_password');
				$users_password_hash = md5($users_password);

			if(!$this->Users_model->get_by('users_username', $users_username) && !$this->Users_model->get_by('users_email', $users_username)){
					$this->session->set_flashdata('error', 'Ce nom d\'utilisateur ou n\'existe pas veuillez rééssayer');
					$this->load->view('login');
				 }
				 else if(
					 	!$this->Users_model->get_by(array('users_username' => $users_username, 'users_password' => $users_password_hash))
						&&
						!$this->Users_model->get_by(array('users_email' => $users_username, 'users_password' => $users_password_hash))
					){
					$this->session->set_flashdata('error', 'Vous avez oublié votre mot de passe !');
					$this->load->view('login');
				 }
				 else{
					 $user = $this->Users_model->get_by('users_username', $users_username);
					 
					 if(!$user){
						 $user = $this->Users_model->get_by('users_email', $users_username);
					 }
					 $permissions = $this->Users_model->get_all_permissions($user->users_id);
					 $user->permissions = $permissions;
					 $this->session->set_userdata('users', $user);
                     if($user->etat_compte!=1){
						 $this->session->set_flashdata('error', 'Désolé votre compte est désactivé, veuillez contacter l\'adminsitrateur');
					     redirect('users/login');
					 }
					/************************** log history *****************************************************************/
					$session = $this->session->userdata('users');
					 $params = array(
						  'etat_online' => 1
						  );
				     $this->Users_model->update($session->users_id,$params);
				  
					 $session = $this->session->userdata('users');
					 log_history('Connexion de l\'utilisateur '.$user->users_nom.' '.$user->users_prenom, $session);
					 /********************************************************************************************************/
					 
					
					 if($users_password == 'passer'){
						$this->session->set_flashdata('warning', 'Veuillez changer votre mot de passe car celui que vous utilisez est celui par défaut qui est "passer"');
						redirect('users/edit');
					 }

					 $next = isset($_GET['next'])?$_GET['next']:'';
					 if($next){
						 redirect($next);
					 }
					redirect(base_url());
					
				}
			}
		}
		
		
		

		public function edit(){
			check();
			has_access('edit_user');
			$session = $this->session->userdata('users');
			$data['users'] = $this->Users_model->get($session->users_id);
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<p class = "error">', '</p>');

			$this->form_validation->set_rules('users_username', 'nom d\'utilisateur', 'trim|required|min_length[3]|alpha_dash');
			$this->form_validation->set_rules('users_email', 'E-mail', 'trim|required|valid_email');
			$this->form_validation->set_rules('users_nom', 'nom', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('users_prenom', 'prénom', 'trim|required|min_length[3]');


			if($this->form_validation->run() === FALSE)
			{
				$this->load->view('header');
				$this->load->view('edit', $data);
				$this->load->view('footer');
			}
			else
			{
				$users_username = $this->input->post('users_username');
				$users_email =  $this->input->post('users_email');
				$users_password =  $this->input->post('users_password');
				$users_nom =  strtoupper($this->input->post('users_nom'));
				$users_prenom =  ucfirst(strtolower($this->input->post('users_prenom')));
				$users_password_hach = md5($users_password);
                $photo_profil = $this->input->post('photo_profil');
				
				$rec = date('Y');
				$ok = date('H:i');
				$id = $session->users_id;
				$date_gen = date('dmYHi');
					
				$params = array(
					'users_username' => $users_username,
					'users_email' => $users_email,
					'users_nom' => $users_nom,
					'users_prenom' => $users_prenom,
					'photo_profil' => $photo_profil,
				);
				
				    $config['upload_path']          = './assets/img/avatar/';
					$config['allowed_types']        = 'png|jpg|jpeg';
					$config['file_ext_tolower']        = true;
					$config['file_name']        = url_title($id.'_'.$date_gen, '-', true);
					$config['overwrite']        = true;
					$this->load->library('upload', $config);
					
					if($this->upload->do_upload('photo_profil')){
						$path='./assets/img/avatar/';
						$file_data =  $this->upload->data();
						$params['photo_profil'] = $file_data['file_name'];
					} 

				if(!empty($users_password)){
					$params['users_password'] = $users_password_hach;
				}

				$update = $this->Users_model->update($session->users_id, $params);

				if($update)
				{
					$user = $this->Users_model->get($session->users_id);
					$this->session->set_flashdata('success', 'Votre compte a été modifié avec succès');
					$this->session->unset_userdata('users');
					$this->session->set_userdata('users', $user);

					/************************** log history *****************************************************************/
					$session = $this->session->userdata('users');
					log_history('Modification de compte de l\'utilisateur '.$session->users_nom.' '.$session->users_prenom, $session);
					/********************************************************************************************************/

					redirect(base_url());
				}
				else
				{
					$this->session->set_flashdata('error', 'Une erreur est survenue lors de la modification de votre compte');
					$this->load->view('header');
					$this->load->view('edit', $data);
					$this->load->view('footer');
				}

			}

		}


		public function update($users_id){
			has_access('user');
			$session = $this->session->userdata('users');

			$user = $this->Users_model->get($users_id);
			if(!$user){
				$this->session->set_flashdata('error', 'Cet utilisateur n\'existe pas');
				redirect('users');
			}

			$users_group = $this->Users_group_model->get_many_by('users_id', $users_id);
			$liste_users_group = array();
			foreach($users_group as $g){
				$liste_users_group[] = $g->group_id;
			}
			
			$data['liste_users_group'] = $liste_users_group;
			$data['liste_group'] = $this->Group_model->get_all();
			$data['users'] = $user;
			
		   
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<p class = "error">', '</p>');

			$this->form_validation->set_rules('users_username', 'nom d\'utilisateur', 'trim|required|min_length[3]|alpha_dash');
			$this->form_validation->set_rules('users_email', 'E-mail', 'trim|required|valid_email');
			$this->form_validation->set_rules('users_nom', 'nom', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('users_prenom', 'prénom', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('users_role', 'fonction', 'trim');

			if($this->form_validation->run() === FALSE)
			{
				$this->load->view('header');
				$this->load->view('update', $data);
				$this->load->view('footer');
			}
			else
			{
				$users_username = $this->input->post('users_username');
				$users_email =  $this->input->post('users_email');
				$users_role =  $this->input->post('users_role');	
				
				$users_password =  $this->input->post('users_password');
				$users_password_hach = md5($users_password);
				$group_ids =  $this->input->post('group_ids');
				$users_nom =  strtoupper($this->input->post('users_nom'));
				$users_prenom =  ucfirst(strtolower($this->input->post('users_prenom')));

				if(empty($group_ids)){
					$this->session->set_flashdata('error', 'Vous devez selectionner au moins un groupe pour cet utilisateur');
					$this->load->view('header');
					$this->load->view('update', $data);
					$this->load->view('footer');
				}
				else{
					$params = array(
						'users_username' => $users_username,
						'users_email' => $users_email,
						'users_nom' => $users_nom,
						'users_prenom' => $users_prenom,
						'users_role' => $users_role,
					);

					if(!empty($users_password)){
						$params['users_password'] = $users_password_hach;
					}

					$update = $this->Users_model->update($users_id, $params);

					if($update)
					{
						$this->Users_group_model->delete_by('users_id', $users_id);
						foreach($group_ids as $group_id){
							$this->Users_group_model->insert(array('users_id' => $users_id, 'group_id' => $group_id));
						}

						/************************** log history *****************************************************************/
						log_history('Modification de compte de l\'utilisateur '.$user->users_nom.' '.$user->users_prenom, $session);
						/********************************************************************************************************/

						$this->session->set_flashdata('success', 'Le compte a été modifié avec succès');
						if($users_id == $session->users_id){
							$this->session->set_flashdata('success', 'Votre compte a été modifié avec succès');
							$user = $this->Users_model->get($session->users_id);
							$this->session->unset_userdata('users');
							$this->session->set_userdata('users', $user);
							redirect(base_url());
						}
						redirect('users/');
					}
					else
					{
						$this->session->set_flashdata('error', 'Une erreur est survenue lors de la modification du compte');
						$this->load->view('header');
						$this->load->view('update', $data);
						$this->load->view('footer');
					}
				}
			}

		}


		public function delete($users_id){
			has_access('user');

			$session = $this->session->userdata('users');
			$users = $this->Users_model->get($users_id);
			if(!$users){
				$this->session->set_flashdata('error', 'Cet utilisateur n\'existe pas');
				redirect('users');
			}
			else if($users_id == $session->users_id){
				$this->session->set_flashdata('error', 'Vous ne pouvez pas supprimer votre propre compte');
				redirect('users');
			}

			$delete = $this->Users_model->delete($users_id);

			if($delete){
				/************************** log history *****************************************************************/
				$session = $this->session->userdata('users');
				log_history('Suppression de l\'utilisateur '.$users->users_nom.' '.$users->users_prenom, $session);
				/********************************************************************************************************/
				$this->session->set_flashdata('success', 'Le compte utilisateur a été supprimé avec succès');
				redirect('users');
			}
		}

		public function group(){
			has_access('group');

			$data['liste_groups'] = $this->Group_model->get_all();
			$this->load->view('header');
			$this->load->view('group/index', $data);
			$this->load->view('footer');
		}


		public function add_group(){
			has_access('group');

			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<p class = "error">', '</p>');

			$this->form_validation->set_rules('group_name', 'nom du groupe', 'trim|required|min_length[2]|is_unique[group.group_name]');


			if($this->form_validation->run() === FALSE)
			{
				$this->load->view('header');
				$this->load->view('group/add');
				$this->load->view('footer');
			}
			else{
				$group_name = $this->input->post('group_name');
				$group_permissions =  $this->input->post('group_permissions');


				$params = array(
					'group_name' => $group_name,
				);

				if(!empty($group_permissions)){
					foreach($group_permissions as $permission){
						$params[$permission] = 1;
					}
				}

				$group_id = $this->Group_model->insert($params);

				if($group_id)
				{
					/************************** log history *****************************************************************/
					$session = $this->session->userdata('users');
					log_history('Création de groupe '.$group_name, $session);
					/********************************************************************************************************/
					$this->session->set_flashdata('success', 'Le groupe a été ajouté avec succès');
					redirect('users/group');
				}
				else
				{
					$this->session->set_flashdata('error', 'Une erreur est survenue lors de l\'ajout du groupe');
					$this->load->view('header');
					$this->load->view('group/add');
					$this->load->view('footer');
				}
		}


		}

		public function update_group($group_id){
			has_access('group');

			$session = $this->session->userdata('users');

			$group = $this->Group_model->get($group_id);
			if(!$group){
				$this->session->set_flashdata('error', 'Ce groupe n\'existe pas');
				redirect('users/group');
			}

			if($group->is_system == 1){
				$this->session->set_flashdata('error', 'Vous ne pouvez pas modifier un groupe système');
				redirect('users/group');
			}
			$data['group'] = $group;
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<p class = "error">', '</p>');

			$this->form_validation->set_rules('group_name', 'nom du groupe', 'trim|required|min_length[2]');


			if($this->form_validation->run() === FALSE)
			{
				$this->load->view('header');
				$this->load->view('group/update', $data);
				$this->load->view('footer');
			}
			else{
				$group_name = $this->input->post('group_name');
				$group_permissions =  $this->input->post('group_permissions');


				$params = array(
					'group_name' => $group_name,
				);

				foreach(get_all_permissions() as $key => $value){
					$params[$key] = 0;
				}

				if(!empty($group_permissions)){
					foreach($group_permissions as $permission){
						$params[$permission] = 1;
					}
				}

				$update = $this->Group_model->update($group_id, $params);

				if($update)
				{
					/************************** log history *****************************************************************/
					$session = $this->session->userdata('users');
					log_history('Modification du groupe  '.$group->group_name, $session);
					/********************************************************************************************************/
					$this->session->set_flashdata('success', 'Le groupe a été modifié avec succès');
					redirect('users/group');
				}
				else
				{
					$this->session->set_flashdata('error', 'Une erreur est survenue lors de la modification du groupe');
					$this->load->view('header');
					$this->load->view('group/update', $data);
					$this->load->view('footer');
				}
			}
		}


		public function group_permissions($group_id){
			has_access('group');

			$session = $this->session->userdata('users');

			$group = $this->Group_model->get($group_id);
			if(!$group){
				$this->session->set_flashdata('error', 'Ce groupe n\'existe pas');
				redirect('users/group');
			}
			$data['group'] = $group;
			$this->load->view('header');
			$this->load->view('group/permissions', $data);
			$this->load->view('footer');

		}

		public function delete_group($group_id){
			has_access('group');

			$session = $this->session->userdata('users');

			$group = $this->Group_model->get($group_id);
			if(!$group){
				$this->session->set_flashdata('error', 'Ce groupe n\'existe pas');
				redirect('users/group');
			}

			if($group->is_system == 1){
				$this->session->set_flashdata('error', 'Vous ne pouvez pas supprimer un groupe système');
				redirect('users/group');
			}

			$delete = $this->Group_model->delete($group_id);

			if($delete){
				/************************** log history *****************************************************************/
				$session = $this->session->userdata('users');
				log_history('Suppression du groupe  '.$group->group_name, $session);
				/********************************************************************************************************/
				$this->session->set_flashdata('success', 'Le groupe utilisateur a été supprimé avec succès');
				redirect('users/group');
			}

		}
	 public function verrouiller_compte_user($users_id)
	   {
		  $user = $this->Users_model->get($users_id);
		  
		  if($user)
		  {
			  $this->load->library('form_validation');			
					  
			  if($this->form_validation->run() === FALSE)
			  {
				  $params = array(
				  'etat_compte' => 0
				  );
				  
				  $this->Users_model->update($users_id,$params);
				  $session = $this->session->userdata('users');
				   /************************** log history *****************************************************************/
						log_history('Verrouillage du compte de l\'utilisateur '.$user->users_nom.' '.$user->users_prenom, $session);
						/********************************************************************************************************/

				   $this->session->set_flashdata('success','Le compte d\'utilisateur ' .$user->users_nom. ' '.$user->users_prenom.' a été verrouillé  avec succès ');
				   redirect('users');
			  }
			  
		  }
		  else
		  {
			  $this->session->set_flashdata('info','Desolé le compte que vous voulez verrouiller n\'existe plus!');
			  redirect('users');
		  }
	  }



	  public function deverrouiller_compte_user($users_id)
		{
			$user = $this->Users_model->get($users_id);

			if($user)
			{
				$this->load->library('form_validation');			
						
				if($this->form_validation->run() === FALSE)
				{

					$params = array(
					'etat_compte' => 1
					);
					
					$this->Users_model->update($users_id,$params);
					
					$session = $this->session->userdata('users');
					 /************************** log history *****************************************************************/
						log_history('Déverrouillage du compte de l\'utilisateur '.$user->users_nom.' '.$user->users_prenom, $session);
						/********************************************************************************************************/

					 $this->session->set_flashdata('success','Le compte de l\'utilisateur ' .$user->users_nom. ' '.$user->users_prenom.' a été déverrouillé  avec succès');
					 redirect('users');
				}
				
			}
			else
			{
				$this->session->set_flashdata('info','Desolé, le compte  d\'utilisateur que vous voulez déverrouillé n\'existe plus!');
				redirect('users');
			}
		}


		/**
		* disconnect the user
		*
		* @access public
		* @return void
		*/
		public function logout()
		{
			/************************** log history *****************************************************************/
			$session = $this->session->userdata('users');
			$params = array(
						  'etat_online' => 0
						  );
		    $this->Users_model->update($session->users_id,$params);
				  
			$session = $this->session->userdata('users');
			log_history('Déconnexion de l\'utilisateur  '.$session->users_nom.' '.$session->users_prenom, $session);
			/********************************************************************************************************/
			$this->session->unset_userdata('users');
			$this->session->set_flashdata('info', 'Vous êtes deconnecté avec succès');
			redirect('users/login');
		}
		

public function envoie_du_nouveau_message()
	{	
		        check();
		        has_access('envoyer_message');
				$this->load->library('form_validation');
				$this->form_validation->set_error_delimiters('<p class = "error">', '</p>');
				$this->form_validation->set_rules('email_expeditaire', 'email Expeditaire', 'trim|valid_email|required');
				$this->form_validation->set_rules('objet_message', 'objet du message', 'trim|required');
				$this->form_validation->set_rules('contenu_message', 'contenu de votre message', 'trim|required');

				if($this->form_validation->run() === FALSE){
					
					$this->load->view('header');
					$this->load->view('users/envoie_du_nouveau_message');
					$this->load->view('footer');
	            }
				
	        else
			{
				$email_expeditaire = $this->input->post('email_expeditaire');
				$objet_message = $this->input->post('objet_message');
				$contenu_message = $this->input->post('contenu_message');
				
			    $session = $this->session->userdata('users');
				$users_id = $session->users_id;
				
				 $params = array(
					'email_impeditaire' => $session->users_email,
					'email_expeditaire' => $email_expeditaire,
					'objet_message' => $objet_message,
					'contenu_message' => $contenu_message,
					'users_id' => $users_id
				);
				
				
				$message_code = $this->Message_model->insert($params);
				
				if($message_code){
					
					/************************** log history *****************************************************************/
						$session = $this->session->userdata('users');
						log_history('Envoi du nouveau message par l\'utilisateur '.$session->users_username.' à l\'email '.$email_expeditaire, $session);
					/********************************************************************************************************/

					$this->session->set_flashdata('success','Votre message a été envoyé avec succès');
					redirect('users/boite_de_reception_des_messages');
				}
				
				else{
					
					$this->session->set_flashdata('error','Une erreur est survenue lors de traitement des données');
					$this->load->view('header');
					$this->load->view('users/envoie_du_nouveau_message');
					$this->load->view('footer');
				}
		    }
    }
	
	
	
	public function boite_de_reception_des_messages()
	
	{
		check();
		has_access('boite_reception');
		$session = $this->session->userdata('users');
		$users_email= $session->users_email;
		 
		/************************** log history *****************************************************************/
			$session = $this->session->userdata('users');
			log_history('Consultation de la boite de receptions des messages de l\'utilisateur '.$session->users_username, $session);
		/********************************************************************************************************/

		$data['les_messages_utilisateur'] = $this->Message_model->les_messages_utilisateur($users_email);
        
		
		$this->load->view('header');
		$this->load->view('users/boite_de_reception_des_messages',$data);
		$this->load->view('footer');
	}
	



//partie traitant la suppression des historiques

  public function remove()
		{
		
			$history_ids = $this->input->post('history_id');
			if(!empty($history_ids)){
				foreach($history_ids as $history_id){
					$this->History_model->delete($history_id);
				}
			}
			
			 /************************** log history *****************************************************************/
				$session = $this->session->userdata('users');
				log_history('Vidage des données de traces des utilisateurs par #'.$session->users_username, $session);
			/********************************************************************************************************/

			$this->session->set_flashdata('success','Ces traces des utilisateurs ont été supprimés avec succès');
			redirect('users/history');
		}

	}


?>
