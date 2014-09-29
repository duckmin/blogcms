<?php
	$server = dirname(__FILE__)."/../server";
	
	include_once $server."/configs.php";
	$db = new MongoClient();
	$getter = new MongoGetter( $db );
	$items = $getter->getHomePagePostsFromDbByCategory( 1, "blog" );
	$formatter = new PostViews();
	foreach ($items as $document) {
    	echo print_r( $document );
    	//echo $formatter->makePostHtmlFromData( $document );
		//$i = new MongoId( $document["_id"] );    	
    	//echo $i->__toString()."<br>";
    	//echo $i->getTimestamp()."<br>";
	}	
	//echo var_dump( $items );
	/*$base = $GLOBALS['base_url'];
	$url = $_SERVER["REQUEST_URI"];
	$cache = new CacheController( $GLOBALS['cache_dir'], $url );
	if( !$cache->urlInCache()true ){
		
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
		
		$db = DBHelper::dbConnection();
		$db_getter = new DbGetter( $db );
		$post_controller = new PostController( $db_getter, $post_views );	
		
		$tmplt_data["body"] = "<section class='main'>".$post_controller->getHomePagePosts( $page, $cat, $search )."</section>";
		
		$base_page = new TemplateBinder( "base_page" );
		$full_page = $base_page->bindTemplate( $tmplt_data );
		//$cache->saveUrlContentToCache( $full_page );
		echo $full_page;
		
	}else{
	
		echo $cache->pullUrlContentFromCache();
		
	}*/
?>