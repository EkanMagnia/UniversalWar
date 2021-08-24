<?
error_reporting(1);
//Includes data for Unit Spy and OPS Scan and more
//include("data/shipstats.php");
include("data/ShipTypes.php");
//include("data/ops.php");
include_once("header.php");

function scanNow($scan_type, $targetinfo, $Scans, $x, $y, $z)
{
   	global $stealth, $ShipTypes, $number2, $Userid, $maxShipID;
	$AttackerSQL = mysql_query("SELECT * FROM uwar_users WHERE id=$Userid");
	$Attacker = mysql_fetch_array($AttackerSQL);
	$AttackerSysSQL = mysql_query("SELECT * FROM uwar_systems WHERE id=$Attacker[sysid]");
	$AttackerSys = mysql_fetch_array($AttackerSysSQL);

	//planetary scan
	if($scan_type == 2)
	{
		mysql_query("UPDATE uwar_users SET stealth=stealth-1 WHERE id=$Userid");
		headerDsp("Scan Results");

        //Get total ships and ops numbers
        $targetid = $targetinfo["id"];
/*		$ShipsTotal = 0;
		$OPSTotal = 0;
        $request = mysql_query("SELECT amount, shipid FROM uwar_fships WHERE userid='$targetid' AND ops='n'");
	    while($shiparray = mysql_fetch_array($request))
		{
			if ($shiparray["shipid"] < $maxShipID) $ShipsTotal += $shiparray['amount'];
		}
        $request = mysql_query("SELECT amount, shipid FROM uwar_fships WHERE userid='$targetid' AND ops='y'");
		while($opsarray = mysql_fetch_array($request))
		{
			if ($opsarray["shipid"] >= 15) $OPSTotal += $opsarray['amount'];
		}
*/
		//print "MaxShipId = ".$maxShipID."<br>";
		?>
		<br>
		<center>
		<table border="0" cellpadding="4" cellspacing="1" width="90%">
			<tr>
				<td bgcolor="<?=$tdbg1;?>" colspan="5"><? print $Scans[$scan_type]["Name"]." on ".$targetinfo["nick"]." of ".$targetinfo["planet"]." ($x:$y:$z)"; if(time()-$ranking["timer"]<600) { ?><font color="green">&nbsp;Online</font><?}?><br></td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>">Commander:</td><td bgcolor="<?=$tdbg2;?>"><?=$targetinfo["nick"]?></td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>">Planet:</td><td bgcolor="<?=$tdbg2;?>"><?=$targetinfo["planet"]?></td>
			</tr>
			<tr><!--
				<td bgcolor="<?=$tdbg2;?>">Alliance:</td>
				<?
					$tagid = $targetinfo["tagid"];
					$allySQL = mysql_query("SELECT * FROM uwar_tags WHERE id=$tagid",$db);
				if (mysql_num_rows($allySQL) == 1)
				{
				$ally = mysql_fetch_array($allySQL);
				?></td><td bgcolor="<?=$tdbg2;?>"><?=$ally["tag"]?></td>
				<?
				} else {
				?></td><td bgcolor="<?=$tdbg2;?>">None</td>
				<? } ?>
				</tr>
				<tr>-->
				<td bgcolor="<?=$tdbg2;?>">Score:</td><td bgcolor="<?=$tdbg2;?>"> <?=number_format($targetinfo["score"],0,".",".")?></td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>">Rank:</td><td bgcolor="<?=$tdbg2;?>"><?=$targetinfo["rank"]?></td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>">Mercury probes:</td><td bgcolor="<?=$tdbg2;?>"><?=number_format($targetinfo["asteroid_mercury"],0,".",".")?></td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>">Cobalt probes:</td><td bgcolor="<?=$tdbg2;?>"><?=number_format($targetinfo["asteroid_cobalt"],0,".",".")?></td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>">Caesium probes:</td><td bgcolor="<?=$tdbg2;?>"><?=number_format($targetinfo["asteroid_helium"],0,".",".")?></td>
			</tr>
            <tr>
				<td bgcolor="<?=$tdbg2;?>">Uninitiated probes:</td><td bgcolor="<?=$tdbg2;?>"><?=number_format($targetinfo["ui_roids"],0,".",".")?></td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>">Mercury:</td><td bgcolor="<?=$tdbg2;?>"><?=number_format($targetinfo["mercury"],0,".",".")?></td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>">Cobalt:</td><td bgcolor="<?=$tdbg2;?>"><?=number_format($targetinfo["cobalt"],0,".",".")?></td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>">Caesium:</td><td bgcolor="<?=$tdbg2;?>"><?=number_format($targetinfo["helium"],0,".",".")?></td>
			</tr>
			<?
				$shipsSQL = mysql_query("SELECT * FROM uwar_fships WHERE userid='$targetid' ORDER by shipid");
        $ShipsTotal = 0;
		$OPSTotal = 0;
        while ($ships = mysql_Fetch_Array($shipsSQL))
        {
			$shipid = $ships["shipid"];
			if ($ShipTypes[$shipid]["Special"] == "CL") continue;
            elseif ($ships["shipid"] >= $maxShipID) $OPSTotal += $ships["amount"];
            else $ShipsTotal += $ships["amount"];
        }        
		?>
			<tr>
				<td bgcolor="<?=$tdbg2;?>">Total ships:</td><td bgcolor="<?=$tdbg2;?>"><?=number_format($ShipsTotal,0,".",".")?></td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>">Total OPS:</td><td bgcolor="<?=$tdbg2;?>"><?=number_Format($OPSTotal,0,".",".")?></td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>">Vacation mode:</td><td bgcolor="<?=$tdbg2;?>"><? if($targetinfo["vacation"] >= 1) print "YES"; else print "NO"; ?></td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>">Sleep mode:</td><td bgcolor="<?=$tdbg2;?>"><? if($targetinfo["sleep"] >= 1) print "YES"; else print "NO"; ?></td>
			</tr>
			<tr>
				<td bgcolor="<?=$tdbg2;?>">Universal Lords Protection:</td><td bgcolor="<?=$tdbg2;?>"><? if($targetinfo["protection"] >= 1) print "YES"; else print "NO"; ?></td>
			</tr>
		</table>
		</center>
		<br>
		<?
		footerDsp();
	}

	//unit scan
	elseif($scan_type == 3)
	{
		mysql_query("UPDATE uwar_users SET stealth=stealth-2 WHERE id=$Userid");
        headerDsp("Scan Results");
        ?>
        <br>
		<center>
		<table border="0" cellpadding="4" cellspacing="1" width="90%">
			<tr>
				<td bgcolor="<?=$tdbg1;?>" colspan="2"><? print $Scans[$scan_type]["Name"]." on ".$targetinfo["nick"]." of ".$targetinfo["planet"]." ($x:$y:$z)"; ?><br></td>
			</tr>
        <?

        //Get total ships numbers
        $targetid = $targetinfo["id"];
		?>        <TABLE align="center" borderColor="#444444" width="200" style="border-collapse: collapse" cellpadding="0" cellspacing="0">
		<?

		$ShipsAvailable = array();
//		print "targetid =".$targetid."<br>";
		$ShipsAvailable = getAvailableShips($ShipTypes, $targetid);
		foreach ( $ShipsAvailable as $x => $shipid )
		{
			$request = mysql_query("SELECT * FROM uwar_fships WHERE userid='$targetid' AND shipid='$shipid' AND ops='n' ORDER BY shipid");
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
			if( $stocks[$idx] == 0 || $ShipTypes[$ship]["Special"] == "CL" || $stocks[$idx]["shipid"]>=$maxShipID) continue;
			?><TR>
					<TD><?=$ShipTypes[$ship]['Name'],"s";?></TD>
					<TD width=40></TD>
					<TD>
						<?print number_format($stocks[$idx]["amount"],0,".",".");
						?></TD>
			</TR><?
		} 
		$targetid = $targetinfo["id"];
		$request = mysql_query("SELECT * FROM uwar_fships WHERE userid='$targetid' AND ops='n' ORDER BY shipid");
        if (mysql_num_rows($request) == 0) print "<TR><TD colspan=2>Target has no ships!</TD></TR>";
		 ?>
        </table>
		</center>
		<br>
		<?
		footerDsp();
	}

	//ops scan
	elseif($scan_type == 4)
	{
		mysql_query("UPDATE uwar_users SET stealth=stealth-2 WHERE id=$Userid");
         headerDsp("Scan Results");
        ?>
        <br>
		<center>
		<table border="0" cellpadding="4" cellspacing="1" width="90%">
			<tr>
				<td bgcolor="<?=$tdbg1;?>" colspan="2"><? print $Scans[$scan_type]["Name"]." on ".$targetinfo["nick"]." of ".$targetinfo["planet"]." ($x:$y:$z)"; ?><br></td>
			</tr>
           <TABLE align="center" borderColor="#444444" width="200" style="border-collapse: collapse" cellpadding="0" cellspacing="0">
		<?
		$targetid = $targetinfo["id"];
		$OPSAvailable = array();
		$OPSAvailable = getAvailableOPS($ShipTypes, $targetid);
		foreach ( $OPSAvailable as $x => $shipid )
		{
			$request = mysql_query("SELECT * FROM uwar_fships WHERE userid='$targetid' AND shipid='$shipid' ORDER BY shipid");
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
		$targetid = $targetinfo["id"];
		$request = mysql_query("SELECT * FROM uwar_fships WHERE userid='$targetid' AND shipid>=$maxShipID");
        if (mysql_num_rows($request) == 0) print "<TR><TD colspan=2>Target has no defensive units!</TD></TR>";
		?>
        </table>
		</center>
		<br>
		<?
		footerDsp();
	}
	//news scan
	elseif($scan_type == 5)
	{
		mysql_query("UPDATE uwar_users SET stealth=stealth-3 WHERE id=$Userid");
        headerDsp("Scan Results");
        ?>
        <br>
		<center>
		<table border="0" cellpadding="4" cellspacing="1" width="90%">
			<tr>
				<td bgcolor="<?=$tdbg1;?>" colspan="2"><? print $Scans[$scan_type]["Name"]." on ".$targetinfo["nick"]." of ".$targetinfo["planet"]." ($x:$y:$z)"; ?><br></td>
			</tr>
        <?
					$targetid = $targetinfo["id"];
					$request = mysql_query("SELECT * FROM uwar_news WHERE userid='$targetid' ORDER BY time DESC");

		if (mysql_num_rows($request)==0) print "<td>No news for that planet</td>";
		else
		{
			for($count = 0; $myrow = mysql_fetch_array($request); $count++)
			{
//				if(strstr($myrow['header'], 'Hostiles')) $prefix = "<font color=red>";
//				if(strstr($myrow['header'], 'Allies')) $prefix = "<font color=green>"; ?>
				<tr>
					<td rowspan=2 align=center valign=middle><img src="images/arrow_off.gif"></td>
					<td bgcolor="<?=$tdbg1;?>">
						<b><? if(isset($prefix)) { print $prefix; } print $myrow["header"]; ?></font></b>
						 - <font size="1"><i><? print strftime("%d/%m-20%y %H:%M:%S",$myrow["time"]); ?></i></font>
					</td>
				</tr>
				<tr>
					<td bgcolor="<?=$tdbg1;?>"><? print $myrow["news"]; ?></td>
				</tr>
				<tr><td height=5></td></tr>
				<?
			} 
		}
		?>
		</table>
		</center>
		<?
		footerDsp();
	}

	//army scan
	elseif($scan_type == 6)
	{
		mysql_query("UPDATE uwar_users SET stealth=stealth-3 WHERE id=$Userid");
	   headerDsp("Scan Results");
        ?>
        <br>
		<center>
		<table border="0" cellpadding="4" cellspacing="1" width="90%">
			<tr>
				<td bgcolor="<?=$tdbg1;?>" colspan="2" align="center"><? print $Scans[$scan_type]["Name"]." on ".$targetinfo["nick"]." of ".$targetinfo["planet"]." ($x:$y:$z)";?><br></td>
			</tr>
        <?
       $targetid = $targetinfo["id"];
		$request = mysql_query("SELECT shipid FROM uwar_fships WHERE amount > 0 AND userid='$targetid' AND ops='n' ORDER by shipid");
		if (mysql_num_rows($request) > 0)
		{

			?><table border="0" cellpadding="4" cellspacing="1" width="90%">
			<tr>
				<td bgcolor="<?=$tdbg1;?>">Ship name</td>
				<td bgcolor="<?=$tdbg1;?>">Base</td>
				<td bgcolor="<?=$tdbg1;?>">Fleet #1</td>
				<td bgcolor="<?=$tdbg1;?>">Fleet #2</td>
				<td bgcolor="<?=$tdbg1;?>">Fleet #3</td>
			</tr>
			<?

			//PRINTS THE SHIPS
			for($i=0; $ship = mysql_fetch_array($request); $i++)
			{
				if($ship["shipid"] == $shipid) continue;
				$shipid = $ship["shipid"];
				if ($shipid >= 15) continue;
				?><tr>
				<td bgcolor="<?=$tdbg2;?>"><? print $ShipTypes[$shipid]["Name"]."s"?></td><?

				for($fleetnum = 0; $fleetnum < 4; $fleetnum++)
				{
					$request2 = mysql_query("SELECT * FROM uwar_fships WHERE shipid='$shipid' AND userid='$targetid' AND amount > 0 AND fleetnum = '$fleetnum' LIMIT 0,1");
					$result2 = mysql_fetch_array($request2);
					if($result2["fleetnum"] == $fleetnum) 
					{
						?><td bgcolor="<?=$tdbg2;?>"><?
						if ($result2["amount"] != '')  print number_format($result2["amount"],0,".",".");
						else print '0';
						?></td><? 
					}
					else { ?><td bgcolor="<?=$tdbg2;?>"></td><? }
				}
				?></tr><?
			}
		?>
		</table><br><br>
		<table border="0" cellpadding="4" cellspacing="1" width="90%">
		<tr>
			<td bgcolor="<?=$tdbg1;?>">Fleet</td>
			<td bgcolor="<?=$tdbg1;?>">Mission</td>
			<td bgcolor="<?=$tdbg1;?>">Target</td>
			<td bgcolor="<?=$tdbg1;?>">ETA</td>
			<td bgcolor="<?=$tdbg1;?>">How long</td>
		</tr>
		<?
			for ($army = 1; $army < 4; $army++)
			{
				$targetid = $targetinfo["id"];
				$request2 = mysql_query("SELECT * FROM uwar_tships WHERE userid='$targetid' AND fleetnum='$army' ");
				$result = mysql_fetch_array($request2);
				$targetSQL = mysql_query("SELECT * FROM uwar_users WHERE id='$result[targetid]'");
				$target = mysql_fetch_array($targetSQL);
				if ($result["action"] == "a")
				{
					$font = "";
					$mission = "Attacking";
				}
				elseif ($result["action"] == "d")
				{
					$font = "";
					$mission = "Defending";
				}
				elseif ($result["action"] == "r")
				{
					$font = "";
					$mission = "Returning";
				}
				elseif ($result["action"] == "h")
				{
					$font = "";
					$mission = "Home";
				}		
					?><td bgcolor="<?=$tdbg2;?>">Fleet <?=$army;?></td><td bgcolor="<?=$tdbg2;?>"><?=$mission;?></td>
					<? if (($result["action"] == "a") || ($result["action"] == "d"))
					{
						?><td bgcolor="<?=$tdbg2;?>"><?=$target["nick"];?> of <?=$target["planet"];?></td><td bgcolor="<?=$tdbg2;?>">ETA <?=$result["eta"];?></td><td bgcolor="<?=$tdbg2;?>"><?=$result["howlong"];?></td><tr><?
					}
					else 
					{?>
					<td bgcolor="<?=$tdbg2;?>"><td></td><td bgcolor="<?=$tdbg2;?>"></td><tr>
					<?
					}
			}
		}
		else print "<td bgcolor=\"$tgbg2\" align=\"center\">Target has not ships!</td>";
		footerDsp();
		footerDsp();
	}
	//thievering
	/* round a number between 1000 and 6000.This will represent the amount
	   round a number between 1 and 3. This will represent the resource
	   For example: 
	   First number = 3000; Second number = 2
	   The scan will steal (3000 * number of thiefs) cobalt
   */
	elseif($scan_type == 7)
	{
	   headerDsp("Operation Results");
        ?>
        <br>
		<center>
		<table border="0" cellpadding="4" cellspacing="1" width="90%">
			<tr>
				<td bgcolor="<?=$tdbg1;?>" colspan="2" align="center"><? print $Scans[$scan_type]["Name"]." on ".$targetinfo["nick"]." of ".$targetinfo["planet"]." ($x:$y:$z)";?><br></td>
			</tr>
        <?
//		$stealth = $Attacker["stealth"];
//		if ($stealth >= 10)
//		{
			if ($targetinfo["score"] > 0.5 * $Attacker["score"])
			{
				if ($targetinfo["vacation"] == 0)
				{
					if ($targetinfo["sleep"] == 0)
					{
						$resource = rand(1, 3);
						$amount = rand(1500, 4500);
						$prefinalamount = $amount * $number2;
						//mercury
						$stealrate = 0.1;
						if ($resource == 1)
						{	
							//target update
							$request = mysql_query("SELECT mercury FROM uwar_users WHERE id='$targetinfo[id]'");
							$result = mysql_fetch_array($request);
							if ($result["mercury"] * $stealrate >= $prefinalamount) $finalamount = 	$prefinalamount;
							else $finalamount = floor($result["mercury"] * $stealrate);
							mysql_query("UPDATE uwar_users SET mercury=mercury-$finalamount WHERE id='$targetinfo[id]'");
							mysql_query("UPDATE uwar_users SET mercury=mercury+$finalamount WHERE id=$Userid");
				
							$news = "Thieves from ".$Attacker["nick"]." of ".$Attacker["planet"]." (".$AttackerSys["x"].":".$AttackerSys["y"].":".$Attacker["z"].") have ravaged our planetary mines and stole ".$finalamount." mercury.";
							add_news("Offensive Operation", $news, $targetinfo["id"]);
							?>
							<tr><td bgcolor="<?=$tdbg2;?>" colspan="2" align="center">We have stolen <?=$finalamount;?> mercury</td></tr>
							<?
						}
						elseif ($resource == 2)
						{	
							//target update
							$request = mysql_query("SELECT cobalt FROM uwar_users WHERE id='$targetinfo[id]'");
							$result = mysql_fetch_array($request);
							if ($result["cobalt"] * $stealrate >= $prefinalamount) $finalamount = $prefinalamount;
							else $finalamount = floor($result["cobalt"] * $stealrate);
							mysql_query("UPDATE uwar_users SET cobalt=cobalt-$finalamount WHERE id='$targetinfo[id]'");
							mysql_query("UPDATE uwar_users SET cobalt=cobalt+$finalamount WHERE id=$Userid");
	
							$news = "Thieves from ".$AttackerSQL["nick"]." of ".$AttackerSQL["planet"]." (".$AttackerSys["x"].":".$AttackerSys["y"].":".$Attacker["z"].") have ravaged our planetary mines and stole ".$finalamount." cobalt.";
							add_news("Offensive Operation", $news, $targetinfo["id"]);

							?><tr><td bgcolor="<?=$tdbg2;?>" colspan="2" align="center">We have stolen <?=$finalamount;?> cobalt</td></tr>				
							<?
						}
						elseif ($resource == 3)
						{	
							//target update
							$request = mysql_query("SELECT helium FROM uwar_users WHERE id='$targetinfo[id]'");
							$result	= mysql_fetch_array($request);
							if ($result["helium"] * $stealrate >= $prefinalamount) $finalamount = $prefinalamount;
							else $finalamount = floor($result["helium"] * $stealrate);
							mysql_query("UPDATE uwar_users SET helium=helium-$finalamount WHERE id='$targetinfo[id]'");
							mysql_query("UPDATE uwar_users SET helium=helium+$finalamount WHERE id=$Userid");
							$news = "Thieves from ".$AttackerSQL["nick"]." of ".$AttackerSQL["planet"]." (".$AttackerSys["x"].":".$AttackerSys["y"].":".$Attacker["z"].") have ravaged our planetary mines and stole ".$finalamount." cesium.";
							add_news("Offensive Operation", $news, $targetinfo["id"]);

							?><tr><td bgcolor="<?=$tdbg2;?>" colspan="2" align="center">We have stolen <?=$finalamount;?> caesium</td></tr>				
							<?

						}	
						$numero = $number2-1;
						mysql_query("UPDATE uwar_scans SET stock=stock-$numero WHERE scanid=7 AND userid=$Userid");
						if ($stealth > 50) $stealth-=0.1 * $stealth;
						elseif ($stealth > 25 && $stealth <= 50) $stealth-=0.2*$stealth;
						elseif ($stealth <= 25) $stealth -= 0.3 * $stealth;
						//elseif ($stealth <= 10) $stealth -= 0.5 * $stealth;
						$stealth = ceil($stealth);						
						mysql_query("UPDATE uwar_users SET stealth='$stealth' WHERE id=$Userid");
					}
					else $msgred = "Target is in sleep mode!";
				}
				else $msgred = "Target is in vacation!";
			}
			else $msgred = "Target is too small!";
//		}
//		else $msgred = "Your stealth rate is less than 10. You can not perform an operation until the rate rises!";
		
		if (isset($msgred))
			?><tr><td><center><font color="#FF0000" size="2"><?=$msgred;?></font></center></td></tr>
		</table>
		</center>
		<?
		footerDsp();
	}

	//converting
	/* rand a number between 0 and 14
	   that number represents the ship id.
	   the number of stolen ships = sent converters * $number_to_steal;
	   $number_to_steal is calculated as follow:
	   1. If the ship is a fighter number_to_steal = 20 - 50;
	   2. If the ship is a Light number_to_steal = 20 - 40;
	   3. If the ship is a frigate number_to_steal = 15 - 30;
	   4. If the ship is a Heavy number_to_steal = 15  - 30;
	   5. If the ship is a cruiser number_to_steal = 15 - 25;
	   6. If the ship is a annihilator number_to_steal = 5 - 20;
	  */
/*	elseif($scan_type == 8)
	{
	   headerDsp("Operation Results");
        ?>
        <br>
		<center>
		<table border="0" cellpadding="4" cellspacing="1" width="90%">
			<tr>
				<td bgcolor="<?=$tdbg1;?>" colspan="2" align="center"><? print $Scans[$scan_type]["Name"]." on ".$targetinfo["nick"]." of ".$targetinfo["planet"]." ($x:$y:$z)";?><br></td>
			</tr>
        <?
//		$stealth = $Attacker["stealth"];
//		if ($stealth >= 15)
//		{
			if ($targetinfo["score"] > 0.5 * $Attacker["score"])
			{
				if ($targetinfo["vacation"] == 0)
				{
					if ($targetinfo["sleep"] == 0)
					{
						if ($targetinfo["protection"] == 0)
						{
							$shipsSQL = mysql_query("SELECT * FROM uwar_fships WHERE userid=$targetinfo[id] AND amount>0");
							if (mysql_num_rows($shipsSQL) > 0)
							{
								$YourShipsSQL = mysql_query("SELECT * FROM uwar_fships WHERE userid=$Userid");
								if (mysql_num_rows($YourShipsSQL) > 0)
								{
									$shipid = rand(1,12);					

									$request = mysql_query("SELECT * FROM uwar_fships WHERE userid=$targetinfo[id] AND shipid='$shipid' AND amount>0");
									$request2 = mysql_query("SELECT * FROM uwar_fships WHERE userid=$Userid AND shipid='$shipid'");
									while (mysql_num_rows($request) == 0 || mysql_num_rows($request2) == 0)
									{
										$shipid = rand(1,12);										$request = mysql_query("SELECT * FROM uwar_fships WHERE userid=$targetinfo[id] AND shipid='$shipid' AND amount<>0");
										$request2 = mysql_query("SELECT * FROM uwar_fships WHERE userid=$Userid AND shipid='$shipid'");

									} 
											print "Shipid".$shipid;
									if ($ShipTypes[$shipid]["ShipClass"] == "FI") $amount = rand(1,4);
									if ($ShipTypes[$shipid]["ShipClass"] == "ST") $amount = rand(1,4);														if ($ShipTypes[$shipid]["ShipClass"] == "CL") $amount = rand(1,3);
									if ($ShipTypes[$shipid]["ShipClass"] == "FR") $amount = rand(1,2);
									if ($ShipTypes[$shipid]["ShipClass"] == "CR") $amount = 1;
									$prefinalamount = $amount * $number2;

									$result = mysql_fetch_array($request);
									if (0.03 * $result["amount"] >= $prefinalamount) $finalamount = $prefinalamount;
									else $finalamount = floor(0.03 * $result["amount"]);

									mysql_query("UPDATE uwar_fships SET amount=amount-$finalamount WHERE userid='$targetinfo[id]' AND shipid='$shipid'");
									mysql_query("UPDATE uwar_fships SET amount=amount+$finalamount WHERE userid=$Userid AND shipid='$shipid'");
									$news = "Agents from ".$Attacker["nick"]." of ".$Attacker["planet"]." (".$AttackerSys["x"].":".$AttackerSys["y"].":".$Attacker["z"].") have invaded our lands and converted ".$finalamount." ".$ShipTypes[$shipid]["Name"]."s";
									add_news("Offensive Operation", $news, $targetinfo["id"]);

									?><tr><td bgcolor="<?=$tdbg2;?>" colspan="2" align="center">We have stolen <?=$finalamount;?> <?=$ShipTypes[$shipid]["Name"]."s";?></td></tr>				
									<?
									$numero = $number2-1;
									mysql_query("UPDATE uwar_scans SET stock=stock-$numero WHERE scanid=8 AND userid=$Userid");

									//stealth
									$stealthSQL = mysql_query("SELECT stealth FROM uwar_users WHERE id='$Userid'");
									$stealthresult = mysql_fetch_array($stealthSQL);
									$stealth = $stealthresult["stealth"];
									print "stealth = ".$stealth."<br>";
									if ($stealth > 50) 
									{
										$stealth-=0.2 * $stealth;
										print "stealth2 = ".$stealth."<br>";
									}
									elseif ($stealth > 25 && $stealth <= 50)
									{
										$stealth-=0.4*$stealth;
										print "stealth2 = ".$stealth."<br>";
									}
									elseif ($stealth <= 25)
									{
										$stealth -= 0.6 * $stealth;	
										print "stealth2 = ".$stealth."<br>";
									}
									print "stealth2 = ".$stealth."<br>";
									$stealth = ceil($stealth);
									print "Stealth updated = ".$stealth."<br>";
									mysql_query("UPDATE uwar_users SET stealth='$stealth' WHERE id=$Userid");

								} else $msgred = "You need to have at least 1 type of ship enabled before you can start this operation!";
							} else $msgred = "Target has no ships!";
						} else $msgred = "Target is in Universal Lords Protection!";
					} else $msgred = "Target is in sleep mode!";
				} else $msgred = "Target is on vacation!";
			} else $msgred = "Target is too small!";
//		} else $msgred = "Your stealth rate is too low!";
			if (isset($msgred))
			?><tr><td><center><font color="#FF0000" size="2"><?=$msgred;?></font></center></td></tr>
			</table>
			</center>
			<?
		footerDsp();
	}
*/
	//coma
	/*
	 - if the AMPS number of the player is less than double targets amps then set the target in vacation mode for 1 tick
	 - if the amps number of the player is more than double, but less then triple targets amps then set the target in vacation for 2 ticks
	 - if the amps number of the player is triple or more then targets amps then rand a number between 3 and 5. 
	 This number represents the number of ticks the target will be put on vacation mode.
	 This operation can not be done if:
	 1. the target is under attack / defence
	 2. the target has outgoing missions
	 3. the target is in protection mode
	 4. the target is in sleep mode / vacation already
	 5. the target is in your system/alliance
	 6. if the account is closed
	*/
}
//other things for operations
/* 1. stealth. Each operation this decreases by 10% of total...u get 1 stealth each tick
   2. you can not cast an operation on: - a planet that is 50% or smaller of ur score
										- a planet that is in vacation mode
										- a planet that is in sleep mode
   3. fix converting so it doesn't enter in a infinite loop
   4. augmenters should be deducted after an operation
   */
?>