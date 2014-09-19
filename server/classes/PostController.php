<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	class PostController
	{
		
		function __construct( DbGetter $db_getter, PostViews $post_views )
		{
			$this->db_getter = $db_getter;
			$this->post_views = $post_views;
		}
		
		public function getHomePagePosts( $page_num, $cat, $search ){
			$str="";
			
			if( $cat === null && $search === null ){
				$posts_from_db = $this->db_getter->getHomePagePostsFromDb( $page_num );
				$url_add = "?";
			}
			if( $cat !== null && $search === null ){
				$posts_from_db = $this->db_getter->getHomePagePostsFromDbByCategory( $page_num, $cat );
				$url_add = "?cat=".$cat."&";
			}
			$L=count( $posts_from_db );
			for( $i=0; $i<$L; $i+=1 ){
				if( $i < $GLOBALS['amount_on_main_page'] ){
					$single = $posts_from_db[$i];
					$post_html = $this->post_views->getPostHTMLFromDBData( $single );
					$str.=$post_html;
				}
			}
			$str.=paginator( $page_num, $L, $GLOBALS['amount_on_main_page'], $url_add );
			return $str;
		}
		
		public function getZoomedPost( $page_num ){
			
		}
	}
	
?>