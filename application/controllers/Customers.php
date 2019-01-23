<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/BaseController.php");
require_once(APPPATH . 'libraries/jdf.php');
require_once(APPPATH . 'libraries/lib_date.php');

class Customers extends BaseController
{


    function __construct()
    {
        parent::__construct();
        $this->load->model("Users_model");
        $this->load->model("Customers_model");
        $this->load->model("Settings_model");
        $this->load->library('Jalali_date');
    }

    public function index()
    {
        $data['active_menu'] = 'm-users';

        $data['query'] = $this->Customers_model->get_all_customers(1);
        $this->view('customers/customers', $data);
    }

    public function customers_list()
    {
        $start  = $this->input->post("start");
        $len    = $this->input->post("length");
        $search = $_POST["search"]["value"];

        if (!empty($search)) {
            $where = "(first_name like '%{$search}%') or ";
            $where .= "(last_name like '%{$search}%') or ";
            $where .= "(mobile like '%{$search}%') or ";
            $where .= "(cardid like '%{$search}%') ";

            $ret    = $this->db->get_where('customers', $where, $start, $len)->result();
            $length = count($this->db->get_where('customers', $where)->result());
            $this->session->set_userdata("customer_list_search", array("type" => "search", "where" => $where));
        } else {
            $ret = $this->Customers_model->get_all_customers($start, $len)->result();
            $this->session->set_userdata("customer_list_search", array("type" => "list", "where" => ""));
            $length = count($this->db->get('customers')->result());
        }

        ejson(array("data"            => $ret,
                    "recordsTotal"    => $length,
                    "draw"            => $this->input->post("draw"),
                    "recordsFiltered" => $length));
    }

    public function sales_csv_export()
    {
        $output = fopen('php://output', 'w') or die("Can't open php://output");
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="sales.csv"');

        $q = $this->_sales_list("select", true);

        if ($q) {
            fputcsv($output, array(
                    'نام',
                    'نام خانوادگی',
                    'تاریخ ورود',
                    'تاریخ خروج',
                    'مجموع',
                    'تاریخ صدور'
                )
            );
            $res = $this->db->query($q)->result_array();
            foreach ($res as $value) {
                $value['checkin']    = unix_timestamp_to_jalali($value['checkin']);
                $value['checkout']   = unix_timestamp_to_jalali($value['checkout']);
                $value['created_at'] = convert_gregorian_iso_to_jalali_iso($value['created_at']);
                fputcsv($output, array(
                        $value['first_name'],
                        $value['last_name'],
                        $value['checkin'],
                        $value['checkout'],
                        $value['total_with_discount'],
                        $value['created_at']
                    )
                );
            }
        }

        fclose($output) or die("Can't close php://output");
    }

    public function csv_export()
    {
        $output = fopen('php://output', 'w') or die("Can't open php://output");
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="customers.csv"');

        $s = $this->session;

        if ($s->has_userdata("customer_list_search")) {
            $data = $s->userdata("customer_list_search");

            if ($data["type"] == "list") {
                $q = "select * from customers";
            }

            if ($data["type"] == "search") {
                $q = "select * from customers where " . $data["where"];

            }

            fputcsv($output, array('نام', 'نام خانوادگی', 'شماره کارت', 'موبایل', 'تاریخ انقضا', 'آخرین ورود', 'آخرین خروج', 'وضعیت ابطلا کارت'));
            $res = $this->db->query($q)->result_array();
            foreach ($res as $value) {
                if (!empty($value["card_expire_date"])) {
                    $value["card_expire_date"] = convert_gregorian_iso_to_jalali_iso($value["card_expire_date"]);
                }


                if ($value["card_canceled"] == "no") {
                    $value["card_canceled"] = 'خیر';
                } else if ($value["card_canceled"] == 'yes') {
                    $value["card_canceled"] = 'بلی';
                }
                if (!empty($value["checkin"])) {
                    $value["checkin"] = unix_timestamp_to_jalali($value["checkin"]);
                }
                if (!empty($value["checkout"])) {
                    $value["checkout"] = unix_timestamp_to_jalali($value["checkout"]);
                }

                fputcsv($output,
                    array($value["first_name"], $value["last_name"], $value["cardid"], $value["mobile"],
                        $value["card_expire_date"], $value["checkin"], $value["checkout"], $value["card_canceled"])
                );
            }
        }

        fclose($output) or die("Can't close php://output");
    }

