
<?php
$pw="a";
$u="b";

if ( !isset($_SERVER['PHP_AUTH_USER']) ){
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}else{
    if( $_SERVER['PHP_AUTH_USER'] === $u && $_SERVER['PHP_AUTH_PW'] === $pw ){
		echo "you are signed in";
	}else{
		header('WWW-Authenticate: Basic realm="My Realm"');
		header('HTTP/1.0 401 Unauthorized');
	}
	//echo "<p>Hello {$_SERVER['PHP_AUTH_USER']}.</p>";
    //echo "<p>You entered {$_SERVER['PHP_AUTH_PW']} as your password.</p>";
}
?>
