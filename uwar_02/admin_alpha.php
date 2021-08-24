<?
error_reporting(1);
$section = "Admin - Alpha testing applications";
include("functions.php");
include("header.php");


if(isset($msggreen))
	print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";
if(isset($msgred))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";

if($action == "view" && isset($id) && is_numeric($id))
{
	$query = mysql_query("SELECT * FROM uwar_alpha WHERE id='$id'");
	if($query)
	{
		headerDsp("Applications");
		$viewapl = mysql_fetch_array($query);
		?>	
		<br><br>
		<center>
		<table border="0" cellpadding="4" cellspacing="1" width="90%">
		<tr>
			<td colspan="2" bgcolor="<?=$tdbg1;?>">Viewing Application</td>
		</tr>
     	<tr>
			<td colspan="2" bgcolor="<?=$tdbg1;?>">Name</td><td><?=$viewapl["name"];?></td>
		</tr>
		<tr>
			<td colspan="2" bgcolor="<?=$tdbg1;?>">Age</td><td><?=$viewapl["age"];?></td>
		</tr>
		<tr>
			<td colspan="2" bgcolor="<?=$tdbg1;?>">Country</td><td><?=$viewapl["countr"];?></td>
		</tr>
		 <tr>
			<td colspan="2" bgcolor="<?=$tdbg1;?>">Computer Specs</td><td><?=$viewapl["pc"];?></td>
		</tr>
		<tr>
			<td colspan="2" bgcolor="<?=$tdbg2;?>"><?=$viewapl["composition"]?><br></td>
		</tr>
		</table>
		<br>
		<a href="<?=$PHP_SELF?>"><< Back to main list</a>
		</center>
		<br>
		<?
		footerDsp();
	}
	else $msgred = "Invalid action!";
}
else
{
	headerDsp("Submited applications");
	$request = mysql_query("SELECT * FROM uwar_alpha");
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>">ID</td>
		<td bgcolor="<?=$tdbg1;?>">Name</td>
		<td bgcolor="<?=$tdbg1;?>">Age</td>
	</tr>
	<?
	while($apl = mysql_fetch_array($request))
	{
		?>
		<tr>	
			<td bgcolor="<?=$tdbg2;?>"><a href="<?=$PHPSELF?>?action=view&id=<?=$apl["id"]?>"><?=$apl["id"]?></td>
			<td bgcolor="<?=$tdbg2;?>"><a href="<?=$PHPSELF?>?action=view&id=<?=$apl["id"]?>"><?=$apl["name"]?></td>
			<td bgcolor="<?=$tdbg2;?>"><a href="<?=$PHPSELF?>?action=view&id=<?=$apl["id"]?>"><?=$apl["age"];?></td>
		</td>
		<?
	}
	?>
	</table>
	<br>
	</center>
	<br>
	<? 
	footerDsp();
}
include("footer.php");
?>