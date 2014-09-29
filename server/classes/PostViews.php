<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	class PostViews extends PostFormatter
	{
		
		//$single["folder_path"], $single["id"], $single["tags"], $single["created"], $single["title"]
		
		public function makePostHtmlFromData( $row ){
			$structure = array();		
			$id = new MongoId( $row["_id"] );    	  	    
			$structure["created"] = $id->getTimestamp();			
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
				$str.='<li '.$added_class.' ><a href="/blog?cat='.$current_cat.'">'.$current_cat.'</a></li>';
			}
			return "<ul>".$str."</ul>";
		}
		
	}
	
?>