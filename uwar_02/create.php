<?
error_reporting(1);
$section = "Alliance Creation";
include("functions.php");
include("header.php");

$request = mysql_query("SELECT * FROM uwar_tags WHERE id='$tagid'",$db);
$ally = mysql_fetch_array($request);

if ($action == "create" && $submit) 
{
	$nameSQL = mysql_query("SELECT id FROM uwar_tags WHERE allyname='$allyname'");
	$tagSQL = mysql_query("SELECT id FROM uwar_tags WHERE tag='$tag'");
	if($myrow["tagid"]>0)
	$msgred = "You cannot create an alliance when being a part of another!";
	if ($pword == $tag || $pword == $allyname) $msgred = "The alliance password cannot be equal to the alliance tag or name";
	if ($tag == $allyname) $msgred = "The alliance tag cannot be equal to the alliance name";
	if ($description == "") $msgred = "Alliance description not specified!";
	if ($pword == "") $msgred = "Alliance password not specified!";
	if ($allyname == "") $msgred = "Alliance name not specified!";
	if ($tag == "") $msgred = "Alliance tag not specified!";
	if (mysql_num_rows($nameSQL) > 0 ) $msgred = "Alliance name already taken!";
	if (mysql_num_rows($tagSQL) > 0 ) $msgred = "Alliance tag already taken!";

	if(!$msgred) //If no errors so far, continue creation of alliance
	{
		$tag = htmlentities($tag);
		$pword = htmlentities($pword);
		$description = htmlentities($description);
		$allyname = htmlentities($allyname);
		 $result = mysql_query("INSERT INTO uwar_tags (tag,allyname,password,description,members) VALUES ('$tag','$allyname','$pword','$description',1)") or die("Database error!");
		$log = "Tag=".$tag."<br>Name=".$allyname."<br>Password=".$pword."<br>Description= ".$description."<br>";
		Logging("Alliance Creation", $log, $Userid, $Userid);

		$request2 = mysql_query("SELECT * FROM uwar_tags WHERE tag='$tag'",$db);
		$tags = mysql_fetch_array($request2);
   		$tagid = $tags["id"];
//		 mysql_query("INSERT INTO uwar_allyfund (tagid,sysmercury,syscobalt,syshelium) VALUES ('$tagid',0,0,0)",$db) or die("DB error");		
		mysql_query("UPDATE uwar_users SET tagid='$tagid' WHERE id='$Userid'",$db)or die("Database error!");
//		mysql_query("UPDATE uwar_users SET tag='$tag' WHERE id='$Userid'",$db)or die("Database error!");
		mysql_query("UPDATE uwar_users SET leader='1' WHERE id='$Userid'",$db) or die("Database error!");

		$msg .= $myrow["name"]."\n\n";
		$msg .= "You have succesfully created an Universal War alliance and your are appointed as the leader of it.\n\n";
		$msg .= "Your alliance information are:\n";
		$msg .= "Name: $allyname\n";
		$msg .= "Tag: $tag\n";
		$msg .= "Password: $pword\n";
		$msg .= "We advice you to save this e-mail, it contains various important information.\n\n";
		$msg .= "The Universal Lords.\n\n";
		mail("$myrow[email]","From: alliance@universalwar.net","$msg");
		$msggreen = "Alliance created succesfully! The alliance details are sent to you by e-mail!";
		$justcreated = "true";
	}
}

if(isset($msggreen))
	print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";
if(isset($msgred))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";

if($myrow["tagid"]>0)
{
	headerDsp( "Create Alliance" );
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="#333333" colspan="2">Create an alliance</td>
	</tr>
	<tr>
		<td bgcolor="#222222">You cannot create an alliance when being part of another!</td>
	</tr>
	</table>
	</center>
	<br>
	<?
	footerDsp();
}
elseif($justcreated != "true")
{
	headerDsp( "Create Alliance" );
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<form method="post" action="<?=$PHP_SELF?>?action=create">
	<tr>
		<td bgcolor="<?=$tdbg1;?>" colspan="2">Create an alliance</td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">Name:</td>
		<td bgcolor="<?=$tdbg2;?>"><input type="text" name="allyname" value="">
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">Tag:</td>
		<td bgcolor="<?=$tdbg2;?>"><input type="text" name="tag" value="" maxlength="15">
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">Password:</td>
		<td bgcolor="<?=$tdbg2;?>"><input type="password" name="pword" value="">
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">Description:</td>
		<td bgcolor="<?=$tdbg2;?>"><textarea cols=40 rows=10 name="description"></textarea></td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" colspan="2" align="center"><input type="Submit" name="submit" value="Create alliance"></td>
	</tr>
	</form>
	</table>
	</center>
	<br>
	<?
	footerDsp();
}
elseif($justcreated == "true")
{
	headerDsp( "New alliance" );
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>" colspan="2">New alliance</td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">
			Congratulations, your new alliance was created succesfully. See your alliance details below:<br><br>

			<b>Alliance name:</b>&nbsp;&nbsp;&nbsp;<?=$allyname?><br>
			<b>Alliance tag:</b>&nbsp;&nbsp;&nbsp;<?=$tag?><br>
			<b>Password:</b>&nbsp;&nbsp;&nbsp
			<?
				$length = strlen ($pword);
				for($x=0; $x <= $length; $x++)
				{
					echo "*";
				}
			?><br>
			<b>Description:</b>&nbsp;&nbsp;&nbsp;<?=$description?><br><br>

			Remember, you are now in command over your new alliance. To access alliance leader settings and other settings demanding your alliance, go to the alliance index.<br/><br/>
			<a href="allyindex.php"><< Alliance index</a><br>
		</td>
	</tr>
	</table>
	</center>
	<br>
	<?
	footerDsp();
}
include("footer.php");
?>
