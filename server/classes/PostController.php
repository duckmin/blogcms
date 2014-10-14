<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	class PostController
	{
		
		function __construct( MongoGetter $db_getter, PostViews $post_views )
		{
			$this->mongo_getter = $db_getter;
			$this->post_views = $post_views;
		}
		
		public function getHomePagePosts( $page_num, $cat, $search ){
			$str="";
			$i = 0;
			if( $cat === null && $search === null ){
				//this is not used ecaue we only show from posts for specific category now				
				$posts_from_db = $this->mongo_getter->getHomePagePostsFromDb( $page_num );
				$url_add = "";
			}
			if( $cat !== null && $search === null ){
				$posts_from_db = $this->mongo_getter->getHomePagePostsFromDbByCategory( $page_num, $cat );
				$url_add = $cat;
			}
			//echo print_r( $posts_from_db->count() );
			$L=count( $posts_from_db );
			foreach( $posts_from_db as $single ){				
				if( $i < $GLOBALS['amount_on_main_page'] ){
					$post_html = $this->post_views->makePostHtmlFromData( $single );
					$str.=$post_html;
					$i++;
				}
			}
			$str.=paginator( $page_num, $posts_from_db->count(true), $GLOBALS['amount_on_main_page'], $url_add );
			return $str;
		}
		
		public function getZoomedPost( $page_num ){
			
		}
	}
	
?>