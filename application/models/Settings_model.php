<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . "helpers/utils.php");

class Settings_model extends CI_Model
{

    function __construct()
    {
    }

    public function get_all_settings()
    {
        $this->db->order_by('value', 'ASC');
        return $this->db->get('settings');
    }

    public function update($key, $value)
    {
        $this->db->set("value", $value);
        $this->db->where("name", $key);
        $this->db->update('settings');
    }

    public function get_all_prices()
    {
        return $this->db->get('prices');
    }

    public function get_price($id)
    {
        return $this->db->where('id', $id)->get('prices');
    }

    public function add_update_price($price, $description, $cat_id, $action, $price_id)
    {
        $price = en_num($price);
        // log_message('debug','Settings_model::add_price()');
        // log_message('debug',json_encode(array($price,$description,$cat_id,$price_id)));
        if ($action == 'insert') {
            return $this->db->insert('prices',
                array('price' => $price, "description" => $description, "cat_id" => $cat_id));
        } else if ($action == 'update') {
            $this->db->set('cat_id', $cat_id);
            $this->db->set('description', $description);
            $this->db->set('price', $price);
            $this->db->where('id', $price_id);
            return $this->db->update('prices');
        }
    }

    public function delete_price($id)
    {
        return $this->db->delete('prices', array("id" => $id));
    }

    public function categories()
    {
        return $this->db->get('categories');
    }


    public function discount_rules()
    {
        return array(
            array("buy_amount" => 20000, "discount_type" => "percent", "discount_value" => "4", "num_days_to_consider" => "30"),
            array("buy_amount" => 30000, "discount_type" => "percent", "discount_value" => "5", "num_days_to_consider" => "20")
        );
    }
}