<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reunions extends MX_Controller {

    public function __construct() {
        parent::__construct();
        // Here you load models, helpers, and check auth
        // $this->load->model('reunions_model');
    }

    public function index() {
        $data['title'] = 'Gestion des Réunions';
        $this->load->view('header');
        $this->load->view('index', $data);
        $this->load->view('footer');
    }
}
