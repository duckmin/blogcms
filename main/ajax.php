<?php
$i=(int)$_GET['action'];
switch ($i) {

    case 0:
        $file = '/picture_service.php';
		break;
	case 1:
        $file = '/get_preview.php';
		break;
	case 2:
        $file = '/post_folder_service.php';
		break;
	case 3:
        $file = '/save_post.php';
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
}

include dirname(__FILE__)."/../server/actions".$file;
?>