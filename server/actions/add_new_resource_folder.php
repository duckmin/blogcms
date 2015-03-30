<?php
	include_once dirname(__FILE__)."/../configs.php";
	$result = false;
	$message = "";
	$data = null;
	$logged_in = ManagerActions::isLoggedIn();
	
	if( $logged_in && isset( $_POST['json'] ) ){ //logged in
	
		$json = json_decode( $_POST['json'], true );
		$folder_name = $json["folder_name"];
		$folder_path = $json["folder_path"];
		$illegal_chars = preg_match( "/[\/\s\\\\]/", $folder_name ); //4 /'s in a row match backslash
		
		if( !$illegal_chars && strlen( $folder_name ) > 0 ){
			$f_path = $folder_path."/".$folder_name;
			$folder_pwd = $GLOBALS['index_path']."/".$f_path;
			if( !is_dir( $folder_pwd ) ){
				if( mkdir( $folder_pwd, 0774 ) ){
					$message = "Folder Added";
					$result = true;
					$data = FileGetter::folderLi( $f_path, "0", "folderUpload" );
				}else{
					$message = "Create Folder Failed";
				}
			}else{
				$message = $folder_name." is already in folder in ".$folder_path;		
			}
		}else{
			$message = "Folder name can contain no spaces, or slashes";	
		}
	
	}
	echo returnMessage( $result, $message, $data );
	
?>