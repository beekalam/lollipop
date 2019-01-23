<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/BaseController.php");
require_once(APPPATH . 'libraries/jdf.php');
require_once(APPPATH . 'libraries/lib_date.php');

class Settings extends BaseController
{


    function __construct()
    {
        parent::__construct();
        $this->load->library('Jalali_date');
        $this->load->model('Settings_model');
    }

    public function index()
    {
        if (!$this->check_perm('view_settings')) return;

        $data['active_menu'] = 'm-settings';
        $data['settings']    = $this->Settings_model->get_all_settings();
        $data['prices']      = $this->Settings_model->get_all_prices()->result();
        $data['categories']  = $this->Settings_model->categories()->result();
        $data['vorodi']      = $this->db->get('vorodi')->result();
        $data['discounts']   = $this->db->get('discounts')->result();

        foreach ($data['prices'] as &$v) {
            foreach ($data['categories'] as $cats) {
                if ($v->cat_id == $cats->id) {
                    $v->cat_name = $cats->name;
                    break;
                }
            }
        }

        $this->view('settings/settings', $data);
    }

    public function update()
    {
        if (!$this->is_post_request()) return;

        foreach ($_POST as $key => $value) {
            $key = str_replace("0_", "0.", $key);
            $this->Settings_model->update($key, $value);
        }

        redirect("/settings");
    }

    //-------------------------------------------------
    // price
    //-------------------------------------------------

    public function add_update_price()
    {
        if (!$this->is_ajax_post()) return;
        if (!$this->check_perm('add_extra_service') or
            !$this->check_perm('edit_extra_service')) return;

        $price       = $this->input->post('price');
        $description = $this->input->post('description');
        $cat_id      = $this->input->post('cat_id');
        $action      = $this->input->post('action');
        $price_id    = isset($_POST["price_id"]) ? $this->input->post('price_id') : null;

        if ($this->Settings_model->add_update_price($price, $description, $cat_id, $action, $price_id)) {
            return ejson(array("success" => true));
        } else {
            return ejson(array("success" => false, "msg" => "error inserting price"));
        }
    }

    public function delete_price()
    {
        if (!$this->is_ajax_post()) return;
        if (!$this->check_perm('delete_extra_service')) return;

        $id = $this->input->post('id');
        if ($this->Settings_model->delete_price($id)) {
            return ejson(array("success" => true));
        } else {
            return ejson(array("success" => false, "msg" => "error deleting price"));
        }
    }

    public function get_price()
    {
        if (!$this->is_ajax_post()) return;

        $id  = $this->input->post('id');
        $ret = $this->Settings_model->get_price($id)->result();
        if (count($ret) != 0)
            ejson(array("success" => true, "data" => $ret));
        else
            ejson(array("success" => false));
    }

    //---------------------------------------------
    // duration
    //---------------------------------------------
    public function add_duration()
    {
        if (!$this->is_post_request()) return;
        if (!$this->check_perm('add_duration')) return;

        $description = $this->input->post('vorodi-description');
        $duration    = $this->input->post('vorodi-duration');
        // pre($_POST);
        $duration_parts = explode(':', $duration);
        $duration       = ($duration_parts[0] * 3600) + ($duration_parts[1] * 60);
        $price          = $this->input->post('vorodi-price');
        $data           = array("duration" => $duration, "description" => $description, "price" => $price);

        if ($this->duration_is_duplicate($duration)) {
            $this->session->set_flashdata("msg", "مدت زمان تکراری می باشد.");
            redirect('settings');
        }

        if ($this->db->insert('vorodi', $data)) {
            $this->session->set_flashdata("msg", "تنظیمات با موفقیت ذخیره شد");
        } else {
            $this->session->set_flashdata("msg", "خطا در ذخیره سازی تنظیمات");
        }

        redirect('settings');
    }

    public function update_duration()
    {
        if (!$this->is_post_request()) return;
        if (!$this->check_perm('edit_duration')) return;

        $duration = $this->input->post('vorodi-duration');
        // if($this->duration_is_duplicate($duration)){
        // 	$this->session->set_flashdata("msg","مدت زمان تکراری می باشد.");
        // 	redirect('settings');
        // }

        $this->db->set('description', $this->input->post('vorodi-description'));
        $this->db->set('price', $this->input->post('vorodi-price'));
        $this->db->where('id', $this->input->post('vorodi-id'));

        if ($this->db->update('vorodi')) {
            $this->session->set_flashdata("msg", "تنظیمات با موفقیت ذخیره شد");
        } else {
            $this->session->set_flashdata("msg", "خطا در ذخیره سازی تنظیمات");
        }

        redirect('settings');
    }

