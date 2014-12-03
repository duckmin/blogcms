<?php
	require_once dirname(__FILE__)."/../configs.php";
		
	class ManagerActions {
       
        public $template_name;
       
        public function __construct(){
            
        }
		
		public function isLoggedIn( ){
            if( isset( $_SESSION['user'] ) && isset( $_SESSION['level'] ) ){
            	return true;
            }else{
            	return false;	
            }
        }
        
        public function authenticate(){
            header('WWW-Authenticate: Basic realm="Manager"');
			header('HTTP/1.0 401 Unauthorized');
        }
        
         public function loginSuccess( $user, $pw ){
			$users = json_decode( file_get_contents( $GLOBALS['server_path']."/includes/logins.json" ), true );  
			          
            if( array_key_exists( $user, $users ) && $users[$user]["pw"] === $pw ){	
            	$_SESSION['user'] = $user;
				$_SESSION['level'] = $users[$user]["level"];
				return true;
			}else{
				return false;	
			}
        }
   
    }
   
?>