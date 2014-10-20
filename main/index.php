<?php
	$main = dirname(__FILE__);  //  main directory 	
	$server = $main."/../server"; // server directory
	include_once $server."/configs.php";
	 
	//echo var_dump( $GLOBALS['url_parts'][0]==="" ).'<br>';	
	//echo print_r( $GLOBALS['url_parts'] );
	$url_parts = $GLOBALS['url_parts'];	
	$part_count = count( $url_parts );
	
	if( in_array( $url_parts[0], $GLOBALS['post_categories'] ) || $url_parts[0] === "" ){
		//if the first sction of url is a category bring to blog.php where the paginated code is
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
		goTo404();
		die();
	} 
?>