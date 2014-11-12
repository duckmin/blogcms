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
					$text = strip_tags( $post_data_array[ "text" ] );
					$element = "<h1>".$text."</h1>";
					break;
				
				case "markdown":			
					$text = $this->parsedown->text( strip_tags( $post_data_array[ "text" ] ) );
					$element = $text;
					break;
					
				case "image":
					$src = strip_tags( $post_data_array[ "src" ] );
					$element = "<img src='".$src."' />";
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
		
		//$single["folder_path"], $single["id"], $single["tags"], $single["created"], $single["title"]
		
		public function makePostHtmlFromData( $row ){		
			$structure = array();		
			$id = new MongoId( $row["_id"] ); 
			$time_stamp = $id->getTimestamp();
			$dt = new DateTime("@$time_stamp");	   	  	    
			$structure["created"] = $dt->format('F d, Y g:i');		
			$structure["title"] = $row["title"];    	    
    	    $structure["inner"] = $this::formatSinglePost( $row["post_data"] );
			$structure["id"] = $id->__toString();
			$structure["base"] = $GLOBALS['base_url'];
			$post_tmplt = new TemplateBinder( "blog_post" );
			return $post_tmplt->bindTemplate( $structure );	
		}
		
		/*public function getPostHTMLFromDBData( $row ){
			$post_data = $this->getPostFileArrayData( $row );
			return $this->makePostHtmlFromData( $row, $post_data );
		}*/
		
		public function getCatHeaderList( $cat = "" ){
			$str = "";			
			for( $i = 0; $i < count( $GLOBALS['post_categories'] ); $i++ ) {
				$current_cat = $GLOBALS['post_categories'][ $i ];				
				$added_class = ( $cat === $current_cat )? "class=current-cat" : "";
				$str.='<li '.$added_class.' ><a href="/'.$current_cat.'">'.$current_cat.'</a></li>';
			}
			return $str; //just the lis of the list
		}
		
	}
	
?>