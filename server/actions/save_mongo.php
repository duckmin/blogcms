<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	$json = json_decode( $_POST['json'], true );
	$procedure = (int)$_GET["procedure"];
	
	$success = false; 
	$message = "";
	$valid_inputs = true;
	
	$category = $json["category"];
	$title = trim( strip_tags( $json["title"] ) );
	$desc = trim( strip_tags( $json["description"] ) );
	//$tags = trim( strip_tags( $json["tags"] ) );
	//$folder = trim( strip_tags( $json["folder_path"] ) );
	
	$title_length = strlen( $title );
	$desc_length = strlen( $desc );
	//$tags_length = strlen( $tags );
	//$folder_length = strlen( $folder );
	
	
	if( $valid_inputs && $title_length > $GLOBALS['max_title_length'] ){
		$valid_inputs = false;
		$message = "Title longer than ".$GLOBALS['max_title_length']." characters";
	}
	
	
	if( $valid_inputs && $desc_length > $GLOBALS['max_desc_length'] ){
		$valid_inputs = false;
		$message = "Description longer than ".$GLOBALS['max_desc_length']." characters";
	}
	
	/*if( $valid_inputs && $tags_length > $GLOBALS['max_tags_length'] ){
		$valid_inputs = false;
		$message = "Tags longer than ".$GLOBALS['max_tags_length']." characters";
	}*/
	
	/*if( $valid_inputs && ( $folder_length <= 0 || $folder_length > $GLOBALS['max_folder_path_length'] ) ){
		$valid_inputs = false;
		$message = "Folder empty or longer than ".$GLOBALS['max_folder_path_length']." characters";
	}*/
	
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
		;
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
			
			//$db_conn->rollback();
			$message = "error message: ".$e->getMessage()."\n";
			
		}
	}
    
	echo returnMessage( $success, $message, null );
	
	
?>