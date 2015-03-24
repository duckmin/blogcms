<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	//$success = false; 
	//$message = "";
	
	if( isset( $_GET["cat"] ) && isset( $_GET["ts"] ) ){
		$time_stamp = (int)$_GET["ts"]; //js use milliseconds mongo uses seconds convert milliseconds to seconds
		$cat = $_GET['cat'];		
		//echo $time_stamp;
		
		try{			
			$db = MongoConnection();
			$db_getter = new MongoGetter( $db );
			$post_data = $db_getter->getPreviousPostsFromTimestamp( $cat, $time_stamp );

			if( $post_data !== NULL ){
				$post_view = new PostViews( new Parsedown );
				$post_template = file_get_contents( $GLOBALS['template_dir']."/blog_post.txt" );
				echo $post_view->makePostHtmlFromData( $post_data, $cat, $post_template );
			}
			
		} catch( MongoCursorException $e ) {;
			echo "error message: ".$e->getMessage()."\n";
		}
		
	}
	//echo returnMessage( $success, $message, null );
?>