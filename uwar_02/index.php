<?
error_reporting(1);
$section = "Base";
include("functions.php");
include("header.php");
include("data/ShipTypes.php");
include("data/construction.php");
//include("data/ops.php");

/*$AllyRequest = mysql_query("SELECT * FROM uwar_tags WHERE tag='$myrow[tag]'",$db);
$ally = mysql_fetch_array($AllyRequest);
$sysid = $myrow["sysid"];*/

headerDsp("Planet Information");
?>
<BR>
<TABLE align="center" borderColor="<?$tdbg1;?>" width="" style="border-collapse: collapse" cellpadding="0" cellspacing="0">
	<TR>
		<TD><?print "Commander: ".$myrow["nick"]; ?></td><tr>
		<td><?print "Planet: ".$myrow["planet"]; ?></td><tr>
		<td><?print "Coordinates: ".$gal["x"].":".$gal["y"].":".$myrow["z"]; ?></td><tr>
		<td><?print "Rank: ".$myrow["rank"]; ?></td><tr>
<td><? if ($myrow["protection"] > 0) print "<font color=\"#00FF00\">You are in the Universal Lords Protection for ".$myrow["protection"]." days."; ?></td>
		</TR>
</TABLE>
<BR>
<?
footerDsp();
$postrequest = mysql_query("SELECT * FROM  uwar_announcements ORDER by time DESC limit 0,1");
if (mysql_num_rows($postrequest) > 0)
{
headerDsp("Creators - Message of the day");
$post = mysql_fetch_array($postrequest);
?>
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<tr>
	<td align="center"><b><font size="4"><?=$post["subject"]?></font></b></td>
</tr>
<tr>
	<td align="center">Posted by <b><?=$post["author"]?></b> on <? print date("M j, Y H:i:s",$post["time"]); ?></td>
</tr>
<tr>
	<td><br><?=$post["motd"]?></td>
</tr>
<tr>
	<td align="right"><a href="announcements.php">View all announcements</a></td>
</tr>
</table>
</center>
<br>
<?
footerDsp();
}
headerDsp( "System - Message Of the Day" );
?>
<BR>
<TABLE align="center" borderColor="<?$tdbg1;?>" width="" style="border-collapse: collapse" cellpadding="0" cellspacing="0">
	<TR>
		<TD><? print $gal["sysmotd"]; ?></TD>
	</TR>
</TABLE>
<BR>
<?
footerDsp();

$Allyrequest = mysql_query("SELECT * FROM uwar_tags WHERE id='$myrow[tagid]'",$db);
if (mysql_num_rows($Allyrequest) == 1)
{
headerDsp( "Alliance - Message Of the Day" );
?>
<BR>
<TABLE align="center" borderColor="<?$tdbg1;?>" width="" style="border-collapse: collapse" cellpadding="0" cellspacing="0">
	<TR>
		<TD><?

		$ally = mysql_fetch_array($Allyrequest);
		print $ally["motd"]; ?></td><tr>

		</TR>
</TABLE>
<BR>
<?
footerDsp();
}
headerDsp( "Laboratory/Technology progress" );
?>
<BR>
<center><TABLE align="center" borderColor="<?$tdbg1;?>" width="500" style="border-collapse: collapse" cellpadding="0" cellspacing="0">
<?
$labrequest = mysql_query("SELECT * FROM uwar_constructions WHERE userid='$Userid' AND  activated='1'",$db);
if (mysql_num_rows($labrequest) > 0)
{
while ($labs = mysql_fetch_array($labrequest))
{
	$count = $labs["constructionid"];
	$completed = $labs["complete"];
	$eta = $labs["eta"] - 1;
			?><tr><td><?
			$date = UST($tickdate+$eta);

			if ($Con[$count][$completed]["Type"] == "c")
			{
				print ("Our engineers are constructing ".$Con[$count][$completed]["Name"].", eta ".$eta.". The laboratory will be done on $date.");?></td></tr>
				<?
			}
			else 
			{
				print ("Our scientists are researching ".$Con[$count][$completed]["Name"].", eta ".$eta.". The technology will be done on $date.");?></td></tr>
<?
			}
			
}
}
else print "<td><center>Our workers are not constructing any laboratory or researching any technology.</center></td>";
?>		
		</TABLE></center>
		<BR>
<?
footerDsp();



//ships
headerDsp( "Ships" );
?>
<BR>
<TABLE align="center" borderColor="#444444" width="200" style="border-collapse: collapse" cellpadding="0" cellspacing="0">
<?

$ShipsAvailable = array();
$ShipsAvailable = getAvailableShips($ShipTypes, $Userid);
$hasShipsSQL = mysql_query("SELECT userid FROM uwar_fships WHERE userid='$Userid' AND ops='n'");
if(mysql_num_rows($hasShipsSQL) == 0) $noShips = "true";
foreach ( $ShipsAvailable as $x => $shipid )
{
	$request = mysql_query("SELECT * FROM uwar_fships WHERE userid='$Userid' AND shipid='$shipid' ORDER BY shipid");
	if(mysql_num_rows($request) > 1)
	{
		$shipsamount = 0;
		$ShipsTotal = 0;
		while($result = mysql_fetch_array($request))
			$shipsamount += $result["amount"];
		$stocks[]["amount"] = $shipsamount;
		}
	else $stocks[] = mysql_fetch_array($request);
}

