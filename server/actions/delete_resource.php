<?php
	include_once dirname(__FILE__)."/../configs.php";
	$result = false;
	$message = "File Not Found";
	$logged_in = ManagerActions::isLoggedIn();
	
	if( $logged_in ){ //logged in
	
		$json = json_decode( $_POST['json'], true );
		$file_path = $json["file_path"];
		$server_path = $GLOBALS['index_path'].$file_path;
		if( is_file( $server_path ) ){
			$deleted = unlink( $server_path );
			$result = $deleted;
			$message = ($deleted)? "File Deleted" : "File Not Deleted";
		}
	
	}
	echo returnMessage( $result, $message, null );

?>