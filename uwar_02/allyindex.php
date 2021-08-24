<?
error_reporting(1);
$section = "Alliance Index";
include("functions.php");
include("header.php");

$mytag = $myrow["tagid"];
$allyrequest = mysql_query("SELECT * FROM uwar_tags WHERE id='$mytag'");
$ally = mysql_fetch_array($allyrequest);

if($action == "leave") $msggreen = "Alliance left successfully!";
if($action == "abdicate") $msggreen = "You have successfully abdicated from the leader position!";
if($action == "delete") $msggreen = "The alliance was successfully deleted!";

if(isset($msggreen))
	print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";
if(isset($msgred))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";

if($myrow["tagid"] == 0)
{
    headerDsp("Alliance Index");
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
    	<td align="center">You are not a member of any alliance!<br><br></td>
    </tr>
    <tr>
    	<td bgcolor="<?=$tdbg1;?>">Alliance Options:</td>
    </tr>
    <tr>
		<td bgcolor="<?=$tdbg2;?>">
        <img src="images/arrow_off.gif">&nbsp;<a href="join.php">Join an alliance</a>
        <br>
        <img src="images/arrow_off.gif">&nbsp;<a href="create.php">Create an alliance</a>
<!--        <br>
		<img src="images/arrow_off.gif">&nbsp;<a href="allysearch.php">Search an alliance</a>-->
        </td>
    </tr>
    </table>
    </center>
    <br>
    <?
	footerDsp();
}
else
{
	headerDsp("Alliance");
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td colspan="2" align="center">You are a member of the alliance <b><?=$ally["allyname"]?></b><br><br></td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg1;?>" colspan="2">Leaders of your alliance</td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">Leader:</td><?
		$leaderSQL = mysql_query("SELECT * FROM uwar_users WHERE tagid='$mytag' and leader=1",$db);
		$leader = mysql_fetch_array($leaderSQL);
		if(mysql_num_rows($leaderSQL) == 0) 
		{ ?><td bgcolor="<?=$tdbg2;?>">There is no leader in the alliance</td><? }
		else 
		{ 
			?><td bgcolor="<?=$tdbg2;?>"><font color="#0000FF"><?=$leader["nick"]?> of <?=$leader["planet"]?></font></td> <? 
		}
		?>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">Battle Commander:</td><?
		$mowSQL = mysql_query("SELECT * FROM uwar_users WHERE tagid='$mytag' and leader=2",$db);
		$mow = mysql_fetch_array($mowSQL);
		if(mysql_num_rows($mowSQL) == 0)
		{ ?><td bgcolor="<?=$tdbg2;?>">There is no Battle Commander in the alliance</td><? }
		else 
		{ ?><td bgcolor="<?=$tdbg2;?>"><font color="#FF0000"><?=$mow["nick"]?> of <?=$mow["planet"]?></font></td><? }
		?>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">Messenger:</td><?
		$socSQL = mysql_query("SELECT * FROM uwar_users WHERE tagid='$mytag' and leader=3",$db);
		$soc = mysql_fetch_array($socSQL);
		if(mysql_num_rows($socSQL) == 0) { ?><td bgcolor="<?=$tdbg2;?>">There is no Messenger in the alliance</td><? }
		else
		{ ?><td bgcolor="<?=$tdbg2;?>"><font color="#00FF00"><?=$soc["nick"]?> of <?=$soc["planet"]?></font></td><? }
		?>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">Senator of Economy:</td><?
		$mofSQL = mysql_query("SELECT * FROM uwar_users WHERE tagid='$mytag' and leader=4",$db);
		$mof = mysql_fetch_array($mofSQL);
		if(mysql_num_rows($mofSQL) == 0) 
		{ ?><td bgcolor="<?=$tdbg2;?>">There is no Senator of Economy in the alliance</td><? }
		else { ?><td bgcolor="<?=$tdbg2;?>"><font color="#CCFF00"><?=$mof["nick"]?> of <?=$mof["planet"]?></font></td><?}
		?>
	</tr>
	</table>
	</center>
	<br>
	<?
	footerDsp();

	headerDsp("Alliance sections");
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>">Sections</td>
	</tr>
	<!--<tr>
	<td bgcolor="<?=$tdbg2;?>">
  	<img src="images/arrow_off.gif">&nbsp;<a href="allysearch.php">Search an alliance</a></td>
	</tr>-->
		<tr>
  		<td bgcolor="<?=$tdbg2;?>"><IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=rules>
		<a href="members.php">Members</a></td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>"><IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=rules>
		<a href="documents.php">Documents</a></td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>"><IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=rules>
		<a href="bstatus.php">War Status</a></td>
	</tr>
<!--	<tr>
		<td bgcolor="<?=$tdbg2;?>"><IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=rules>
		<a href="allyfund.php">Bank</a></td>
	</tr>
-->
	<?
	if ($myrow["leader"] != 0) { ?><tr><td bgcolor="<?=$tdbg2;?>"><IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=rules>
	<a href="management.php">Management</a></td></tr><? } 
	?>
	<tr>
		<td bgcolor="<?=$tdbg2;?>"><IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=rules>
		<a href="leave.php">Leave your alliance</a></td>
	</tr>	
	</table>
	</center>
	<br>
	<?
	footerDsp();
}
include("footer.php");
?>