<?php
include_once dirname(__FILE__)."/../configs.php";

$db = DBHelper::dbConnection();
$db_getter = new DbGetter( $db );

//$r = $db_getter->getHomePagePostsFromDb( 1 );

//echo print_r( $r );
//$post_views = new PostViews();
//$controller = new PostController( $db_getter, $post_views );
//echo $controller->getHomePagePosts( 1 );

//echo print_r( $db_getter->getHomePagePostsFromDbByCategory( 1, 'video' ) );

echo print_r($_SERVER );
?>