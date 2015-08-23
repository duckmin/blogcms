<?php

	include_once dirname(__FILE__)."/../configs.php";
	
	$page_num = $_GET["p"];
	$cat = $_GET["cat"];
	
	if( true ){

		try{		
			$db = MongoConnection();
			$db_getter = new MongoGetter( $db );
			if( strlen($cat) === 0 && isset( $_GET["search"] ) ){
			    $search = $_GET["search"];
			    $cursor = $db_getter->getPostsFromDbBySearch( $page_num, $search ); 
			}else{
			    $cursor = $db_getter->getBlogManagePosts( $page_num, $cat );
			}
			$posts = iterator_to_array( $cursor );

			if( count( $posts ) > $GLOBALS['amount_on_manger_tab'] ){
				array_pop( $posts );
				$next=true;
			}else{
				$next=false;
			}
			
			$parsedown = new Parsedown();				
			$post_views = new PostViews( $parsedown );	
			$modified_array=array();
			$post_template = file_get_contents( $GLOBALS['template_dir']."/blog_post.txt" );
			foreach( $posts as $row ){ 			
                $post_cat = ( strlen($cat) > 0 )? $cat : $row["category"][0]; //when viewing all posts/search cat will be empty string so use base cat				
				$modified_row = $post_views->generateModifedListingForPostInfo( $row, $post_cat );	
                $row["show_id"] = true; //show_id on template, so manager page JavaScript can identify them
                $post_html = $post_views->makePostHtmlFromData( $row, $post_cat, $post_template );				
				array_push( $modified_array, array("post_data"=>$modified_row, "post_html"=>$post_html) );		
			}
			
			$prev=( $page_num>1 )? true : false;
			$data=array( "posts"=>$modified_array, "next"=>$next, "prev"=>$prev );
			
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode( array( "result"=>true, "data"=>$data ) );
			
		}catch( MongoCursorException $e ) {
			echo returnMessage( false, 'ERROR:'.$e->getMessage(), null );
		}
	}
?>