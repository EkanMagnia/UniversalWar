<?
error_reporting(1);
$section = "Preferences";
include("functions.php");
include("header.php");

$request = mysql_query("SELECT * FROM ".$Uwar["table"]." WHERE id='$Userid'",$db);
$users = mysql_fetch_array($request);

//Blending mode
if($_POST["blending"])
{
	//enabling
	if($_POST["blendingstatus"] == 1)
	{
		mysql_query("UPDATE uwar_users SET blendingoff=1 WHERE id='$Userid'");
		$changePMsg = "<font size=\"2\" color=\"#00CC00\">Blending effect disabled!</font>";
	}
	elseif($_POST["blendingstatus"] == 0)
	{
		mysql_query("UPDATE uwar_users SET blendingoff=0 WHERE id='$Userid'");
		$changePMsg = "<font size=\"2\" color=\"#00CC00\">Blending effect enabled!</font>";
	}
}

//Sleep mode
//if (isset($sleep))
//{
//	if ($myrow["
//}



/*//Sleep mode
if (isset($sove))
{
	if ($users["lastsleep"]!=0)
		Header("Location: pref.php?msgred=You need to wait 16 hours from when you went into sleep mode!");

	if ($users["lastsleep"]==0)
	{
		mysql_query("UPDATE ".$Uwar["table"]." SET sleep='$sleepmode' WHERE id='$Userid'");
		mysql_query("UPDATE ".$Uwar["table"]." SET lastsleep='$lastsleeptime' WHERE id='$Userid'");
		Header("Location: logout.php");
		die();
	}
}*/
//Vacation mode
if (isset($vac))
{       mysql_query("UPDATE ".$Uwar["table"]." SET vacation='$vactime' WHERE id='$Userid'");
        Header("Location: logout.php");
        die();
}
//Delete action
if (isset($delete))
{
	if ($myrow["leader"] == 1)
	{
		headerDsp("Alliance Warning");
				 echo "<br/><center>You can not delete your account before you have abdicated from the leader position. This can be done in the Alliance -> Management section.</center><br/>";
		footerDsp();
	}
	else
	{
		if (!isset( $confirmed ) )
		{
			?>
			<center>
			Are you sure you want to delete your account?
			<a href="<? print $PHP_SELF; ?>?delete=true&confirmed=yes"><font color="#FF0000">Yes</font></a>/
			<a href="<? print $PHP_SELF; ?>"><font color="#00FF00">No</font></a>
			</center>
			<?
			include("footer.php");
			die();
		}
		elseif ( $confirmed == "yes" )
		{
			$tickSQL = mysql_query("SELECT * FROM uwar_tick WHERE id='1'");
			$tick = mysql_fetch_array($tickSQL);
			if ($tick["number"] == 0)
			{
				$id = $Userid;
				$sysSQL = mysql_query("SELECT * FROM uwar_users WHERE sysid='$gal[id]'",$db);
				if (mysql_num_rows($sysSQL) == 1)
				{
					mysql_query("DELETE FROM uwar_systems WHERE id='$gal[id]'",$db);
					mysql_query("DELETE FROM uwar_sgovernment WHERE sysid='$gal[id]'",$db);
					mysql_query("DELETE FROM uwar_sysfund WHERE sysid='$gal[id]'",$db);
				}
				if ($myrow["tagid"] != 0)
				{
					$tagSQL = mysql_query("SELECT * FROM uwar_tags WHERE id='$myrow[tagid]'",$db);
					$tag = mysql_fetch_array($tagSQL);
					if ($tag["members"] == 1)
					{
						mysql_query("DELETE FROM uwar_tags WHERE id='$myrow[tagid]'",$db);
						mysql_query("DELETE FROM uwar_agovernment WHERE sysid='$myrow[tagid]'",$db);
						mysql_query("DELETE FROM uwar_allyfund WHERE tagid='$myrow[tagid]'",$db);
					}		
				}				
				mysql_query("UPDATE uwar_tags SET members=members-1 WHERE id='$myrow[tagid]'",$db);
				mysql_query("DELETE FROM uwar_agovernment WHERE userid='$id'",$db);
				mysql_query("DELETE FROM uwar_constructions WHERE userid='$id'",$db);
				mysql_query("DELETE FROM uwar_fships WHERE userid='$id'",$db);
				mysql_query("DELETE FROM uwar_mail WHERE userid='$id'",$db);
				mysql_query("DELETE FROM uwar_news WHERE userid='$id'",$db);
				mysql_query("DELETE FROM uwar_pscans WHERE userid='$id'",$db);
				mysql_query("DELETE FROM uwar_pships WHERE userid='$id'",$db);
				mysql_query("DELETE FROM uwar_scans WHERE userid='$id'",$db);
				mysql_query("DELETE FROM uwar_sgovernment WHERE userid='$id'",$db);
				mysql_query("DELETE FROM uwar_tships WHERE userid='$id'",$db);
				mysql_query("DELETE FROM uwar_users WHERE id='$id'",$db);
			}
			else
				mysql_query("UPDATE ".$Uwar["table"]." SET deletemode='$deltime' WHERE id='$Userid'");
			Header("Location: logout.php");
	        die();
		}
	}
}
// change design
if (isset($design))
{
	mysql_query("UPDATE uwar_users SET design='$designid' WHERE id='$Userid'",$db);
	$changePMsg = "<font size=\"2\" color=\"#00CC00\">Design changed succesfully!</font>";
}

