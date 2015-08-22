<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	class PostController
	{
		
		function __construct( MongoGetter $db_getter, PostViews $post_views )
		{
			$this->mongo_getter = $db_getter;
			$this->post_views = $post_views;
		}
		
		private function sortOldestToNewest(){
		    return ( isset($_COOKIE["sort"]) && (int)$_COOKIE["sort"] === 1 )? true : false;   
		}		
		
		public function getHomePagePosts( $page_num, $cat ){
			$str="";
			$i = 0;
			$reverse_sort_applied = $this->sortOldestToNewest();
			$posts_from_db = $this->mongo_getter->getHomePagePostsFromDbByCategory( $page_num, $cat,$reverse_sort_applied  );
			$url_add = $cat;
			$L = $posts_from_db->count(true);
			
			if( $L > 0 ){	 
				$post_template = file_get_contents( $GLOBALS['template_dir']."/blog_post.txt" );		
				foreach( $posts_from_db as $single ){				
					if( $i < $GLOBALS['amount_on_main_page'] ){
						$post_html = $this->post_views->makePostHtmlFromData( $single, $cat, $post_template ); //pass in cat because post can have multiple cats and we want to know which one we are looking at
						$str.=$post_html;
						$i++;
					}
				}
				$paginator_template = file_get_contents( $GLOBALS['template_dir']."/paginator.txt" );
				$paginator = $this->post_views->paginator( $page_num, $L, $GLOBALS['amount_on_main_page'], $url_add, $paginator_template, $reverse_sort_applied );
				return $paginator.$str.$paginator;
			}else{			
				//no results return false and we will send them to 404 (paginator logic should not allow this to happen)
				return false;
			}
		}
		
		public function getSearchPagePosts( $page_num, $cat, $search ){
			$str="";
			$i = 0;	
			$reverse_sort_applied = $this->sortOldestToNewest();		
			$posts_from_db = $this->mongo_getter->getHomePagePostsFromDbByCategoryAndSearch( $page_num, $cat, $search, $reverse_sort_applied );
            $s = urlencode( $search );			
			$url_add = "search/$cat/$s";
			$L = $posts_from_db->count(true);
			
			if( $L > 0 ){	
				$post_template = file_get_contents( $GLOBALS['template_dir']."/blog_post.txt" );		
				foreach( $posts_from_db as $single ){				
					if( $i < $GLOBALS['amount_on_main_page'] ){
						$post_html = $this->post_views->makePostHtmlFromData( $single, $cat, $post_template ); //pass in cat because post can have multiple cats and we want to know which one we are looking at
						$str.=$post_html;
						$i++;
					}
				}
				$paginator_template = file_get_contents( $GLOBALS['template_dir']."/paginator.txt" );
				$paginator = $this->post_views->paginator( $page_num, $L, $GLOBALS['amount_on_main_page'], $url_add, $paginator_template, $reverse_sort_applied );
				return $paginator.$str.$paginator;
			}else{
				if( $page_num === 1 ){	
					$empty_search_template = file_get_contents( $GLOBALS['template_dir']."/empty_search.txt" );				
					//if search is set and count is 0 and page = one then search return no n results show them a non result page
					return $this->post_views->emptySearchHtml( $cat, $search, $empty_search_template );
				}else{
					//if page > 1 and search count is zero something is wrong send to 404						
					return false;
				}
			}
		}
		
		public function getZoomedPost( $page_num ){
			
		}
	}
	
?>