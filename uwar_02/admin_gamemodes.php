<?
error_reporting(1);
$section = "Admin - Change game modes";
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
	if($gamemode == "signup" && $action == "change" && isset($status))
	{
		mysql_query("UPDATE uwar_modes SET signup='$status'") or die("Database error!");
		if($status == 0) $msggreen = "Signup was successfully opened!";
		elseif($status == 1) $msggreen = "Signup was successfully closed!";
	}
	if($gamemode == "havoc" && $action == "change" && isset($status))
	{
		mysql_query("UPDATE uwar_modes SET havoc='$status'") or die("Database error!");
		if($status == 1) $msggreen = "Havoc was successfully started!";
		elseif($status == 0) $msggreen = "Havoc was successfully stopped!";
	}
	if($gamemode == "gametype" && $action == "change" && isset($status))
	{
		mysql_query("UPDATE uwar_modes SET gametype='$status'") or die("Database error!");
		if($status == 1) $msggreen = "Gametype was successfully changed!";
		elseif($status == 0) $msggreen = "Gametype was successfully changed!!";
	}
	if($gamemode == "pause" && $action == "change" && isset($status))
	{
		mysql_query("UPDATE uwar_modes SET pause='$status'") or die("Database error!");
		if($status == 1) $msggreen = "Round was successfully continued!";
		elseif($status == 0) $msggreen = "Round was successfully paused!";
	}

	if(isset($msggreen))
		print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";

	if($gamemode == "signup")
	{
		headerDsp("Administrator Interface - Open/Close signup");
		$signuprequest = mysql_query("SELECT signup FROM uwar_modes");
		$signup = mysql_fetch_array($signuprequest);
		if($signup["signup"] == 1) { $newstatus = 0; $newstatusword = "Open"; $oldstatusword = "Closed"; }
		elseif($signup["signup"] == 0) { $newstatus = 1; $newstatusword = "Close"; $oldstatusword = "Open"; }
		?>
		<br><br>
		<center>
		<table border="0" cellpadding="4" cellspacing="1" width="90%">
		<form action="<?=$PHP_SELF?>?gamemode=<?=$gamemode?>&action=change&status=<?=$newstatus?>" method="post" name="submit">
		<tr>
			<td bgcolor="<?=$tdbg1;?>"><?=$newstatusword?> signup</td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>">The signup section is currently <b><?=$oldstatusword?></b></td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>">Would you like to <b><?=$newstatusword?></b> the signup section?</td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>" align="center"><input type="submit" name="submit" value="<?=$newstatusword?> signup"></td>
		</tr>
		</form>
		</table>
		<br>
		<a href="<?=$PHP_SELF?>"><< Back to change game modes</a>
		</center>
		<br>
		<? 
		footerDsp();
	}
	elseif($gamemode == "havoc")
	{
		headerDsp("Administrator Interface - Start/Stop havoc");
		$havocrequest = mysql_query("SELECT havoc FROM uwar_modes");
		$havoc = mysql_fetch_array($havocrequest);
		if($havoc["havoc"] == 0) { $newstatus = 1; $newstatusword = "Start"; $oldstatusword = "Off"; }
		elseif($havoc["havoc"] == 1) { $newstatus = 0; $newstatusword = "Stop"; $oldstatusword = "On"; }
		?>
		<br><br>
		<center>
		<table border="0" cellpadding="4" cellspacing="1" width="90%">
		<form action="<?=$PHP_SELF?>?gamemode=<?=$gamemode?>&action=change&status=<?=$newstatus?>" method="post" name="submit">
		<tr>
			<td bgcolor="<?=$tdbg1;?>"><?=$newstatusword?> havoc</td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>">Havoc is currently <b><?=$oldstatusword?></b></td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>">Would you like to <b><?=$newstatusword?></b> the havoc?</td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>" align="center"><input type="submit" name="submit" value="<?=$newstatusword?> havoc"></td>
		</tr>
		</form>
		</table>
		<br>
		<a href="<?=$PHP_SELF?>"><< Back to change game modes</a>
		</center>
		<br>
		<? 
		footerDsp();
	}
	elseif($gamemode == "gametype")
	{
		headerDsp("Administrator Interface - Set gametype");
		$gametyperequest = mysql_query("SELECT gametype FROM uwar_modes");
		$gametype = mysql_fetch_array($gametyperequest);
		if($gametype["gametype"] == 'i') { $newstatus = "l"; $newstatusword = "LAN"; $oldstatusword = "Inet"; }
		elseif($gametype["gametype"] == 'l') { $newstatus = "i"; $newstatusword = "Inet"; $oldstatusword = "LAN"; }
		?>
		<br><br>
		<center>
		<table border="0" cellpadding="4" cellspacing="1" width="90%">
		<form action="<?=$PHP_SELF?>?gamemode=<?=$gamemode?>&action=change&status=<?=$newstatus?>" method="post" name="submit">
		<tr>
			<td bgcolor="<?=$tdbg1;?>"><?=$newstatusword?> as gametype</td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>">Current gametype is <b><?=$oldstatusword?></b></td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>">Would you like to change gametype to <b><?=$newstatusword?></b>?</td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>" align="center"><input type="submit" name="submit" value="Change to <?=$newstatusword?>"></td>
		</tr>
		</form>
		</table>
		<br>
		<a href="<?=$PHP_SELF?>"><< Back to change game modes</a>
		</center>
		<br>
		<? 
		footerDsp();
	}
	elseif($gamemode == "pause")
	{
		headerDsp("Administrator Interface - Pause/Continue round");
		$pauserequest = mysql_query("SELECT pause FROM uwar_modes");
		$pause = mysql_fetch_array($pauserequest);
		if($pause["pause"] == '0') { $newstatus = "1"; $newstatusword = "continue"; $oldstatusword = "paused"; }
		elseif($pause["pause"] == '1') { $newstatus = "0"; $newstatusword = "pause"; $oldstatusword = "continued"; }
		?>
		<br><br>
		<center>
		<table border="0" cellpadding="4" cellspacing="1" width="90%">
		<form action="<?=$PHP_SELF?>?gamemode=<?=$gamemode?>&action=change&status=<?=$newstatus?>" method="post" name="submit">
		<tr>
			<td bgcolor="<?=$tdbg1;?>"><?=$newstatusword?> round</td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>">Round is currently <b><?=$oldstatusword?></b></td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>">Would you like to <b><?=$newstatusword?></b> round ?</td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>" align="center"><input type="submit" name="submit" value="<?=$newstatusword?> round"></td>
		</tr>
		</form>
		</table>
		<br>
		<a href="<?=$PHP_SELF?>"><< Back to change game modes</a>
		</center>
		<br>
		<? 
		footerDsp();
	}
	else
	{
		headerDsp("Change game modes");
		?>
			<br><br>
			<center>
			<table border="0" cellpadding="4" cellspacing="1" width="90%">
			<tr>
				<td bgcolor="<?=$tdbg1;?>">Change game modes:</td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="<?=$PHP_SELF?>?gamemode=signup">Open/Close signup</a></td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="<?=$PHP_SELF?>?gamemode=havoc">Start/Stop havoc</a></td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="<?=$PHP_SELF?>?gamemode=gametype">Set gametype (lan or internet)</a></td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>"><img src="images/arrow_off.gif">&nbsp;<a href="<?=$PHP_SELF?>?gamemode=pause">Pause/Continue round</a></td>
			</tr>
			</table>
			<br>
			<a href="administrator.php"><< Back to admin index</a>
			</center>
			<br>
		<?
		footerDsp();
	}
}
include("footer.php");
?>
