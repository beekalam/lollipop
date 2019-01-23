<?php
require_once (APPPATH.'helpers/perms_map.php');


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
			 $msg = $this->session->userdata('msg');
			 $msg_type = "success";

			 if($msg and strpos($msg,"|") != false)
			 {
			 	list($type,$message) = explode("|", $msg);
			 	$msg_type = $type;
			 	$msg = $message;
			 }
			 $data['msg'] = $msg;
			 $data['msg_type'] = $msg_type;
		}

		$data['footer'] =  "By<a href='#'> FANACMP </a>" . "@". date('Y');
		
		$data['isadmin']	=  $this->is_admin();
		// dump($data);
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

	public function is_get_request()
	{
		return $_SERVER['REQUEST_METHOD'] === 'GET';
	}
	

	public function allow_post_only()
	{
		if(!$this->is_post_request())
			exit;
	}

	public function allow_ajax_post_only()
	{
		if(!$this->is_ajax_post())
			exit;
	}

	public function check_perm($permname)
	{
		if(check_perm($permname)) return true;

		if($this->is_ajax())
		{
			ejson(array("success" => false, "error"=> "شما به ای نعملیت دسترسی ندارید"));
		}else{
			$this->view("no-access");
		}

		return false;
	}
	
	public function is_admin()
	{
		return isset($_SESSION['isadmin']) && $_SESSION['isadmin'] ? 
				true : false;
	}	

}

function check_perm($permname)
{
		if($_SESSION['isadmin']) return true;

		$perms = $_SESSION["perms"];
		$ret = false;
		
		if(!isset($perms[$permname])){
			// xlog("invalid perm name");
			die("invalid perm name");
		}

		$ret =  $perms[$permname];
		// pre($perms['view_settings']);
		// switch($permname){
		// 	case 'change_settings':
		// 		$ret = $_SESSION["isadmin"] or $ret;;
		// 		break;
		// 	case 'view_sales':
		// 		$ret = $_SESSION["isadmin"] or $ret;
		// 		break;
		// 	case 'view_users':
		// 		$ret = $_SESSION['isadmin'] or $ret;
		// 		break;
		// }
		return $ret;
}