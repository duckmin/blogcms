<?php
	$server = dirname(__FILE__)."/../server";
	include_once $server."/configs.php";
	$base = $GLOBALS['base_url'];
	$url = $_SERVER["REQUEST_URI"];
	$cache = new CacheController( $GLOBALS['cache_dir'], $url );

	if( /*!$cache->urlInCache()*/true ){
		
		if( !isset( $_GET['p'] ) ){ 
			$page=1;
		}else{
			$page=(int)$_GET['p'];
		}
		
		$cat = ( isset( $_GET['cat'] ) )? $_GET['cat'] : null;
		$search = ( isset( $_GET['s'] ) )? $_GET['s'] : null;
		
		$tmplt_data = array();
		$tmplt_data["title"] = "Blog - Page ".$page;
		$tmplt_data["description"] = "";
		$tmplt_data["styles"] = "";
		$tmplt_data["scripts"] = "";
		$tmplt_data["base"] = $base;
		
		$db = DBHelper::dbConnection();
		$db_getter = new DbGetter( $db );
		$post_views = new PostViews();
		$post_controller = new PostController( $db_getter, $post_views );	
		
		$tmplt_data["body"] = "<section class='main'>".$post_controller->getHomePagePosts( $page, $cat, $search )."</section>";
		
		$base_page = new TemplateBinder( "base_page" );
		$full_page = $base_page->bindTemplate( $tmplt_data );
		//$cache->saveUrlContentToCache( $full_page );
		echo $full_page;
		
	}else{
	
		echo $cache->pullUrlContentFromCache();
		
	}
?>