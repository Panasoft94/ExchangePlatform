<?php
	/**
	 * =======================================================================
	 *  ACL Helper — Gestion centralisée des habilitations
	 * =======================================================================
	 *  Système hiérarchique :
	 *    Super Admin  →  peut tout faire et déléguer les accès
	 *    Admin        →  gère les modules qui lui sont attribués
	 *    Utilisateur  →  accède uniquement à ses propres données
	 *
	 *  Les permissions sont stockées dans la table `group` (colonnes tinyint).
	 *  Un utilisateur hérite des permissions de TOUS ses groupes (union).
	 * =======================================================================
	 */


	/* ---------------------------------------------------------------
	 *  VÉRIFICATION DE CONNEXION
	 * --------------------------------------------------------------- */
	if(!function_exists('check')){
		function check($msg = null){
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


	/* ---------------------------------------------------------------
	 *  CONTRÔLE D'ACCÈS — bloque et redirige si non autorisé
	 * --------------------------------------------------------------- */
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


	/* ---------------------------------------------------------------
	 *  VÉRIFICATION DE PERMISSION — retourne true/false
	 *  Utilise un cache en mémoire pour éviter les requêtes multiples
	 * --------------------------------------------------------------- */
	if(!function_exists('is_allowed')){
		function is_allowed($name){
			static $cache = array();

			$obj = & get_instance();
			$session = $obj->session->userdata('users');
			if(!$session || !isset($session->users_id)){
				return false;
			}

			$uid = $session->users_id;

			// Charger les permissions une seule fois par requête
			if(!isset($cache[$uid])){
				$obj->load->model('Users_model');
				$rows = $obj->Users_model->get_all_permissions($uid);
				$merged = array();
				$is_super = false;
				foreach($rows as $row){
					foreach($row as $key => $val){
						if($val == 1){
							$merged[$key] = true;
						}
					}
					if(isset($row->super_admin) && $row->super_admin == 1){
						$is_super = true;
					}
				}
				// Super admin a automatiquement TOUTES les permissions
				if($is_super){
					$all_perms = get_flat_permissions();
					foreach($all_perms as $perm_key => $label){
						$merged[$perm_key] = true;
					}
				}
				$cache[$uid] = $merged;
			}

			return isset($cache[$uid][$name]);
		}
	}


	/* ---------------------------------------------------------------
	 *  SUPER ADMIN — vérifie si l'utilisateur courant est super admin
	 * --------------------------------------------------------------- */
	if(!function_exists('is_super_admin')){
		function is_super_admin(){
			return is_allowed('super_admin');
		}
	}


	/* ---------------------------------------------------------------
	 *  VÉRIFICATION DE PROPRIÉTÉ — L'utilisateur est-il propriétaire ?
	 *  Permet à chaque utilisateur de gérer ses propres données
	 *  sans que d'autres n'y aient accès.
	 * --------------------------------------------------------------- */
	if(!function_exists('is_owner')){
		function is_owner($owner_id){
			$obj = & get_instance();
			$session = $obj->session->userdata('users');
			if(!$session || !isset($session->users_id)){
				return false;
			}
			return ((int) $session->users_id === (int) $owner_id);
		}
	}


	/* ---------------------------------------------------------------
	 *  ACCÈS PROPRIÉTAIRE OU PERMISSION
	 *  Autorise si l'utilisateur est propriétaire OU a la permission
	 * --------------------------------------------------------------- */
	if(!function_exists('can_access_or_owner')){
		function can_access_or_owner($permission, $owner_id){
			return is_owner($owner_id) || is_allowed($permission);
		}
	}


	/* ---------------------------------------------------------------
	 *  CONTRÔLE PROPRIÉTAIRE OU PERMISSION — bloque si ni l'un ni l'autre
	 * --------------------------------------------------------------- */
	if(!function_exists('require_access_or_owner')){
		function require_access_or_owner($permission, $owner_id, $msg = null){
			check();
			if(!can_access_or_owner($permission, $owner_id)){
				$obj = & get_instance();
				$str = $msg ? $msg : 'Vous n\'avez pas les permissions nécessaires pour accéder à cette ressource';
				$obj->session->set_flashdata('error', $str);
				redirect(base_url());
			}
		}
	}


	/* ---------------------------------------------------------------
	 *  OBTENIR L'ID DE L'UTILISATEUR COURANT
	 * --------------------------------------------------------------- */
	if(!function_exists('current_user_id')){
		function current_user_id(){
			$obj = & get_instance();
			$session = $obj->session->userdata('users');
			return ($session && isset($session->users_id)) ? (int) $session->users_id : 0;
		}
	}


	/* ---------------------------------------------------------------
	 *  OBTENIR LA SESSION UTILISATEUR COURANTE
	 * --------------------------------------------------------------- */
	if(!function_exists('current_user')){
		function current_user(){
			$obj = & get_instance();
			return $obj->session->userdata('users');
		}
	}


	/* ---------------------------------------------------------------
	 *  LABEL D'UNE PERMISSION
	 * --------------------------------------------------------------- */
	if(!function_exists('get_permission_label')){
		function get_permission_label($name, $default = null){
			$all = get_all_permissions();
			foreach($all as $category){
				if(isset($category['perms'][$name])){
					return $category['perms'][$name];
				}
			}
			return $default;
		}
	}


	/* ---------------------------------------------------------------
	 *  CATALOGUE COMPLET DES PERMISSIONS — organisé par catégorie
	 *  Chaque clé correspond à une colonne tinyint dans la table `group`
	 * --------------------------------------------------------------- */
	if(!function_exists('get_all_permissions')){
		function get_all_permissions(){
			return array(
				'admin_general' => array(
					'label' => 'Administration Générale',
					'icon'  => 'fa-crown',
					'color' => '#d93025',
					'perms' => array(
						'super_admin'   => 'Super Administrateur (accès total + délégation)',
						'admin'         => 'Administrateur',
					)
				),
				'users_management' => array(
					'label' => 'Gestion des Utilisateurs',
					'icon'  => 'fa-users',
					'color' => '#1a73e8',
					'perms' => array(
						'user'          => 'Voir la liste des utilisateurs',
						'add_user'      => 'Ajouter un utilisateur',
						'edit_user'     => 'Modifier son propre profil',
						'update_user'   => 'Modifier les comptes des autres',
						'delete_user'   => 'Supprimer un utilisateur',
						'lock_user'     => 'Verrouiller / Déverrouiller un compte',
					)
				),
				'groups_management' => array(
					'label' => 'Gestion des Groupes & Permissions',
					'icon'  => 'fa-layer-group',
					'color' => '#ea8600',
					'perms' => array(
						'group'         => 'Gérer les groupes utilisateurs',
					)
				),
				'reunions_management' => array(
					'label' => 'Réunions',
					'icon'  => 'fa-calendar-check',
					'color' => '#188038',
					'perms' => array(
						'view_reunions'    => 'Voir les réunions',
						'create_reunion'   => 'Créer une réunion',
						'manage_reunions'  => 'Gérer toutes les réunions (admin)',
						'join_reunion'     => 'Rejoindre une visioconférence',
					)
				),
				'documents_management' => array(
					'label' => 'Documents',
					'icon'  => 'fa-folder-open',
					'color' => '#9334e6',
					'perms' => array(
						'view_documents'    => 'Voir les documents',
						'upload_document'   => 'Uploader un document',
						'manage_documents'  => 'Gérer tous les documents (admin)',
						'manage_categories' => 'Gérer les catégories de documents',
					)
				),
				'chat_management' => array(
					'label' => 'Messagerie',
					'icon'  => 'fa-comment-dots',
					'color' => '#00897b',
					'perms' => array(
						'view_chat'         => 'Accéder à la messagerie',
						'envoyer_message'   => 'Envoyer un message',
						'repondre_message'  => 'Répondre aux messages',
						'boite_reception'   => 'Boîte de réception',
						'delete_message'    => 'Supprimer des messages',
						'create_chat_group' => 'Créer un groupe de discussion',
					)
				),
				'recordings_management' => array(
					'label' => 'Enregistrements',
					'icon'  => 'fa-circle-dot',
					'color' => '#c5221f',
					'perms' => array(
						'view_recordings'   => 'Voir les enregistrements',
						'record_reunion'    => 'Enregistrer une réunion',
						'manage_recordings' => 'Gérer tous les enregistrements (admin)',
					)
				),
				'visio_management' => array(
					'label' => 'Visioconférence',
					'icon'  => 'fa-video',
					'color' => '#4285f4',
					'perms' => array(
						'manage_visio_config' => 'Configurer la visioconférence (serveurs)',
					)
				),
				'audit' => array(
					'label' => 'Audit & Historique',
					'icon'  => 'fa-clock-rotate-left',
					'color' => '#607d8b',
					'perms' => array(
						'view_history'  => 'Afficher l\'historique des actions',
					)
				),
			);
		}
	}


	/* ---------------------------------------------------------------
	 *  LISTE PLATE DES PERMISSIONS (compatibilité avec les views)
	 *  Retourne array('clé' => 'libellé', ...)
	 * --------------------------------------------------------------- */
	if(!function_exists('get_flat_permissions')){
		function get_flat_permissions(){
			$flat = array();
			foreach(get_all_permissions() as $cat){
				foreach($cat['perms'] as $key => $label){
					$flat[$key] = $label;
				}
			}
			return $flat;
		}
	}


	/* ---------------------------------------------------------------
	 *  VÉRIFICATION MULTI-PERMISSIONS — au moins une parmi la liste
	 * --------------------------------------------------------------- */
	if(!function_exists('is_allowed_any')){
		function is_allowed_any($permissions_array){
			foreach($permissions_array as $perm){
				if(is_allowed($perm)){
					return true;
				}
			}
			return false;
		}
	}


	/* ---------------------------------------------------------------
	 *  DÉLÉGATION D'ACCÈS — un super admin peut accorder une permission
	 *  à un groupe. Vérifie que le demandeur est super admin.
	 * --------------------------------------------------------------- */
	if(!function_exists('can_delegate')){
		function can_delegate(){
			return is_super_admin();
		}
	}
?>
