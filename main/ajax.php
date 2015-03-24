<?php
include dirname(__FILE__)."/../server/configs.php";

if( isset( $_GET['action'] ) ){
	
	$i=(int)$_GET['action'];
	switch ($i) {
	
	    case 0:
	        $file = '/picture_service.php';
			break;
		case 1:
	        $file = '/get_preview.php';
			break;
		case 2:
		case 3:
	       // $file = '/save_post.php';
	       $file = '/save_mongo.php';
			break;
		case 4:
	        $file = '/get_post_info.php';
			break;
		case 5:
	        $file = '/delete_blog_entry.php';
			break;
		case 6:
	        $file = '/get_edit_template_json.php';
			break;
		case 7:
	        $file = '/save_edited_post_template.php';
			break;
		case 8:
	        $file = '/update_post_date.php';
			break;
		case 9:
	        $file = '/delete_resource.php';
			break;
		case 10:
	        $file = '/count_page_view.php';
			break;
		case 11:
	        $file = '/analytics/unique_url_paginator.php';
			break;
		case 12:
	        $file = '/analytics/graph_data_by_daterange.php';
			break;
		case 13:
	        $file = '/add_new_resource_folder.php';
			break;
		case 14:
	        $file = '/get_previous_post_html_from_timestamp.php';
			break;
	}

	include $GLOBALS['server_path']."/actions".$file;
}else{
	goTo404();
}
?>