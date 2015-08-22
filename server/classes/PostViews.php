<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	class PostViews
	{

		function __construct( Parsedown $parsedown )
		{
			$this->parsedown = $parsedown;
		}
		
		public function paginator( $page_num, $amount_retrieved, $amount_per_page, $add_to_base, $template, $reverse_sort_applied ){
			$data = array(
                "base_url"=>$GLOBALS['base_url'],
                "add_to_base"=>$add_to_base,
                "current_page"=>$page_num
			);
			
			if( $page_num > 1 ){
			    $data["back_page"] = true; 
                $data["last_page"] = $page_num-1;			    
			}
			
			if( $amount_retrieved > $amount_per_page ){
                $data["third_page"] = true;
                $data["next_page"] = $page_num+1; 
			}
			
			//what class of sort icon is to decide how to display, cookie is set with JS in blog_actions.js
			$data["sort_title"] = ( !$reverse_sort_applied )? "Sort Oldest To Newest" : "Sort Newest To Oldest";
			$data["sort_class"] = ( !$reverse_sort_applied )? "" : "sorted";
			
			return TemplateBinder::bindTemplate( $template, $data );
		}			
		
		private function makeItem( $post_data_array ){
			$element = "";
			switch( $post_data_array[ "data-posttype" ] ){
				
				case "markdown":			
					$text = $this->parsedown->text( strip_tags( $post_data_array[ "text" ] ) );
					$element = $text;
					break;
					
				case "image":
					$src = strip_tags( $post_data_array[ "src" ] );
					$alt_val = strip_tags( $post_data_array[ "alt" ] );
					$alt = ( $alt_val !== "" )? $alt_val : "Image Failed to Load";
					$element = "<img src=\"$src\" alt=\"$alt\" />";
					break;
					
				case "audio":
					$src = strip_tags( $post_data_array[ "src" ] );
					$element = "<audio   controls>
                        <source onerror=\"makeFlashAudioEmbed(this)\"  src=\"$src\" type=\"audio/mpeg\">
                    </audio> ";
					break;
					
				case "video":
					$src = strip_tags( $post_data_array[ "src" ] );
					$element = "<div class=\"iframe-embed\" ><iframe src=\"$src\" ></iframe></div>";
					break;
					
			}
			//echo var_dump( $element );
			return $element;
		}
	
		private function formatSinglePost( $data ){
			$count = count( $data );
			$inner_post = "";
			for( $i = 0; $i < $count; $i++ ){
				$single_item = $this->makeItem( $data[ $i ] );
				$inner_post .= $single_item;
			}
			return $inner_post;
		}
		
		public function convertPostTitleSpacesToHyphens( $title ){
			if( preg_match( "/\s/", $title ) ){
				$title = preg_replace ( "/\s/", "-", $title );
			}
			return $title;
		}	
		
		public function convertPostTitleHyphensToSpaces( $title ){
			if( preg_match( "/-/", $title ) ){
				$title = preg_replace ( "/-/", " ", $title );
			}
			return $title;
		}					
		
		//takes a blog post row from mongo and returns a modifed row with converted values used for URLs
		private function convertRowValues( $row ){
			$id = new MongoId( $row["_id"] );  
			$time_stamp = $row["lastModified"]->sec;//$id->getTimestamp();
			$dt = new DateTime("@$time_stamp");	   	  	    	   	  	    
			$row["created"] = $dt->format('F d, Y g:i');			    	    
			$row["id"] = $id->__toString();
			//parse date modified to use in direct URL to post
			$date_of_post = date_parse( $row["created"] );
			$row["month"] = $date_of_post["month"];
			$row["day"] = $date_of_post["day"];
			$row["year"] = $date_of_post["year"];	
			$row["safe_title"] = $this->convertPostTitleSpacesToHyphens( $row["title"] );
			return $row;
		}		
		
		
		public function makePostHtmlFromData( $row, $cat, $template ){		
			$structure = $this->convertRowValues( $row );
			$structure["time_stamp"] = $structure["lastModified"]->sec * 1000; //for js accurrate UTC conversion
			$structure["inner"] = $this->formatSinglePost( $row["post_data"] );
			$structure["page_category"] = $cat; //dont get from DB data get from page so we know which cat is currently in view on the page
			$structure["base"] = $GLOBALS['base_url'];
			return TemplateBinder::bindTemplate( $template, $structure );	
		}
		
		//when search returns no results show this HTML
		public function emptySearchHtml( $cat, $search, $template ){		
			$structure = array();
			$structure["category"] = $cat;
			$structure["search_term"] = htmlspecialchars($search, ENT_QUOTES);
			return TemplateBinder::bindTemplate( $template, $structure );	
		}
		
		// called in actons/get_previous_post_html_from_timestamp
		public function getNextPostButton(  $row, $cat, $template ){
			$structure = $this->convertRowValues( $row );
			$structure["page_category"] = $cat;
			$structure["base"] = $GLOBALS['base_url'];
			return TemplateBinder::bindTemplate( $template, $structure );
		}
		
		private function getSelectedOption( $cats ){
			$options="";
			foreach( $GLOBALS['post_categories'] as $post_cat ){ 
				$pre_opt = "<option value='".$post_cat."'";
				( in_array( $post_cat, $cats ) )? $pre_opt.=" selected=''" : false;
				$options .= $pre_opt." >".$post_cat."</option>";		
			}
			return $options;
		}				
		
		//for actions/get_post_info.php we must modify the posting to put in the form
		public function generateModifedListingForPostInfo( $row ){
			$row = $this->convertRowValues( $row );
			$row["post_type_options"] = $this->getSelectedOption( $row['category'] );
			$row["first_category"] = $row['category'][0]; //for link to post on manager tab
			return $row;
		}
		
		public function getCatHeaderList( $cat = "" ){
			$categories = $GLOBALS['post_categories'];
			$count = count($categories);
			$str = "";			
			$li_tmplt = '<li class="{{ added_class }}" ><a href="/{{ current_cat }}/1/" data-blogaction="category-link" >{{ uc_cat }}</a></li>';
			for( $i = 0; $i < $count; $i++ ) {
				$current_cat = $categories[ $i ];
				$data = array(
				    "current_cat"=>$current_cat,			
                    "uc_cat"=>ucwords( $current_cat ),				
				    "added_class"=>( $cat === $current_cat )? "current-cat" : ""
				);
				$str .= TemplateBinder::bindTemplate( $li_tmplt, $data );
			}
			return $str; //just the lis of the list
		}
		
	}
	
?>