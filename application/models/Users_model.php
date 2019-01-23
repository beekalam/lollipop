<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (APPPATH.'helpers/perms_map.php');
require_once (APPPATH.'helpers/utils.php');
class Users_model extends CI_Model {
    
    function __construct() {
        // parent::__construct();
    }

    public function get_all_users() {
        return $this->db->get('users');
    }


    public function get_user_permissions($roleid)
    {
    	global $perms;
    	$ret = $perms;
    	global $default_perms;
    	$role = $this->db->get('roles',array('id' => $roleid))->result();
    	if(count($role))
    	{
    		$role=$role[0];
    		$role_perms = o2a(json_decode($role->value));

    		foreach($ret as $k=>&$v){
    			$v = false;
    			if(isset($role_perms[$k]) && $role_perms[$k])
    			{
    				$v=true;
    			}
    		}
    		return $ret;
    	}
   		return $default_perms;
    }

    public function get_user($username, $password)
    {
		return $this->db->get_where('users',
							array('user_name'=>$username,'password' => $password))->result();
    }

}