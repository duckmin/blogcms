<?php
	include_once dirname(__FILE__)."/../configs.php";
	$path = $_GET['dir_path'];
	
	$files = FileGetter::listFolderContents( $path );
	$list = "";
	
	for( $i = 0; $i < count( $files ); $i++ ){
		$item = $files[ $i ];
		
		if( isset( $item['folder'] ) ){
			$filepath = $path."/".$item['folder'];
			$list.=FileGetter::folderLi( $filepath, "2", "savePopupFolderClickAction" );
		}
	}
	
	echo $list;

?>