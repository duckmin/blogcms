<?php
	require_once dirname(__FILE__)."/../configs.php";
		
	class ManagerActions {
 
		public static function isLoggedIn( ){
			if( isset( $_SESSION['user'] ) && isset( $_SESSION['level'] ) ){
				return true;
			}else{
				return false;	
			}
		}
		  
		public static function authenticate(){
			include $GLOBALS['server_path']."/pages/manager_login.php";
		}
		  
		public static function loginSuccess( $user, $pw ){
			$users = json_decode( file_get_contents( $GLOBALS['server_path']."/includes/logins.json" ), true );  
		 
			if( array_key_exists( $user, $users ) && $users[$user]["pw"] === $pw ){	
				$_SESSION['user'] = $user;
				$_SESSION['level'] = $users[$user]["level"];
				$_SESSION['created'] = time();
				return true;
			}else{
				return false;	
			}
		}
	
	}
   
?>
