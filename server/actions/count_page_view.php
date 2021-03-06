<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	$success = false; 
	$message = "";
	//sleep(15);
	if( isset( $_POST["url"] ) ){		
		$visited_url = $_POST["url"];
		
		$ip = $_SERVER['REMOTE_ADDR'];
		
		if( filter_var( $ip, FILTER_VALIDATE_IP) ){
			$db = MongoConnection();
			$dt = date('Y-m-d'); //todays date with no time
			
			$ts = new MongoDate( strtotime( $dt." 00:00:00" ) ); //time of 00:00:00 because we only want 1 record per page per day	
				
			$write_result = $db->$GLOBALS['mongo_db_name']->analytics->update( array("url"=>$visited_url,'date'=>$ts ), array( '$inc'=>array('views'=>1), '$addToSet'=>array( 'ips'=>$ip ) ), array('upsert'=>true) );
			$success = ( $write_result['n'] === 1 )? true : false;	
		}	
	}
	echo returnMessage( $success, $message, null );
?>