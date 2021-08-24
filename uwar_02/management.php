<?php
error_reporting(1);
$section = "Alliance Management";
include("functions.php");
include("header.php");

if($myrow["tagid"] == 0) 
{
	header("Location: allyindex.php"); 
	die(); 
}

# ld -> leader
# bc -> battle commander
# msn -> messenger
# sof -> senator of finance

elseif ($myrow["leader"] == 0) 
{
	header("Location: allyindex.php"); 
	die(); 
}
$tagid = $myrow["tagid"];
$leader = $myrow['leader'];
$request = mysql_query("SELECT * FROM uwar_tags WHERE id='$tagid'");
$myally = mysql_fetch_array($request);

$ally = mysql_query("select count(nick) as allymember from uwar_users where tagid='$tagid'");
$allyp = mysql_fetch_object($ally);
$votesNeeded = round(($allyp->allymember/3+0.5),0);

//Abdicate
if ((isset($choosesuc)) && ($choosesuc != "None"))
{
	if($choosesof != $Userid)
	{
		mysql_query("update uwar_users set leader='0' where tagid='$tagid' AND id='$Userid'")or die("bc1");
		mysql_query("update uwar_users set leader='1' where tagid='$tagid' and id='$choosesuc'")or die("bc1");
		Header("Location: allyindex.php?action=abdicate");
		die();
			
	} else $msgred = "You cannot choose yourself as successor!";
}

//Elects new bc
if ((isset($choosebc)) && ($choosebc != "None"))
{
	if($choosebc != $Userid)
	{
		mysql_query("update uwar_users set leader='0' where tagid='$tagid' and leader='2'")or die("bc1");
		mysql_query("update uwar_users set leader='2' where tagid='$tagid' and id='$choosebc'")or die("bc2");
		$msggreen = "Alliance preferences changed successfully!";
	}
	elseif($choosebc == $Userid)
	{
		if((isset($choosesuc)) && ($choosesuc != "None"))
		{
			if($choosesuc != $Userid)
			{
				mysql_query("update uwar_users set leader='0' where tagid='$tagid' and leader='2'")or die("bc1");
				mysql_query("update uwar_users set leader='1' where tagid='$tagid' and id='$choosesuc'")or die("bc1");
				mysql_query("update uwar_users set leader='2' where tagid='$tagid' and id='$Userid'")or die("bc2");
				$msggreen = "Alliance preferences changed successfully!";
			}
			else $msgred = "You cannot choose yourself as minister and successor!";
		}
		else $msgred = "You must choose a successor if you are abdicating or electing yourself as a minister!";
	}
}

//Elects new msn 
if ((isset($choosemsn)) && ($choosemsn != "None"))
{
	if($choosemsn != $Userid)
	{
		mysql_query("update uwar_users set leader='0' where tagid='$tagid' and leader='3'")or die("bc1");
		mysql_query("update uwar_users set leader='3' where tagid='$tagid' and id='$choosemsn'")or die("bc2");
		$msggreen = "Alliance preferences changed successfully!";
	}
	elseif($choosemsn == $Userid)
	{
		if((isset($choosesuc)) && ($choosesuc != "None"))
		{
			if($choosesuc != $Userid)
			{
				mysql_query("update uwar_users set leader='0' where tagid='$tagid' and leader='3'")or die("bc1");
				mysql_query("update uwar_users set leader='1' where tagid='$tagid' and id='$choosesuc'")or die("bc1");
				mysql_query("update uwar_users set leader='3' where tagid='$tagid' and id='$Userid'")or die("bc2");
				$msggreen = "Alliance preferences changed successfully!";
			}
			else $msgred = "You cannot choose yourself as minister and successor!";
		}
		else $msgred = "You must choose a successor if you are abdicating or electing yourself as a minister!";
	}
}
//Elects new sof
if ((isset($choosesof)) && ($choosesof != "None"))
{
	if($choosesof != $Userid)
	{
		mysql_query("update uwar_users set leader='0' where tagid='$tagid' and leader='4'")or die("bc1");
		mysql_query("update uwar_users set leader='4' where tagid='$tagid' and id='$choosesof'")or die("bc2");
		$msggreen = "Alliance preferences changed successfully!";
	}
	elseif($choosesof == $Userid)
	{
		if((isset($choosesuc)) && ($choosesuc != "None"))
		{
			if($choosesuc != $Userid)
			{
				mysql_query("update uwar_users set leader='0' where tagid='$tagid' and leader='4'")or die("bc1");
				mysql_query("update uwar_users set leader='1' where tagid='$tagid' and id='$choosesuc'")or die("bc1");
				mysql_query("update uwar_users set leader='4' where tagid='$tagid' and id='$Userid'")or die("bc2");
				$msggreen = "Alliance preferences changed successfully!";
			}
			else $msgred = "You cannot choose yourself as minister and successor!";
		}
		else $msgred = "You must choose a successor if you are abdicating or electing yourself as a minister!";
	}
}


