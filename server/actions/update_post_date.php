<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	$success = false; 
	$message = "";
	
	if( true && isset( $_POST["json"] ) ){
		$json = json_decode( $_POST['json'], true );
		$id = trim( strip_tags( $json["id"] ) );
		
		try{			
			$db = MongoConnection();
			$db_getter = new MongoGetter( $db );
			$result = $db_getter->renewPostDate( $id );
			$success = ( $result['nModified'] === 1 )? true : false;
			$message = ( $success )? "Post Moved To Top" : "Could not update post with id ".$id; 
		} catch( MongoCursorException $e ) {;
			$message = "error message: ".$e->getMessage()."\n";
		}
		
	}
	echo returnMessage( $success, $message, null );
?>