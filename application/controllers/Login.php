<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->database();

	}

	public function index()
	{

		if($this->session->userdata('login_in')==true){
			redirect('dashboard');
		}
		if($_POST){
			$username = $this->input->post('username');
			$password=sha1($this->input->post('password'));
			$row = $this->db->get_where('users',array('user_name'=>$username,'password' => $password));
			if(count($row)!=0){
				// $this->session->set_userdata('name',$result[0]->station_name);
				// $this->session->set_userdata('id',$result[0]->id);
				// $this->session->set_userdata('code',$result[0]->code);
				// $this->session->set_userdata('email',$result[0]->email);
				$this->session->set_userdata('login_in',true);
				redirect('dashboard');
			}else{
				redirect('login');
			}
		
		}
		$this->load->view('login');
	}

	public function logout()
	{
		$this->session->set_userdata('login_in',false);
		$this->session->unset_userdata('name');
		$this->session->unset_userdata('id'); 
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('code');
		redirect('login');
	}
}
