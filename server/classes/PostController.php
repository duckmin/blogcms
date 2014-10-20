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
		
		public function getHomePagePosts( $page_num, $cat, $search ){
			$str="";
			$i = 0;
			/*if( $cat === null && $search === null ){
				//this is not used becaue we only show from posts for specific category now				
				$posts_from_db = $this->mongo_getter->getHomePagePostsFromDb( $page_num );
				$url_add = "";
			}*/
			if( $search === null ){
				$posts_from_db = $this->mongo_getter->getHomePagePostsFromDbByCategory( $page_num, $cat );
				$url_add = $cat;
			}
			
			if( $search !== null ){
				echo $search;				
				$posts_from_db = $this->mongo_getter->getHomePagePostsFromDbByCategoryAndSearch( $page_num, $cat, $search );
				$url_add = $cat."/".$search;
			}
			//echo print_r( $posts_from_db->count() );
			$L=$posts_from_db->count(true);
			
			if( $L > 0 ){			
				foreach( $posts_from_db as $single ){				
					if( $i < $GLOBALS['amount_on_main_page'] ){
						$post_html = $this->post_views->makePostHtmlFromData( $single );
						$str.=$post_html;
						$i++;
					}
				}
				$str.=$this->paginator( $page_num, $L, $GLOBALS['amount_on_main_page'], $url_add );
				return $str;
			}else{
				if( $search !== null ){
					if( $page_num ===1 ){					
						//if search is set and count is 0 and page = one then search return no n results show them a non result page
						return "<h1>Search not found</h1>";
					}else{
						//if page > 1 and search is set something is wrong send to 404						
						return false;
					}
				}else{
					//if result set is 0 and seach is not set return false and take action on page					
					return false;
				}
			}
		}
		
		public function getZoomedPost( $page_num ){
			
		}
	}
	
?>