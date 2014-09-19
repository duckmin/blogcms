<?php
	include_once dirname(__FILE__)."/../configs.php";
	$result = false;
	$message = "Not Logged In";
	
	if( true ){
	
		$json = json_decode( $_POST['json'], true );
		$id = $json["id"];
		$path = $json["folder_path"];
		
		try{	
			$db_conn=DBHelper::dbConnection();
			$db_getter = new DbGetter( $db_conn );	
			$db_conn->beginTransaction();
			$deleted = $db_getter->deletePost( $id, $path );
			
			if( $deleted ){
				$db_conn->commit();
				$result = true;
				$message = 'Deleted';
			}else{
				$db_conn->rollback();
				$message = $json["folder_path"].' Does Not Exist';
			}
		
		}
		catch(PDOException $e) {
			$db_conn->rollback();
			$message = 'ERROR:'.$e->getMessage();
		}
	
	}
	echo returnMessage( $result, $message, null );

?>