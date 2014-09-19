<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	$json = json_decode( $_POST['json'], true );
	$procedure = (int)$_GET["procedure"];
	//echo print_r( $json );
	//echo $procedure;
	
	$success = false; 
	$message = "";
	$valid_inputs = true;
	
	$category = (int)trim( strip_tags( $json["category"] ) );
	$title = trim( strip_tags( $json["title"] ) );
	$desc = trim( strip_tags( $json["description"] ) );
	$tags = trim( strip_tags( $json["tags"] ) );
	$folder = trim( strip_tags( $json["folder_path"] ) );
	
	$title_length = strlen( $title );
	$desc_length = strlen( $desc );
	$tags_length = strlen( $tags );
	$folder_length = strlen( $folder );
	
	
	if( $valid_inputs && $title_length > $GLOBALS['max_title_length'] ){
		$valid_inputs = false;
		$message = "Title longer than ".$GLOBALS['max_title_length']." characters";
	}
	
	
	if( $valid_inputs && $desc_length > $GLOBALS['max_desc_length'] ){
		$valid_inputs = false;
		$message = "Description longer than ".$GLOBALS['max_desc_length']." characters";
	}
	
	if( $valid_inputs && $tags_length > $GLOBALS['max_tags_length'] ){
		$valid_inputs = false;
		$message = "Tags longer than ".$GLOBALS['max_tags_length']." characters";
	}
	
	if( $valid_inputs && ( $folder_length <= 0 || $folder_length > $GLOBALS['max_folder_path_length'] ) ){
		$valid_inputs = false;
		$message = "Folder empty or longer than ".$GLOBALS['max_folder_path_length']." characters";
	}
	
	if( $valid_inputs && ( $desc_length > $GLOBALS['max_desc_length'] ) ){
		$valid_inputs = false;
		$message = "Description longer than ".$GLOBALS['max_desc_length']." characters";
	}
	
	if( $valid_inputs && !isset( $GLOBALS['post_categories'][ $category ] ) ){
		$valid_inputs = false;
		$message = "Category Not Regulated";
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

			$db_conn = DBHelper::dbConnection(); 
			$db_conn->beginTransaction();
			
			if( $procedure === 1 ){
				$prep = array( 'category'=>$GLOBALS['post_categories'][ $category ], 'title'=>$title, 'description'=>$desc, 'tags'=>$tags, 'folder_path'=>$folder );
				DBHelper::inserter( 'posts', $prep, $db_conn );
				$file_path = $GLOBALS['index_path'].$folder."/".$db_conn->lastInsertId().".txt";

				if( !is_file( $file_path ) ){
					
					$new_file = fopen( $file_path, 'w' );  
					fwrite( $new_file, serialize( $post_data ) );
					$db_conn->commit();
					$success = true;
					$message = "Post Published";
					
				}else{
					$db_conn->rollback();
				}
			}
			
			if( $procedure === 2 && isset( $json["id"] ) ){
				$prep = array( 'id'=>trim( $json["id"] ), 'category'=>$GLOBALS['post_categories'][ $category ], 'title'=>$title, 'description'=>$desc, 'tags'=>$tags );
				DBHelper::upDateByPrimaryKey( 'posts', 'id', $prep, $db_conn );
				$db_conn->commit();
				$success = true;
				$message = "Post Details Edited";
			}
			
			
		
		} catch(PDOException $e) {
			
			$db_conn->rollback();
			$message = 'ERROR:'.$e->getMessage();
			
		}
	}
    
	echo returnMessage( $success, $message, null );
	
	
?>