    public function reopen_factor()
    {
        $factor_id = $this->input->post("factor_id");

        $res       = $this->Customers_model->reopen_factor($factor_id);
        if ($res)
            return ejson(array("success" => $res, "msg" => "عملیات با موفقیت انجام شد."));
        else
            return ejson(array("success" => $res, "msg" => "خطا در انجام عملیات."));
    }

    public function manage()
    {
        $this->load->library('parser');
        $data['active_menu'] = 'm-users';

        // handle post request
        if ($this->is_post_request()) {
            $action = $this->input->post('action');
            $id     = $this->input->post("id");
            $rfid   = $this->input->post("cardid");
            if ($action == 'checkin') {
                $this->Customers_model->checkin($id);
                $this->session->set_flashdata('msg', 'ورود کاربر ثبت شد.');
                redirect('customers/manage?id=' . $rfid);
            } else if ($action == 'checkout') {
                $this->Customers_model->final_checkout($id);
                $this->session->set_flashdata('msg', 'خروج کاربر ثبت شد.');
                redirect('customers/manage?id=' . $rfid);
            }
        }

        // invalid id
        $id       = $this->input->get("id");
        $customer = $this->Customers_model->get_user_by_rfid($id);
        if (!$customer) {
            $this->session->set_flashdata('msg', 'کاربر یافت نشد.');
            redirect("dashboard");
        }


        $data['customer']          = $customer;
        $data['show_user_checkin'] = false;
        if (is_null($customer->checkin)) {
            $data['show_user_checkin'] = true;
        }

        $data['show_user_checkout'] = false;
        if (is_null($customer->checkout) && !is_null($customer->checkin)) {
            $data['show_user_checkout'] = true;
        }

        $data["show_last_factor"] = false;
        if (is_null($customer->checkout) && is_null($customer->checkin)) {
            $data["show_last_factor"] = true;
            $q                        = "select * from transactions where customer_id='" . $customer->id . "' order by created_at DESC limit 1";
            $res                      = $this->db->query($q)->result();
            if (count($res) == 0) {
                $data["show_last_factor"] = false;
            } else {
                $data["last_factor_id"] = $res[0]->id;
            }

        }

        $data["customer_history"] = $this->db->query("select * from transactions where customer_id='" . $customer->id . "'")->result_array();
        $data["vorodi"]           = $customer->vorodi;
        $data['prices']           = $this->Settings_model->get_all_prices()->result();
        $data['extra_services']   = $this->Customers_model->get_user_extra_services($customer->id)->result();

        $total = 0;
        foreach ($data['extra_services'] as $value) {
            $total += $value->num * $value->price;
        }
        $data["total"] = $total;

        $data['discounts'] = $this->db->get('discounts')->result();
        if ($this->session->has_userdata('discounts')) {
            $discounts = $this->session->userdata('discounts');
            if (isset($discounts[$customer->id])) {
                $data["discount_percent"]    = $discounts[$customer->id];
                $data["discount"]            = ($data["total"] * $data["discount_percent"]) / 100;
                $data["total_with_discount"] = $data["total"] - $data["discount"];
            }
        }
        // $data['categories'] = $this->Settings_model->categories()->result();
        $this->view('customers/manage', $data);
    }

    public function add()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('first_name', '', 'required', array('required' => 'نام وارد نشده.'));
        $this->form_validation->set_rules('last_name', '', 'required', array('required' => 'نام خانوادگی وارد نشده'));
        $this->form_validation->set_rules('new_rfid', '', 'required', array('required' => 'شماره کارت وارد نشده است'));
        $this->form_validation->set_rules('card_expire_date', '', 'required', array('required' => 'تاریخ انقضای کارت وارد نشده'));
        $this->form_validation->set_rules('mobile', '', 'callback_mobile_check');

