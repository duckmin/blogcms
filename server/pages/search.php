<?php
	echo "search";
	//get the vars and rediect to pretty url
	$cat = $_GET["cat"];
	$s = $_GET["search"];
	$search = ( strlen( $s ) > 0 )? urlencode( $s ) : "";
	$redirect_url = $GLOBALS['base_url']."/search/".$cat."/".$search."/1";
	header("location:".$redirect_url );
?>