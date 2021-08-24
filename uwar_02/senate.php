<?php
error_reporting(1);
$section = "Senate";
include("functions.php");
include("header.php");

$tickSQL = mysql_query("SELECT number FROM uwar_tick WHERE id=1");
$tick = mysql_fetch_array($tickSQL);
$mytick = $tick["number"];
# sl -> system leader
# fc -> fleet commander
# soc -> senator of communication
# mof -> minister of finance

//Selects info from db
$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'");
$myrow = mysql_fetch_array($request);
$sysid = $myrow['sysid'];
$commander = $myrow['commander'];
$request = mysql_query("SELECT * FROM uwar_systems WHERE id='$sysid'");
$mygal = mysql_fetch_array($request);

$gal = mysql_query("select count(id) as sysmember from uwar_users where sysid='$sysid'");
$galp = mysql_fetch_object($gal);
//calculates votes needed to be elected as sl

$votesNeeded = ceil ($galp->sysmember * 0.51);

//$gal = mysql_query("SELECT count(id) as sysmember FROM uwar_users WHERE sysid='$sysid'");
//$galp = mysql_fetch_array($gal);
//$votesNeeded = ceil ($galp["sysmember"] * 0.51);

//Elects new fc
if ((isset($choosefc)) && ($choosefc != "None") && $commander == 1)
{
	strip_tags($choosefc);
	mysql_query("update uwar_users set commander='0' where sysid='$sysid' and commander='2'")or die("fc1");
	mysql_query("update uwar_users set commander='2' where sysid='$sysid' and nick='$choosefc'")or die("fc2");
	$msggreen = "System preferences changed successfully!";

}
//Elects new soc
if ((isset($choosesoc)) && ($choosesoc != "None")  && $commander == 1)
{
	strip_tags($choosesoc);
	mysql_query("update uwar_users set commander='0' where sysid='$sysid' and commander='3'");
	mysql_query("update uwar_users set commander='3' where sysid='$sysid' and nick='$choosesoc'");
	$msggreen = "System preferences changed successfully!";
}
//Elects new mof
if ((isset($choosemof)) && ($choosemof != "None") && $commander == 1)
{
	strip_tags($choosemof);
	mysql_query("update uwar_users set commander='0' where sysid='$sysid' and commander='4'") or die("doh");
	mysql_query("update uwar_users set commander='4' where sysid='$sysid' and nick='$choosemof'") or die("doh");
	$msggreen = "System preferences changed successfully!";
}
//New system picture
if ($newsyspic && $newsyspic != $mygal["syspic"] && $commander == 1 )
{
	define (MAXWIDTH, 580);
	define (MAXHEIGHT, 400);
	
	if( @getImageSize ($newsyspic))
	{
		$pic = getImageSize ($newsyspic);
		if( $pic[0] <= MAXWIDTH || $pic[1] <= MAXHEIGHT )
		{
			if($pic[2] == 1 || $pic[2] == 2 || $pic[2] == 3)
			{
				mysql_query("update uwar_systems set syspic='$newsyspic' where id='$sysid'");
				$msggreen = "System preferences changed successfully!";
			}
			else $msgred = "Invalid image extension. Valid extensions are .gif, .jpg and .png!";
		}
		else $msgred = "The selected system picture is too large. Maximum measurements are 580x400 pixels!";
	}
	else $msgred = "The selected system picture was not found or syntax error!"; 
}
//new motd
if ($msg && $msg != $mygal["sysmotd"]  && $commander != 0)
{
	$msg = str_replace( "\n", "<br>", $msg );
	$msg = str_replace( "'", "`", $msg );
	strip_tags($msg);
	mysql_query("UPDATE uwar_systems SET sysmotd='$msg' where id='$sysid'") or die(mysql_error());
	$msggreen = "System preferences changed successfully!";
}
//new sysname
if (isset($newsysname) && $newsysname != $mygal["sysname"] && $commander == 1)
{
	strip_tags($newsysname);
	mysql_query("update uwar_systems set sysname='$newsysname' where id='$sysid'");
	$msggreen = "System preferences changed successfully!";
}

