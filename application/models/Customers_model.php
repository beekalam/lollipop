<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once (APPPATH."helpers/utils.php");
class Customers_model extends CI_Model {
    
    function __construct() {
        // parent::__construct();
        // $this->load->database();
    }

    // public function get_all_customers($page=null)
    public function get_all_customers($start=null,$len=null)
    {
        if(is_null($start))
            $ret =  $this->db->get('customers');
        else
            $ret = $this->db->get('customers',$len,$start);


        $settings = $this->db->get('settings');
        $rate = array();
        foreach($settings->result() as $k=>$v){
            $tmp = explode('_', $v->name);
            $rate[] = array("rate" => $v->value, "duration" => floatval($tmp[1]) * 3600);
        }
      
        
        foreach ($ret->result() as $key => $value) {
            if(is_null($value->checkout)){
                $value->diff = time() - $value->checkin;
                $value->diff_seconds = $value->diff;
                $value->diff = persian_duration($value->diff);
            }else{
                $value->diff = $value->checkout - $value->checkin;
                $value->diff_seconds = $value->diff;
                $value->diff = persian_duration($value->diff);
            }
            
            foreach ($rate as $v) {
                if($value->diff_seconds <= intval($v["duration"]))
                    $value->to_pay = $v["rate"];
            }
        }
        
        return $ret;
    }

    private function calculate_vorodi(&$customer)
    {
        $vorodi = 0;
        if(!is_null($customer->checkin)){
            $s =  $this->db->order_by("duration","DESC")->get("vorodi")->result();
            if(!is_null($customer->checkout))
                $diff = $customer->checkout - $customer->checkin;
            else
                $diff = time() - $customer->checkin;
            
            for($i=0,$c=count($s);$i < $c; $i++){
                $v = $s[$i];
                if($diff <= $v->duration){
                    $vorodi = $v->price;
                }
            }
        }
        $customer->vorodi = $vorodi;

    }
    public function active_customers_count()
    {
        $ret = 0;
        $res = $this->db->get('customers');
        foreach ($res->result() as  $value) {
            if(!is_null($value->checkin) && is_null($value->checkout)){
                $ret++;
            }
        }
        return $ret;
    }

    public function active_customers($limit=10)
    {
       $q = "select * from customers where ((checkin is not null) and (checkout is null)) limit " . $limit;
       $res = $this->db->query($q);
       return $res;
    }
    
    public function add($data)
    {
        $data['checkout'] = null;
        $data['checkin'] = null;
        $this->db->insert('customers',$data);    
    }

    public function checkin($id)
    {
    	$this->db->set('checkin',time());
        $this->db->set('checkout',null);
    	$this->db->where('id',$id);
    	$this->db->update('customers');
    }

    public function checkout($id)
    {
    	$this->db->set('checkout', time());
    	$this->db->where('id',$id);
    	$this->db->update('customers');
    }

    public function get_user_by_rfid($id)
    {
        $row = $this->db->where('cardid',$id)->get('customers')->result();
        if(count($row)){
            $row =$row[0];
            $this->calculate_vorodi($row);
        }else{
            $row = false;
        }
        return $row;
    }

    public function get_user_by_id($id)
    {
        $row = $this->db->where('id',$id)->get('customers')->result();
        if(count($row)){
            $row =$row[0];
            $this->calculate_vorodi($row);
        }else{
            $row = false;
        }
        return $row;
    }

    public function get_user_extra_services($id)
    {
        $row = $this->db->where('customer_id',$id)->get('users_extra');
        return $row;
    }

    public function add_extra_service($params)
    {
        $this->db->insert('users_extra',$params);
        return $this->db->affected_rows();
    }

    public function delete_extra_service($id)
    {
        return $this->db->delete('users_extra',array("id" => $id));
    }
    
    public function final_checkout($id)
    {
        $params= array();
        $this->checkout($id);
        $customer = $this->db->get_where('customers',array("id" => $id))->result();
        $extra_services = $this->db->get_where('users_extra',array('customer_id' => $id))->result();
        if(count($customer)!=0){
            $customer = $customer[0];
            $params["customer_id"] = $customer->id;
            $params["checkin"] = $customer->checkin;
            $params["checkout"] = $customer->checkout;
            $params["extras_total"] = 0;
            if(count($extra_services)!=0){
                $params["extra_services"] = json_encode($extra_services);
                foreach($extra_services as $val){
                    $params["extras_total"] += $val->price;
                }
            }

            $this->calculate_vorodi($customer);
            $params["vorodi"] = $customer->vorodi;
            $params["total"] = $params["vorodi"] + $params["extras_total"];

            $this->db->trans_start();
            $this->db->delete('users_extra',array('customer_id'=> $customer->id));
            $this->db->update('customers',array('checkin'=>null,'checkout'=>null),"id='" . $customer->id . "'");
            $this->db->insert('transactions',$params);
            $this->db->trans_complete();
            return $this->db->trans_status();
        }

        return false;
    }
}