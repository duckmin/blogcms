<?php
	$server = dirname(__FILE__)."/../server";
	include_once $server."/configs.php";
	$base = $GLOBALS['base_url'];
	$id = $_GET['id'];
	$db = DBHelper::dbConnection();
	$db_getter = new DbGetter( $db );
	$post_views = new PostViews();
	$single_post_data = $db_getter->getSinglePostRowFromDb( $id );
	//echo $post_views->getPostHTMLFromDBData( $single_post_data );
	//echo print_r( $single_post_data );
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $single_post_data["title"] ?></title>
	<meta charset="utf-8"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="General" name="rating"/>
	<meta content="English" name="language"/>
	<meta name="description" content="<?php echo $single_post_data["description"] ?>" >
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
			echo $post_views->getPostHTMLFromDBData( $single_post_data );
		?>
	</section>
			
</div>

</body>
</html>