//Exile
/*
if ($mytick>0 && $startexile && $exilevote && $commander == 1)
{
    $request = mysql_query("SELECT sysscore FROM uwar_systems WHERE id=$sysid");
	$system = mysql_fetch_array($request);
    $ecost = $system["sysscore"] * 0.05;

    $res = mysql_query("SELECT sysid, z FROM uwar_users WHERE id='$exilevote' ".
		       "AND sysid='$sysid'", $db);

    if ($res && mysql_num_rows($res)>0)
	{
      // check for res and take it
       $pay = 0;

       $res = mysql_query("SELECT sysmercury, syscobalt, syshelium FROM uwar_sysfund WHERE sysid='$sysid'");
       
       if ($res)
	   {
         $grow = mysql_fetch_row($res);

         if (($grow[0]+$grow[1]+$grow[2]) > $ecost)
		 {
            // ok we have enough
            $gm = $grow[0];
            $gc = $grow[1];
            $ge = $grow[2];

            if ($ecost > $ge)
			{
              $ge = 0;
              $ecost -= $ge;
              if ($ecost > $gc)
			  {
                $gc = 0;
                $ecost -= $gc;
                $gm -= $ecost;
              } else
			  {
                $gc -= $ecost;
              }
            } else
			{
              $ge -= $ecost;
            }
            mysql_query ("UPDATE uwar_sysfund SET sysmercury='$gm', syscobalt='$gc', syshelium='$ge' ".
              "WHERE sysid=$sysid");
            $pay = 1;
         } else
		 {
            // not evaluated !
            $msg .= "Your system do not have enough resources in the fund!";
         }
       }


       if ( $pay==1 )
	  {
        // reset 
        mysql_query ("UPDATE uwar_users SET exile_vote=0 ".
		     "WHERE sysid=$sysid"); 

        // start
        mysql_query("UPDATE uwar_systems SET exile_id=$exilevote, ".
	  	    "exile_date=now() + INTERVAL 24 HOUR ".
		    "WHERE id=$sysid");

        // send info
        $nmsg = "$myrow[nick] of $myrow[planet] has started an exile ".
	  "vote on our planet. It will run for 24 hours from now.";
		add_news ("Exile vote", $nmsg, $exilevote);
        // set my vote
        mysql_query ("UPDATE uwar_users SET exile_vote=1 WHERE id='$Userid'",$db); 
        $myrow["exile_vote"] = 1;
      }
    } else
    {
      // CLEAR
      mysql_query ("UPDATE uwar_users SET exile_vote=0 ".
		   "WHERE sysid=$sysid",$db); 
      mysql_query("UPDATE uwar_systems SET exile_id=0, ".
		  "exile_date=0 ".
		  "WHERE id=$sysid", $db);
    }  
}

$res = mysql_query ("SELECT exile_id, date_format(exile_date,'%D %b %H:%i CEST') ".
		    "AS exile_date FROM uwar_systems ".
		    "WHERE id=$sysid AND exile_id!=0", $db);

if ($mytick>0 && $res && mysql_num_rows($res)>0) {

  if ($exvote) {
    mysql_query ("UPDATE uwar_users SET exile_vote='$myexvote' WHERE id='$Userid'", $db);
    $myrow["exile_vote"] = $myexvote;
  }

  $row = mysql_fetch_row($res);
	$exwhoSQL = mysql_query("SELECT nick, planet, sysid, z FROM uwar_users WHERE id=$row[0]");
	$exwho = mysql_fetch_array($exwhoSQL);
	$exCoordsSQL = mysql_query("SELECT x,y FROM uwar_systems WHERE id=$exwho[sysid]");
	$exCoords = mysql_fetch_array($exCoordsSQL);

  if ($myrow["exile_vote"] == 1) $check_yes = "checked";
  else $check_no = "checked";
  
  $gal = mysql_query("select count(id) as sysmember from uwar_users where sysid='$sysid'");
  $galp = mysql_fetch_object($gal);
  $count = $galp->sysmember;

  $res = mysql_query("SELECT count(id) FROM uwar_users "."WHERE sysid='$sysid' AND exile_vote=1", $db);
  $rex = mysql_fetch_row($res);
  $yes_vote = $rex[0];
  $no_vote = $count - $yes_vote;
  $yes_percent = ((1000 * $yes_vote) / $count) * 1. / 10.;
  $no_percent = 100. - $yes_percent;
  
  echo <<<EOF
    <br>
    <form method="post" action="$PHP_SELF">
    <table width="650" border="1" cellpadding="5">
    <tr><th colspan="3" class="a">Exile voting</th></tr>
    <tr><td colspan="3">There is an exile vote running against $exwho[nick] of 
          $exwho[planet] ($exCoords[x]:$exCoords[y]:$exwho[z]). 
          It will end at $row[1] - to be succesfull 66.67% of system members 
          have to vote <em>exile</em>.</td></tr>
    <tr><td>Current vote rate:</td>
    <td colspan="2">EXILE: <b>$yes_vote</b> votes ($yes_percent %)&nbsp;&nbsp;
          Dont EXILE: <b>$no_vote</b> votes ($no_percent %)&nbsp;&nbsp;
	  of $count total.</td></tr>
	 <tr><td>My vote:</td>
         <td><input type="radio" name="myexvote" value="1" $check_yes>EXILE
          &nbsp;&nbsp;<input type="radio" name="myexvote" value="0" $check_no>
          DONT EXILE</td>
    <td align="center">
      <input type="submit" name="exvote" value="  Vote  "></td></tr>
    </table>
    </form>
<br>
EOF; 
}
*/
//Voting system
if ((isset($voted)) && ($voted != "none" ) && is_numeric($voted))
{
	strip_tags($voted);
	mysql_query("UPDATE uwar_users set vote='$voted' WHERE id='$Userid'", $db);
    $votes_query = mysql_query("SELECT * FROM uwar_users WHERE sysid='$sysid'") or die("dajk");
    while($votes = mysql_fetch_object($votes_query))
	{
        $votes_amount = mysql_query("SELECT * FROM uwar_users WHERE sysid='$sysid' AND vote='$votes->z' AND vote !=''") or die("duh");
	    $NumberOfVotes = mysql_num_rows($votes_amount);
 		if ($NumberOfVotes >= $votesNeeded)
		{
			mysql_query("UPDATE uwar_users SET commander='0' WHERE commander='1' AND sysid='$sysid'");
			mysql_query("UPDATE uwar_users SET commander='1' WHERE z='$votes->vote' AND sysid='$sysid'");
            break;
		}
		elseif ($votes->commander == 1 && $NumberOfVotes < $votesNeeded)
		{	mysql_query("UPDATE uwar_users SET commander='0' WHERE sysid='$sysid'");
            break;
        }
 	}
}

