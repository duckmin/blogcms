<?php
	include_once dirname(__FILE__)."/../configs.php";
	$json = json_decode( $_POST['json'], true );
	$id = $json["id"];
	$path = $json["folder_path"];
	
	$post_formatter = new PostFormatter();		
	$data = $post_formatter->getPostFileArrayData( array( "folder_path"=>$path, "id"=>$id ) );
	$ret = ( $data )? json_encode( $data ) : "";
	echo $ret;

?>