<?php
	include_once dirname(__FILE__)."/../configs.php";
	$result = false;
	$message = "Not Logged In";
	
	if( true ){
	
		$json = json_decode( $_POST['json'], true );
		$id = $json["id"];
		
		try{	
			$db = MongoConnection();
			$db_getter = new MongoGetter( $db );
			$deleted = $db_getter->removeSingleRowById( $id );
			$result = true;
			$message = 'Deleted';
		
		} catch( MongoCursorException $e ) {;
			$message = "error message: ".$e->getMessage()."\n";
		}
	
	}
	echo returnMessage( $result, $message, null );

?>