/*if ($Access>=10 && isset($Adminuserid))
	$Userid=$Adminuserid;*/

/*//change system
if (isset($ChangeSystem))
{
	$request = mysql_query("SELECt * FROM uwar_systems WHERE id=$myrow[sysid]",$db);
	$system = mysql_fetch_array($request);
	//check if the user is in a random system
	if ($system["systype"] == 1)
	{
		//player is not under attack
		$targetSQL = mysql_query("SELECT * FROM uwar_tships WHERE targetid='$Userid'",$db);
		if (mysql_num_rows($targetSQL)==0)
		{
			//check if the player ain't attacking
			$attacking = mysql_query("SELECT * FROM uwar_tships WHERE (action='a' OR action='d' OR action='r') AND userid='$Userid'");
			if (mysql_num_rows($attacking)==0)
			{
				//check if the system exists and it is private
				$NewSysSQL = mysql_query("SELECT * FROM uwar_systems WHERE syspword='$syspass' AND systype=2",$db);
				if (mysql_num_rows($NewSysSQL)>0)
				{
					$NewSys = mysql_fetch_array($NewSysSQL);
					$members = mysql_query("SELECT * FROM uwar_users WHERE sysid='$NewSys[id]'",$db);
					//check if system is not full
					if (mysql_num_rows($members)<10)
					{
						//check if the user has enough resources for the transfer.
						if ($myrow["mercury"]>=$myrow["score"] && $myrow["cobalt"]>=$myrow["score"] && $myrow["helium"]>=$myrow["score"])
						{
							$myrow["mercury"] -= $myrow["score"];					
							$myrow["cobalt"] -= $myrow["score"];
							$myrow["helium"] -= $myrow["score"];
							
							for($x = 1; $x<= 10; $x++)
							{
								$memberz = mysql_query("SELECT z FROM uwar_users WHERE sysid='$NewSys[id]' AND z='$x'");
								if (mysql_num_rows($memberz) == 0)
								{
									$z = $x;
									break;
								}								
							}
							mysql_query("UPDATE uwar_users SET mercury=$myrow[mercury], cobalt=$myrow[cobalt], helium=$myrow[helium], sysid='$NewSys[id]', z='$z' WHERE id='$Userid'",$db);
							$msggreen = "System changed succesfully!";
						} else $msgred = "Insufficient resources!";
					} else $msgred = "System is full!";
				} else $msgred = "The password you supplied is invalid!";
			} else $msgred = "You cannot change your system while you have outgoing fleets!";
		} else $msgred = "You cannot change your system while you are under attack!";
	} else $msgred = "You are already in a private system!";
} */

if(isset($msggreen))
	print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";
if(isset($msgred))	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";

					
if (isset($newpass) || isset($newpass2))
{
	if ($newpass=="") $changePMsg = "Password not specified!";
	elseif ($newpass2=="") $changePMsg = "Password confirmation not specified!";
	elseif ($newpass != $newpass2) $changePMsg = "Password doesn't match!";
	else
	{
		$sql = mysql_query("UPDATE ".$Uwar["table"]." SET password='$newpass' WHERE id=$Userid",$db);
		$changePMsg = "<font size=\"2\" color=\"#00CC00\">Password changed succesfully!</font>";
		setcookie("password",$newpass);
		$Password = $newpass;
	}
}

