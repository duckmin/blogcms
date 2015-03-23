<?php
	include_once dirname(__FILE__)."/../../configs.php";
	
	$success = false; 
	$message = "";

	if( true ){	
		$db = MongoConnection();
		$db_getter = new MongoGetter( $db );	
		$data = $db_getter->getUniqueAnalyticUrlPage( );
		$list = "";
		foreach( $data as $url ){
			$list.="<li data-url=".$url." onclick='urlClickAction(this)' >".$url."</li>";		
		}		
	}
	echo $list
?>