<?php
	include_once dirname(__FILE__)."/../../server/configs.php";
	
	$upload=$_FILES["new_img"];
	$allowedExts = array("gif", "jpeg", "jpg", "png");
	$allowedTypes = array("image/jpeg", "image/gif", "image/jpg", "image/png");
	$temp = explode(".", $upload["name"]);
	$extension = end($temp);
	$type=$upload["type"];
	$folder_path = $_POST["folder_path"];
	$data=null;
	$success=false;
	if( $upload["error"] === 0 ){
		if( in_array( $type , $allowedTypes ) && in_array( strtolower( $extension ), $allowedExts ) ){
			if( ($upload["size"]/1024) < $GLOBALS['max_kb_img_upload'] ){  //conversion bytes to KB
			
				if ( !file_exists( $GLOBALS['index_path'].$folder_path."/".$upload["name"] ) ){
					//echo $upload["name"]. " no exist<br>";
					//echo $upload["tmp_name"];
					move_uploaded_file( $upload["tmp_name"], $GLOBALS['index_path'].$folder_path."/".$upload["name"] );
					$result =  "File Up Loaded";
					$success=true;
					//$file_icon="<img src='".$GLOBALS['base_url']."/style/resources/images.png'  data-filepath='".$GLOBALS['base_url'].$folder_path."/".$upload["name"]."' class='icon' onclick='selectFolderList(this)' />";
					$li = FileGetter::pictureLi( $GLOBALS['base_url'].$folder_path."/".$upload["name"], $upload["name"] );
					$data = $li;
				}else{
					$result = "File Already Exists In This Folder";
				}
			
			}else{
				$result = "File To Large over 500KB";
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






