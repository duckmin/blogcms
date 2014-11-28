<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	$json = json_decode( $_POST['json'], true );
	$post_view = new PostViews( new Parsedown );
	$single = array();
	$single["_id"] ="5428784f7f8b9afe1a779e93"; 
	$single["lastModified"] = new MongoDate();
	$single["title"] = "Preview";
	$single["post_data"] = $json;
	echo $post_view->makePostHtmlFromData( $single, $GLOBALS['post_categories'][0]  );
	//post category is just a placeholder the link will not work i the preview it is just a sample
?>