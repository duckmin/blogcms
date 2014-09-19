<?php
	$server = dirname(__FILE__)."/../server";
	include_once $server."/configs.php";
	$base = $GLOBALS['base_url'];
	$post_class = new PostClass;
	
	if( !isset( $_GET['p'] ) ){ 
		$page=1;
	}else{
		$page=(int)$_GET['p'];
	}
	
	$cat = ( isset( $_GET['cat'] ) )? $_GET['cat'] : null;
	$search = ( isset( $_GET['s'] ) )? $_GET['s'] : null;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Blog - Page <?php echo $page; ?></title>
	<meta charset="utf-8"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="General" name="rating"/>
	<meta content="English" name="language"/>
	<meta name="viewport" content="width=device-width; initial-scale=1.0;">
	<link href='http://fonts.googleapis.com/css?family=Raleway+Dots' rel='stylesheet' type='text/css'>
	<link rel='stylesheet' type='text/css' href='style/global_style.css'>
	<link rel='stylesheet' type='text/css' href='style/tab_style.css'>
	<link rel='stylesheet' type='text/css' href='style/blog_style.css'>
	<?php
		include $server."/includes/global_style_scripts.php";
	?>
</head>

<body>
<div class=wrapper>
	<?php include $server."/includes/header.php"; ?>
	<section class='main' >
		<?php
			$db = DBHelper::dbConnection();
			$db_getter = new DbGetter( $db );
			$post_views = new PostViews();
			$post_controller = new PostController( $db_getter, $post_views );
			echo $post_controller->getHomePagePosts( $page, $cat, $search );
		?>
	</section>
			
</div>

</body>
</html>