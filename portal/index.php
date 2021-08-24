<?php

ob_start();
session_start();
include_once("config.php");
include_once("functions.php");
include_once("database.php");

if (!isset($_GET["page"])) {
	$_GET["page"] = "main";
}

if ($_GET["page"] == "logout") {
	/*** Clear sessions ***/
	$_SESSION = array();
	session_destroy();	
}

drawContents($_GET["page"]);
ob_end_flush();

?>