if(isset($msggreen))
	print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";
elseif(isset($msgred))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";

headerDsp( "Senate" );
?>
<br>
<img src="images/arrow_off.gif">Voting
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>" width="5%">ID</td>
		<td bgcolor="<?=$tdbg1;?>" width="45%">Commander</td>
		<td bgcolor="<?=$tdbg1;?>" width="45%">Voted for</td>
		<td bgcolor="<?=$tdbg1;?>" width="5%">Votes</td>
	</tr>
	<?
    $query = mysql_query("select * from uwar_users where sysid='$sysid' order by z");
	while ($record = mysql_fetch_object($query))
    {
		//selects and count the votes of the current user
		$currentZ = $record->z;
		$votes_amount = mysql_query("SELECT * FROM uwar_users WHERE sysid='$sysid' AND vote='$currentZ' AND vote !=''") or die("duh");
	    $NumberOfVotes = mysql_num_rows($votes_amount);

        //Select the nick of the planet that the current user has voted on
        $voter_row = mysql_query("SELECT * FROM uwar_users WHERE sysid='$sysid' AND z='$currentZ' AND vote !=''") or die("duh");
        $voterrow = mysql_fetch_array($voter_row);
        $VotedOnZ = $voterrow['vote'];
        $Votedonq = mysql_query("SELECT nick FROM uwar_users WHERE sysid='$sysid' AND z='$VotedOnZ'");
        $Votedonres = mysql_fetch_object($Votedonq);
        $VotedOn = $Votedonres->nick;

   	   	if ($record->commander == 1){ $kleur="<font color=blue>"; $sl=$record->nick; }
		if ($record->commander == 2){ $kleur="<font color=red>"; $fc=$record->nick; }
		if ($record->commander == 3){ $kleur="<font color=green>"; $soc=$record->nick; }
		if ($record->commander == 4){ $kleur="<font color=yellow>"; $mof=$record->nick; }
        if(!isset($kleur)) $kleur="";
            ?>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" width="5%"><? print $record->z; ?></td>
		<td bgcolor="<?=$tdbg2;?>" width="45%"><? print $kleur; print $record->nick." of ".$record->planet; ?></td>
		<td bgcolor="<?=$tdbg2;?>" width="45%"><?=$VotedOn ?></td>
		<td bgcolor="<?=$tdbg2;?>" width="5%"><?=$NumberOfVotes ?></td>
	</tr>
		<? $kleur="</font>";
	}
	?>
 	<tr>
		<form method=post action=<? print $PHP_SELF; ?>>
		<td colspan=4 bgcolor="<?=$tdbg2;?>">
	        <center>
            Vote for:&nbsp;<select size=1 name=voted>
			<?
            $query2 = mysql_query("select * from uwar_users where sysid='$sysid' order by z");
			while ($rec = mysql_fetch_object($query2))
			{
				?><option value=<? print $rec->z; ?>> <? print $rec->nick. " of ".$rec->planet; ?></option><?
			}
			?>
			</select>&nbsp;&nbsp;
			<input type="Submit" name="submit" value="Vote">
		</td>
		</form>
	</tr>
    <tr>
    	<td colspan=4 align=center>
        <?
            $gal = mysql_query("select count(nick) as sysmember from uwar_users where sysid='$sysid'");
			$galp = mysql_fetch_object($gal) or die("ERROR!");
			if ($galp->sysmember > 1) 
			{
				$verb = "are";
				$com = "commanders";
			}
			else
			{
				$verb ="is";
				$com = "commander";
			}

            echo "There $verb $galp->sysmember $com in this system.";
			if ($votesNeeded > 1) $votes = "votes";
			else $votes = "vote";
			echo "<br>You need ".$votesNeeded." ".$votes." to become System Leader.<br><br>";
        ?>
        </td>
    </tr>
