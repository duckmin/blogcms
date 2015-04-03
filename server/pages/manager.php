<?php
	$server = dirname(__FILE__)."/../";
	require_once $server."/configs.php";
	//$manager = new ManagerActions();
	
	if( !isset( $_SERVER['PHP_AUTH_USER'] ) && !isset( $_SERVER['PHP_AUTH_PW'] ) ){
		
		ManagerActions::authenticate();
		
	}else{
		
		if( !ManagerActions::isLoggedIn() ){
			$sent_user = $_SERVER['PHP_AUTH_USER'];
			$sent_pw = $_SERVER['PHP_AUTH_PW'];
			
			if( ManagerActions::loginSuccess( $sent_user, $sent_pw )  ){	//if true will set 2 session variables and log to file
				//give access to page if user is is a key in array and the value matches the PW
				
				//TODO: write to log file				
				include $server."/pages/manager_body.php";
	
			}else{
				//wrong guess make sleep to prevent brute force
				sleep(4);
				ManagerActions::authenticate();
			}
		}else{
			include $server."/pages/manager_body.php";
		}
	}//end else
?>
