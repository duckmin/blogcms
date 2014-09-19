<?php
	$server = dirname(__FILE__)."/../server";
	include_once $server."/configs.php";
	$base = $GLOBALS['base_url'];
	$id = $_GET['id'];
	$db = DBHelper::dbConnection();
	$db_getter = new DbGetter( $db );
	$post_views = new PostViews();
	$single_post_data = $db_getter->getSinglePostRowFromDb( $id );

	$tmplt_data = array();
	$tmplt_data["title"] = $single_post_data["title"];
	$tmplt_data["description"] = $single_post_data["description"];
	$tmplt_data["styles"] = "";
	$tmplt_data["scripts"] = "";
	$tmplt_data["base"] = $base;
	$tmplt_data["body"] = "<section class='main'>".$post_views->getPostHTMLFromDBData( $single_post_data )."</section>";
	
	$base_page = new TemplateBinder( "base_page" );
	echo $base_page->bindTemplate( $tmplt_data );
	
?>