foreach ( $ShipsAvailable as $idx => $ship)
{
	if($stocks[$idx] == 0) continue;
?>	<TR>
			<TD><?=$ShipTypes[$ship]['Name'],"s";?></TD>
			<TD width="40"></TD>
			<TD>
				<?print number_format($stocks[$idx]["amount"],0,".",".");
				?></TD>
		</TR>
<? } 
if($noShips == "true") { ?>	<tr><td>We do not have any ships.</td></tr> <? }
	?>
	</TABLE>
	<BR>
<?

footerDsp();
//ops
headerDsp( "Orbital Protection System" );
?>
<BR>
<TABLE align="center" borderColor="#444444" width="200" style="border-collapse: collapse" cellpadding="0" cellspacing="0">
<?
$OPSAvailable = array();
$OPSAvailable = getAvailableOPS($ShipTypes, $Userid);
$hasOPSSQL = mysql_query("SELECT userid FROM uwar_fships WHERE userid='$Userid'");
if(mysql_num_rows($hasOPSSQL) == 0) $noOPS = "true";
foreach ( $OPSAvailable as $x => $shipid )
{
	$request = mysql_query("SELECT * FROM uwar_fships WHERE userid='$Userid' AND shipid='$shipid' ORDER BY shipid");
	if(mysql_num_rows($request) > 1)
	{
		$shipsamount = 0;
		while($result = mysql_fetch_array($request))
			$shipsamount += $result["amount"];
		$stocks2[]["amount"] = $shipsamount;
	}
	else $stocks2[] = mysql_fetch_array($request);
}
foreach ( $OPSAvailable as $idx => $def)
{
	if($stocks2[$idx] == 0) continue;
?>	<TR>
			<TD><?=$ShipTypes[$def]['Name'],"s";?></TD>
			<TD width=40></TD>
			<TD>
				<?print number_format($stocks2[$idx]["amount"],0,".",".");
				?></TD>
		</TR>
<? } 
if($noOPS == "true") { ?>	<tr><td>We do not have any OPS units.</td></tr> <? }
?>
	</TABLE>
	<BR>
<?
footerDsp();

headerDsp("Incoming fleets");
?>
<BR>
<CENTER>
<?
$battleSQL = mysql_query("SELECT * FROM uwar_tships WHERE targetid='$Userid'",$db);
if (mysql_num_rows($battleSQL) > 0) 
{
while ($battle = mysql_fetch_array($battleSQL))
{
	$attackerSQL = mysql_query("SELECT * FROM uwar_users WHERE id='$battle[userid]'",$db);
	if ($attacker = mysql_fetch_array($attackerSQL))
	{
		$sysSQL = mysql_query("SELECT x,y FROM uwar_systems WHERE id='$attacker[sysid]'",$db);
		$sys = mysql_fetch_array($sysSQL);
		$shipsSQL = mysql_query("SELECT * FROM uwar_fships WHERE userid='$attacker[id]' and fleetnum='$battle[fleetnum]' ORDER by shipid",$db);
			$ShipsTotal = 0;
			while ($ships = mysql_Fetch_Array($shipsSQL))
				$ShipsTotal += $ships["amount"];
	}
	if ($battle["action"] == "a") 
	{
		$font = "#FF0000";
		?><font color="#FF0000" >Hostile incoming fleet of <?=$ShipsTotal;?> ships originating from <?=$attacker["nick"]?> of <?=$attacker["planet"];?> (<?=$sys["x"];?>:<?=$sys["y"];?>:<?=$attacker["z"];?>) ETA <?=$battle["eta"];?>.<br>
		<?
	}
	elseif ($battle["action"] == "d") 
	{
		?><font color="#339900" >Friendly incoming fleet of <?=$ShipsTotal;?> ships originating from <?=$attacker["nick"]?> of <?=$attacker["planet"];?> (<?=$sys["x"];?>:<?=$sys["y"];?>:<?=$attacker["z"];?>) ETA <?=$battle["eta"];?>.<br><?
	}
}
}
else print "We do not have any incoming fleets.";
?></CENTER><BR>
<?
footerDsp();



