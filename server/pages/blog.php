<?php
	$server = dirname(__FILE__)."/../";
	//include_once $server."/configs.php";
	//included in index.php which has configs.php included already
	$base = $GLOBALS['base_url'];
	$url = $_SERVER["REQUEST_URI"];
	$cache = new CacheController( $GLOBALS['cache_dir'], $url );
	
	if( !$cache->urlInCache() || $cache->cacheMinutesOverLimit( $GLOBALS['max_page_cache_mins'] ) ){
		
		$_GET['cat'] = ( $url_parts[0] !== "" )? $url_parts[0] : $GLOBALS['post_categories'][0]; //cat is first url part or the default cat	
		$_GET['page'] = ( $part_count > 1 )? (int)$url_parts[ $part_count-1 ] : 1; //page is always last part of url or 1		
		$_GET['search'] = ( $part_count >= 3 )? $url_parts[1] : null; //if url part is 3 entries long search is the second entry		
		
		try{		
			$cat = $_GET['cat'];		
			$page = $_GET['page'];
			$search = $_GET['search'];
			$parsedown = new Parsedown();				
			$post_views = new PostViews( $parsedown );		
			$db = MongoConnection();
			$db_getter = new MongoGetter( $db );
			$post_controller = new PostController( $db_getter, $post_views );
			$mongo_results = $post_controller->getHomePagePosts( $page, $cat, $search ); //false if no result set
			$template = file_get_contents( $GLOBALS['template_dir']."/base_page.txt" );
			
			if( $mongo_results ){
				$tmplt_data = array();
				$tmplt_data["title"] = $cat." - page ".$page." ".$_SERVER['HTTP_HOST'];
				$tmplt_data["description"] = "";
				$tmplt_data["styles"] = "";
				$tmplt_data["scripts"] = ( $search === null )? "<script src='".$base."/scripts/page_actions/main_analytics.js'></script>" : ""; //no analytics on search pages
				$tmplt_data["base"] = $base;
				$tmplt_data["search_cat"] = $cat;		
				$tmplt_data["header"] = $post_views->getCatHeaderList( $cat );
				$tmplt_data["body"] = $mongo_results;
				
				$full_page = TemplateBinder::bindTemplate( $template, $tmplt_data );
				if( $search === null ){	//do not cache search results			
					$cache->saveUrlContentToCache( $full_page );
				}
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
