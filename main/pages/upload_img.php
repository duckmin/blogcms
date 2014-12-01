<?php
	include_once dirname(__FILE__)."/../../server/configs.php";
	
	$upload = $_FILES["new_img"];
	$upload_vars = $GLOBALS["upload_vars"];
	$allowedExts = $upload_vars["allowed_extensions"];
	$allowedTypes = $upload_vars["allowed_mimetypes"];
	$temp = explode(".", $upload["name"]);
	$extension = end($temp);
	$type = $upload["type"];
	$folder_path = $_POST["folder_path"];
	$data = null;
	$success = false;
	if( $upload["error"] === 0 ){
		if( in_array( $type , $allowedTypes ) && in_array( strtolower( $extension ), $allowedExts ) ){
			$upload_kbs = $upload["size"]/1024;	
			//its either in img extensions or its an audio file			
			$is_img = ( in_array( $extension,  $upload_vars["allowed_image_extensions"] ) )? true : false;		
			$max_kbs = ( $is_img )? $upload_vars["max_kb_img_upload"] : $upload_vars["max_mb_audio_upload"]*1000;		
			$to_big = ( (int)$upload_kbs >= $max_kbs )? true : false;

			if( !$to_big ){  //conversion bytes to KB
			
				if ( !file_exists( $GLOBALS['index_path'].$folder_path."/".$upload["name"] ) ){
					
					move_uploaded_file( $upload["tmp_name"], $GLOBALS['index_path'].$folder_path."/".$upload["name"] );
					$result =  "File Up Loaded";
					$success=true;
					//$file_icon="<img src='".$GLOBALS['base_url']."/style/resources/images.png'  data-filepath='".$GLOBALS['base_url'].$folder_path."/".$upload["name"]."' class='icon' onclick='selectFolderList(this)' />";
					$server_path = 	$folder_path."/".$upload["name"];
					$url_path =  $GLOBALS['base_url'].$server_path;			
					if( $is_img ){
						$li = FileGetter::pictureLi( $url_path, $server_path, $upload["name"] );
					}else{
						$li = FileGetter::audioLi( $url_path, $server_path, $upload["name"] );
					}					
					$data = $li;
					
				}else{
					$result = "File Already Exists In This Folder";
				}
			
			}else{
				$result = "File To Large over ".$max_kbs." KB";
			}
		}else{
			$result = "File Type ".$type." Not Allowed";
		}
	}else{
		$result = "upload error";
	}
	
?>

<script language="javascript" type="text/javascript">
		
	
	window.top.window.uploadResponseAction(<?php echo json_encode( array( "result"=>$success, "message"=>$result, "data"=>$data ) ); ?> ); 

</script> 






