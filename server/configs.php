<?php
session_start();
date_default_timezone_set('UTC');

$GLOBALS['index_path'] = dirname(__FILE__)."/../main";
$GLOBALS['base_url'] = "http://".$_SERVER['HTTP_HOST']."/templater/main";
$GLOBALS['max_kb_img_upload']=500;

$GLOBALS['amount_on_main_page'] = 3;
$GLOBALS['amount_on_manger_tab'] = 5;

$GLOBALS['max_category_length'] = 500;
$GLOBALS['max_title_length'] = 500;
$GLOBALS['max_desc_length'] = 500;
$GLOBALS['max_tags_length'] = 1000;
$GLOBALS['max_folder_path_length'] = 1000;

$GLOBALS['cache_dir'] = dirname(__FILE__)."/page_cache";

$GLOBALS['post_categories'] = array(
	"0"=>"blog",
	"1"=>"video",
	"2"=>"project"
);

$GLOBALS['blog_users'] = array(
	"bob"=>"cookedduck",
	"sue"=>"sauces234"
);

function returnMessage( $success, $message, $data ){
	$holder=Array( 'result'=>$success, 'message'=>$message, 'data'=>$data );
	return json_encode( $holder );
};
	
//autoload any class with this function
spl_autoload_register('myAutoloader');

function myAutoloader( $className )
{
    $path = dirname(__FILE__).'/classes/';

    include $path.$className.'.php';
}

function paginator( $page_num, $amount_retrieved, $amount_per_page, $add_to_base ){
	$paginator="<ul class='paginator' >";
	if( $page_num>1 ){
		$back=$page_num-1;
		$paginator.="<li><a href='".$GLOBALS['base_url']."/blog.php".$add_to_base."p=".$back."' >Page ".$back."</a></li>";
	}
	if( $amount_retrieved > $amount_per_page ){
		$forward=$page_num+1;
		$paginator.="<li><a href='".$GLOBALS['base_url']."/blog.php".$add_to_base."p=".$forward."' >Page ".$forward."</a></li>";
	}
	return $paginator."</ul>";
}

?>