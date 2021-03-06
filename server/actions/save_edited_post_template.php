<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	$success = false; 
	$message = "";
	$logged_in = ManagerActions::isLoggedIn();
	
	if( $logged_in && isset( $_POST["json"] ) ){
		$json = json_decode( $_POST['json'], true );
		$id = trim( strip_tags( $json["id"] ) );
		$post_data = $json["post_data"];
		
		if( count( $post_data > 0 ) ){
			try{			
				$db = MongoConnection();
				$db_getter = new MongoGetter( $db );
				$db_getter->updateSinglePostDataById( $id, $post_data );
				$success = true;
				$message = "Post Edited"; 
			} catch( MongoCursorException $e ) {;
				$message = "error message: ".$e->getMessage()."\n";
			}
		}
		
	}
	echo returnMessage( $success, $message, null );
?>