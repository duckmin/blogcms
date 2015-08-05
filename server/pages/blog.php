<?php
	//included in index.php which has configs.php included already
	$base = $GLOBALS['base_url'];
	$url = $_SERVER["REQUEST_URI"];
	$cache = new CacheController( $GLOBALS['cache_dir'], $url );
	
	if( !$cache->urlInCache() || $cache->cacheMinutesOverLimit( $GLOBALS['max_page_cache_mins'] ) ){
		//$par_count is defined in index.php
		$_GET['cat'] = ( $url_parts[0] !== "" )? $url_parts[0] : $GLOBALS['post_categories'][0]; //cat is first url part or the default cat	
		$_GET['page'] = ( $part_count > 1 )? (int)$url_parts[ $part_count-1 ] : 1; //page is always last part of url or 1		
		
		try{		
			$cat = $_GET['cat'];		
			$page = $_GET['page'];
			$parsedown = new Parsedown();				
			$post_views = new PostViews( $parsedown );		
			$db = MongoConnection();
			$db_getter = new MongoGetter( $db );
			$post_controller = new PostController( $db_getter, $post_views );
			$mongo_results = $post_controller->getHomePagePosts( $page, $cat ); //false if no result set
			$template = file_get_contents( $GLOBALS['template_dir']."/base_page.txt" );
			$title = $cat." page ".$page." - ".$_SERVER['HTTP_HOST'];		
			$desc	= 	$_SERVER['HTTP_HOST']." - browse ".$cat." page ".$page;
			
			if( $mongo_results ){
				$tmplt_data = array();
				$tmplt_data["title"] = $title;
				$tmplt_data["description"] = $desc;
				$tmplt_data["styles"] = "";
				$tmplt_data["scripts"] = "<script src='".$base."/scripts/page_actions/main_analytics.js'></script>";
				$tmplt_data["base"] = $base;
				$tmplt_data["category"] = $cat;
				$tmplt_data["search_placeholder"] = "search $cat";	
				$tmplt_data["search_value"] = "";		
				$tmplt_data["header"] = $post_views->getCatHeaderList( $cat );
				$tmplt_data["body"] = $mongo_results;
				
				$full_page = TemplateBinder::bindTemplate( $template, $tmplt_data );	
				$cache->saveUrlContentToCache( $full_page ); //save page to cache
				echo $full_page;
			}else{
				//if mongo results are false go to 404,	logic in getHomePagePosts Funtion			
				goTo404();
			}
		}catch( MongoException $e ){
			//echo $e->getMessage();
			//Mongo error, go to 404 page		
			goTo404();
		}	
	}else{ 
	   // echo var_dump( $cache->cacheMinutesOverLimit( $GLOBALS['max_page_cache_mins'] ) );
		echo $cache->pullUrlContentFromCache();
		echo "pulled from cache";
		
	}
?>
