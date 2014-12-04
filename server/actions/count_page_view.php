<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	$success = false; 
	$message = "";
	//sleep(15);
	if( isset( $_POST["url"] ) ){		
		$visited_url = $_POST["url"];
		
		$db = new MongoClient();
		$dt = date('Y-m-d'); //todays date with no time
		
		$ts = new MongoDate( strtotime( $dt." 00:00:00" ) ); //time of 00:00:00 because we only want 1 record per page per day
		$url = "http://localhost:8080/video";	
			
		$write_result = $db->blog->analytics->update( array("url"=>$visited_url,'date'=>$ts), array('$inc'=>array('views'=>1)), array('upsert'=>true) );
		$success = ( $write_result['n'] === 1 )? true : false;		
	}
	echo returnMessage( $success, $message, null );
?>