<?php
	$server = dirname(__FILE__)."/../server";
	include_once $server."/configs.php";
	$base = $GLOBALS['base_url'];
	$url = $_SERVER["REQUEST_URI"];
	$cache = new CacheController( $GLOBALS['cache_dir'], $url );
	
	if( !$cache->urlInCache() || $cache->cacheMinutesOverLimit( $GLOBALS['max_page_cache_mins'] ) ){
	    //echo var_dump( $cache->cacheMinutesOverLimit( $GLOBALS['max_page_cache_mins'] ) );
		$_GET['cat'] = ( $url_parts[0] !== "" )? $url_parts[0] : $GLOBALS['post_categories'][0]; //cat is first url part or the default cat	
		$_GET['page'] = ( $part_count > 1 )? (int)$url_parts[ $part_count-1 ] : 1; //page is always last part of url		
		$_GET['search'] = ( $part_count >= 3 )? $url_parts[1] : null; //if url part is 3 entries long search is the second entry		
		
		$cat = $_GET['cat'];		
		$page = $_GET['page'];
		$search = $_GET['search'];
		$post_views = new PostViews();		
		
		$tmplt_data = array();
		$tmplt_data["title"] = "Blog - Page ".$page;
		$tmplt_data["description"] = "";
		$tmplt_data["styles"] = "";
		$tmplt_data["scripts"] = "";
		$tmplt_data["base"] = $base;
		$tmplt_data["search_cat"] = $cat;		
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
	   // echo var_dump( $cache->cacheMinutesOverLimit( $GLOBALS['max_page_cache_mins'] ) );
		echo $cache->pullUrlContentFromCache();
		
	}
?>
