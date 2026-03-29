<?php

    if(!function_exists('log_history')){
        function log_history($action, $user){
			$obj = & get_instance();
			$obj->load->model('History_model');
			
			$params = array(
					'history_action' => $action,
					'history_users' => ''
				);
			if(isset($user->users_nom) && isset($user->users_prenom)){
				$params['history_users'] = $user->users_nom.' '.$user->users_prenom;
			}
			$obj->History_model->insert($params);
        }
    }



?>
