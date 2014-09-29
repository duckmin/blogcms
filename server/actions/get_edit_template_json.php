<?php
	include_once dirname(__FILE__)."/../configs.php";
	$json = json_decode( $_POST['json'], true );
	$id = $json["id"];
	//$path = $json["folder_path"];
	$db = new MongoClient();
	$db_getter = new MongoGetter( $db );
	$post_formatter = new PostFormatter();		
	$data = $db_getter->getSinglePostDataById( $id );
	$ret = json_encode( $data["post_data"] );
	echo $ret;

?>