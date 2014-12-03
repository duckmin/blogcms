<?php

	include_once dirname(__FILE__)."/../configs.php";
	
	$page_num = $_GET["p"];
	$cat = $_GET["cat"];
	
	if( true ){

		function getSelectedOption( $cats ){
			$options="";
			foreach( $GLOBALS['post_categories'] as $post_cat ){ 
				$pre_opt = "<option value='".$post_cat."'";
				( in_array( $post_cat, $cats ) )? $pre_opt.=" selected=''" : false;
				$options .= $pre_opt." >".$post_cat."</option>";		
			}
			return $options;
		}		
		
		try{		
			$db = new MongoClient();
			$db_getter = new MongoGetter( $db );
			$posts = iterator_to_array ( $db_getter->getBlogManagePosts( $page_num, $cat ) );
			
			if( count( $posts ) > $GLOBALS['amount_on_manger_tab'] ){
				array_pop( $posts );
				$next=true;
			}else{
				$next=false;
			}
			
			$modified_array=array();
			foreach( $posts as $row ){ 			
				$row["post_type_options"] = getSelectedOption( $row['category'] );
				$id = new MongoId( $row["_id"] );  
				$time_stamp = $id->getTimestamp();	  	  	    
				$dt = new DateTime("@$time_stamp");	   	  	    
				$row["created"] = $dt->format('F d, Y g:i');			    	    
				$row["id"] = $id->__toString();
				$row["first_category"] = $row['category'][0]; //for link to post on manager tab			
				array_push( $modified_array, $row );
				//echo print_r($post);			
			}
			//echo print_r($modified_array);
			
			$prev=( $page_num>1 )? true : false;
			$data=array( "posts"=>json_encode( $modified_array ), "next"=>$next, "prev"=>$prev );
			echo json_encode( array( "result"=>true, "data"=>$data ) );
			
		}catch( MongoCursorException $e ) {
			echo returnMessage( false, 'ERROR:'.$e->getMessage(), null );
		}
	}
?>