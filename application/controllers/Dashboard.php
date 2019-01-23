<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once dirname(__FILE__) . "/BaseController.php";
require_once(APPPATH . 'libraries/jdf.php');
require_once(APPPATH . 'libraries/lib_date.php');

class Dashboard extends BaseController
{


    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model("Users_model");
        $this->load->model("Customers_model");
    }

    public function index()
    {
        $data['title']       = 'index';
        $data['active_menu'] = 'm-dashboard';
        // build cashier's summary
        if ($this->session->userdata("isadmin")) {
            $users = $this->db->query("select * from users")->result_array();
            // $res = array();
            if (count($users)) {
                $date = date("Y-m-d", time()) . " 00:00:00";
                $q    = "select transactions.total_with_discount,transactions.user_id,transactions.total_with_discount ";
                $q    .= "from transactions JOIN users ON transactions.user_id=users.id ";
                $q    .= " WHERE user_id='%s' and created_at >= '%s'";
                foreach ($users as &$user) {
                    $query       = sprintf($q, $user["id"], $date);
                    $sales       = $this->db->query($query)->result_array();
                    $user["sum"] = array_sum(array_column($sales, "total_with_discount"));
                }
            }
            $data["user_summary"] = $users;
        }

        $data['active_customers']       = $this->Customers_model->active_customers()->result();
        $data['active_customers_count'] = $this->Customers_model->active_customers_count();
//        pre($data);

        $this->view('dashboard/main', $data);
    }

    public function calc_discount()
    {
        $this->load->model("Users_model");
        $this->load->model("Customers_model");
        $this->load->model("Settings_model");
        $customer_id             = 10;
        $discount_rules          = $this->Settings_model->discount_rules();
        $max_days_to_consider    = max(array_column($discount_rules, "num_days_to_consider"));
        $latest_date_to_consider = date_create("today")->sub(new DateInterval(sprintf('P%sD', $max_days_to_consider)))
                                                       ->format('Y-m-d');
        $res                     = $this->db->select("*")
                                            ->from("transactions")
                                            ->where("customer_id", $customer_id)
                                            ->where("created_at", ">=", $latest_date_to_consider)
                                            ->get()->result_array();
        pre($res);
//        pr(date_create("today")->format('Y-m-d'));
        pre($latest_date_to_consider);
//        $customer_usage = $this->
        pre($discount_rules);
    }

}
