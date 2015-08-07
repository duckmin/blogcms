<!DOCTYPE html>
<html>
<head>
	<title>Manager</title>
	<meta charset="utf-8"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta content="General" name="rating"/>
	<meta content="English" name="language"/>
	<meta name="viewport" content="width=device-width; initial-scale=1.0;">
	<link rel='stylesheet' type='text/css' href='style/global_style.css'>
	<link rel='stylesheet' type='text/css' href='style/tab_style.css'>
	<link rel='stylesheet' type='text/css' href='style/manager_style.css'>
	<link rel='stylesheet' type='text/css' href='style/blog_style.css'>
	<link rel='stylesheet' type='text/css' href='style/date_picker.css'>
	<script src="http://yui.yahooapis.com/3.18.1/build/yui/yui-min.js"></script>	
	<script src="scripts/element_extender.js" ></script>
	<script src="scripts/forms.js" ></script>
	<script src="scripts/globals.js" ></script>	
	<script src="scripts/manager/calendar.js" ></script>
	<script src="scripts/manager/alert_boxes.js" ></script>
	<script src="scripts/manager/picture_manager.js" ></script>
	<script src="scripts/manager/template_manager.js" ></script>
	<script src="scripts/extender_new_tabs.js" ></script>
	<script src="scripts/multiple_select_replace.js" ></script>
	<script src="scripts/manager/main_manager.js" ></script>
	<script src="scripts/manager/analytics_graphs.js" ></script>
</head>

<body>
<div class=wrapper>
	<ul class='login-bar' >
		<li><?php
			echo $_SESSION["user"];
		?></li>
		<li data-loadaction="logout" >logout<form id="logout" method="GET" action="/logout"></form></li>
	</ul>
	<ul class='tab-top' >
		<li data-tab='template' >Template</li>
		<li data-tab='pictures' >Resources</li>
		<li data-tab='posts' >Posts</li>
		<li data-tab='preview' style="display:none" >Preview</li>
		<li data-tab='analytics'  >Analytics</li>
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
			<li class="hide" id="edit-mode-form" >
				<input class="hide" type="checkbox" name="edit_mode" />
				<input type="hidden" name="id_in_edit" /> 
				<img src="style/resources/pencil.png" />
				Edit Mode
				<img src="style/resources/arrow-31-16.png" />		
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
		<ul class="inline-list form-list" >
			<li><span>all</span><input type='radio' name='blog_grid_sort' data-templateaction="select-post-filter" value='' checked="" /></li>				
			<?php
				foreach( $GLOBALS['post_categories'] as $key => $post_type ){ 
					echo "<li><span>".$post_type."</span><input type='radio' name='blog_grid_sort' data-templateaction='select-post-filter' value='".$post_type."' /></li>";		
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
		<ul class='button-list' >
		
		</ul>
	</section>
		
	<section data-tab='pictures' >
		<form id="img-upload-form" action="/upload_img.php" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="return imageUploadValidator();" >
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
		<!-- explorer appended with JS -->
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
	
	<section class="clearfix" data-tab='analytics' >
		<ul class="inline-list form-list" data-templateaction="date-picker">
			<!--li><span>line</span><input type="radio" value="combo" name="chart_type" checked=""></li>				
			<li><span>bar</span><input type="radio" value="column" name="chart_type"></li-->
			<li>
				<span>start</span>
				<input data-datepick="" type="text" value='<?php  echo date( "m/d/Y", strtotime("-1 week") ); ?>' name="start_date">
			</li>	
			<li>
				<span>end</span>
				<input data-datepick="" type="text" value='<?php  echo date( "m/d/Y" ); ?>' name="end_date">
			</li>
			<li>
				<!---input type="hidden" value="column" name="url" value='' -->
				<script>
					document.write( "<input type='hidden' name='url' value='/' >" );
				</script>	
			</li>
		</ul>
		<div class="left" >
			<ul class="multi-replace" >
		
			</ul>
		</div>
		<div class="right">
			<div id='views-graph'>
			</div>
		</div>
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