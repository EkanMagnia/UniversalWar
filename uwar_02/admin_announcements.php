<?
error_reporting(1);
$section = "Admin - Add Announcements ";
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
		<td bgcolor="<?=$tdbg1;?>">Admin</td>
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
	if ($subject && $message)
	{
		$time =	time();
		$message = nl2br($message);
		$author = $myrow["nick"];
		mysql_query("INSERT INTO uwar_announcements (subject,author,time,motd) VALUES ('$subject','$author','$time','$message')");
		$msggreen = "Announcement added!";
	}

	if(isset($msggreen))
		print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";

	headerDsp("Administrator Interface - Add Announcement");
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<form action="<?=$PHP_SELF?>" method="post" name="sendNews">
	<tr>
		<td bgcolor="<?=$tdbg1;?>" colspan="2">New announcement</td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" width="1%">Subject:</td>
		<td bgcolor="<?=$tdbg2;?>"><input type="text" name="subject" size=36></td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">Text:</td>
		<td bgcolor="<?=$tdbg2;?>"><textarea name="message" cols="35" rows="10"></textarea></td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" colspan="2" align="center"><input type="submit" name="add" value="Add announcement"></td>
	</tr>
	</form>
	</table>
	<br>
	<a href="administrator.php"><< Back to admin index</a>
	</center>
	<br>
	<? 
}
footerDsp();
include("footer.php");
?>
