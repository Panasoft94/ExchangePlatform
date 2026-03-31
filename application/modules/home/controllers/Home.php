<?php
	class Home extends MX_Controller
	{
		public function __construct()
		{
			parent::__construct();
			check();
			$this->load->model('Users_model');
		}

		public function index()
		{
			$data['users'] = $this->Users_model
												->limit(5,0)
												->order_by('create_at', 'desc')
												->get_all();

			$data['nb_users'] = $this->Users_model->count_all();

			// Load reunion stats
			$this->load->model('reunions/Reunions_model');
			$data['nb_reunions_today'] = $this->Reunions_model->count_today();
			$data['nb_reunions_upcoming'] = $this->Reunions_model->count_upcoming();
			$data['next_reunion'] = $this->Reunions_model->get_next_reunion();

			// Load document stats
			$this->load->model('documents/Documents_model');
			$data['nb_documents'] = $this->Documents_model->count_all();

			// Recent activity from history
			$this->load->model('History_model');
			$data['recent_activity'] = $this->History_model
												->limit(6, 0)
												->order_by('history_id', 'desc')
												->get_all();

			$this->load->view('header');
			$this->load->view('index', $data);
			$this->load->view('footer');
		}

		/**
		 * Global search endpoint (AJAX)
		 * Returns JSON: { results: [{ title, subtitle, url, icon, bg, color }] }
		 */
		public function search()
		{
			if (!$this->input->is_ajax_request()) {
				redirect('home');
			}

			$q = trim($this->input->get('q', TRUE));
			$results = array();

			if (strlen($q) < 2) {
				echo json_encode(array('results' => $results));
				return;
			}

			$q_like = '%' . $q . '%';

			// Search users
			$users = $this->db->select('users_id, users_nom, users_prenom, users_email, users_role')
				->from('users')
				->group_start()
					->like('users_nom', $q, 'both', FALSE)
					->or_like('users_prenom', $q, 'both', FALSE)
					->or_like('users_email', $q, 'both', FALSE)
					->or_like('users_username', $q, 'both', FALSE)
				->group_end()
				->limit(5)
				->get()->result();

			foreach ($users as $u) {
				$results[] = array(
					'title'    => $u->users_prenom . ' ' . $u->users_nom,
					'subtitle' => $u->users_role . ' — ' . $u->users_email,
					'url'      => site_url('users/update/' . $u->users_id),
					'icon'     => 'fas fa-user',
					'bg'       => '#e8f0fe',
					'color'    => '#1a73e8'
				);
			}

			// Search reunions
			$reunions = $this->db->select('id, title, scheduled_at')
				->from('reunions')
				->group_start()
					->like('title', $q, 'both', FALSE)
					->or_like('description', $q, 'both', FALSE)
				->group_end()
				->order_by('scheduled_at', 'DESC')
				->limit(5)
				->get()->result();

			foreach ($reunions as $r) {
				$results[] = array(
					'title'    => $r->title,
					'subtitle' => 'Réunion — ' . date('d/m/Y H:i', strtotime($r->scheduled_at)),
					'url'      => site_url('reunions/view/' . $r->id),
					'icon'     => 'fas fa-calendar-check',
					'bg'       => '#fef7e0',
					'color'    => '#e37400'
				);
			}

			// Search documents
			$docs = $this->db->select('d.id, d.filename, d.created_at')
				->from('documents d')
				->like('d.filename', $q, 'both', FALSE)
				->order_by('d.created_at', 'DESC')
				->limit(5)
				->get()->result();

			foreach ($docs as $d) {
				$results[] = array(
					'title'    => $d->filename,
					'subtitle' => 'Document — ' . date('d/m/Y', strtotime($d->created_at)),
					'url'      => site_url('documents/download/' . $d->id),
					'icon'     => 'fas fa-file',
					'bg'       => '#f3e8fd',
					'color'    => '#8430ce'
				);
			}

			// Static navigation search
			$nav_items = array(
				array('title' => 'Accueil',       'url' => base_url(),                'icon' => 'fas fa-home'),
				array('title' => 'Messagerie',     'url' => site_url('chat'),           'icon' => 'fas fa-comment-dots'),
				array('title' => 'Réunions',       'url' => site_url('reunions'),       'icon' => 'fas fa-calendar-check'),
				array('title' => 'Documents',      'url' => site_url('documents'),      'icon' => 'fas fa-folder-open'),
				array('title' => 'Comptes',        'url' => site_url('users'),          'icon' => 'fas fa-users'),
				array('title' => 'Groupes',        'url' => site_url('users/group'),    'icon' => 'fas fa-layer-group'),
				array('title' => 'Historique',      'url' => site_url('users/history'),  'icon' => 'fas fa-clock-rotate-left'),
				array('title' => 'Mon Profil',     'url' => site_url('users/edit'),     'icon' => 'fas fa-user-circle'),
			);

			$q_lower = mb_strtolower($q);
			foreach ($nav_items as $nav) {
				if (mb_strpos(mb_strtolower($nav['title']), $q_lower) !== false) {
					$results[] = array(
						'title'    => $nav['title'],
						'subtitle' => 'Navigation',
						'url'      => $nav['url'],
						'icon'     => $nav['icon'],
						'bg'       => '#e6f4ea',
						'color'    => '#1e8e3e'
					);
				}
			}

			echo json_encode(array('results' => array_slice($results, 0, 12)));
		}
	}
?>