$request = mysql_query("SELECT * FROM ".$Uwar["table"]." WHERE id=$Userid",$db);
if ($myrow = mysql_fetch_array($request))
{
headerDsp( "Player Information" );
?>
<br>
<center>
<? if(isset($changePMsg))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$changePMsg."</B></FONT></CENTER><BR>"; ?>

<form method="post" action="<?php echo $PHP_SELF?>">
<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>" colspan=2>Change my password</td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" width="100%">New Password:</td>
		<td bgcolor="<?=$tdbg2;?>"><input type="Password" name="newpass" value="" size="28"></td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" width="100%">Confirm password:</td>
		<td bgcolor="<?=$tdbg2;?>"><input type="Password" name="newpass2" value="" size="28"></td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" align=center colspan=2><input type="Submit" name="submit" value="Change password"></td>
	</tr>
	<tr>
		<td>
<? /*		<input type="hidden" name="changepass" value="True">
		<input type="hidden" name="Adminuserid" value="<? echo $Adminuserid?>"> */?>
		</td>
	</tr>
</table>
</form>
</center>
<?
footerDsp();

headerDsp( "Special Modes" );
/*
?>
<br>
<center>
<form method="post" action="<?php echo $PHP_SELF?>">
<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>" colspan=2>Sleep mode</td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" width="100%" align=center>You become immune from attacks for 8 hours while in sleep mode. If you login before this time has passed, sleep mode is deactivated. Also, you need to wait 16 hours after enabling it before you can go into sleep mode again. This is to prevent abuse.</td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" align=center colspan=2>
			<input type="Submit" name="sove" value="Let me sleep 8 hours!">
		</td>
	</tr>
</table>
</form>
</center>

<?
*/
?>
<br>

<center>
<form method="post" action="<?php echo $PHP_SELF?>">
<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>" colspan=2>Vacation mode</td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" width="100%" align=center>You become immune from attacks for 96 Universal days while you are in the vacation mode, but in this time your planet won't produce any resources and you won't be able to construct ships. Also you are not allowed to login for 96 Universal days after enabling the vacation mode.</td>
	</tr>
		<td bgcolor="<?=$tdbg2;?>" align=center colspan=2>
			<input type="Submit" name="vac" value="Let me go on vacation!">
		</td>
	</tr>
	<tr>
		<td>
			<input type=hidden name=vac value=2160>
		</td>
	</tr>
</table>
</form>
</center>

<center>
<form action="<?print $php_self;?>"  method="post">
<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>" colspan=2>Change design</td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" width="100%" align=center>Universal War features for now 3 types of diferent designs. To change them all you have to do is to chose the design you want to use below. You can change your design at any time. After changing your design you will have to refresh the page or to go to another section for the changes to take effect.</td>
	</tr>
		<td bgcolor="<?=$tdbg2;?>" align=center colspan=2>
			<select name="designid">
	        <option selected>Select design</option>
	        <option value="0">Metallic</option>
	        <option value="1">Blue Heavens</option>
	        <option value="2">Grey Lands</option>
			</select>
		</td>
	</tr>
	<tr>
			<td bgcolor="<?=$tdbg2;?>" align=center colspan=2>
<input type="submit" name="design" value="Change design"></form>
		</td>
	</tr>
</table>
</form>
</center>
<?
/*
$sysSQL = mysql_query("SELECT systype FROM uwar_systems WHERE id=$myrow[sysid]",$db);
$sys = mysql_fetch_array($sysSQL);
if ($sys["systype"] == '1')
{
	?>
	<center>
	<form method="post" action="<?php echo $PHP_SELF?>">
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
		<tr>
			<td bgcolor="<?=$tdbg1;?>" colspan=2>System Transfer</td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>"  align=center colspan=2>This option lets you change your system if it is random to a private system. However the process of teleporting your planet will cost some resources. The cost is the value of your score in all the 3 resources. For example if your score is 100.000 you would have to pay 100.000 mercury, 100.000 cobalt and 100.000 cesium for the transfer.</td>
		</tr>
		<tr align="center">		
			<td bgcolor="<?=$tdbg2;?>" width="50%">
				System Password:
			</td>
			<td bgcolor="<?=$tdbg2;?>" width="50%">
				<input type="text" name="syspass">
			</td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>" align=center colspan=2>
				<input type="Submit" name="ChangeSystem" value="Teleport">
			</td>
		</tr>
	</table>
	</form>
	</center>
<?
}
*/
?>
<br>

<?
	$blending_request = mysql_query("SELECT blendingoff FROM uwar_users WHERE id='$Userid'");
	$blending_result = mysql_fetch_array($blending_request);
	if($blending_result["blendingoff"] == 0 || !isset ($blending_result["blendingoff"])) 
	{
		$blend_msg = "Disable Blending";
		$blend_val = 1;
	}
	else 
	{
		$blend_msg = "Enable Blending";
		$blend_val = 0;
	}
	

?>
<center>
<form action="<?print $php_self;?>"  method="post">
<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>" colspan=2><?=$blend_msg?></td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" width="100%" align=center>This option allows you to enable/disable the blending effect while changing page.</td>
		<tr>
			<td bgcolor="<?=$tdbg2;?>" align=center colspan=2>
			<input type="hidden" name="blendingstatus" value="<?=$blend_val?>">
			<input type="submit" name="blending" value="<?=$blend_msg?>">
			</form>
		</td>
	</tr>
</table>
</form>
</center>

<br>

<center>
<form method="post" action="<?php echo $PHP_SELF?>">
<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>" colspan=2>Delete my account</td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" width="100%" align=center>You may mark your account for deletion. When this mode is set, you have 72 hours to unset it before your account gets deleted. Note that if you log in after setting this mode, it will be automatically unset.</td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" align=center colspan=2>
			<input type="submit" name="delete" value="Delete my account!">
		</td>
	</tr>
</table>
</form>
</center>
<?
}
footerDsp();
include("footer.php");
?>