        if ($this->is_post_request()) {

            if ($this->form_validation->run() === false) {
                $this->view('dashboard/main');
            } else {
                $first_name       = $this->input->post('first_name');
                $last_name        = $this->input->post('last_name');
                $mobile           = $this->input->post('mobile');
                $rfid             = $this->input->post('new_rfid');
                $card_expire_date = $this->input->post('card_expire_date');
                $params           = array("first_name"       => $first_name,
                                          "last_name"        => $last_name,
                                          "mobile"           => $mobile,
                                          "cardid"           => $rfid,
                                          "card_expire_date" => $card_expire_date);
                $this->Customers_model->add($params);

                redirect('customers/manage?id=' . $rfid);
            }
        }
    }

    public function delete()
    {
        $id = $this->input->get('id');
        $this->db->delete('customers', array('id' => $id));
        redirect('customers/index');
    }

    public function customers()
    {
        $data['query'] = $this->Customers_model->get_all_customers();
        $this->load->view('customers/customers', $data);
    }

    public function checkin()
    {
        $id = $this->input->get('id');
        $this->Customers_model->checkin($id);
        $this->session->set_flashdata('msg', 'successfuly checked in user.');
        redirect('users');
    }

    public function checkout()
    {
        $id                 = $this->input->post('id');
        $cardid             = $this->input->post('cardid');
        $checkout_timestamp = $this->input->post('checkout_timestamp');

        if ($this->Customers_model->final_checkout($id)) {
            $this->session->set_flashdata('msg', 'successfuly checkout in user.');
        } else {
            $this->session->set_flashdata('msg', 'error checking user out.');
        }

        // $this->Customers_model->checkout($id);
        redirect('customers/manage?id=' . $id);
    }

    public function add_extra_service()
    {
        if (!$this->is_post_request()) return;

        $price_id        = $this->input->post('price-select');
        $customer_id     = $this->input->post('customer-id');
        $cat_description = $this->Settings_model->get_price($price_id)->result();
        $cat_description = $cat_description[0]->description;
        $params          = array("description" => $cat_description,
                                 "price"       => $this->input->post('item-price'),
                                 "num"         => $this->input->post('item-count'),
                                 "customer_id" => $customer_id);
        $this->Customers_model->add_extra_service($params);
        $row = $this->Customers_model->get_user_by_id($customer_id);

        redirect("customers/manage?id=" . $row->cardid);
    }

    public function delete_extra_service()
    {
        if (!$this->is_post_request()) return;

        $ret = $this->Customers_model->delete_extra_service($this->input->post('id'));
        if ($ret) {
            return ejson(array("success" => true));

        }
        ejson(array("success"), false);

    }

    public function factor()
    {
        $id                     = $this->input->get('id');
        $customer               = $this->Customers_model->get_user_by_id($id);
        $data["extra_services"] = $this->Customers_model->get_user_extra_services($id)->result();
        $data["customer"]       = $customer;
        $customer_duration      = 0;
        $total                  = 0;
        foreach ($data['extra_services'] as $value) {
            $total += $value->num * $value->price;
        }

        $data["duration"] = $customer_duration;
        $data["vorodi"]   = $customer->vorodi;
        $data["total"]    = $total + $customer->vorodi;

        $this->load->view('customers/factor', $data);
    }

    public function factor2()
    {
        $id                     = $this->input->get('id');
        $row                    = $this->db->get_where('transactions', array('id' => $id))->result()[0];
        $data["extra_services"] = empty($row->extra_services) ? array() : json_decode($row->extra_services);
        $customer               = $this->db->get_where('customers', array('id' => $row->customer_id))->result()[0];
        $customer->checkin      = $row->checkin;
        $customer->checkout     = $row->checkout;

        $data["customer"] = $customer;

        $total = 0;
        foreach ($data['extra_services'] as $value) {
            $total += $value->num * $value->price;
        }

        $data["duration"]            = $row->checkout - $row->checkin;
        $data["vorodi"]              = $row->vorodi;
        $data["factor_date"]         = $row->created_at;
        $data["total"]               = $total + $data["vorodi"];
        $data["factor_num"]          = $id;
        $data["discount_percent"]    = $row->discount;
        $data["total_with_discount"] = $row->total_with_discount;
        $data["discount"]            = $data["total"] - $data["total_with_discount"];

        $this->load->view('customers/factor', $data);
    }

    public function final_checkout()
    {
        if (!$this->is_post_request()) return;

        $id = $this->input->post('id');
        if ($this->Customers_model->final_checkout($id)) {
            $this->session->set_flashdata('msg', 'فاکتور با موفقیت ثبت شد');
            ejson(array("success" => true));
        } else {
            $this->session->set_flashdata('msg', 'خطا در ثبت فاکتور');
            ejson(array("success" => false, "msg" => "خطا در ثبت فاکتور"));
        }
    }

    public function sales_list()
    {

        $draw = $this->input->post("draw");

        $ret = $this->_sales_list();
        foreach ($ret["data"] as $key => &$value) {
            $value->created_at_persian = convert_gregorian_iso_to_jalali_iso($value->created_at)
                . " " . date("H:i:s", strtotime($value->created_at));

        }
        $sum          = 0;
        $show_summary = false;
        if (isset($_SESSION['from_date']) && isset($_SESSION['to_date'])) {
            $show_summary = true;
            $sum          = $this->_sales_list("sum");
        }

        ejson(array("data"            => $ret["data"],
                    "recordsTotal"    => $ret["total"],
                    "draw"            => $draw,
            // "recordsFiltered"=>count($ret["data"]),
                    "recordsFiltered" => $ret["total"],
                    "sum"             => $sum,
                    "show_summary"    => $show_summary));
    }

    private function _sales_list($method = "select", $ret_query_only = false)
    {
        $draw      = $this->input->post("draw");
        $start     = $this->input->post("start");
        $len       = $this->input->post("length");
        $is_search = !empty($_POST["search"]["value"]);

        // $ret  = $this->db->order_by('created_at','DESC')->get('transactions',$start,$len)->result();
        if ($method == "select") {
            $q = 'first_name,last_name,transactions.id,transactions.customer_id,transactions.checkin,transactions.checkout,transactions.vorodi,transactions.extra_services,transactions.total_with_discount,transactions.total,transactions.extras_total,transactions.created_at';
            $this->db->select($q);
            $this->db->from('transactions');
            $this->db->join('customers', 'transactions.customer_id = customers.id');
            $where = '';
            if (isset($_SESSION['from_date']) && isset($_SESSION['to_date'])) {
                $from_date = $this->parse_persian_date($_SESSION["from_date"]);
                $to_date   = $this->parse_persian_date($_SESSION["to_date"]);

                $where = " (transactions.created_at >= '" . $from_date . "' and transactions.created_at <= '" . $to_date . "') ";
            }

            if (!empty($_POST["search"]["value"])) {
                $s           = $_POST["search"]["value"];
                $name_search = " ( (first_name like  '%{$s}%') or (last_name like '%{$s}%') ) ";
                $where       = empty($where) ? $name_search : ' and ' . $name_search;
            }
            if (!empty($where))
                $this->db->where($where);

            $this->db->order_by('transactions.created_at', 'DESC');
            $total = $this->db->count_all_results('', false);
            // xlog($this->db->get_compiled_select());
            // exit;
            if ($ret_query_only)
                return $this->db->get_compiled_select();
            $this->db->limit($len, $start);
            return array("total" => $total, "data" => $this->db->get()->result());
        }

        if ($method == "sum") {
            $this->db->select_sum('total_with_discount', 'total');
            $where = '';
            if (isset($_SESSION['from_date']) && isset($_SESSION['to_date'])) {
                $from_date = $this->parse_persian_date($_SESSION["from_date"]);
                $to_date   = $this->parse_persian_date($_SESSION["to_date"]);

                $where = " (transactions.created_at >= '" . $from_date . "' and transactions.created_at <= '" . $to_date . "') ";
            }

            if (!empty($_POST["search"]["value"])) {
                $s           = $_POST["search"]["value"];
                $name_search = " ( (first_name like  '%{$s}%') or (last_name like '%{$s}%') ) ";
                $where       = empty($where) ? $name_search : ' and ' . $name_search;
            }
            if (!empty($where))
                $this->db->where($where);
            //xlog($this->db->get_compiled_select());

            if ($ret_query_only)
                return $this->db->get_compiled_select();

            $ret = $this->db->get('transactions')->result();
            if (count($ret))
                return $ret[0]->total;
            else
                return 0;
        }

    }

    public function sales()
    {
        if (!$this->check_perm('view_sales')) return;

        $data["active_menu"] = 'm-reports';

        if ($this->session->clear_search) {
            $this->session->unset_userdata("from_date");
            $this->session->unset_userdata("to_date");
        }
        if (!$this->session->clear_search) {
            $this->session->set_userdata("clear_search", true);
        }

        $data["from_date"] = $this->session->from_date;
        $data["to_date"]   = $this->session->to_date;
        $this->view('customers/sales', $data);
    }

    public function sales_search()
    {

        if ($this->session->clear_search) {
            $this->session->unset_userdata("from_date");
            $this->session->unset_userdata("to_date");
        }

        $from_date = $this->input->get('fd');
        $to_date   = $this->input->get('td');

        if ($this->input->get('duration')) {
            switch ($this->input->get('duration')) {
                case 'thismonth':
                    $from_date = jalali_beginning_of_this_month();
                    $to_date   = jalali_end_of_this_month();
                    break;
                case 'lastmonth':
                    $from_date = jalali_beginning_of_last_month();
                    $to_date   = jalali_end_of_last_month();
                    break;
            }
        }
        $to_date   = str_replace("-", "/", $to_date);
        $from_date = str_replace("-", "/", $from_date);

        $this->session->set_userdata(
            array(
                "from_date"    => $from_date,
                "to_date"      => $to_date,
                "clear_search" => false
            )
        );
        redirect('customers/sales');
    }


    private function parse_persian_date($date)
    {
        list($year, $month, $day) = explode("/", $date);
        $from_date = JalaliToGregorian($year, $month, $day);
        $from_date = date_create($from_date[2] . "-" . $from_date[1] . "-" . $from_date[0]);
        $from_date = date_format($from_date, 'Y-m-d');
        return $from_date;
    }

    public function mobile_check($mob)
    {
        if ($mob == '') return true;
        else if (!mobile_number_is_valid($mob)) {
            $this->form_validation->set_message('mobile_check', 'شماره موبایل نامعتبر');
            return false;
        }
        return true;
    }

    public function add_discount()
    {
        if (!$this->is_post_request()) return;

        $customer_id = $this->input->post('customer-id');
        $rfid        = $this->input->post('customer-rfid');
        $discount    = $this->input->post('discount');
        $discounts   = $this->session->has_userdata('discounts') ? $this->session->userdata('discounts') : array();

        if ($discount && is_numeric($discount)) {
            $discounts[strval($customer_id)] = $discount;
            $this->session->set_userdata("discounts", $discounts);
        }


        redirect("customers/manage?id={$rfid}");
    }

    public function remove_discount()
    {
        if (!$this->is_post_request()) return;

        $customer_id = $this->input->post('customer-id');
        $rfid        = $this->input->post('rfid');
        $discounts   = $this->session->has_userdata('discounts') ? $this->session->userdata('discounts') : array();

        if (isset($discounts[strval($customer_id)])) {
            unset($discounts[strval($customer_id)]);
            $this->session->set_userdata("discounts", $discounts);
        }

        redirect("customers/manage?id={$rfid}");
    }

    public function cancel_card()
    {
        if (!$this->is_post_request()) return;

        $id = $this->input->post('id');
        $this->db->set('card_canceled', 'yes');
        $this->db->where('id', $id);
        $res = $this->db->update('customers');
        if ($res) {
            return ejson(array("success" => true, "msg" => ""));
        } else {
            return ejson(array("success" => false, "error" => "خطا در غیر فعال سازی کارت"));
        }
    }

}
