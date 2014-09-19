<?php
	$server = dirname(__FILE__)."/../server";
	include_once $server."/configs.php";
	if( !isset( $_SERVER['PHP_AUTH_USER'] ) ){
		header('WWW-Authenticate: Basic realm="Manager"');
		header('HTTP/1.0 401 Unauthorized');
	}else{
		$users = $GLOBALS['blog_users'];
		$sent_user = $_SERVER['PHP_AUTH_USER'];
		if( array_key_exists( $sent_user, $users ) && $users[$sent_user] === $_SERVER['PHP_AUTH_PW'] ){	
		//give access to page if user is is a key in array and the value matches the PW
?>
<!DOCTYPE html>
<html>
<head>
	<title>Manager</title>
	<meta charset="utf-8"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="General" name="rating"/>
	<meta content="English" name="language"/>
	<meta name="viewport" content="width=device-width; initial-scale=1.0;">
	<link rel='stylesheet' type='text/css' href='style/global_style.css'>
	<link rel='stylesheet' type='text/css' href='style/tab_style.css'>
	<link rel='stylesheet' type='text/css' href='style/manager_style.css'>
	<link rel='stylesheet' type='text/css' href='style/blog_style.css'>
	<?php
		include $server."/includes/global_style_scripts.php";
	?>
	<script src="scripts/manager/picture_manager.js" ></script>
	<script src="scripts/manager/template_manager.js" ></script>
	<script src="scripts/manager/main_manager.js" ></script>
	<script src="scripts/extender_tabs.js" ></script>
</head>

<body>
<div class=wrapper>
	<ul class='tab-top' >
		<li data-tab='template' class='selected-tab' >Template</li>
		<li data-tab='posts' >Posts</li>
		<li data-tab='pictures' >Pictures</li>
		<li data-tab='preview' style="display:none" >Preview</li>
		<li data-tab='tab5' >Tab 5</li>
	</ul>
	
	<section data-tabsection='template' >
	    <ul class="button-list" >
			<li class="drop" >
				<img src="style/resources/expand.png" />
				Template Item
				<ul>
					<li data-templateaction="additem" data-action="heading" >
						<span>
							<img src="style/resources/document-small.png" />
							Heading
						</span>
					</li>
					<li data-templateaction="additem" data-action="paragraph" >
						<span>
							<img src="style/resources/document-text.png" />
							Paragraph
						</span>
					</li>
					<li data-templateaction="additem" data-action="image" >
						<span>
							<img src="style/resources/camera.png" />
							Image
						</span>
					</li>
					<li data-templateaction="additem" data-action="video" >
						<span>
							<img src="style/resources/movie.png" />
							Video
						</span>
					</li>
				</ul>
			</li>
		</ul>
		
		<ul class="template" id="template" >
		</ul>
		
		<ul class="button-list" >
			<li data-templateaction="preview-post" >
				Preview
			</li>
		</ul>
	</section>
	
	<section data-tabsection='posts' >
		<table class="manage-table">
			<thead>
				<tr>
					<th>Type</th>
					<th>Description</th>
					<th>Title</th>
					<th>Tags</th>
					<th>Posted</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<ul class='list-nav' >
		
		</ul>
	</section>
		
	<section data-tabsection='pictures' >
		<form id="img-upload-form" action="pages/upload_img.php" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="return imageUploadValidator();" >
			<b><label>Folder:  
				<input id='upload-path' name="folder_path" readonly  type="text" />
			</label></b>
			<b><label>File:  
				<input type="file" name="new_img" />
			</label></b>
			<label>
				<input type="submit" name="submitBtn" style="padding:0 5px" value="Upload" />
			</label>
			<iframe style="display:none" id="upload_target" name="upload_target" src="#" ></iframe>
		</form>
		
		<ul class='folders'>
			<?php
				echo FileGetter::folderLi( '/pics', "0", "folderUpload" );
			?>
		<ul>
	</section>
	
	<section data-tabsection='preview' >
		<section class='main' id='preview' >
		
		</section>
		<ul class="button-list" >
			<li data-templateaction="save-preview" >
				Save
			</li>
			<li data-templateaction="cancel-preview" >
				Cancel
			</li>
		</ul>
	</section>
	
	<section data-tabsection='tab5' >
		tab5
	</section>
	
	
	
</div>

<div class='dark-shade hide' id='save-preview-popup' >
	<div class='fixed-box save-preview-popup form' >
		<label>Category:</label>
		<select name="category" ><?php
			foreach( $GLOBALS['post_categories'] as $key => $post_type ){ 
				echo "<option value='".$key."'>".$post_type."</option>";		
			}
		?></select>
		
		<label>Title:</label>
		<input type="text" name="title" />
		
		<label>Description:</label>
		<textarea name="description" ></textarea>
		
		<label>Tags:</label>
		<input type="text" name="tags" />
		
		<ul class='folders'>
			<?php
				echo FileGetter::folderLi( '/posts', "2", "savePopupFolderClickAction" );
			?>
		<ul>
		
		<label>Folder:</label>
		<input type="text" readonly name="folder_path" />
		
		<ul class="button-list" >
			<li data-templateaction="save-new-post" >
				Save
			</li>
			<li data-templateaction="close-popup" >
				Cancel
			</li>
		</ul>
	</div>
</div>

</body>
</html>
<?php
		}else{
			//wrong guess make sleep to prevent brute force
			sleep(4);
			header('WWW-Authenticate: Basic realm="Manager"');
			header('HTTP/1.0 401 Unauthorized');
		}
	}//end else
?>