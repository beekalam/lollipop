<?php
class html {
	private $tags = array();

	private $groups = array();
	private $templates = array();

	private $debug = false;
	private $str="";
	function __construct($template_parser="codeigniter"){
		$this->debug = true;
	}

	public static function ins(){
		return new html();
	}

	public function div($params=array()){
		$item = new stdClass;
		$item->tag = "<div>";
		$item->style = isset($params["style"]) ? $params["style"] :"";
		$this->tags[] = $item;
		return $this;
	}

	public function add_content($content){
		$this->tags[] = $content;
		return $this;
	}

	public function set_template($name,$fmt)
	{
		$this->templates[] = array("name" => $name, "fmt" => $fmt);
	}

	public function echo_template($name,$str)
	{
		foreach ($this->templates as  $value) {
			if($value["name"] == $name){
				echo vprintf($value["fmt"],explode(',',$str));
				break;
			}
		}
	}

	public function set_group($name, $header,$footer)
	{
		$item = new stdClass;
		$item->header = $header;
		$item->footer = $footer;
		$this->groups[$name] = $item;	
	}

	public function group_header($name)
	{
 		
 		if(isset($this->groups[$name]))
			return $this->groups[$name]->header;
		else
		{
			$this->_dbg(sprintf("%s is empty",$name));
			return "";
		}
	}

	public function echo_group_header($name){
		echo $this->group_header($name);
	}

	public function group_footer($name)
	{
		if(isset($this->groups[$name]))
			return $this->groups[$name]->footer;
		else
		{
			$this->_dbg(sprintf("%s is empty",$name));
			return "";
		}
	}

	public function echo_group_footer($name){
		echo $this->group_footer($name);
	}

	public function echo(){
		$content = "";
		while(!empty($this->tags)){
			$item = array_pop($this->tags);
			if(is_object($item)){
				switch($item->tag){
					case "<div>":
						echo "<div style='" . $item->style . "'>" . $content . "</div>";
						$content='';
						break;
				}
			}else{
				$content .= $item;
			}
		}
	}

	private function _dbg($str)
	{
		if($this->debug)
			$this->str .= $str;
	}

	public function dbg()
	{
		return $this->str;
	}
}

$h = new html();
		// $h->div(array("style" => "background-color:red;"))->add_content('tttttttt')->echo();
		// $h->set_template("label","<label for='%s'>%s</label>");
		// $h->set_template("input","<input type='%s' id='%s' class='%s' value='%s' >");

		// $h->echo_header("form-group");
		// $h->echo_template("label",implode(',',array("test","testcontent")));
		// $h->echo_template("input",implode(',',array("button","btn-name","btn btn-sm btn-default","testvalue")));
		// $h->echo_footer("form-group");

		// exit;