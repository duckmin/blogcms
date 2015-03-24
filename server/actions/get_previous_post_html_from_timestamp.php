<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	if( isset( $_GET["cat"] ) && isset( $_GET["ts"] ) ){
		$time_stamp = (int)$_GET["ts"]/1000; //js use milliseconds mongo uses seconds convert milliseconds to seconds
		$cat = $_GET['cat'];		
		//echo $time_stamp;
		
		try{			
			$db = MongoConnection();
			$db_getter = new MongoGetter( $db );
			$post_data = $db_getter->getPreviousPostsFromTimestamp( $cat, $time_stamp );

			foreach( $post_data as $posting ){
				$post_view = new PostViews( new Parsedown );
				$post_template = file_get_contents( $GLOBALS['template_dir']."/blog_post.txt" );
				echo $post_view->makePostHtmlFromData( $posting, $cat, $post_template );
			}
			
		} catch( MongoCursorException $e ) {;
			echo "error message: ".$e->getMessage()."\n";
		}
		
	}
?>