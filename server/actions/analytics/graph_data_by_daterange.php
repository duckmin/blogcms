<?php
	include_once dirname(__FILE__)."/../../configs.php";
	
	$holder = array();

	if( isset( $_POST['json'] ) ){	
		$db = MongoConnection();
		$db_getter = new MongoGetter( $db );
		$json = json_decode( $_POST['json'], true );
		$url = $json['url'];
		//$start_date = new DateTime( $json['start_date'] );
		//$end_date = new DateTime( $json['end_date'] );
		//$start = $start_date->format( DateTime::ISO8601 );
		//$end = $end_date->format( DateTime::ISO8601 );
		$start = strtotime( $json['start_date'] );
		$end = strtotime( $json['end_date'] );
		$data = $db_getter->getPageCountsByUrlAndDateRange( $url, $start, $end );
		
		foreach( $data as $row ){
			$tmp = array();
			$sec = $row["date"]->sec;
			$dt = new DateTime("@$sec");	
			$tmp['date'] = $dt->format('m/d/Y');
			$tmp['views'] = $row['views'];
			$tmp['unique'] = count( $row["ips"] );
			array_push( $holder, $tmp ); 		
		}		
	}
	echo json_encode( $holder );
?>