    public function delete_duration()
    {
        if (!$this->is_post_request()) return;
        if (!$this->check_perm('delete_duration')) return;


        $res      = $this->db->delete('vorodi', array("id" => $this->input->post('what')));
        $json_res = array("success" => "true");
        if ($res) {
            // $this->session->set_flashdata("msg","حذف بازه با موفقیت ثبت شد");
        } else {
            // $this->session->set_flashdata("msg","خطا در حذف بازه");
            $json_res["success"] = false;
            $json_res["error"]   = "خطا در حذف بازه";
        }
        ejson($json_res);
    }

    public function validate_duration()
    {
        if (!$this->is_ajax_post()) return;

        $duration       = $this->input->post('what');
        $duration_parts = explode(':', $duration);
        $duration       = ($duration_parts[0] * 3600) + ($duration_parts[1] * 60);
        $res            = array("success" => true, "msg" => "");
        if ($this->duration_is_duplicate($duration)) {
            $res["success"] = false;
            $res["msg"]     .= "این بازه زمانی قبلا ثبت شده است";
        }
        ejson($res);
    }

    private function duration_is_duplicate($duration)
    {
        return count($this->db->get_where('vorodi', array('duration' => $duration))->result()) > 0;
    }

    //--------------------------------------------
    //   categories
    //--------------------------------------------
    public function add_category()
    {
        if (!$this->check_perm('add_category')) return;

        $name = $this->input->post('category-name');
        $name = trim($name);

        $res = $this->validate_category($name);

        if ($res["success"] and !empty($name)) {
            $ret = $this->db->insert('categories', array('name' => $name));
            $this->session->set_flashdata("msg", "اطلاعات با موفقیت ثبت شد");
        }
        redirect('settings');
    }


    private function validate_category($name)
    {
        $res = array("success" => true, "error" => "");

        $row = $this->db->get_where('categories', array("name" => $name))->result();
        if (count($row)) {
            $res["success"] = false;
            $res["error"]   = "دسته ای با این نام قبلا ثبت شده است";
        }
        return $res;
    }

    public function ajax_validate_category()
    {
        return ejson($this->validate_category($this->input->post('name')));
    }


    public function delete_category()
    {
        if (!$this->check_perm('delete_category')) return;

        $id  = $this->input->post("id");
        $res = $this->validate_category_delete($id);

        if ($res["success"]) {
            $ret = $this->db->delete('categories', array("id" => $id));
            return ejson(array("success" => $ret, "msg" => ""));
        } else {
            return ejson(array("success" => false, "error" => $res["error"]));
        }

    }

    public function validate_category_delete($id)
    {
        $res = array("success" => true, "error" => "");
        $row = $this->db->get_where('prices', array('cat_id' => $id))->result();
        if (count($row)) {
            $res["success"] = false;
            $res["error"]   = "هزینه هایی مربوط به این دسته وجود دارد";
        }
        return $res;
    }
    //--------------------------------------------------------
    // 	discounts
    //--------------------------------------------------------
    public function ajax_validate_discount()
    {
        if (!$this->is_ajax_post()) return;

        $discount = $this->input->post('what');
        $row      = $this->db->get_where('discounts', array('value' => $discount))->result();
        return ejson(array("success" => count($row) == 0, "error" => "این مقدار تخفیف قبلا ثبت شده است."));
    }

    public function add_discount()
    {
        if (!$this->is_post_request()) return;
        if (!$this->check_perm('add_discount')) return;

        $discount = $this->input->post('discount-value');
        $res      = $this->db->insert('discounts', array('value' => $discount));
        if ($res) {
            $this->session->set_flashdata("msg", "اطلاعات با موفقیت ثبت شد");
        } else {
            $this->session->set_flashdata("msg", "خطا در ثبت اطلاعات");
        }
        redirect('settings');
    }

    public function delete_discount()
    {
        if (!$this->is_post_request()) return;
        if (!$this->check_perm('delete_discount')) return;

        $id  = $this->input->post('id');
        $res = $this->db->delete('discounts', array("id" => $id));
        if ($res) {
            return ejson(array("success" => true, "msg" => ""));
        }

        return ejson(array("success" => false, "error" => "خطا در حذف تخفیف"));
    }

}
