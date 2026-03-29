<?php
	if(!function_exists('has_access')){
		function has_access($name, $msg = null){
			check();
			$access = is_allowed($name);
			$obj = & get_instance();
			if(!$access){
				$str = 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page';
				if($msg){
					$str = $msg;
				}
				$obj->session->set_flashdata('error', $str);
				redirect(base_url());
			}

		}
	}

	if(!function_exists('is_allowed')){
		function is_allowed($name){
			$access = false;
			$obj = & get_instance();
			$session = $obj->session->userdata('users');
			if(!$session){
				return;
			}
			$obj->load->model('Users_model');
			$permissions = $obj->Users_model->get_all_permissions($session->users_id);
			foreach($permissions as $permission){
				if(isset($permission[$name]) && $permission[$name] == 1){
					$access = true;
					break;
				}
			}
			return $access;
		}
	}


	if(!function_exists('get_permission_label')){
		function get_permission_label($name, $default = null){
			$permissions = get_all_permissions();
			return isset($permissions[$name])?$permissions[$name]:$default;
		}
	}


	if(!function_exists('get_all_permissions')){
		function get_all_permissions(){
			$permissions = array(
									'user' => 'Gestion des utilisateurs',
									'group' => 'Gestion des groupes utilisateurs',

									'view_history' => 'Afficher l\'historique des actions',
								);
			return $permissions;
		}
	}


	if(!function_exists('check')){
		function check($msg = null){
			$isLogin = false;
			$obj = & get_instance();
			$users = $obj->session->userdata('users');
			$isLogin = !empty($users)
			&& isset($users->users_username)
			&& isset($users->users_id)
			&& isset($users->users_email);

			if(!$isLogin){
				$str = 'Veuillez vous connecter pour accéder à cette page';
				if($msg){
					$str = $msg;
				}
				$obj->session->set_flashdata('info', $str);
				$next = current_url();
				redirect('users/login?next='.$next);
			}
		}
	}
?>
