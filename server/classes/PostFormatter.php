<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	function formatParagraphTags( $text ){
		$pattern = "/\[link\]\s*(http:\/\/(www\.|)[\w\d]+\.{1}[^\[\]]+)\[\/link\]/";
		preg_match( $pattern, $text, $matches );
		if( count( $matches ) > 0 ){
			$replaced = preg_replace ( $pattern, "<a href='$1' >$1</a>", $text, '-1' );
			return $replaced;
		}else{
			return $text;
		};
	}
	
	function makeItem( $post_data_array ){
		$element = "";
		//echo var_dump( $post_data_array );
		switch( $post_data_array[ "data-posttype" ] ){
			
			case "heading":
				$text = strip_tags( $post_data_array[ "text" ] );
				$element = "<h1>".$text."</h1>";
				break;
			
			case "paragraph":			
				$text = $parsedown->text( htmlentities( strip_tags( $post_data_array[ "text" ] ) ) );
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
	
	class PostFormatter
	{
		/*public function getPostFileArrayData( $row ){
			$file = $GLOBALS['index_path'].$row['folder_path']."/".$row['id'].".txt";
			if( file_exists( $file ) ){
				$contents = unserialize( file_get_contents($file) );
				return $contents;
			}else{
				return false;
			}
		}
		
		public function savePostFileArrayData( $row, $post_data ){
			$file = $GLOBALS['index_path'].$row['folder_path']."/".$row['id'].".txt";
			if( file_exists( $file ) ){
				$contents = serialize( $post_data );
				file_put_contents( $file, $contents );
				return true;
			}else{
				return false;
			}
		}*/
		
		public function formatSinglePost( $data ){
			$count = count( $data );
			$inner_post = "";
			for( $i = 0; $i < $count; $i++ ){
				$single_item = makeItem( $data[ $i ] );
				$inner_post .= $single_item;
			}
			return $inner_post;
		}
			
	};
	

?>

