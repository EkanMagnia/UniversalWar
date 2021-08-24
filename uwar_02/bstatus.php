<?php
error_reporting(1);
$section = "War Status";
include("functions.php");
include("header.php");
include("data/ShipTypes.php");

//incoming fleets
headerDsp("Incoming fleets");
?><center><br>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<td bgcolor="<?=$tdbg1;?>" width="80%"><center>From</td>
<td bgcolor="<?=$tdbg1;?>" width="10%"><center>Mission</td>
<td bgcolor="<?=$tdbg1;?>" width="5%"><center>ETA</td>
<td bgcolor="<?=$tdbg1;?>" width="5%"><center>Ships</td>
</tr>
<?
$request = mysql_query("SELECT * FROM uwar_users WHERE tagid='$myrow[tagid]'",$db);
while ($user = mysql_fetch_array($request))
{
    $target_request = mysql_query("SELECT * FROM uwar_tships WHERE targetid='$user[id]' AND (action='a' OR action='d')");
    if (mysql_num_rows($target_request) > 0)
    {
        $incommings = TRUE;
        $sysSQL = mysql_query("SELECT * FROM uwar_systems WHERE id='$user[sysid]'");
        $sys = mysql_fetch_array($sysSQL);
        ?><td colspan="5" bgcolor="<?=$tdbg2;?>"><b><?=$user["nick"];?> of <?=$user["planet"];?> (<?=$sys["x"];?>:<?=$sys["y"];?>:<?=$user["z"];?>) :</td>
        <?
        while ($target = mysql_fetch_array($target_request))
        {
            //select from info
            $fromSQL = mysql_query("SELECT nick, planet, sysid, z FROM uwar_users WHERE id='$target[userid]'",$db);
            $from = mysql_fetch_array($fromSQL);
            //select from x and y coords
            $fromSysSQL = mysql_query("SELECT x,y FROM uwar_systems WHERE id='$from[sysid]'",$db);
            $fromSys = mysql_fetch_array($fromSysSQL);
            //ships
            $shipsSQL = mysql_query("SELECT * FROM uwar_fships WHERE userid='$target[userid]' and fleetnum='$target[fleetnum]' ORDER by shipid",$db) or die(mysql_error());
                $ShipsTotal = 0;
                while ($ships = mysql_Fetch_Array($shipsSQL))
                {
					$shipid = $ships["shipid"];
                    if ($ships["shipid"] >= $maxShipID || $ShipTypes[$shipid]["Special"] == "CL") continue;
                    $ShipsTotal += $ships["amount"];
                }
            ?><tr>
            <td width="80%" bgcolor="<?=$tdbg2;?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/arrow_off.gif" />&nbsp;<?=$from["nick"];?> of <?=$from["planet"];?> (<?=$fromSys["x"];?>:<?=$fromSys["y"];?>:<?=$from["z"];?>)</td>
            <td width="10%" bgcolor="<?=$tdbg2;?>"><center><?
                if ($target["action"] == "a") $action = "<font color=\"#FF0000\">Attack</font>";
                elseif ($target["action"] == "d") $action = "<font color=\"#339900\">Defend</font>";
                print $action;?>
            </td>
            <td width="5%" bgcolor="<?=$tdbg2;?>"><center><?=$target["eta"];?></td>
            <td width="5%" bgcolor="<?=$tdbg2;?>"><center><?=$ShipsTotal;?></td></tr>
            <?
        }
        ?>
        
        <?
    }
}

if ($incommings != TRUE) 
{
    ?><td colspan="4" bgcolor="<?=$tdbg2;?>"><center>No incomming fleets</td><?
}

?></table></center><br>
<?
footerDsp();

//outgoing fleets
headerDsp("Outgoing Fleets");
// Commander Renex of Micromaxtera is attacking Ymer of Savvy Planet (1:1:2),eta 3.
//From      To        Missions          Eta        Ships
?><center><br>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<td bgcolor="<?=$tdbg1;?>" width="40%"><center>From</td>
<td bgcolor="<?=$tdbg1;?>" width="40%"><center>To</td>
<td bgcolor="<?=$tdbg1;?>" width="10%"><center>Mission</td>
<td bgcolor="<?=$tdbg1;?>" width="5%"><center>ETA</td>
<td bgcolor="<?=$tdbg1;?>" width="5%"><center>Ships</td>
</tr>
<?
$userSQL = mysql_query("SELECT * FROM uwar_users WHERE tagid='$myrow[tagid]'",$db);
while ($user = mysql_fetch_array($userSQL))
{
    $outgoingSQL = mysql_Query("SELECT * FROM uwar_tships WHERE userid='$user[id]' AND (action='a' OR action='d')",$db);
    if (mysql_num_rows($outgoingSQL)>0)
    {
        while ($outgoing = mysql_fetch_array($outgoingSQL))
        {
            $outgoings = TRUE;
            //from x,y
            $UserSysSQL = mysql_query("SELECT x,y FROM uwar_systems WHERE id='$user[sysid]'",$db);
            $UserSys = mysql_fetch_array($UserSysSQL);
            //to commander,planet, z, sysid
            $TargetInfoSQL = mysql_query("SELECT * FROM uwar_users WHERE id='$outgoing[targetid]'",$db);
            $TargetInfo = mysql_fetch_array($TargetInfoSQL);
            //select to x and y coords
            $TargetSysSQL = mysql_query("SELECT x,y FROM uwar_systems WHERE id='$TargetInfo[sysid]'",$db);
            $TargetSys = mysql_fetch_array($TargetSysSQL);
            //ships
            $shipsSQL = mysql_query("SELECT * FROM uwar_fships WHERE userid='$outgoing[userid]' and fleetnum='$outgoing[fleetnum]' ORDER by shipid",$db) or die(mysql_error());
                $ShipsTotal = 0;
                while ($ships = mysql_Fetch_Array($shipsSQL))
                {
					$shipid = $ships["shipid"];
                    if ($ships["shipid"]>=$maxShipID || $ShipTypes[$shipid]["Special"] == "CL") continue;
                    $ShipsTotal += $ships["amount"];
                }
            ?><tr><td bgcolor="<?=$tdbg2;?>"><center><?=$user["nick"];?> of <?=$user["planet"];?> (<?=$UserSys["x"];?>:<?=$UserSys["y"]; ?>:<?=$user["z"];?>)</td>
            <td bgcolor="<?=$tdbg2;?>"><center><?=$TargetInfo["nick"];?> of <?=$TargetInfo["planet"];?> (<?=$TargetSys["x"];?>:<?=$TargetSys["y"];?>:<?=$TargetInfo["z"];?>)</td>
            <td bgcolor="<?=$tdbg2;?>"><center><? if ($outgoing["action"] == "a") $mission = "<font color=\"#FF0000\">Attack</font>";
               elseif ($outgoing["action"] == "d") $mission = "<font color=\"#339900\">Defend</font>";
               print $mission;?></td>
            <td bgcolor="<?=$tdbg2;?>"><center><?=$outgoing["eta"];?></td>
            <td bgcolor="<?=$tdbg2;?>"><center><?=$ShipsTotal;?></td></tr>
            <?
        }
    }
}

if ($outgoings != TRUE) 
{
    ?><td colspan="5" bgcolor="<?=$tdbg2;?>"><center>There are no outgoing fleets from our alliance.</td><?
}
    
footerDsp();
footerDsp();
include("footer.php");
?>
