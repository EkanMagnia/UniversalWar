<?
error_reporting(1);
$section = "Admin - Open/Close signup";
include("functions.php");
include("header.php");

if ($myrow["access"] < 1) die("You are not an Universal War administrator or creator");

if ($myrow["access"] < 1)
{
	headerDsp("Administrator Interface");
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="#333333">Admin</td>
	</tr>
	<tr>
		<td bgcolor="#222222">You are not an Universal War administrator or creator!</td>
	</tr>
	</table>
	</center>
	<br>
	<?
	footerDsp();
}
else
{
	if($action == "change" && isset($status))
	{
		mysql_query("UPDATE uwar_signup SET status='$status'") or die("Database error!");
		if($status == 1) $msggreen = "Signup was successfully opened!";
		elseif($status == 0) $msggreen = "Signup was successfully closed!";
	}

	if(isset($msggreen))
		print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";

	headerDsp("Administrator Interface - Open/Close signup");
	$signuprequest = mysql_query("SELECT status FROM uwar_signup");
	$signup = mysql_fetch_array($signuprequest);
	if($signup["status"] == 0) { $newstatus = 1; $newstatusword = "Open"; $oldstatusword = "Closed"; }
	elseif($signup["status"] == 1) { $newstatus = 0; $newstatusword = "Close"; $oldstatusword = "Open"; }
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<form action="<?=$PHP_SELF?>?action=change&status=<?=$newstatus?>" method="post" name="submit">
	<tr>
		<td bgcolor="#333333"><?=$newstatusword?> signup</td>
	</tr>
	<tr>
		<td bgcolor="#222222">The signup section is currently <b><?=$oldstatusword?></b></td>
	</tr>
	<tr>
		<td bgcolor="#222222">Would you like to <b><?=$newstatusword?></b> the signup section?</td>
	</tr>
	<tr>
		<td bgcolor="#222222" align="center"><input type="submit" name="submit" value="<?=$newstatusword?> signup"></td>
	</tr>
	</form>
	</table>
	</center>
	<br>
	<? 
}
footerDsp();
include("footer.php");
?>
