<?php
	$main = dirname(__FILE__);  //  main directory 	
	$server = $main."/../server"; // server directory
	include_once $server."/configs.php";
	 
	echo "controlled";
	
	if( in_array( $GLOBALS['url_parts'][0], $GLOBALS['post_categories'] ) || $GLOBALS['url_parts'][0] === "" ){
	
		$file = '/blog.php';
	
	}else{	
		switch ( $GLOBALS['url_parts'][0] ) {
		
			case "post":
		        $file = '/post.php';
				break;		
			default:
				$file = "";
				break;
		}
	}
	
	if( $file !== "" ){	
		include_once( $main.$file );
	}else{
		header( $_SERVER["SERVER_PROTOCOL"]." 404 Not Found" ); 
	}
?>