<?
error_reporting(1);
$section = "Alliance members";
include("functions.php");
include("header.php");


if($myrow["tagid"] == 0)
{
    headerDsp("Members");
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
    	<td align="center">You are not part of any alliance!<br><br></td>
    </tr>
	</table>
    </center>
    <br>
    <?
	footerDsp();
}
else
{
	headerDsp("Members");
	$mytag = $myrow["tagid"];
	$allyrequest = mysql_query("SELECT * FROM uwar_tags WHERE id='$mytag'");
	$ally = mysql_fetch_array($allyrequest);
	$membersrequest = mysql_query("SELECT * FROM uwar_users WHERE tagid='$mytag' ORDER BY score DESC",$db);

	/*
	$allyscore = $ally["allyscore"] + $user["score"];
	$allysize = $ally["allysize"] + $user["size"];
	$allyscore = number_format($ally["allyscore"],0,".",".");
	$allysize = number_format($ally["allysize"],0,".",".");
	*/
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>" colspan="4">Memberlist of <b><?=$ally["allyname"]?> - Score: </b> <?=$ally["score"];?><b>      Size:</b> <?=$ally["size"];?></td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg1;?>">Commander</td>
		<td bgcolor="<?=$tdbg1;?>">Coordinates</td>
		<td bgcolor="<?=$tdbg1;?>">Size</td>
		<td bgcolor="<?=$tdbg1;?>">Score</td>
	</tr>
	<tr>
	<?
	while($members = mysql_fetch_array($membersrequest))
	{
		$sysid = $members["sysid"];
		$systemrequest = mysql_query("SELECT x,y FROM uwar_systems WHERE id='$sysid'");
		$system = mysql_fetch_array($systemrequest);
		$x = $system["x"];
		$y = $system["y"];
		$z = $members["z"];

		$totalroids = $members["asteroid_mercury"] + $members["asteroid_cobalt"] + $members["asteroid_helium"] +  $members["ui_roids"];
		?>

		<td bgcolor="<?=$tdbg2;?>">
		<a href="communication.php?action=new&x=<?=$x?>&y=<?=$y?>&z=<?=$z?>"><?=$members["nick"]?> of <?=$members["planet"]; if (time()-$members["timer"]<600) { print "<font color=green>*</font>"; }?></a></td>
		<td bgcolor="<?=$tdbg2;?>"><?=$x.":".$y.":".$z?></td>
		<td bgcolor="<?=$tdbg2;?>"><?=$totalroids?></td>
		<td bgcolor="<?=$tdbg2;?>"><?=$members["score"]?></td><tr>
		<?
	}
	?>
	</tr>
	</table>
	<br>
	<?
	footerDsp();
}
include("footer.php");
?>