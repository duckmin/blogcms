<?php
	include_once dirname(__FILE__)."/../configs.php";
	$path = $_GET['dir_path'];
	
	$files = FileGetter::listFolderContents( $path );
	$list = "";
	
	for( $i = 0; $i < count( $files ); $i++ ){
		$item = $files[ $i ];
		
		if( isset( $item['folder'] ) ){
			$filepath = $path."/".$item['folder'];
			$list.=FileGetter::folderLi( $filepath, "0", "folderUpload" );
		}
		
		if( isset( $item['file'] ) ){
			$picture_path = $GLOBALS['base_url'].$path."/".$item['file'];
			$list.=FileGetter::pictureLi( $picture_path, $item['file'] );
		}
	}
	
	echo $list;

?>