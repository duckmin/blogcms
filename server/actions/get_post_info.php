<?php

	include_once dirname(__FILE__)."/../configs.php";
	
	$page_num = $_GET["p"];
	
	function getSelectedOption( $db_post_type ){
		$options="";
		foreach( $GLOBALS['post_categories'] as $key => $post_type ){ 
			$pre_opt = "<option value='".$key."'";
			( $db_post_type === $post_type )? $pre_opt.=" selected=''" : false;
			$options .= $pre_opt." >".$post_type."</option>";		
		}
		return $options;
	}
	
	try{		
		$db_conn = DBHelper::dbConnection();
		$db_getter = new DbGetter( $db_conn );
		$posts = $db_getter->getBlogManagePosts( $page_num );

		if( count( $posts ) > $GLOBALS['amount_on_manger_tab'] ){
			array_pop( $posts );
			$next=true;
		}else{
			$next=false;
		}
		
		$modified_array=array();
		foreach( $posts as $post ){ 
			$post["post_type_options"] = getSelectedOption( $post['category'] );
			array_push( $modified_array, $post );
			//echo print_r($post);			
		}
		//echo print_r($modified_array);
		
		$prev=( $page_num>1 )? true : false;
		$data=array( "posts"=>json_encode( $modified_array ), "next"=>$next, "prev"=>$prev );
		echo json_encode( array( "result"=>true, "data"=>$data ) );
	}
	catch(PDOException $e) {
		echo returnMessage( false, 'ERROR:'.$e->getMessage(), null );
	}
?>