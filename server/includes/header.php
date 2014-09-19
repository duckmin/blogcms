<?php
	function highlight( $cat ){
		$url_cat = ( isset( $_GET['cat'] ) )? $_GET['cat'] : "all";
		
		if( $url_cat === $cat ){
			echo "current-cat";
		}
	}
?>
<header>
	<h1>My Olde Blog</h1>
	<ul>
		<li class="<?php highlight( "all" ) ?>" ><a href="<?php echo $base."/blog.php"; ?>">All</a></li>
		<li class="<?php highlight( "blog" ) ?>" ><a href="<?php echo $base."/blog.php?cat=blog"; ?>">Blogs</a></li>
		<li class="<?php highlight( "video" ) ?>" ><a href="<?php echo $base."/blog.php?cat=video"; ?>">Videos</a></li>
		<li class="<?php highlight( "project" ) ?>" ><a href="<?php echo $base."/blog.php?cat=project"; ?>">Projects</a></li>
	</ul>
</header>