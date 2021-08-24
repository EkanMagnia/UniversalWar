<?
error_reporting(1);
$section = "Admin - Reported Cheaters";
include("functions.php");
include("header.php");

if($action == "remove" && isset($id) && is_numeric($id))
{
	$query = mysql_query("SELECT id FROM uwar_bugs WHERE id='$id'");
	if($query)
	{
		mysql_query("DELETE FROM uwar_bugs WHERE id='$id'");
		$msggreen = "Bug successfully removed!";
	}
	else $msgred = "Invalid action!";
}

if(isset($msggreen))
	print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";
if(isset($msgred))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";

if($action == "view" && isset($id) && is_numeric($id))
{
	$query = mysql_query("SELECT * FROM uwar_bugs WHERE id='$id'");
	if($query)
	{
		headerDsp("Reported bugs - View");
		$viewbug = mysql_fetch_array($query);
		?>	
		<br><br>
		<center>
		<table border="0" cellpadding="4" cellspacing="1" width="90%">
		<tr>
			<td colspan="2" bgcolor="<?=$tdbg1;?>">Viewing bug <b><?=$viewbug["name"]?></b> on section <b><?=$viewbug["section"]?></b></td>
		</tr>
		<tr>
			<td colspan="2" bgcolor="<?=$tdbg2;?>"><?=$viewbug["description"]?><br></td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>" width="21%">Link to screenshot:</td>
			<td bgcolor="<?=$tdbg2;?>"><a href="http://<?=$viewbug["links"]?>"><?=$viewbug["links"]?></a></td>
		</tr>
		</table>
		<br>
		<a href="<?=$PHP_SELF?>"><< Back to cheaters list</a>
		</center>
		<br>
		<?
		footerDsp();
	}
	else $msgred = "Invalid action!";
}
else
{
	headerDsp("Reported bugs");
	$request = mysql_query("SELECT * FROM uwar_bugs");
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>">Section</td>
		<td bgcolor="<?=$tdbg1;?>">Cheater name</td>
		<td bgcolor="<?=$tdbg1;?>">Remove Cheater</td>
		<td bgcolor="<?=$tdbg1;?>">Sent By</td>
	</tr>
	<?
	while($bugs = mysql_fetch_array($request))
	{
		$creatorid = $bugs["creator"];
		$CreatorSQL = mysql_query("SELECT * FROM uwar_users WHERE id='$creatorid'",$db);
		$creator = mysql_fetch_array($CreatorSQL);
		?>
		<tr>	
			<td bgcolor="<?=$tdbg2;?>"><a href="<?=$PHPSELF?>?action=view&id=<?=$bugs["id"]?>"><?=$bugs["section"]?></td>
			<td bgcolor="<?=$tdbg2;?>"><?=$bugs["name"]?></td>
			<td bgcolor="<?=$tdbg2;?>"><a href="<?=$PHPSELF?>?action=remove&id=<?=$bugs["id"]?>">Remove</a></td>
			<td bgcolor="<?=$tdbg2;?>"><?=$creator["nick"];?> of <?=$creator["planet"];?></td>
		</td>
		<?
	}
	?>
	</table>
	<br>
	<a href="administrator.php"><< Back to admin index</a>
	</center>
	<br>
	<? 
	footerDsp();
}
include("footer.php");
?>