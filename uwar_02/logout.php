<?
error_reporting(1);
include("functions.php");
//SET THE USER TO OFFLINE
mysql_query("UPDATE uwar_users SET online=0 WHERE id='$Userid'");
setcookie("username","");
setcookie("password","");
setcookie("Access","");
setcookie("Userid","");

header("Location: ../login.php?action=loggedout");
?>
