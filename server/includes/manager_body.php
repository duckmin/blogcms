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
	<script src="scripts/manager/alert_boxes.js" ></script>
	<script src="scripts/manager/picture_manager.js" ></script>
	<script src="scripts/manager/template_manager.js" ></script>
	<script src="scripts/extender_new_tabs.js" ></script>
	<script src="scripts/multiple_select_replace.js" ></script>
	<script src="scripts/manager/main_manager.js" ></script>
</head>

<body>
<div class=wrapper>
	<ul class='tab-top' >
		<li data-tab='template' >Template</li>
		<li data-tab='posts' >Posts</li>
		<li data-tab='pictures' >Resources</li>
		<li data-tab='preview' style="display:none" >Preview</li>
		<li data-tab='tab5' style="display:none" >Tab 5</li>
	</ul>
	
	<section data-tab='template' >
	    <ul class="button-list" >
			<li class="drop" >
				<img src="style/resources/expand.png" />
				Template Item
				<ul>
					<li data-templateaction="additem" data-action="markdown" >
						<span>
							<img src="style/resources/document-text.png" />
							Markdown
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
					<li data-templateaction="additem" data-action="audio" >
						<span>
							<img src="style/resources/audio.png" />
							Audio
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
			<li class="red-button" data-templateaction="cancel-template" >
				Cancel
			</li>
		</ul>
	</section>
	
	<section data-tab='posts' >
		<ul class="inline-list" >
			<li><input type='radio' name='blog_grid_sort' data-templateaction="select-post-filter" value='' checked="" />all</li>				
			<?php
				foreach( $GLOBALS['post_categories'] as $key => $post_type ){ 
					echo "<li><input type='radio' name='blog_grid_sort' data-templateaction='select-post-filter' value='".$post_type."' />".$post_type."</li>";		
				}
			?>	
		</ul>
		<table class="manage-table">
			<thead>
				<tr>
					<th>Category</th>
					<th>Description</th>
					<th>Title</th>
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
		
	<section data-tab='pictures' >
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
	
	<section data-tab='preview' >
		<ul class="button-list" >
			<li data-templateaction="save-preview" >
				Save
			</li>
		</ul>		
		<section class='main' id='preview' >
		
		</section>
		
	</section>
	
	<section data-tab='tab5' >
		tab5
	</section>
	
	
	
</div>

<div class='dark-shade hide' id='save-preview-popup' >
	<div class='fixed-box save-preview-popup form' >
		<label>Category:</label>
		<select name="category" multiple="" ><?php
			foreach( $GLOBALS['post_categories'] as $key => $post_type ){ 
				echo "<option value='".$post_type."'>".$post_type."</option>";		
			}
		?></select>
		
		<label>Title:</label>
		<input type="text" name="title" />
		
		<label>Description:</label>
		<textarea name="description" ></textarea>
		
		<!--label>Tags:</label>
		<input type="text" name="tags" />
		
		<ul class='folders'>
			<?php
				echo FileGetter::folderLi( '/posts', "2", "savePopupFolderClickAction" );
			?>
		<ul>
		
		<label>Folder:</label>
		<input type="text" readonly name="folder_path" /-->
		
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