<?php
	$main = dirname(__FILE__);  //  main directory 	
	$server = $main."/../server"; // server directory
	include_once $server."/configs.php";
	 
	echo var_dump( $GLOBALS['url_parts'][0]==="" ).'<br>';	
	echo print_r( $GLOBALS['url_parts'] );
	$url_parts = $GLOBALS['url_parts'];	
	$part_count = count( $url_parts );
	
	if( in_array( $url_parts[0], $GLOBALS['post_categories'] ) || $url_parts[0] === "" ){
		//if the first sction of url is a category bring to blog.php where the paginated code is
		$_GET['cat'] = ( $url_parts[0] !== "" )? $url_parts[0] : $GLOBALS['post_categories'][0]; //cat is first url part or the default cat	
		$_GET['page'] = ( $part_count > 1 )? (int)$url_parts[ $part_count-1 ] : 1; //page is always last part of url		
		if( $part_count >= 3 ){ echo $url_parts[1]; }  //if url part is 3 entries long search is the second entry		
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
	
	( $file !== "" )? include_once( $main.$file ) : header( $_SERVER["SERVER_PROTOCOL"]." 404 Not Found" ); 
?>