<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	$success = false; 
	$message = "";
	$valid_inputs = true;
		
	if( $valid_inputs && false/*logged in*/ ){  //if not logged in all validations will skip and go straight to message
		$valid_inputs = false;
		$message = "Not Logged In";
	}
	
	if( $valid_inputs && isset( $_GET["procedure"] ) && isset( $_POST["json"] ) ){  //if all required fields are set set up vars
		$json = json_decode( $_POST['json'], true );
		$procedure = (int)$_GET["procedure"];

		$category = $json["category"];
		$title = trim( strip_tags( $json["title"] ) );
		$desc = trim( strip_tags( $json["description"] ) );
		
		$title_length = strlen( $title );
		$desc_length = strlen( $desc );
	}else{
		$valid_inputs = false;
	}
	
	if( $valid_inputs && $title_length > $GLOBALS['max_title_length'] ){
		$valid_inputs = false;
		$message = "Title longer than ".$GLOBALS['max_title_length']." characters";
	}
	
	
	if( $valid_inputs && $desc_length > $GLOBALS['max_desc_length'] ){
		$valid_inputs = false;
		$message = "Description longer than ".$GLOBALS['max_desc_length']." characters";
	}
	
	if( $valid_inputs && count( $category ) < 1 ){
		$valid_inputs = false;
		$message = "Must Select Atleast 1 category";
	}
	
	if( $valid_inputs ){ //category not valid give error message
		foreach( $category as $cat ){
			if( !in_array( $cat, $GLOBALS['post_categories'] ) ){
				$valid_inputs = false;
				$message = "Category Not Regulated";
				break;
			}		
		}
	}
	
	if( $procedure === 1 ){
		$post_data = $json["post_data"];
		$post_data_length = count( $post_data );
		
		if( $valid_inputs && $post_data_length <= 0 ){
			$valid_inputs = false;
			$message = "Template is empty";
		}
	}
	
	if( $valid_inputs ){
		
		try {
			
			$m = new MongoClient();
			$db = $m->$GLOBALS['mongo_db_name'];
			$collection = $db->posts;
			
			//procedure 1 create new listing with post_data
			if( $procedure === 1 ){
				$mongo_id = new MongoId();			
				$document = array( 
					'_id'=>$mongo_id,					
					'category'=>$category,
		   	   	 	'title'=>$title,
			   	 	'description'=>$desc,
			   	 	'post_data'=> $post_data,
			   	 	'lastModified'=>new MongoDate()
				);
				$write_result = $collection->insert($document);				
				$written = ( $write_result['ok']>=1 )? true : false;			
				$success = $written; 
				$message = ( $written )? "Post Published" : "Post Not Saved";
			}
			
			//procedure2 update listings meta data
			if( $procedure === 2 && isset( $json["id"] ) ){
				$mongo_id = new MongoId( $json["id"] ); 
				$update_array = array( '$set'=> array( "category"=>$category, "title"=>$title, "description"=>$desc ) );	
				$write_result = $collection->update( array( "_id"=>$mongo_id ), $update_array );
				$written = ( $write_result['nModified'] === 1 )? true : false;				
				$success = $written;
				$message = ( $written )? "Post Details Edited" : "No Changes Made To Post";
			}			
		
		} catch( MongoCursorException $e ) {
			
			$message = "error message: ".$e->getMessage()."\n";
			
		}
	}
    
	echo returnMessage( $success, $message, null );
	
	
?>