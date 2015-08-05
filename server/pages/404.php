<?php
	//included in index.php which has configs.php included already
	$base = $GLOBALS['base_url'];
	$url = $_SERVER["REQUEST_URI"];
	$_GET['cat'] = $GLOBALS['post_categories'][0];  //use default category if on 404		
	$cat = $_GET['cat'];		
	$parsedown = new Parsedown();				
	$post_views = new PostViews( $parsedown );		
	$template = file_get_contents( $GLOBALS['template_dir']."/base_page.txt" );
	$title = "Page Not Found - ".$_SERVER['HTTP_HOST'];		
	$desc	= 	"";
	$entry_template_404 = file_get_contents( $GLOBALS['template_dir']."/404_entry.txt" );
	$entry_data_404 = array(
		"bad_url" => "$base$url"	
	);
	$article = TemplateBinder::bindTemplate( $entry_template_404, $entry_data_404 );
	
	//need to special chars anything using $search param that gets inserted into HTML
	$tmplt_data = array();
	$tmplt_data["title"] = htmlspecialchars($title, ENT_QUOTES);
	$tmplt_data["description"] = htmlspecialchars($desc, ENT_QUOTES);
	$tmplt_data["styles"] = "";
	$tmplt_data["scripts"] = "";
	$tmplt_data["base"] = $base;
	$tmplt_data["category"] = $cat;
	$tmplt_data["search_placeholder"] = htmlspecialchars("search ".$cat, ENT_QUOTES);	
	$tmplt_data["search_value"] = "";		
	$tmplt_data["header"] = $post_views->getCatHeaderList( $cat );
	$tmplt_data["body"] = $article;
	
	$full_page = TemplateBinder::bindTemplate( $template, $tmplt_data );
	echo $full_page;
		

?>
