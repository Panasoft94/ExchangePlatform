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


			$this->load->view('header');
			$this->load->view('index', $data);
			$this->load->view('footer');
		}
	}
?>
