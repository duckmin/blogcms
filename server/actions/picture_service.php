<?php
	include_once dirname(__FILE__)."/../configs.php";
	$path = $_GET['dir_path'];
	
	$files = FileGetter::listFolderContents( $path );
	$list = "";
	$img_exts = $GLOBALS["upload_vars"]["allowed_image_extensions"];
	$audio_exts = $GLOBALS["upload_vars"]["allowed_audio_extensions"];
	
	for( $i = 0; $i < count( $files ); $i++ ){
		$item = $files[ $i ];
		
		if( isset( $item['folder'] ) ){
			$filepath = $path."/".$item['folder'];
			$list.=FileGetter::folderLi( $filepath, "0", "folderUpload" );
		}
		
		if( isset( $item['file'] ) ){
			$file_path = $GLOBALS['index_path'].$path."/".$item['file'];
			$extension = pathinfo( $file_path , PATHINFO_EXTENSION );		
			$server_path = 	$path."/".$item['file'];
			$resource_path = $GLOBALS['base_url'].$server_path;
			
			if( in_array( $extension, $img_exts ) ){			
				$list.=FileGetter::pictureLi( $resource_path, $server_path, $item['file'] );
			}
			
			if( in_array( $extension, $audio_exts ) ){			
				$list.=FileGetter::audioLi( $resource_path, $server_path, $item['file'] );
			}
		}
	}
	
	echo $list;

?>