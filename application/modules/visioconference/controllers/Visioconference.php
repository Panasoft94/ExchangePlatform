<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Visioconference extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('reunions/Reunions_model');
        check();
    }

    public function configuration()
    {
        $session = $this->session->userdata('users');
        if (!$this->can_manage_visio_settings($session)) {
            $this->session->set_flashdata('error', 'Vous n\'avez pas accès à la configuration du salon local.');
            redirect('reunions');
        }

        $app_config = $this->load_app_config();
        $turn_config = $this->load_turn_config();
        $ice_servers = $this->load_ice_servers();

        $data = array(
            'app_config' => $app_config,
            'turn_config' => $turn_config,
            'ice_servers' => $ice_servers
        );

        $this->load->view('header');
        $this->load->view('configuration', $data);
        $this->load->view('footer');
    }

    public function save_configuration()
    {
        $session = $this->session->userdata('users');
        if (!$this->can_manage_visio_settings($session)) {
            $this->session->set_flashdata('error', 'Vous n\'avez pas accès à la configuration du salon local.');
            redirect('reunions');
        }

        if ($this->input->method() !== 'post') {
            redirect('visioconference/configuration');
        }

        $signal_port = (int) $this->input->post('signal_port');
        $turn_port = (int) $this->input->post('turn_port');
        $rtc_host = trim($this->input->post('rtc_host'));
        $turn_username = trim($this->input->post('turn_username'));
        $turn_password = trim($this->input->post('turn_password'));
        $turn_enabled = $this->input->post('turn_enabled') ? true : false;

        if ($signal_port <= 0) {
            $signal_port = 8081;
        }
        if ($turn_port <= 0) {
            $turn_port = 3478;
        }
        if ($rtc_host === '') {
            $rtc_host = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
        }
        if ($turn_username === '') {
            $turn_username = 'reunion';
        }
        if ($turn_password === '') {
            $turn_password = 'reunion123';
        }

        $app_config = array(
            'signalPort' => $signal_port,
            'rtcHost' => $rtc_host,
            'turnEnabled' => $turn_enabled,
            'turnPort' => $turn_port,
            'turnUsername' => $turn_username,
            'turnPassword' => $turn_password
        );

        $turn_config = array(
            'listeningPort' => $turn_port,
            'listeningIps' => array('0.0.0.0'),
            'authMech' => 'long-term',
            'credentials' => array(
                $turn_username => $turn_password
            ),
            'realm' => 'chat-mfpra.local',
            'debugLevel' => 'INFO'
        );

        $ice_servers = array(
            array(
                'urls' => array('stun:' . $rtc_host . ':' . $turn_port)
            )
        );

        if ($turn_enabled) {
            $ice_servers[] = array(
                'urls' => array(
                    'turn:' . $rtc_host . ':' . $turn_port . '?transport=udp',
                    'turn:' . $rtc_host . ':' . $turn_port . '?transport=tcp'
                ),
                'username' => $turn_username,
                'credential' => $turn_password
            );
        }

        $write_success = $this->write_json_file($this->get_app_config_path(), $app_config)
            && $this->write_json_file($this->get_turn_config_path(), $turn_config)
            && $this->write_json_file($this->get_ice_servers_path(), array('iceServers' => $ice_servers));

        if ($write_success) {
            $this->session->set_flashdata('success', 'La configuration RTC locale a été mise à jour.');
        } else {
            $this->session->set_flashdata('error', 'Impossible d\'enregistrer la configuration RTC locale.');
        }

        redirect('visioconference/configuration');
    }

    public function reunion($id)
    {
        $reunion = $this->Reunions_model->get($id);
        if (!$reunion) {
            $this->session->set_flashdata('error', 'Cette réunion n\'existe pas.');
            redirect('reunions');
        }

        $session = $this->session->userdata('users');
        if (!$session) {
            $this->session->set_flashdata('error', 'Votre session a expiré.');
            redirect('reunions/view/' . (int) $id);
        }

        if (!$this->can_access_room($reunion, $session->users_id)) {
            $this->session->set_flashdata('error', 'Vous devez être participant à cette réunion pour accéder au salon de visioconférence.');
            redirect('reunions/view/' . (int) $id);
        }

        $participants = $this->Reunions_model->get_participants($id);
        $display_name = trim($session->users_prenom . ' ' . $session->users_nom);
        if ($display_name === '') {
            $display_name = isset($session->users_username) ? $session->users_username : 'Participant';
        }

        $is_reunion_host = (int) $reunion->created_by === (int) $session->users_id;
        $room_url = site_url('visioconference/reunion/' . (int) $id);
        $app_config = $this->load_app_config();
        $signal_port = !empty($app_config['signalPort']) ? (int) $app_config['signalPort'] : 8081;
        $rtc_host = !empty($app_config['rtcHost']) ? $app_config['rtcHost'] : (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost');

        $ice_servers = $this->load_ice_servers();

        $data = array(
            'reunion' => $reunion,
            'participants' => $participants,
            'room_name' => $this->build_room_name($reunion),
            'room_id' => 'reunion-' . (int) $reunion->id,
            'display_name' => $display_name,
            'current_user_id' => (int) $session->users_id,
            'user_email' => isset($session->users_email) ? $session->users_email : '',
            'avatar_url' => $this->build_avatar_url(isset($session->photo_profil) ? $session->photo_profil : ''),
            'back_url' => site_url('reunions/view/' . (int) $id),
            'room_url' => $room_url,
            'is_reunion_host' => $is_reunion_host,
            'room_action_label' => $is_reunion_host ? 'Reunion en cours' : 'Reunion rejointe',
            'rtc_host' => $rtc_host,
            'signal_port' => $signal_port,
            'ice_servers' => $ice_servers,
            'has_turn_server' => $this->has_turn_server($ice_servers),
            'can_manage_visio_settings' => $this->can_manage_visio_settings($session),
            'configuration_url' => site_url('visioconference/configuration'),
            'reunion_date' => isset($reunion->date_reunion) ? $reunion->date_reunion : '',
            'reunion_duration' => isset($reunion->duration) ? (int) $reunion->duration : 60
        );

        $this->load->view('header');
        $this->load->view('reunion', $data);
        $this->load->view('footer');
    }

    private function can_access_room($reunion, $user_id)
    {
        $user_id = (int) $user_id;
        if ($user_id <= 0) {
            return false;
        }

        if ((int) $reunion->created_by === $user_id) {
            return true;
        }

        return $this->Reunions_model->is_user_participant($reunion->id, $user_id);
    }

    private function build_room_name($reunion)
    {
        $title = isset($reunion->title) ? trim($reunion->title) : '';

        if ($title !== '' && function_exists('iconv')) {
            $normalized = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $title);
            if ($normalized !== false) {
                $title = $normalized;
            }
        }

        $title = strtolower($title);
        $title = preg_replace('/[^a-z0-9]+/', '-', $title);
        $title = trim($title, '-');

        if ($title === '') {
            $title = 'reunion';
        }

        return $title;
    }

    private function build_avatar_url($photo_profil)
    {
        if (empty($photo_profil)) {
            return '';
        }

        $photo_file = basename($photo_profil);
        $photo_path = FCPATH . 'assets/img/avatar/' . $photo_file;
        if (!file_exists($photo_path)) {
            return '';
        }

        return base_url('assets/img/avatar/' . rawurlencode($photo_file));
    }

    private function load_ice_servers()
    {
        $app_config = $this->load_app_config();
        $default_host = !empty($app_config['rtcHost']) ? $app_config['rtcHost'] : (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost');
        $default_turn_port = !empty($app_config['turnPort']) ? (int) $app_config['turnPort'] : 3478;
        $default_turn_enabled = isset($app_config['turnEnabled']) ? (bool) $app_config['turnEnabled'] : true;
        $default_turn_username = !empty($app_config['turnUsername']) ? $app_config['turnUsername'] : 'reunion';
        $default_turn_password = !empty($app_config['turnPassword']) ? $app_config['turnPassword'] : 'reunion123';

        $default_servers = array(
            array('urls' => array('stun:' . $default_host . ':' . $default_turn_port))
        );

        if ($default_turn_enabled) {
            $default_servers[] = array(
                'urls' => array(
                    'turn:' . $default_host . ':' . $default_turn_port . '?transport=udp',
                    'turn:' . $default_host . ':' . $default_turn_port . '?transport=tcp'
                ),
                'username' => $default_turn_username,
                'credential' => $default_turn_password
            );
        }

        $config_path = $this->get_ice_servers_path();
        if (!file_exists($config_path)) {
            return $default_servers;
        }

        $json = @file_get_contents($config_path);
        if ($json === false || trim($json) === '') {
            return $default_servers;
        }

        $decoded = json_decode($json, true);
        if (!is_array($decoded) || empty($decoded['iceServers']) || !is_array($decoded['iceServers'])) {
            return $default_servers;
        }

        foreach ($decoded['iceServers'] as &$server) {
            if (empty($server['urls'])) {
                continue;
            }

            if (is_array($server['urls'])) {
                foreach ($server['urls'] as &$url) {
                    $url = str_replace('{host}', $default_host, $url);
                }
                unset($url);
            } else {
                $server['urls'] = str_replace('{host}', $default_host, $server['urls']);
            }
        }
        unset($server);

        return $decoded['iceServers'];
    }

    private function get_app_config_path()
    {
        return APPPATH . '..\\realtime\\app-config.json';
    }

    private function get_ice_servers_path()
    {
        return APPPATH . '..\\realtime\\ice-servers.json';
    }

    private function get_turn_config_path()
    {
        return APPPATH . '..\\realtime\\turn-config.json';
    }

    private function write_json_file($path, $data)
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return false;
        }

        return @file_put_contents($path, $json . PHP_EOL) !== false;
    }

    private function has_turn_server($ice_servers)
    {
        if (empty($ice_servers) || !is_array($ice_servers)) {
            return false;
        }

        foreach ($ice_servers as $server) {
            if (empty($server['urls'])) {
                continue;
            }

            $urls = is_array($server['urls']) ? $server['urls'] : array($server['urls']);
            foreach ($urls as $url) {
                if (strpos($url, 'turn:') === 0 || strpos($url, 'turns:') === 0) {
                    return true;
                }
            }
        }

        return false;
    }

    private function can_manage_visio_settings($session)
    {
        if (empty($session)) {
            return false;
        }

        $role = isset($session->users_role) ? strtolower($session->users_role) : '';
        if ($role !== '' && strpos($role, 'admin') !== false) {
            return true;
        }

        if (function_exists('is_allowed') && (is_allowed('group') || is_allowed('user'))) {
            return true;
        }

        return false;
    }

    private function load_app_config()
    {
        $default_host = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
        $defaults = array(
            'signalPort' => 8081,
            'rtcHost' => $default_host,
            'turnEnabled' => true,
            'turnPort' => 3478,
            'turnUsername' => 'reunion',
            'turnPassword' => 'reunion123'
        );

        $path = $this->get_app_config_path();
        if (!file_exists($path)) {
            return $defaults;
        }

        $json = @file_get_contents($path);
        $decoded = $json ? json_decode($json, true) : null;

        if (!is_array($decoded)) {
            return $defaults;
        }

        return array_merge($defaults, $decoded);
    }

    private function load_turn_config()
    {
        $defaults = array(
            'listeningPort' => 3478,
            'listeningIps' => array('0.0.0.0'),
            'authMech' => 'long-term',
            'credentials' => array('reunion' => 'reunion123'),
            'realm' => 'chat-mfpra.local',
            'debugLevel' => 'INFO'
        );

        $path = $this->get_turn_config_path();
        if (!file_exists($path)) {
            return $defaults;
        }

        $json = @file_get_contents($path);
        $decoded = $json ? json_decode($json, true) : null;

        if (!is_array($decoded)) {
            return $defaults;
        }

        return array_merge($defaults, $decoded);
    }

}
