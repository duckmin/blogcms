<?php
	echo "search";
	//get the vars and rediect to pretty url
	$cat = "blog";
	$search = $_GET["search"];
	$redirect_url = $GLOBALS['base_url']."/".$cat."/".urlencode( $search )."/1";
	header("location:".$redirect_url );
?>