//kick member from alliance
if ((isset($kick)) && ($kick != "None"))
{
	if($myrow["nick"] != $kick)
	{
		strip_tags($kick);
		mysql_query("UPDATE uwar_users SET tagid='0' WHERE nick='$kick'",$db);
		mysql_query("UPDATE uwar_users SET leader='0' WHERE nick='$kick'",$db);
		mysql_query("UPDATE uwar_tags SET members=members-1 WHERE id='$tagid'",$db);
		$request = mysql_query("SELECT * FROM uwar_users WHERE nick='$kick'",$db);
		if ($user = mysql_fetch_array($request))
		{
			$news = "You have been kicked from alliance!";
			add_news("Alliance", $news, $user["id"]);
		}
		$msggreen = "Alliance preferences changed successfully!";
	} else $msgred = "You can't exile yourself! Use Leave Alliance page if you want to leave the alliance.";
}

//new password
if (isset($newpassword) && $newpassword != $myally["password"])
{
	strip_tags($newpassword);
	mysql_query("update uwar_tags set password='$newpassword' where id='$tagid'");
	$msggreen = "Alliance preferences changed successfully!";
}
//set to public
if (isset($public) && $public != $myally["public"])
{
	mysql_query("update uwar_tags set public='$public' where id='$tagid'");
	$msggreen = "Alliance preferences changed successfully!";
}
//new motd
if (isset($msg) && $msg != $myally["allymotd"])
{
	$msg = str_replace( "\n", "<br>", $msg );
	$msg = str_replace( "'", "`", $msg );
	strip_tags($msg);
	mysql_query("UPDATE uwar_tags SET motd='$msg' where id='$tagid'",$db) or die(mysql_error());
	$msggreen = "Alliance preferences changed successfully!";
}
//new description
if (isset($description) && $description != $myally["description"])
{ 
	strip_tags($description);
	$description = str_replace( "'", "`", $description );
	mysql_query("UPDATE uwar_tags SET description='$description' WHERE id='$tagid'",$db) or die("dsda");
	$msggreen = "Alliance preferences changed successfully!";
}
/*//new name
if (isset($newallyname) && $newallyname != $myally["allyname"])
{
	strip_tags($newallyname);
	mysql_query("update uwar_tags set allyname='$newallyname' where id='$tagid'");
	$msggreen = "Alliance preferences changed successfully!";
}*/

//Delete action
if (isset($delete))
{
	if (!isset( $confirmed ) )
	{
		?>
		<center>
		Are you sure you want to delete your alliance?
		<a href="<? print $PHP_SELF; ?>?delete=true&confirmed=yes"><font color="#FF0000">Yes</font></a>/
		<a href="<? print $PHP_SELF; ?>"><font color="#00FF00">No</font></a>
		</center>
		<?
		include("footer.php");
		die();
	}
	elseif ( $confirmed == "yes" )
	{
		$tagid = $myrow["tagid"];

		$request = Mysql_Query("SELECT * FROM uwar_users WHERE tagid='$tagid'",$db);
		while($user = Mysql_Fetch_Array($request))
		{
			if ($user["leader"] == 1) 
			{
				$news = "Unfortunatelly a great alliance was lost. The people can no longer speak of the magnificent alliance that once existed but now is forgotten. Rumors are now spreading concerning a commander that ruined the vision of many others. One that destroyed a big part of the universe. And that commander is you.";
				add_news("Alliance Destroyed",$news,$user["id"]);
			}
			else 
			{
				$news = "Unfortunatelly a great alliance was lost. The people can no longer speak of the magnificent alliance that once existed but now is forgotten. Rumors are now spreading concerning a commander that ruined the vision of many others. The union has been broken, and you are one of those who now stand alone. Now that this time has come, will you have the strenght to become what you once were?";
				add_news("Alliance Lost",$news,$user["id"]);

			}
		}
		$log = "Alliance ".$myally["allyname"]." has been destroyed on ".UST($tickdate);
		Logging("Alliance Deleted", $log, $Userid, $Userid);
		mysql_query("UPDATE uwar_users SET tagid=0, leader=0 WHERE tagid='$tagid'");
		mysql_query("DELETE from uwar_tags WHERE id='$tagid'");
		mysql_query("DELETE FROM uwar_agovernment WHERE id='$tagid'");
		mysql_query("DELETE FROM uwar_allyfund WHERE tagid='$tagid'");
        Header("Location: allyindex.php?action=deleted");
        die();
	}
}


