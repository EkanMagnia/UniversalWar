<?
	ob_start();
	session_start();
	include_once( "database.php" );
	include_once( "functions.php" );
	
	if ( !isset( $_GET["p"] ) ) {
	
		$_GET["p"] = "login";
	}
	
	include_once( "header.php" );
	include_once( $_GET["p"].".php" );
	include_once( "footer.php" );
	ob_end_flush();
	
?>