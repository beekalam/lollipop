<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once dirname(__FILE__)."/BaseController.php";
require_once (APPPATH.'libraries/jdf.php');
require_once (APPPATH.'libraries/lib_date.php');

class Dashboard extends BaseController {


	function __construct(){
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->database();
		$this->load->library('session');
		$this->load->model("Users_model");
		$this->load->model("Customers_model");
	}

	public function index()
	{
		$data['title'] = 'index';
		$data['active_menu'] = 'm-dashboard';
		$data['active_customers']  = $this->Customers_model->active_customers()->result();		
		$data['active_customers_count'] = $this->Customers_model->active_customers_count();

		$this->view('dashboard/main',$data);
	}

}
