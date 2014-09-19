<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	$success = false; 
	$message = "";
	
	if( isset( $_POST["json"] ) ){
		$json = json_decode( $_POST['json'], true );
		$path = trim( strip_tags( $json["folder_path"] ) );
		$id = trim( strip_tags( $json["id"] ) );
		$post_data = $json["post_data"];
		
		if( count( $post_data > 0 ) ){
			$post_formatter = new PostFormatter();
			$saved = $post_formatter->savePostFileArrayData( array( "folder_path"=>$path, "id"=>$id ), $post_data );
			$success = $saved;
			$message = ( $saved )? "Post Edited" : "File Does Not Exist"; 
		}
		
	}
	echo returnMessage( $success, $message, null );
?>