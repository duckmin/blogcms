<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	class PostViews
	{

		function __construct( Parsedown $parsedown )
		{
			$this->parsedown = $parsedown;
		}		
		
		private function makeItem( $post_data_array ){
			$element = "";
			//echo var_dump( $post_data_array );
			switch( $post_data_array[ "data-posttype" ] ){
				
				case "heading":
					//not used as markdown can be used for headings
					$text = strip_tags( $post_data_array[ "text" ] );
					$element = "<h1>".$text."</h1>";
					break;
				
				case "markdown":			
					$text = $this->parsedown->text( strip_tags( $post_data_array[ "text" ] ) );
					$element = $text;
					break;
					
				case "image":
					$src = strip_tags( $post_data_array[ "src" ] );
					$alt_val = strip_tags( $post_data_array[ "alt" ] );
					$alt = ( $alt_val !== "" )? $alt_val : "Image Failed to Load";
					$element = "<img src='".$src."' alt=\"$alt\" />";
					break;
					
				case "audio":
					$src = strip_tags( $post_data_array[ "src" ] );
					$flash_vars = 'config={"autoPlay":false,"autoBuffering":false,"showFullScreenButton":false,"showMenu":false,"videoFile":"'.$src.'","loop":false,"autoRewind":true}';					
					$element = "<embed flashvars='".$flash_vars."' wmode='transparent' pluginspage='http://www.adobe.com/go/getflashplayer' quality='high' allowscriptaccess='always' allowfullscreen='true' bgcolor='#ffffff' src='/scripts/FlowPlayerClassic.swf' type='application/x-shockwave-flash'>";
					break;
					
				case "video":
					$src = strip_tags( $post_data_array[ "src" ] );
					$element = "<div class='iframe-embed' ><iframe src='".$src."' ></iframe></div>";
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
			$str = "";			
			for( $i = 0; $i < count( $GLOBALS['header_categories'] ); $i++ ) {
				$current_cat = $GLOBALS['header_categories'][ $i ];				
				$added_class = ( $cat === $current_cat )? "class=current-cat" : "";
				$str.='<li '.$added_class.' ><a href="/'.$current_cat.'/1">'.ucwords( $current_cat ).'</a></li>';
			}
			return $str; //just the lis of the list
		}
		
	}
	
?>