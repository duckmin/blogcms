<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	$json = json_decode( $_POST['json'], true );
	$post_view = new PostViews();
	$single = array();
	$single["id"] ="#"; 
	$single["tags"] = "";
	$single["created"] = "2014-08-14 21:17:42";
	$single["title"] = "Preview";
	echo $post_view->makePostHtmlFromData( $single, $json  );

?>