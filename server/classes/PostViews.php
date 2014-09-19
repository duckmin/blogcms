<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	class PostViews extends PostFormatter
	{
		
		//$single["folder_path"], $single["id"], $single["tags"], $single["created"], $single["title"]
		
		public function makePostHtmlFromData( $row, $post_data  ){
			if( $post_data !== false ){
				$row["inner"] = $this::formatSinglePost( $post_data );
				$row["base"] = $GLOBALS['base_url'];
				$post_tmplt = new TemplateBinder( "blog_post" );
				return $post_tmplt->bindTemplate( $row );
			}else{
				return "";
			}
		}
		
		public function getPostHTMLFromDBData( $row ){
			$post_data = $this->getPostFileArrayData( $row );
			return $this->makePostHtmlFromData( $row, $post_data );
		}
	}
	
?>