</table>
</center>
<img src="images/arrow_off.gif">Leaders
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td colspan=2 bgcolor="<?=$tdbg1;?>">Leaders of your system</td>
	</tr>
   	<tr>
		<td bgcolor="<?=$tdbg2;?>">System Leader:</td>
        <td bgcolor="<?=$tdbg2;?>"><?
        if (isset($sl))
				{ ?>Your System Leader is <b><font color=blue><? print $sl; ?></b><br><? }
        else
				{ ?>No System Leader elected<? }
        ?></td>
    </tr>
   	<tr>
		<td bgcolor="<?=$tdbg2;?>">Fleet Commander:</td>
        <td bgcolor="<?=$tdbg2;?>"><?
        if (isset($fc)) { ?>Your Fleet Commander is <b><font color=red><? print $fc; ?></b><br><? }
        else { ?>No Fleet Commander elected<? }
        ?></td>
    </tr>
  	<tr>
		<td bgcolor="<?=$tdbg2;?>">Senator of Communication:</td>
        <td bgcolor="<?=$tdbg2;?>"><?
        if (isset($soc))
				{ ?>Your Senator of Communication is <b><font color=green><? print $soc; ?></b><br><? }
        else
				{ ?>No Senator of Communication elected</font><? }
        ?></td>
    </tr>
      	<tr>
		<td bgcolor="<?=$tdbg2;?>">Minister of Finance:</td>
        <td bgcolor="<?=$tdbg2;?>"><?
        if (isset($mof)) { ?>Your Minister of Finance is <b><font color=yellow><? print $mof; ?></b><br><? }
        else { ?>No Minister of Finance elected<? }
        ?></td>
    </tr>
