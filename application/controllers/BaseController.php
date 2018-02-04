<?php

class BaseController extends CI_Controller {


	function __construct(){
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->database();
		$this->load->library('session');
		$this->checkAuth();
	}

	private function checkAuth()
	{
		if($this->session->userdata('login_in')==false){
			redirect('login');
		}
	}

	public function view($view,$data=array())
	{
		if($this->session->has_userdata('msg'))
		{
			$data['msg'] = $this->session->userdata('msg');
		}

		$data['footer'] =  "By<a href='#'> Fanacmp </a>" . "@". date('Y');		
		$this->load->view('header',$data);
		$this->load->view($view,$data);
		$this->load->view('footer',$data);
	}


	public function is_ajax()
	{
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']=="XMLHttpRequest");
	}
	
	public function is_ajax_post()
	{
		return $this->is_ajax() &&  $this->is_post_request();
	}

	public function is_post_request()
	{
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}



}