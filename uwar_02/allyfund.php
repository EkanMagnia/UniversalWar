<?
error_reporting(1);
$section = "Economy";
include("functions.php");
include("header.php");

$request = mysql_query("SELECT * FROM uwar_users WHERE id=$Userid", $db);
$UserInfo = mysql_fetch_array($request);
$request2 = mysql_query("SELECT * FROM uwar_allyfund WHERE tagid=$UserInfo[tagid]", $db);
$SysFundInfo = mysql_fetch_array($request2);
if ($UserInfo["protection"]>0) 
{
	headerDsp("Protection Mode");
	Print "<br><center>You can not donate / receive donations while you are in Universal Lords Protection.</center><br>";
	footerDsp();
}
else
{

// Donate to fund - Checks the variables, if they are accepted, add the new values to db
if(isset($mAmount) || isset($cAmount) || isset($hAmount))
{
	if(is_numeric($mAmount) && is_numeric($cAmount) && is_numeric($hAmount))
	{

		if($mAmount > $UserInfo["mercury"] || $cAmount > $UserInfo["cobalt"] || $hAmount > $UserInfo["helium"])
		{
			$msgred = "<font face=arial size=2 color=red>You do not have enough of resources to donate that amount to alliance fund!</font>";
			die();
		}
		elseif ($mAmount>=0 && $cAmount>=0 && $hAmount>=0)
		{

			$UserInfo["mercury"] -= $mAmount;
			$UserInfo["cobalt"] -= $cAmount;
			$UserInfo["helium"] -= $hAmount;
			$SysFundInfo["sysmercury"] += $mAmount;
			$SysFundInfo["syscobalt"] += $cAmount;
			$SysFundInfo["syshelium"] += $hAmount;
			$msggreen = "<font face=\"arial\" size=\"2\">You have donated the choosen amount to alliance fund!";

			mysql_query("UPDATE uwar_users SET mercury=$UserInfo[mercury], cobalt=$UserInfo[cobalt], helium=$UserInfo[helium] WHERE id=$Userid");
			mysql_query("UPDATE uwar_allyfund SET sysmercury=$SysFundInfo[sysmercury], syscobalt=$SysFundInfo[syscobalt], syshelium=$SysFundInfo[syshelium]  WHERE tagid=$UserInfo[tagid]");

            //Gives the mof a notice about the donation
            $mofrequest = mysql_query("SELECT id, ip FROM ".$Uwar["table"]." WHERE commander=4 AND tagid='$UserInfo[tagid]'");
            $mof = mysql_fetch_array($mofrequest);
            $mofid = $mof["id"];
            $news = "$UserInfo[nick] of $UserInfo[planet] has donated $mAmount mercury, $cAmount cobalt and $hAmount caesium, to the alliance fund.";
            Add_News("Received Donation!",$news, $mofid);
			//Logging
			$ip = $mof["ip"];
			Logging($mofid, "System Donation", $news, $ip);

			$news2 = "We have donated $mAmount mercury, $cAmount cobalt and $hAmount caesium to the alliance fund.";
			Add_News("Donation gived!",$news2,$Userid);
			//Logging
			Logging($Userid, "System Donation", $news2, $REMOTE_ADDR);

			//Get the new values
			$request = mysql_query("SELECT * FROM uwar_users WHERE id=$Userid", $db);
			$UserInfo = mysql_fetch_array($request);
			$request2 = mysql_query("SELECT * FROM uwar_allyfund WHERE tagid=$UserInfo[tagid]", $db);
			$SysFundInfo = mysql_fetch_array($request2);
		}
	}
}

// Donate to sysmember - Checks the variables, if they are accepted, add the new values to db
if(isset($mAmount2) || isset($cAmount2) || isset($hAmount2))
{
	if(is_numeric($mAmount2) && is_numeric($cAmount2) && is_numeric($hAmount2))
	{

		if($sysmember != $UserInfo['id'])
		{
		if($mAmount2 > $UserInfo["mercury"] || $cAmount2 > $UserInfo["cobalt"] || $hAmount2 > $UserInfo["helium"])
		{
			$msgred = "<font face=arial size=2 color=red>You do not have enough of resources to donate that amount to the choosen alliance member!</font>";
		}

		elseif ($mAmount2>=0 && $cAmount2>=0 && $hAmount2>=0)
		{
  			$request3 = mysql_query("SELECT * FROM uwar_users WHERE id=$sysmember");
			$AidUserInfo = mysql_fetch_array($request3);

			$UserInfo["mercury"] -= $mAmount2;
			$UserInfo["cobalt"] -= $cAmount2;
			$UserInfo["helium"] -= $hAmount2;
			$AidUserInfo["mercury"] += $mAmount2;
			$AidUserInfo["cobalt"] += $cAmount2;
			$AidUserInfo["helium"] += $hAmount2;
			$msggreen = "<font face=\"arial\" size=\"2\">The choosen amount is donated to the choosen alliance member!";

			mysql_query("UPDATE uwar_users SET mercury=$AidUserInfo[mercury], cobalt=$AidUserInfo[cobalt], helium=$AidUserInfo[helium] WHERE id=$sysmember");
			mysql_query("UPDATE uwar_users SET mercury=$UserInfo[mercury], cobalt=$UserInfo[cobalt], helium=$UserInfo[helium] WHERE id=$Userid");

            //Gives the user a notice about the donation
            $news = "You have received a donation of $mAmount2 mercury, $cAmount2 cobalt and $hAmount2 caesium, from alliance member $UserInfo[nick] of $UserInfo[planet]";
            Add_News("Received Donation!",$news, $sysmember);

			$news2 = "You have donated $mAmount2 mercury, $cAmount2 cobalt and $hAmount2 caesium to $AidUserInfo[nick] of $AidUserInfo[planet]";
            Add_News("Sent Donation!",$news2, $Userid);

			//Get the new values
			$request = mysql_query("SELECT * FROM uwar_users WHERE id=$Userid", $db);
			$UserInfo = mysql_fetch_array($request);
		}
		}
	}
}

// Donate from fund to sysmember - Checks the variables, if they are accepted, add the new values to db
if(isset($mAmount3) || isset($cAmount3) || isset($hAmount3))
{
	if(is_numeric($mAmount3) && is_numeric($cAmount3) && is_numeric($hAmount3))
	{

		if($mAmount3 > $SysFundInfo["sysmercury"] || $cAmount3 > $SysFundInfo["syscobalt"] || $hAmount3 > $SysFundInfo["syshelium"])
		{
			$msgred = "The alliance fund has not enough of resources to donate that amount to the choosen alliance member!";
		}

		elseif ($mAmount3>=0 && $cAmount3>=0 && $hAmount3>=0)
		{
			$request3 = mysql_query("SELECT * FROM uwar_users WHERE id=$sysmember");
			$AidUserInfo = mysql_fetch_array($request3);

			$SysFundInfo["sysmercury"] -= $mAmount3;
			$SysFundInfo["syscobalt"] -= $cAmount3;
			$SysFundInfo["syshelium"] -= $hAmount3;
			$AidUserInfo["mercury"] += $mAmount3;
			$AidUserInfo["cobalt"] += $cAmount3;
			$AidUserInfo["helium"] += $hAmount3;
			$msggreen = "The choosen amount is donated from the alliance fund to the choosen alliance member!";

			mysql_query("UPDATE uwar_users SET mercury=$AidUserInfo[mercury], cobalt=$AidUserInfo[cobalt], helium=$AidUserInfo[helium] WHERE id=$sysmember");
			mysql_query("UPDATE uwar_allyfund SET sysmercury=$SysFundInfo[sysmercury], syscobalt=$SysFundInfo[syscobalt], syshelium=$SysFundInfo[syshelium] WHERE tagid=$AidUserInfo[tagid]");

            //Gives the user a notice about the donation
            $news = "You have received a donation of $mAmount3 mercury, $cAmount3 cobalt and $hAmount3 caesium, from the alliance fund";
            Add_News("Received Donation!",$news, $sysmember);

			//Get the new values
			$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'", $db);
			$UserInfo = mysql_fetch_array($request);
			$request2 = mysql_query("SELECT * FROM uwar_allyfund WHERE tagid=$UserInfo[tagid]", $db);
			$SysFundInfo = mysql_fetch_array($request2);
		}
	}
}

if(isset($msggreen))
	print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";
if(isset($msgred))	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";

headerDsp("Finance");
?>
<br><img src="images/arrow_off.gif">Donate to Alliance Fund
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<form method=post action=<? $PHP_SELF ?>>
<tr>
	<td bgcolor="<?=$tdbg1;?>">Resource</td>
	<td bgcolor="<?=$tdbg1;?>">Fund</td>
	<td bgcolor="<?=$tdbg1;?>">Yours</td>
	<td bgcolor="<?=$tdbg1;?>">Amount</td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>">Mercury:</td>
	<td bgcolor="<?=$tdbg2;?>"><? print $SysFundInfo["sysmercury"]; ?></td>
	<td bgcolor="<?=$tdbg2;?>"><? print $UserInfo["mercury"]; ?></td>
	<td bgcolor="<?=$tdbg2;?>"><input type=text name=mAmount value=0 size=5></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>">Cobalt:</td>
	<td bgcolor="<?=$tdbg2;?>"><? print $SysFundInfo["syscobalt"]; ?></td>
	<td bgcolor="<?=$tdbg2;?>"><? print $UserInfo["cobalt"]; ?></td>
	<td bgcolor="<?=$tdbg2;?>"><input type=text name=cAmount value=0 size=5></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>">Caesium:</td>
	<td bgcolor="<?=$tdbg2;?>"><? print $SysFundInfo["syshelium"]; ?></td>
	<td bgcolor="<?=$tdbg2;?>"><? print $UserInfo["helium"]; ?></td>
	<td bgcolor="<?=$tdbg2;?>"><input type=text name=hAmount value=0 size=5></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>" colspan=4 align=center>
	<input type=submit value=Donate>
	</td>
</tr>
</table>
</form>
</center>
<br>

<img src="images/arrow_off.gif">Donate to another alliance member
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<form method=post action=<? $PHP_SELF ?>>
<td bgcolor="<?=$tdbg1;?>">Resource</td>
	<td bgcolor="<?=$tdbg1;?>">Yours</td>
	<td bgcolor="<?=$tdbg1;?>">Amount</td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>">Mercury:</td>
	<td bgcolor="<?=$tdbg2;?>"><? print $UserInfo["mercury"]; ?></td>
	<td bgcolor="<?=$tdbg2;?>"><input type=text name=mAmount2 value=0 size=5></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>">Cobalt:</td>
	<td bgcolor="<?=$tdbg2;?>"><? print $UserInfo["cobalt"]; ?></td>
	<td bgcolor="<?=$tdbg2;?>"><input type=text name=cAmount2 value=0 size=5></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>">Caesium:</td>
	<td bgcolor="<?=$tdbg2;?>"><? print $UserInfo["helium"]; ?></td>
	<td bgcolor="<?=$tdbg2;?>"><input type=text name=hAmount2 value=0 size=5></td>
</tr>
	<td bgcolor="<?=$tdbg2;?>" colspan=3 align=center><p align="left">
	Member:&nbsp;<select name=sysmember>
<?
		$request3 = mysql_query("SELECT * FROM uwar_users WHERE tagid=$UserInfo[tagid] ORDER BY id");
		while($SysInfo = mysql_fetch_array($request3))
		{
            if($UserInfo["id"] == $SysInfo["id"])
            	continue;
            print "<option value=".$SysInfo["id"].">".$SysInfo["nick"]." of ".$SysInfo["planet"]."</option>";
		}
?>
	</select>&nbsp;&nbsp;
	<input type=submit value=Donate></p>
	</td>
</tr>
</table>
</form>
</center>
<?
if($UserInfo["leader"] == 4)
{
?>
<br>
<img src="images/arrow_off.gif">SoE Privilege - Donate from fund to a member
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<form method=post action=<? $PHP_SELF ?>>
<tr>
	<td bgcolor="<?=$tdbg1;?>">Resource</td>
	<td bgcolor="<?=$tdbg1;?>">Fund</td>
	<td bgcolor="<?=$tdbg1;?>">Amount</td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>">Mercury:</td>
	<td bgcolor="<?=$tdbg2;?>"><? print $SysFundInfo["sysmercury"]; ?></td>
	<td bgcolor="<?=$tdbg2;?>"><input type=text name=mAmount3 value=0 size=5></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>">Cobalt:</td>
	<td bgcolor="<?=$tdbg2;?>"><? print $SysFundInfo["syscobalt"]; ?></td>
	<td bgcolor="<?=$tdbg2;?>"><input type=text name=cAmount3 value=0 size=5></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>">Caesium:</td>
	<td bgcolor="<?=$tdbg2;?>"><? print $SysFundInfo["syshelium"]; ?></td>
	<td bgcolor="<?=$tdbg2;?>"><input type=text name=hAmount3 value=0 size=5></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>" colspan=3 align=center><p align="left">
	Member:&nbsp;<select name=sysmember>
<?
		$request3 = mysql_query("SELECT * FROM uwar_users WHERE tagid='$UserInfo[tagid]' ORDER BY id");
		while($SysInfo = mysql_fetch_array($request3))
		{
			print "<option value=".$SysInfo["id"].">".$SysInfo["nick"]." of ".$SysInfo["planet"]."</option>";
		}
?>
	</select>&nbsp;&nbsp;
	<input type=submit value=Donate></p>
	</td>
</tr>
</table>
</form>
</center>
<?
}
?>
<center><a href="allyindex.php"><< Alliance index</a></center><br>
<?
footerDsp();
}
include("footer.php");
?>