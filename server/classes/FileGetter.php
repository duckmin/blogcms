<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	class FileGetter
	{
		const FOLDER_LI = "<li>
			<img src=\"{{ base }}/style/resources/folder.png\" title='Show Folder Contents' data-filepath=\"{{ file_path }}\" onclick='listFile(this)' />
			<img src=\"{{ base }}/style/resources/arrow-curve.png\" title='Upload to Folder' data-folderpath=\"{{ file_path }}\" onclick=\"folderUpload(this)\" />
			<img class='hide' src=\"{{ base }}/style/resources/folder-add.png\" title='New Folder' data-folderpath=\"{{ file_path }}\" onclick='newFolder(this)' />
			<span>{{ file_path }}</span>
		</li>";
		
		const FILE_LI = "<li>
			<img src=\"{{ base }}/style/resources/image.png\" title='Add Picture to Template' data-picturepath=\"{{ picture_path }}\" onclick='pictureClick(this)' onmouseover='imageOver(this)' onmouseout='imageOut(this)' />
			<img src=\"{{ base }}/style/resources/action_delete.png\" title='Delete Resource' data-filepath=\"{{ server_path }}\" onclick='deleteResource(this)' />				
			{{ pic_name }}
		</li>";
		
		const AUDIO_LI = "<li>
			<img src=\"{{ base }}/style/resources/audio.png\" title='Add Audio to Template' data-audiopath=\"{{ audio_path }}\" onclick='audioClick(this)' />
			<img src=\"{{ base }}/style/resources/action_delete.png\" title='Delete Resource' data-filepath=\"{{ server_path }}\" onclick='deleteResource(this)' />				
			{{ audio_name }}
		</li>";
		
		public static function listFolderContents( $path ){
			$holder = array();
			$folder_path = $GLOBALS['index_path'].$path;
			if( is_dir( $folder_path ) ){
				$contents_of_folder = scandir( $folder_path );	
				foreach( $contents_of_folder as $folder_item ){
					if( $folder_item !== '.' && $folder_item !== '..' ){
						if( is_dir( $folder_path."/".$folder_item ) ){
							array_push( $holder, array( 'folder'=>$folder_item ) );
						}else{
							array_push( $holder, array( 'file'=>$folder_item ) );
						}
					}
				}
				return $holder;
			}else{
				return array();
			}
		}
		
		public static function folderLi( $filepath ){
			$info = array(
				"base"=>$GLOBALS['base_url'],
				"file_path"=>$filepath
			);
			return TemplateBinder::bindTemplate( self::FOLDER_LI, $info );
		}
		
		public static function pictureLi( $picture_path, $server_path, $pic_name ){
			$info = array(
				"base"=>$GLOBALS['base_url'],
				"picture_path"=>$picture_path,
				"server_path"=>$server_path,
				"pic_name"=>$pic_name
			);
			return TemplateBinder::bindTemplate( self::FILE_LI, $info );
		}
		
		public static function audioLi( $audio_path, $server_path, $audio_name ){
			$info = array(
				"base"=>$GLOBALS['base_url'],
				"audio_path"=>$audio_path,
				"server_path"=>$server_path,
				"audio_name"=>$audio_name
			);
			return TemplateBinder::bindTemplate( self::AUDIO_LI, $info );
		}
		
	};
	

?>

