<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'helpers/perms_map.php');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model("Users_model");
        $this->load->database();
    }

    public function index()
    {
        if ($this->session->userdata('login_in') == true) {
            redirect('dashboard');
        }
        if ($_POST) {
            $username = $this->input->post('username');
            $password = sha1($this->input->post('password'));
            $row      = $this->Users_model->get_user($username, $password);

            if (count($row) != 0) {
                $row = $row[0];
                $this->session->set_userdata('login_in', true);
                if ($row->isadmin == "1")
                    $this->session->set_userdata('isadmin', true);
                else
                    $this->session->set_userdata('isadmin', false);

                $this->session->set_userdata('name', $row->first_name . " " . $row->last_name);
                $this->session->set_userdata('userid', $row->id);


                // $this->session->set_userdata("perms",$perms);
                $perms = $this->Users_model->get_user_permissions($row->role_id);

                $this->session->set_userdata("perms", $perms);

                $this->session->set_userdata("profile_img",
                    val($row->img, base_url() . "/assets/layouts/layout2/img/avatar.png"));
                redirect('dashboard');
            } else {
                redirect('login');
            }
        }
        $data['footer'] = "By<a href='#'> FANACMP </a>" . "@" . date('Y');
        $this->load->view('login', $data);
    }

    public function logout()
    {
        $this->session->set_userdata('login_in', false);
        $this->session->set_userdata('isadmin', false);
        $this->session->unset_userdata("userid");
        $this->session->sess_destroy();
        redirect('login');
    }
}
