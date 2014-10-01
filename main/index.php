<?php
	$server = dirname(__FILE__)."/../server";
	include_once $server."/configs.php";
	$base = $GLOBALS['base_url'];
	$url = $_SERVER["REQUEST_URI"];
	$cache = new CacheController( $GLOBALS['cache_dir'], $url );
	if( !$cache->urlInCache() || $cache->cacheMinutesOverLimit( $GLOBALS['max_page_cache_mins'] ) ){
	    echo var_dump( $cache->cacheMinutesOverLimit( $GLOBALS['max_page_cache_mins'] ) );
		if( !isset( $_GET['p'] ) ){ 
			$page=1;
		}else{
			$page=(int)$_GET['p'];
		}
		
		$post_views = new PostViews();		
		$cat = ( isset( $_GET['cat'] ) )? $_GET['cat'] : $GLOBALS['post_categories'][0];
		$search = ( isset( $_GET['s'] ) )? $_GET['s'] : null;
		
		$tmplt_data = array();
		$tmplt_data["title"] = "Blog - Page ".$page;
		$tmplt_data["description"] = "";
		$tmplt_data["styles"] = "";
		$tmplt_data["scripts"] = "";
		$tmplt_data["base"] = $base;
		$tmplt_data["header"] = $post_views->getCatHeaderList( $cat );
		
		$db = new MongoClient();
		$db_getter = new MongoGetter( $db );
		$post_controller = new PostController( $db_getter, $post_views );	
		
		$tmplt_data["body"] = "<section class='main'>".$post_controller->getHomePagePosts( $page, $cat, $search )."</section>";
		
		$base_page = new TemplateBinder( "base_page" );
		$full_page = $base_page->bindTemplate( $tmplt_data );
		$cache->saveUrlContentToCache( $full_page );
		echo $full_page;
		
	}else{
	    echo "pulled from cache";
	    //$cache->cacheMinutesOverLimit( $GLOBALS['max_page_cache_mins'] );
		echo $cache->pullUrlContentFromCache();
		
	}
?>
