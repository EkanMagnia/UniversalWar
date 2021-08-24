<?
error_reporting(1);
$section = "Administrator Interface";
include("functions.php");
include("header.php");


if ($myrow["access"] < 1)
{
	headerDsp("Administrator Interface");
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>">Admin:</td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">You are not an Universal War administrator or creator!</td>
	</tr>
	</table>
	</center>
	<br>
	<?
	footerDsp();
}
else
{
	headerDsp("Administrator Interface");

	$request = mysql_query("SELECT * FROM uwar_admins WHERE id='$Userid'");
	$access = mysql_fetch_array($request);
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>">Admin options:</td>
	</tr>
	<? if ($access["add_news"] == "y")
	{ ?>
	<tr>
		<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="admin_announcements.php">Add announcements !</a></td>
	</tr>
	<? }
	if ($access["bugs"] == "y")
	{ ?>
	<tr>
		<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="admin_bugs.php">View reported bug list !</a></td>
	</tr>
	<? 
	}
	if ($access["modes"] == "y") { ?>
	<tr>
		<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="admin_gamemodes.php">Change game modes !</a></td>
	</tr>
	<?
	}
	if ($access["havoc"] == "y") { ?>
	<tr>
		<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="admin_havoc.php">Havoc Management !</a></td>
	</tr>
  	<? }
    if ($access["email"] == "y") { ?>
	<tr>
		<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="admin_email.php">Administration E-mail Form !</a></td>
	</tr>
  	<? }
    if ($access["mail"] == "y") { ?>
	<tr>
		<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="adminmail.php">Universal Communication Center !</a></td>
	</tr>
  	<? }
    if ($access["newround"] == "y") { ?>

	<tr>
		<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="admin_setup.php">Setup a new round</a></td>
	</tr>
	<? }
    if ($access["multi"] == "y") { ?>

	<tr>
		<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="admin_multi.php">Multi-account finder tool</a></td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="admin_logs.php">Logs</a></td>
	</tr>
	<tr>
  		<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="admin_search.php">Search Player</a></td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="admin_bugs.php">Cheater list !</a></td>
	</tr>
  	<? }
    if ($access["forum"] == "y") { ?>
	<tr>
		<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="admin_forum.php">Forum !</a></td>
	</tr>
  	<? }
    if ($access["ticker"] == "y") { ?>
	<tr>
		<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="admin_ticker.php">Ticker Setup</a></td>
	</tr>
  	<? }
    if ($access["supreme"] == "y") { ?>
	<tr>
		<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="admin_management.php">Management</a></td>
	</tr>
  	<? }
	 ?>
	<tr>
		<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="admin_universe.php">Universe !</a></td>
	</tr>


	</table>
	</center>
	<br>
	<?
	footerDsp();
}

include("footer.php");
?>