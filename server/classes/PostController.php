<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	class PostController
	{
		
		function __construct( MongoGetter $db_getter, PostViews $post_views )
		{
			$this->mongo_getter = $db_getter;
			$this->post_views = $post_views;
		}
		
		private function paginator( $page_num, $amount_retrieved, $amount_per_page, $add_to_base ){
			$paginator="<ul class='paginator' >";
			if( $page_num>1 ){
				$back=$page_num-1;
				$paginator.="<li><a href='".$GLOBALS['base_url']."/".$add_to_base."/".$back."' >".$back."</a></li>";
			}
			$paginator.="<li class='current-cat' >".$page_num."</li>";
			if( $amount_retrieved > $amount_per_page ){
				$forward=$page_num+1;
				$paginator.="<li><a href='".$GLOBALS['base_url']."/".$add_to_base."/".$forward."' >".$forward."</a></li>";
			}
			return $paginator."</ul>";
		}		
		
		public function getHomePagePosts( $page_num, $cat ){
			$str="";
			$i = 0;
			$posts_from_db = $this->mongo_getter->getHomePagePostsFromDbByCategory( $page_num, $cat );
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
				$paginator = $this->paginator( $page_num, $L, $GLOBALS['amount_on_main_page'], $url_add );
				return $paginator.$str.$paginator;
			}else{			
				//no results return false and we will send them to 404 (paginator logic should not allow this to happen)
				return false;
			}
		}
		
		public function getSearchPagePosts( $page_num, $cat, $search ){
			$str="";
			$i = 0;			
			$posts_from_db = $this->mongo_getter->getHomePagePostsFromDbByCategoryAndSearch( $page_num, $cat, $search );
			$url_add = "search/".$cat."/".$search;
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
				$paginator = $this->paginator( $page_num, $L, $GLOBALS['amount_on_main_page'], $url_add );
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