if(isset($msggreen))
	print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";
elseif(isset($msgred))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";
?>
<br><?
$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'",$db);
$myrow = mysql_fetch_array($request);
$leader = $myrow["leader"];
$ld = $myrow["leader"];
$tagid = $myrow["tagid"];
$request = mysql_query("SELECT * FROM uwar_tags WHERE id='$tagid'");
$myally = mysql_fetch_array($request);

if ($leader != 0)
{
	headerDsp("Leaders Privilege - Modify alliance settings");?>
	<br><br>
	<center>
    <form method=post action=<? print $PHP_SELF; ?>>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
    	<tr>
        	<td bgcolor="<?=$tdbg1;?>" colspan=2>General settings</td>
        </tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>"><font face=arial size=2>Message of the day:</td>
			<td bgcolor="<?=$tdbg2;?>"><textarea name=msg cols=40 rows=5><? print $myally["motd"]; ?></textarea></font></td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>"><font face=arial size=2>Description:</td>
			<td bgcolor="<?=$tdbg2;?>"><textarea name=description cols=40 rows=5><? print $myally["description"]; ?></textarea></font></td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>" align=center colspan=2><input type=submit name=submit value="Update alliance settings"></td>
		</tr>
	</table>
	</form>
	</center>
	<?
	if ($leader == 1){
	$query2 = mysql_query("select * from uwar_users where tagid='$tagid' AND leader !=1 order by id");
			?>  
		<br>
		<center>
		<form method=post action=<? print $PHP_SELF; ?>>
		<table border="0" cellpadding="4" cellspacing="1" width="90%">	
			<tr>
				<td bgcolor="<?=$tdbg1;?>" colspan=2>Management</td>
			</tr>
			<?if(mysql_num_rows($query2) !=0)
			{?>
				<tr>
					<td bgcolor="<?=$tdbg2;?>" colspan=2>&nbsp;</td>
				</tr>
				<tr>
					<td bgcolor="<?=$tdbg2;?>">Choose a Battle Commander:</font></td>
					<td bgcolor="<?=$tdbg2;?>">
						<select size="1" name="choosebc" style="width: 300px;">
						<option value="None">Choose Battle Commander</option>
						<?
						$query2 = mysql_query("select * from uwar_users where tagid='$tagid' order by id");
						while ($rec = mysql_fetch_object($query2))
						{
							if(mysql_num_rows($query2) < 2) break;
							?><option value=<? print $rec->id; ?>> <? print $rec->nick. " of ".$rec->planet; ?></option><?
						}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td bgcolor="<?=$tdbg2;?>">Choose a Messenger:</font></td>
					<td bgcolor="<?=$tdbg2;?>">
						<select size="1" name="choosemsn" style="width: 300px;">
						<option value="None">Choose Messenger</option>
						<?
						$query2 = mysql_query("select * from uwar_users where tagid='$tagid' order by id");
						while ($rec = mysql_fetch_object($query2))
						{
							if(mysql_num_rows($query2) < 2) break;
							?><option value=<? print $rec->id; ?>> <? print $rec->nick. " of ".$rec->planet; ?></option><?
						}
						?>
						</select>
					</td>
				</tr>
				<tr>

					<td bgcolor="<?=$tdbg2;?>">Choose a Senator of Economy:</td>
					<td bgcolor="<?=$tdbg2;?>">
						<select size="1" name="choosesof" style="width: 300px;">
						<option value="None">Choose Senator of Economy</option>
						<?
						$query2 = mysql_query("select * from uwar_users where tagid='$tagid' order by id");
						while ($rec = mysql_fetch_object($query2))
						{
							if(mysql_num_rows($query2) < 2) break;
							?><option value=<? print $rec->id; ?>> <? print $rec->nick. " of ".$rec->planet; ?></option><?
						}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td bgcolor="<?=$tdbg2;?>" colspan=2>&nbsp;</td>
				</tr>
				<tr>
					<td bgcolor="<?=$tdbg2;?>" colspan=2>In case you are electing yourself as a minister, you must appoint a successor to the leader position. This function can also be used if you just want to abdicate. This is to avoid this alliance to be left without a leader.</td>
				</tr>
				<tr>

					<td bgcolor="<?=$tdbg2;?>">Choose a Successor:</td>
					<td bgcolor="<?=$tdbg2;?>">
						<select size="1" name="choosesuc" style="width: 300px;">
						<option value="None">Choose successor</option>
						<?
						$query2 = mysql_query("select * from uwar_users where tagid='$tagid' order by id");
						while ($rec = mysql_fetch_object($query2))
						{
							if($rec->leader == $leader)
							 continue;
							?><option value=<? print $rec->id; ?>> <? print $rec->nick. " of ".$rec->planet; ?></option><?
						}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td bgcolor="<?=$tdbg2;?>" colspan=2>&nbsp;</td>
				</tr>
				<tr>
					<td bgcolor="<?=$tdbg2;?>" align=center colspan=2><input type=submit name=submit value="Update alliance settings"></td>
				</tr>
				<? } else echo "<tr><td bgcolor=$tdbg2 colspan=2><center><br>You cannot change the management when you are the only member of the alliance.</center><br></td></tr>"; 
				?>
		</table>
		</form>
		</center>
	<br>
	<center>
    <form method="post" action="<?=$PHP_SELF?>">
	<table border="0" cellpadding="4" cellspacing="1" width="90%">	
		<tr>
        	<td bgcolor="<?=$tdbg1;?>" colspan=2>Other</td>
        </tr>
		 <tr>
    	    <td bgcolor="<?=$tdbg2;?>">Exile member:</font></td>
	        <td bgcolor="<?=$tdbg2;?>">
				<select size="1" name="kick" style="width: 300px;">
	            <option value=None>Choose member</option>
	            <?
				$query2 = mysql_query("select * from uwar_users where tagid='$tagid' order by id");
				while ($rec = mysql_fetch_object($query2))
				{
					if($rec->id == $leader)
                     continue;
					?><option value=<? print $rec->nick; ?>> <? print $rec->nick. " of ".$rec->planet; ?></option><?
				}
	            ?>
	            </select>
	        </td>
	    </tr>
		<tr>
        	<td bgcolor="<?=$tdbg2;?>">Enter new password:</td>
	        <td bgcolor="<?=$tdbg2;?>"><input type="password" name=newpassword value=<? print $myally["password"]; ?>></font></td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>" colspan=2>&nbsp;</td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>" colspan=2>Set the alliance to public mode means that other commanders outside the alliance are able to view the alliance tag in sections like Universe and System. It is good to set your alliance to public while you are recruiting members, so everyone can see it and possibly join.</td>
		</tr>
		<tr>
			<form method="post" action="$PHP_SELF">
        	<td bgcolor="<?=$tdbg2;?>">Set alliance to public:</td>
	        <td bgcolor="<?=$tdbg2;?>">
				Yes: <input type="radio" name="public" value="1" style="background-color: <?=$tdbg2?>;" <? if($myally["public"] == 1) { ?> checked="yes" <? } ?>>&nbsp;&nbsp;&nbsp;&nbsp;
				No: <input type="radio" name="public" value="0" style="background-color: <?=$tdbg2?>;" <? if($myally["public"] != 1) { ?> checked="yes" <? } ?>>
			</td>
		</tr>
		<!--<tr>
			<td bgcolor="<?=$tdbg2;?>"><font face=arial size=2>Name:</td>
			<td bgcolor="<?=$tdbg2;?>"><input type=text name=newallyname value="<? print $myally["allyname"]; ?>"></font></td>
		</tr>-->
		<tr>
			<td bgcolor="<?=$tdbg2;?>" align=center colspan=2><input type=submit name=submit value="Update alliance settings"></td>
		</tr>
	</table>
    </form>
	<br>
	<center>
    <form method="post" action="<?=$PHP_SELF?>">
	<table border="0" cellpadding="4" cellspacing="1" width="90%">	
		<tr>
        	<td bgcolor="<?=$tdbg1;?>" colspan="2">Deletion</td>
        </tr>
		 <tr>
    	    <td bgcolor="<?=$tdbg2;?>" colspan="2">This option makes it possible to you to remove this entire alliance. You and all alliance members will be allianceless. Note that this action cannot be regret after being confirmed.</td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>" colspan="2"><center><input type="submit" name="delete" value="Delete alliance"></center></td>
		</tr>
	</table>
	</form>
	<? }
}
?>
<center><a href="allyindex.php"><< Alliance index</a></center><br>
<?
footerDsp();
include ("footer.php");
?>