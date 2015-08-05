<?php
	$root_dir = dirname(__FILE__)."/../";
	include_once $root_dir."/server/configs.php";
	$url_parts = $GLOBALS['url_parts'];	
	$part_count = count( $url_parts );
	
	if( in_array( $url_parts[0], $GLOBALS['post_categories'] ) || $url_parts[0] === "" ){
		//if the first sction of url is a category bring to blog.php where the paginated code is
		$file = '/server/pages/blog.php';
	}else{	
		switch ( $GLOBALS['url_parts'][0] ) {
			
			case "post":
				$file = '/server/pages/post.php';
				break;
				
			case "search":
				$file = '/server/pages/search_page.php';
				break;
				
			case "ajax":
				$file = '/server/pages/ajax.php';
				break;
				
			case "submit-search":
				$file = '/server/pages/search.php';
				break;
				
			case $GLOBALS['manager_keyword']:
				$file = '/server/pages/manager.php';
				break;
				
			case "logout":
				$file = '/server/pages/logout.php';
				break;
						
			default:
				$file = "";
				break;

		}
		
	}
	
	if( $file !== "" ){
		include_once( $root_dir.$file ); 
	}else{
		goTo404();
		die();
	} 
?>