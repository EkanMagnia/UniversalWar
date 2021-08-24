<?
error_reporting(1);
$section = "Join alliance";
include("functions.php");
include("header.php");


if ($action == "join")
{
	$jointag = strip_tags($jointag);
   	$joinpass = strip_tags($joinpassword);
    if($myrow["tagid"]==0)
    {
	    if($jointag)
	    {
	        if($joinpassword)
	        {
	            $request = mysql_query("SELECT * FROM uwar_tags WHERE tag='$jointag' AND password='$joinpassword'");
	            if(mysql_num_rows($request) != 0)
	            {
	                $ally = mysql_fetch_array($request);
	                if ($ally["members"] < 50)
	                {
	                    $newmembers = $ally["members"] + 1;
	                    mysql_query("UPDATE uwar_tags SET members='$newmembers' WHERE tag='$jointag'");
	                    $msggreen = "Alliance joined succesfully!";
	                    $justjoined = "true";
						$allySQL = mysql_query("SELECT * FROM uwar_tags WHERE tag='$jointag'",$db);
						$ally = mysql_fetch_array($allySQL);
						$tagid = $ally["id"];
						mysql_query("UPDATE uwar_users SET tagid='$tagid' WHERE id='$Userid'");
						$LeaderSQL = mysql_query("SELECT * FROM uwar_users WHERE tagid='$tagid' AND leader=1",$db);
						$leader = mysql_fetch_array($LeaderSQL);
						$news = "Commander ".$myrow["nick"]." of ".$myrow["planet"]." (".$gal["x"].":".$gal["y"].":".$myrow["z"].") has joined our alliance.";
						add_news("New member", $news, $leader["id"]);
	                } else $msgred = "Alliance full. It cannot accept new members!";
	            } else  $msgred = "Incorrect alliance tag or password!";
	        } else $msgred = "Alliance password not specified!";
	    } else $msgred = "Alliance tag not specified!";
    } else $msgred = "You are already part of an alliance!";
}

if(isset($msggreen))
	print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";
if(isset($msgred))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";

if($justjoined == "true")
{

	$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'",$db);
	$myrow = mysql_fetch_array($request);
	$tag = $myrow["tagid"];
	$allySQL = mysql_query("SELECT * FROM uwar_tags WHERE id='$tag'",$db);
	$ally = mysql_fetch_array($allySQL);
	headerDsp("Join alliance");
    ?>
    <br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
    <tr>
    	<td>You are now member of alliance <b><?=$ally["allyname"]?></b></td>
    </tr>
	<tr>
    	<td><a href="allyindex.php">Click here to go to the home of your new alliance >></a></td>
    </tr>
    </table>
    </center>
    <br>
    <?
    footerDsp();
}

if ($myrow["tagid"] == 0)
{
	headerDsp("Join alliance");
    ?>
    <br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<form action="<?=$PHP_SELF?>?action=join" name="join" method="post">
    <tr>
		<td bgcolor="<?=$tdbg2;?>">Tag:</td><td bgcolor="<?=$tdbg2;?>"><input type="text" name="jointag"></td>
    </tr>
    <tr>
		<td bgcolor="<?=$tdbg2;?>">Password:</td><td bgcolor="<?=$tdbg2;?>"><input type="password" name="joinpassword"></td>
    </tr>
    <tr>
    	<td bgcolor="<?=$tdbg2;?>"></td><td bgcolor="<?=$tdbg2;?>"><input type="submit" value="Join alliance"></td>
    </tr>
    </form>
    </table>
    </center>
    <br>
    <?
    footerDsp();
}
elseif($myrow["tagid"] > 0 && $justjoined != "true")
{
	headerDsp("Join alliance");
	?>
    <br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td>While you already are a member of an alliance, you can not join another!</td>
    </tr>
    </form>
    </table>
    </center>
    <br>
    <?
    footerDsp();
}
include("footer.php");
?>