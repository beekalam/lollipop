<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once (APPPATH."controllers/BaseController.php");
require_once (APPPATH.'libraries/jdf.php');
require_once (APPPATH.'libraries/lib_date.php');


class Tmp extends BaseController {

	function __construct(){
		parent::__construct();
		$this->load->model("Users_model");
		$this->load->model("Customers_model");
		$this->load->model("Settings_model");
		$this->load->library('Jalali_date');
	}

    public function index()
    {
        foreach(array_diff(get_class_methods("Tmp"),get_class_methods(get_parent_class())) as $item)
        {
            if($item=="index") continue;
            echo anchor("tmp/" . $item,$item) . "<br/>";
        }
    }


    public function insert_test_transactions()
    {
        $template=array(
            "customer_id" => 8251,
            "user_id" => 14,
            "checkin" => 1518496415,
            "checkout" => 1518496428,
            "extra_services" => '{"id":"8","description":"\u062f\u0633\u062a\u06af\u0627\u0647 vr","price":"5000","num":"1","customer_id":"8251"},{"id":"9","description":"\u062f\u0633\u062a\u06af\u0627\u0647 vr","price":"5000","num":"1","customer_id":"8251"}',
            "vorodi" => 500,
            "extras_total" => 10000,
            "total" => 10500,
            "total_with_discount" => 10500,
            "discount" => 0,
            "created_at" => "2018-02-14 08:03:48"
            );
        $index = 8251;
        foreach(range(1,100) as $v){
            $template["customer_id"] = $index++;
            echo $this->db->insert('transactions',$template);    
            echo "<br/>";
        }
    }
    public function tail2()
    {
        $url = base_url('/tmp/tail');
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title></title>
        </head>
        <body>
            <iframe src='{$url}' id='if' style='width:100%;height:600px'>
            </iframe>
            <script>
             (function()
             {
                    setInterval(function()
                    {
                        document.getElementById('if').contentWindow.location.reload();
                    },1000);
             })();
            </script>
        </body>
        </html>";
    }
    public function tail()
    {

        $ds = DIRECTORY_SEPARATOR;
        $p = realpath(dirname(__FILE__) . $ds . "..".$ds);
        $p .= $ds . "logs". $ds .  "log-" .  date("Y-m-d") . ".php";// ."log-2018-02-18.php"; //"log-"  . date("Y-m-d").".php"; 
        echo "<!DOCTYPE html><html>";
        // echo '<meta http-equiv="refresh" content="1;url='. base_url('/tmp/tail') .'" />';
        echo "<body>";
        $arr = explode("\n",file_get_contents($p));

        $arr = array_reverse($arr);

        $arr = array_filter($arr,function($item){
            if(strpos($item,'Session') === false)
                    return $item;
        });

        $arr = array_slice($arr, 0,40);
        function prepare($item=''){
            if(strlen($item) > 80){
                $pos = 0;
                $ret = "";
                while( $pos < strlen($item) or $pos + 80 < strlen($item)){
                    $ret .= substr($item,$pos,80) . "<br/>";
                    $pos+=80;
                }
            }else{
               return "<pre>".$item."</pre>";
            }
            return "<pre>".$ret."</pre>";
        }
        
        array_map(function($item)
        {
            echo prepare($item) . "<div style='height:2px;background:black;'></div>";
        },$arr);

        echo "</body></html>";
    }
}