headerDsp("Outgoing fleets");
?>
<BR>
<CENTER>
<?
$outgoingSQL = mysql_query("SELECT * FROM uwar_tships WHERE userid='$Userid' AND (action='a' OR action='d' OR action='r')",$db);
if (mysql_num_rows($outgoingSQL) > 0)
{
	while ($out = mysql_fetch_array($outgoingSQL))
	{
		$targetSQL = mysql_Query("SELECT * FROM uwar_users WHERE id='$out[targetid]'",$db);
		$target = mysql_fetch_array($targetSQL);
		$sysSQL = mysql_Query("SELECT * FROM uwar_systems WHERE id='$target[sysid]'",$db);
		$sys = mysql_fetch_array($sysSQL);
		
		if ($out["action"] == "a")
		{
			?>Fleet <?=$out["fleetnum"];?> is attacking <?=$target["nick"];?> of <?=$target["planet"];?> (<?=$sys["x"];?>:<?=$sys["y"];?>:<?=$target["z"];?>), eta <?=$out["eta"];?> for <?=$out["howlong"];?> days.<br>
			<?
		}
		elseif ($out["action"] == "d")
		{
			?>Fleet <?=$out["fleetnum"];?> is defending <?=$target["nick"];?> of <?=$target["planet"];?> (<?=$sys["x"];?>:<?=$sys["y"];?>:<?=$target["z"];?>), eta <?=$out["eta"];?> for <?=$out["howlong"];?> days.<br>
			<?
		}
		elseif ($out["action"] == "r")
		{
			?>Fleet <?=$out["fleetnum"]?> is returning home.<br>
			<?
		}
		elseif ($out["action"] == "h")
		{
			?>Fleet <?=$out["fleetnum"];?> is home.<br>
			<?
		}
	}
}
else print "We do not have any outgoing fleets.";
?></CENTER><BR>
<?
footerDsp();


headerDsp( "Probes" );
?>
<BR>
<TABLE align="center" borderColor="<?$tdbg1;?>" width="200" style="border-collapse: collapse" cellpadding="0" cellspacing="0">
	<TR>
		<TD>Mercury:</TD>
		<TD width=40></TD>
		<TD><? print number_format($myrow["asteroid_mercury"],0,".",".");?></TD>
	</TR>
	<TR>
		<TD>Cobalt:</TD>
		<TD width=40></TD>
		<TD><? print number_format($myrow["asteroid_cobalt"],0,".",".");?></TD>
	</TR>
		<TR>
		<TD>Caesium:</TD>
		<TD width=40></TD>
		<TD><? print number_format($myrow["asteroid_helium"],0,".",".");?></TD>
	</TR>
		<TR>
		<TD>Uninitiated probes:</TD>
		<TD width=40></TD>
		<TD><? print number_format($myrow["ui_roids"],0,".","."); ?></TD>
	</TR>
		</TR>
	<TR>
		<TD><b>Total probes:</b></TD>
		<TD width=40></TD>
		<TD><B><? $roids = $myrow["asteroid_mercury"] + $myrow["asteroid_cobalt"] + $myrow["asteroid_helium"] + $myrow["ui_roids"];
		print number_format($roids,0,".",".");?></B></TD>
	</TR>
</TABLE>
<BR>
<?
footerDsp();

headerDsp("Game statistics");
?><BR><CENTER>
<TABLE align="center" borderColor="<?$tdbg1;?>" width="250" style="border-collapse: collapse" cellpadding="0" cellspacing="0">
<TR>
	<TD><? 
		$tickerSQL = mysql_query("SELECT tickertime, pause FROM uwar_modes WHERE id=1");
		$tickerARR = mysql_fetch_array($tickerSQL);
		$tickertime = $tickerARR["tickertime"];
		
		if ($tickerARR["pause"] == 0 || $ticktime == 0) echo "<font color=\"#CCCC00\">Ticker is currently stopped.</font>";
		elseif ($tickdif > $tickertime ) echo "<font color=\"#CCCC00\">Last tick: ".seconds_for_tick($tickdif)."</font><br>";
		else 
		{	echo "Last tick: ".seconds_for_tick($tickdif)."<br>";
			echo "Next tick: ".seconds_for_tick($tickertime - $tickdif)."<br>";
		}
?></TD>
</TR>
<TR>
	<?$UserCountSQL = mysql_query("SELECT id FROM uwar_users",$db);
	$UserCount = mysql_num_rows($UserCountSQL);
	$OnlineCountSQL = mysql_query("SELECT id FROM uwar_users WHERE timer>".(time()-600),$db);
	$UserOnline = mysql_num_rows($OnlineCountSQL);
	$ClosedSQL = mysql_query("SELECT id FROM uwar_users WHERE closed='1'",$db);
	$Closed = mysql_num_rows($ClosedSQL);
	?>
	<TD>
		<?print $UserCount;?> total commanders.</TD>
</TR>
<TR>
	<TD><?print $UserOnline;?> online commanders.</TD>
</TR>
<TR>	
	<?if ($myrow["access"]>=1)
	{
		?>
		<TD><?print $Closed;?> closed accounts</TD>
</TR>
<TR>
	<?} ?>
	<td>
		Current time  <?
		$time = time();
		echo date("M j, Y H:i:s",time()-7200)." GMT"?>
	</TD>
</TR>
</TABLE>
</CENTER>
<BR>
<?
footerDsp();
	if ($users["design"]==0) $footerfile = "footer.php";
	elseif ($users["design"]==1) $footerfile = "footer2.php";
include("footer.php");
?>