<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	class FileGetter
	{
		
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
		
		public static function folderLi( $filepath, $action_num, $folder_upload_function ){
			return "<li>
				<img src='".$GLOBALS['base_url']."/style/resources/folder.png' title='Show Folder Contents' data-actionnum='".$action_num."' data-filepath='".$filepath."' onclick='listFile(this)' />
				<img src='".$GLOBALS['base_url']."/style/resources/arrow-curve.png' title='Upload to Folder' data-folderpath='".$filepath."' onclick='".$folder_upload_function."(this)' />
				<img class='hide' src='".$GLOBALS['base_url']."/style/resources/folder-add.png' title='New Folder' data-folderpath='".$filepath."' onclick='newFolder(this)' />
				<span>".$filepath."</span>
			</li>";
		}
		
		public static function pictureLi( $picture_path, $server_path, $pic_name ){
			return "<li>
				<img src='".$GLOBALS['base_url']."/style/resources/image.png' title='Add Picture to Template' data-picturepath='".$picture_path."' onclick='pictureClick(this)' onmouseover='imageOver(this)' onmouseout='imageOut(this)' />
				<img src='".$GLOBALS['base_url']."/style/resources/action_delete.png' title='Delete Resource' data-filepath='".$server_path."' onclick='deleteResource(this)' />				
				".$pic_name."
			</li>";
		}
		
		public static function audioLi( $audio_path, $server_path, $audio_name ){
			return "<li>
				<img src='".$GLOBALS['base_url']."/style/resources/audio.png' title='Add Audio to Template' data-audiopath='".$audio_path."' onclick='audioClick(this)' />
				<img src='".$GLOBALS['base_url']."/style/resources/action_delete.png' title='Delete Resource' data-filepath='".$server_path."' onclick='deleteResource(this)' />				
				".$audio_name."
			</li>";
		}
		
	};
	

?>

