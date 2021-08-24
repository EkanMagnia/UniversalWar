<?
error_reporting(1);
$section = "Announcements";
include("functions.php");
include("header.php");


if(isset($msggreen))
	print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";
if(isset($msgred))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";

if($action == "view" && isset($id) && is_numeric($id))
{
	$query = mysql_query("SELECT * FROM uwar_announcements WHERE id='$id'");
	if($query)
	{
		headerDsp("Announcements - View");
		$post = mysql_fetch_array($query);
		?>	
		<br><br>
		<center>
		<table border="0" cellpadding="4" cellspacing="1" width="90%">
		<tr>
			<td align="center"><b><font size="4"><?=$post["subject"]?></font></b></td>
		</tr>
		<tr>
			<td align="center">Posted by <b><?=$post["author"]?></b> on <? print date("M j, Y H:i:s",$post["time"]); ?></td>
		</tr>
		<tr>
			<td><br><?=$post["motd"]?></td>
		</tr>
		</table>
		<br>
		<a href="announcements.php"><< Back to announcements list</a>
		</center>
		<br>
		<?
		footerDsp();
	}
	else $msgred = "Invalid action!";
}
else
{
	headerDsp("All announcements");
	$request = mysql_query("SELECT * FROM uwar_announcements order by time DESC");
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>">Subject</td>
		<td bgcolor="<?=$tdbg1;?>">Author</td>
		<td bgcolor="<?=$tdbg1;?>">Time</td>
	</tr>
	<?
	while($posts = mysql_fetch_array($request))
	{
		?>
		<tr>	
			<td bgcolor="<?=$tdbg2;?>"><a href="<?=$PHPSELF?>?action=view&id=<?=$posts["id"]?>"><?=$posts["subject"]?></td>
			<td bgcolor="<?=$tdbg2;?>"><?=$posts["author"]?></td>
			<td bgcolor="<?=$tdbg2;?>"><? print date("M j, Y H:i:s",$posts["time"]); ?></td>
		</td>
		<?
	}
	?>
	</table>
	</center>
	<br>
	<? 
	footerDsp();
}
include("footer.php");
?>