</table>
</center>
<br><br>
<? 
$commSQL = mysql_query("SELECT commander, sysid FROM uwar_users WHERE id='$Userid'");
$com = mysql_fetch_array($commSQL);
$commander = $com["commander"];
$sysid = $com['sysid'];
$request = mysql_query("SELECT * FROM uwar_systems WHERE id='$sysid'");
$mygal = mysql_fetch_array($request);


if ($commander != 0)
{
	?>
	<center>
    <form method=post action=<? print $PHP_SELF; ?>>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td colspan="2" bgcolor="<?=$tdbg1;?>"><font face="Arial" size="2">Ministers Priviledge</td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>"><font face=arial size=2>Modify message of the day:</td>
		<td bgcolor="<?=$tdbg2;?>"><textarea name=msg cols=40 rows=5><? print $mygal["sysmotd"]; ?></textarea></font></td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" colspan=2><font face="arial" size=2><center><input type="submit" value="Modify">
		</td>
	</tr>
	</table></center>
<?
}
if ($commander == 1)
{
	?><br><img src="images/arrow_off.gif">SL Privilege - Modify system settings
	<br><br>
	<center>
    <form method=post action=<? print $PHP_SELF; ?>>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
    	<tr>
        	<td bgcolor="<?=$tdbg1;?>" colspan=2>Modify system settings</td>
        </tr>

   		<?
		$query2 = mysql_query("select * from uwar_users where sysid='$sysid' AND commander<>'1' order by z");
		$systype = $mygal['systype'];
		if ($systype==2)
		{
			?><tr>
	            <td bgcolor="<?=$tdbg2;?>"><?
	            $galpas = mysql_query("SELECT * FROM uwar_systems WHERE id='$sysid' AND systype=2") or die("test");
	            $galpass=mysql_fetch_object($galpas);
	            print "The system password is:"; ?>
	            </td>
    			<td bgcolor="<?=$tdbg2;?>"><b><? print $galpass->syspword; ?></b></td>
           	</tr><?
            }
			?>
        <tr>
        	<td bgcolor="<?=$tdbg2;?>" colspan=2>&nbsp;</td>
        </tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>">Choose a Fleet Commander:</font></td>
			<td bgcolor="<?=$tdbg2;?>">
	            <select size=1 name=choosefc>
	            <option value=None>Choose Fleet Commander</option>
	            <?
				$query2 = mysql_query("select * from uwar_users where sysid='$sysid' order by z");
				while ($rec = mysql_fetch_object($query2))
				{
                	if($rec->nick == $sl)
                     continue;
					?><option value="<? print $rec->nick; ?>"> <? print $rec->nick. " of ".$rec->planet; ?></option><?
				}
	            ?>
	            </select>
			</td>
		</tr>
		<tr>
    	    <td bgcolor="<?=$tdbg2;?>">Choose a Senator of Communication:</font></td>
	        <td bgcolor="<?=$tdbg2;?>">
				<select size=1 name=choosesoc>
	            <option value=None>Choose Senator of Communication</option>
	            <?
				$query2 = mysql_query("select * from uwar_users where sysid='$sysid' order by z");
				while ($rec = mysql_fetch_object($query2))
				{
					if($rec->nick == $sl)
                     continue;
					?><option value="<? print $rec->nick; ?>"> <? print $rec->nick. " of ".$rec->planet; ?></option><?
				}
	            ?>
	            </select>
	        </td>
	    </tr>
	    <tr>
	        <td bgcolor="<?=$tdbg2;?>">Choose Minister of Finance:</td>
	        <td bgcolor="<?=$tdbg2;?>">
	            <select size=1 name=choosemof>
	            <option value=None>Choose Minister of Finance</option>
	            <?
				$query2 = mysql_query("select * from uwar_users where sysid='$sysid' order by z");
				while ($rec = mysql_fetch_object($query2))
				{
                	if($rec->nick == $sl)
                     continue;
                    ?><option value="<? print $rec->nick; ?>"> <? print $rec->nick. " of ".$rec->planet; ?></option><?
				}
                //A new mygal selection, needed for the values to be updated
                $request = mysql_query("SELECT * FROM uwar_systems WHERE id='$sysid'");
				$mygal = mysql_fetch_array($request);
	            ?>
	            </select>
	        </td>
	    </tr>
		<tr>
        	<td bgcolor="<?=$tdbg2;?>" colspan=2>&nbsp;</td>
        </tr>
		<tr>
			<form method=post action="senate.php">
        	<td bgcolor="<?=$tdbg2;?>">Enter URL for system picture:</td>
	        <td bgcolor="<?=$tdbg2;?>"><input type=text name=newsyspic value="<? if($mygal["syspic"] != "images/system.jpg") print $mygal["syspic"]; ?>"></font></td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>"><font face=arial size=2>System name:</td>
			<td bgcolor="<?=$tdbg2;?>"><input type=text name=newsysname value="<? print $mygal["sysname"]; ?>"></font></td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg2;?>" align=center colspan=2><input type=submit name=submit value="Update system settings"></td>
		</tr>
	</table>
    </form>
<!--	<br>
	<form method=post action="<?=$PHP_SELF;?>";>
	<table width="90%">
		<tr>
			<td colspan=2 bgcolor="<?=$tdbg1;?>">Exile</td>
		</tr>
<?  $request = mysql_query("SELECT sysscore FROM uwar_systems WHERE id=$sysid");
	$system = mysql_fetch_array($request);
    $exile_cost = $system["sysscore"] * 0.05;
?>
		<tr><td colspan=2 bgcolor="<?=$tdbg2;?>">Exiling will take 24 hours and 66.67% of system members 
        beeing active in last 36 hours have to vote 'Exile' (closed and planets in vacation do not count).
        Exile costs 5% of system score in resources at start of vote.The resources are taken from the system fund in order of cesium, cobalt and last mercury. Today, the exile cost is <?=$exile_cost;?></td></tr>
		<tr><td bgcolor="<?=$tdbg2;?>">Choose member to exile:</td><td bgcolor="<?=$tdbg2;?>">
				<select size=1 name=exilevote>
		        <?
				$query2 = mysql_query("select * from uwar_users where sysid='$sysid' order by z");
				while ($rec = mysql_fetch_object($query2))
				{
                	if($rec->nick == $sl)
                     continue;
                    ?><option value="<? print $rec->id; ?>"> <? print $rec->nick. " of ".$rec->planet; ?></option><?
				}
				?>
	            </select>
	        </tr><tr>
	<td colspan=2 bgcolor="<?=$tdbg2;?>">
	 <center><input type=submit value="Start exile" name="startexile"></td></tr>		
	</table>
    </form>
	<br>
-->
	<?
}
footerDsp();

headerDsp("Help Guide");
?><p style="margin: 10px;"><?
echo PrintHelp($section);
?></p><?
footerDsp();

include("footer.php");
?>