<?php

	include_once dirname(__FILE__)."/../configs.php";
	
	$page_num = $_GET["p"];
	$cat = $_GET["cat"];
	
	if( true ){
		
		header('Content-Type: application/json; charset=utf-8');
		
		try{		
			$db = MongoConnection();
			$db_getter = new MongoGetter( $db );
			$posts = iterator_to_array ( $db_getter->getBlogManagePosts( $page_num, $cat ) );
			
			if( count( $posts ) > $GLOBALS['amount_on_manger_tab'] ){
				array_pop( $posts );
				$next=true;
			}else{
				$next=false;
			}
			
			$parsedown = new Parsedown();				
			$post_views = new PostViews( $parsedown );	
			$modified_array=array();
			foreach( $posts as $row ){ 			
				$modified_row = $post_views->generateModifedListingForPostInfo( $row );	
				array_push( $modified_array, $modified_row );		
			}
			
			$prev=( $page_num>1 )? true : false;
			$data=array( "posts"=>json_encode( $modified_array ), "next"=>$next, "prev"=>$prev );
			
			echo json_encode( array( "result"=>true, "data"=>$data ) );
			
		}catch( MongoCursorException $e ) {
			echo returnMessage( false, 'ERROR:'.$e->getMessage(), null );
		}
	}
?>