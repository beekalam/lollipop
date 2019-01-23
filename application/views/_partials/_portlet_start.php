<?php
$title = isset($title) ? $title : "";
$portlet_class= isset($portlet_class) ? $portlet_class : "box red";
$caption_class= isset($captoon_class) ? $caption_class : "icon-list";

?>

<div class="portlet <?php echo $portlet_class; ?>">
	<div class="portlet-title">
	    <div class="caption">
	        <i class="<?php echo $caption_class; ?>"></i>
	        <span class="caption-subject bold uppercase"><?php echo $title; ?></span>
	    </div> 
	    <?php 
	    	if(isset($actions_parser) && is_callable($actions_parser)){
	    		echo "<div class='actions'>";
	    			echo $actions_parser();
	    		echo "</div>";
	    	}
	     ?>
	</div>
<?php 
if(isset($body_parser) && is_callable($body_parser)){
	echo "<div class='portlet-body'>";
		echo $body_parser($vm);
	echo "</div>";
	echo "</div>";
}
?>


