<?
error_reporting(1);
$section = "Military";
include("functions.php");
include("header.php");
/* Actions
 a = attacking
 d = defending
 r  = returning
 h =  home
 */
//die("<center>Military is disabled for now</center>");
include("data/ShipTypes.php"); //includes all the ships types stuff

$myrow_request = mysql_query("SELECT * FROM uwar_users where id='$Userid'");
$myrow = mysql_fetch_array($myrow_request);
$mysysid = $myrow["sysid"];
$sys_request = mysql_query("SELECT x,y FROM uwar_systems WHERE id='$mysysid'");
$sys_result = mysql_fetch_array($sys_request);
$myx = $sys_result["x"];
$myy = $sys_result["y"];
$myz = $myrow["z"];

function CalcTravelTime($fleet, $target)
{
	global $myx, $myy, $Userid, $ShipTypes;
	$shiptravel = 0;
	$placetime = 0;

	#Formula - longest ship traveltime + place time - construction reduction

	//Calculates the longest ship traveltime
	$fleet_request = mysql_query("SELECT * FROM uwar_fships WHERE fleetnum='$fleet' AND userid='$Userid' and ops='n' ORDER by shipid");
	while($fleet_array = mysql_fetch_array($fleet_request))
	{
		if ($fleet_array["amount"]>0)
		{
			$shipid = $fleet_array["shipid"];
			if($ShipTypes[$shipid]["Travel"] > $shiptravel) $shiptravel = $ShipTypes[$shipid]["Travel"];
		}
	}

	//Calculates the place time
	if ($target["x"] == $myx && $target["y"] == $myy) $placetime = 3; //System
	elseif($target["x"] == $myx) $placetime = 7; //Sector
	else $placetime = 8; // Universe

	$request_reduce1 = mysql_query("SELECT complete FROM uwar_constructions WHERE constructionid=2 AND userid='$Userid'");
	
	if ($const = mysql_fetch_array($request_reduce1))
	{
		if ($const["complete"] == 4) $reduction = 4;
		elseif ($const["complete"] == 3) $reduction = 3;
		elseif ($const["complete"] == 2) $reduction = 2;
		elseif ($const["complete"] == 1) $reduction = 1;
		else $reduction = 0;
	}
	//print "coord = ".$target["x"].":".$target["y"]."<br>";
	//print "reduction = ".$reduction."<br>";
	//print "placetime = ".$placetime."<br>";
	//print "shiptravel = ".$shiptravel."<br>";

	//smaller = 3
	//highest = 
	$eta = $shiptravel + $placetime - $reduction;
	//print "eta = ".$eta."<br>";
	return $eta;
}

function CalcTravelCost ($fleet, $target)
{
	global $myx, $myy, $Userid, $ShipTypes;
	$request = mysql_query("SELECT * FROM uwar_fships WHERE userid='$Userid' and fleetnum='$fleet' and ops='n' ORDER BY shipid") or die(mysql_error());
	$Cost = 0;
	while ($ship = mysql_fetch_array($request))
	{
	   $shipid = $ship["shipid"];
	   $TravelCost = $ship["amount"] * $ShipTypes[$shipid]["Fuel"];
	   $Cost +=  $TravelCost;
	}
//	print "ship cost = ".$Cost."<br>";
//	print "CalcTravelCost - Cost = ".$Cost."<br>";
	//Calculates the place time
	if($target["x"] == $myx && $target["y"] == $myy) $TotalCost = round($Cost * 0.5); //System
	elseif($target["x"] == $myx) $TotalCost = round($Cost * 0.75); //Sector
	else $TotalCost = round($Cost); // Universe
//	print "TotalCost = ".$TotalCost."<br>";
	return $TotalCost;
}


function MoveShips($shiptype, $fromfleet, $tofleet, $amount)
{
	global $Userid, $maxShipID;
	if ($fromfleet != $tofleet)
	{
	if($shiptype != "allships")
	{
		//Updates the "fromfleet"
		$from = mysql_query("SELECT * FROM uwar_fships WHERE shipid='$shiptype' AND fleetnum='$fromfleet' AND userid='$Userid'");
		$from_array = mysql_fetch_array($from);
		if($amount > $from_array["amount"]) $amount = $from_array["amount"]; //Move all ships if the requested amount is more too high
		if($amount < 0) $amount = 0;

		$fromamount = $from_array["amount"] - $amount;
		if($fromamount == 0) $fromamount = '';
		mysql_query("UPDATE uwar_fships SET amount='$fromamount' WHERE shipid='$shiptype' AND fleetnum='$fromfleet' AND userid='$Userid'");

		//Updates the "tofleet"
		$to = mysql_query("SELECT * FROM uwar_fships WHERE shipid='$shiptype' AND fleetnum='$tofleet' AND userid='$Userid'");
		if(mysql_num_rows($to) > 0) //If the user already has this type of ship in this fleet, increase the amount
		{
			$to_array = mysql_fetch_array($to);
			$toamount = $to_array["amount"] + $amount;
			mysql_query("UPDATE uwar_fships SET amount='$toamount' WHERE shipid='$shiptype' AND fleetnum='$tofleet' AND userid='$Userid'");
		}
		else //...Else insert the new amount...
		{
			mysql_query("INSERT INTO uwar_fships (userid, shipid, fleetnum, amount, ops) VALUES ('$Userid', '$shiptype', '$tofleet', '$amount', 'n')") or die("ERROR!");
		}
	}
	else
	{
		$from = mysql_query("SELECT * FROM uwar_fships WHERE fleetnum='$fromfleet' AND userid='$Userid' ORDER by shipid");
		while($from_array = mysql_fetch_array($from))
		{
			if ($from_array["shipid"] >= $maxShipID) continue;
			//Updates the "tofleet"
			$to = mysql_query("SELECT * FROM uwar_fships WHERE shipid='$from_array[shipid]' AND fleetnum='$tofleet' AND userid='$Userid'");
			if(mysql_num_rows($to) > 0) //If user already has this type of ship in this fleet, increase the amount
			{
				$to_array = mysql_fetch_array($to);
					$toamount = $to_array["amount"] + $from_array["amount"];
				mysql_query("UPDATE uwar_fships SET amount='$toamount' WHERE shipid='$from_array[shipid]' AND fleetnum='$tofleet' AND userid='$Userid'");
			}
			else //...Else insert the new amount...
			{
				mysql_query("INSERT INTO uwar_fships (userid, shipid, fleetnum, amount,ops) VALUES ('$Userid', '$from_array[shipid]', '$tofleet', '$from_array[amount]','n')");
			}

			//Updates the "fromfleet"
			$amount = "";
			mysql_query("UPDATE uwar_fships SET amount='$amount' WHERE shipid='$from_array[shipid]' AND fleetnum='$fromfleet' ANd userid='$Userid'");
		}
		}
	}
	else $msgred = "You can not move ships to the same fleet!";
}

function LaunchFleet($fleet, $assignment, $howlong, $ticks, $x, $y, $z)
{
	global $Userid, $myrow, $sys_result, $msgred, $msggreen, $myx, $myy, $myz, $mysysid;

	if($assignment == "Attacking")
	{
		//Select target info
		$sys_request = mysql_query("SELECT * FROM uwar_systems WHERE x='$x' AND y='$y'");
		$sys_result = mysql_fetch_array($sys_request);
		$sysid = $sys_result["id"];
		$target_request = mysql_query("SELECT * FROM uwar_users WHERE sysid='$sysid' AND z='$z'");
		$target = mysql_fetch_array($target_request);
		$targetid = $target['id'];
		$cost = CalcTravelCost($fleet, $sys_result);
		if (mysql_num_rows($sys_request)>0 && mysql_num_rows($target_request)>0)
		{
//			print "Launch cost = ".$cost."<br>";
		if ($myrow["helium"] >= $cost)
		{
			//Check so the user doesn't try to attack/defend himself
			if($targetid != $Userid)
			{
				//Check if the user has the chosen fleet at home
				$checkfleet = mysql_query("SELECT * FROM uwar_tships WHERE fleetnum='$fleet' AND eta > 0 AND action!='h' AND userid='$Userid'");
				if(mysql_num_rows($checkfleet) < 1)
				{
					//Check if the user has any ships in the fleet
					$checkfleet = mysql_query("SELECT * FROM uwar_fships WHERE fleetnum='$fleet' AND amount > 0 AND userid='$Userid'");
					if(mysql_num_rows($checkfleet) > 0)
					{
						//Check so the target is outside own system
						if($target["sysid"] != $mysysid)
						{
							//Check if the target is sleeping
							if ($target["sleep"] == 0)
							{
								//Checks if the target is in vacation
								if ($target["vacation"] == 0)
								{
									if ($target["protection"] == 0)
									{
										if ($target["closed"] == 0)
										{
											if (0.3 * $myrow["score"] < $target["score"])
											{
												$sys_request = mysql_query("SELECT x,y FROM uwar_systems WHERE id='$mysysid'");
												$sys_result2 = mysql_fetch_array($sys_request);
												$coordsSQL = mysql_query("SELECT * FROM uwar_systems WHERE id='$target[sysid]'");
												$coords = mysql_fetch_array($coordsSQL);
												$eta = CalcTravelTime($fleet, $coords);
												//Launch
												mysql_query("UPDATE uwar_tships SET eta='$eta', r_eta='$eta', action='a', targetid='$targetid',  howlong='$howlong' WHERE userid='$Userid' AND fleetnum='$fleet'") or die("Couldn't insert to database!");
												mysql_query("UPDATE uwar_users SET helium=helium-$cost WHERE id='$Userid'");
												$msggreen = "Fleet(s) is/are now launched to attack!";
												$subject = "Hostiles incoming";
												$news = "Hostile incoming fleet from commander $myrow[nick] of $myrow[planet] ($sys_result2[x]:$sys_result2[y]:$myrow[z]) is attacking us,eta $eta.";
												$subject2 = "Hostile Outgoing";
												$news2 = "We have sent a hostile fleet at $target[nick] of $target[planet] ($sys_result[x]:$sys_result[y]:$target[z]), eta $eta.";
												add_news($subject,$news,$target["id"]);
												add_news($subject2,$news2,$Userid);
												Logging("Military - Hostile", $news2, $Userid, $target["id"]);
												
											}
											else $msgred = "Score Limit - You cannot attack commanders that are smaller than 30% of your score!";
										}
										else $msgred = "Account Closed - This Planet is under investigation of Universal Lords and cannot be attacked!";
									}
									else $msgred = "Target is in the Universal Lords Protection!";
								}
								else $msgred = "Vacation Mode - You cannot attack commanders that are on vacation!";
							}
							else $msgred = "Sleep Mode - You cannot attack commanders that are sleeping!";
						}
						else $msgred = "Your own system - You cannot attack commanders from your own system!";
					}
					else $msgred = "Fleet $fleet is empty of ships!";
				}
				else $msgred = "Fleet $fleet is currently not at home!";
			}
			else $msgred = "You cannot attack yourself!";
		}
		else $msgred = "You do not have enough fuel (caesium) to launch that fleet.";
	}
	else $msgred = "Invalid coordinates!";
	}
		


	else if($assignment == "Defending")
	{
		//Get target info
		$sys_request = mysql_query("SELECT * FROM uwar_systems WHERE x='$x' AND y='$y'");
		$sys_result = mysql_fetch_array($sys_request);
		$sysid = $sys_result["id"];
		$target_request = mysql_query("SELECT * FROM uwar_users WHERE sysid='$sysid' AND z='$z'");
		$target = mysql_fetch_array($target_request);
		$targetid = $target['id'];
		$cost = CalcTravelCost($fleet, $sys_result);
		if (mysql_num_rows($sys_request) > 0 && mysql_num_rows($target_request) > 0)
		{
		if ($myrow["helium"] >= $cost)
		{
			//Check so the user doesn't try to attack/defend himself
			if($targetid != $Userid)
			{
				//Check if the user has the chosen fleet at home
				$checkfleet = mysql_query("SELECT * FROM uwar_tships WHERE fleetnum='$fleet' AND eta > 0 AND userid='$Userid'");
				if(mysql_num_rows($checkfleet) < 1)
				{
					//Check if the user has any ships in the fleet
					$checkfleet = mysql_query("SELECT * FROM uwar_fships WHERE fleetnum='$fleet' AND amount > 0 AND userid='$Userid'");
					if(mysql_num_rows($checkfleet) > 0)
					{
						$eta = CalcTravelTime($fleet, $sys_result);
						$sys_request = mysql_query("SELECT x,y FROM uwar_systems WHERE id='$mysysid'");
						$sys_result2 = mysql_fetch_array($sys_request);
						//Launch fleet
						mysql_query("UPDATE uwar_tships SET eta='$eta', r_eta='$eta', action='d', targetid='$targetid', howlong='$howlong' WHERE userid='$Userid' AND fleetnum='$fleet'") or die("Couldn't insert to database!");
						$msggreen = "Fleet(s) is/are now launched to defend!";

						//deduct cost
						$cost = CalcTravelCost($fleet, $sys_result);
						mysql_query("UPDATE uwar_users SET helium=helium-$cost WHERE id='$Userid'");

						//send news
						$subject = "Friendly incoming";
						$news = "Friendly incoming fleet from commander $myrow[nick] of $myrow[planet]  ($sys_result2[x]:$sys_result2[y]:$myrow[z]) is defending us,eta $eta.";
						$subject2 = "Friendly Outgoing";
						$news2 = "We have sent a friendly fleet at $target[nick] of $target[planet] ($sys_result[x]:$sys_result[y]:$target[z]), eta $eta.";
						Logging("Military - Friendly", $news2, $Userid, $target["id"]);
						add_news($subject,$news,$target["id"]);
						add_news($subject2,$news2,$Userid);
					}
					else $msgred = "Fleet $fleet is empty!";
				}
				else $msgred = "Fleet $fleet is currently not at home!";
			}
			else $msgred = "You cannot defend yourself!";
		}
		else $msgred = "You don't have enough fuel (caesium) to launch that fleet!";
	}
	else $msgred = "Invalid coordinate!";
	}

	elseif ($assignment == "Recalling")
	{
		//Check if the user has the chosen fleet at home already
		$checkfleet = mysql_query("SELECT * FROM uwar_tships WHERE fleetnum='$fleet' AND action='h' AND userid='$Userid'");
		if(mysql_num_rows($checkfleet) == 0)
		{
			//Check if the user's chosen fleet already is recalling
			$checkfleet = mysql_query("SELECT * FROM uwar_tships WHERE fleetnum='$fleet' AND action='r' AND userid='$Userid'");
			if(mysql_num_rows($checkfleet) == 0)
			{
				$selectetaSQL = mysql_query("SELECT eta, r_eta, targetid FROM uwar_tships WHERE fleetnum='$fleet' AND userid='$Userid'");
				$selecteta = mysql_fetch_array($selectetaSQL);
				$etapassed = $selecteta["eta"];
				//Recall
				$target_request = mysql_query("SELECT * FROM uwar_users WHERE id='$selecteta[targetid]'");
				$user = mysql_fetch_array($target_request);
				$targetSQL = mysql_query("SELECT * FROM uwar_systems WHERE id='$user[sysid]'");
				$target = mysql_fetch_array($targetSQL);

				$eta = $selecteta["r_eta"] - $etapassed;
				if ($eta > 0)
				{
					mysql_query("UPDATE uwar_tships SET action='r', howlong=0, targetid='0', eta='$eta', r_eta=0 WHERE userid='$Userid' AND fleetnum='$fleet'") or die("Couldn't update database!");
				}
				elseif($eta == 0)
				{
					mysql_query("UPDATE uwar_tships SET action='h', howlong=0, targetid=0, eta=0, r_eta=0 WHERE userid='$Userid' AND fleetnum='$fleet'") or die("Couldn't update database!");
				}

				$targetSQL = mysql_query("SELECT * FROM uwar_users WHERE id='$selecteta[targetid]'");
				$target2 = mysql_fetch_array($targetSQL);
				$news = "Commander ".$myrow["nick"]." of ".$myrow["planet"]." ($sys_result[x]:$sys_result[y]:$myrow[z]) recalled a fleet from our planet.";
				add_news("Fleet Recalled", $news, $selecteta["targetid"]);
				$news2 = "Recalled a fleet from ".$target2["nick"]." of ".$target2["planet"];
				add_news("Fleet Recalled", $news2, $myrow["id"]);
				Logging("Military - Recall", $news2, $Userid, $target["id"]);

				$msggreen = "Fleet $fleet is now recalling from target!";
			}
			else $msgred = "Fleet $fleet is already recalling!";
		}
		else $msgred = "Fleet $fleet is already at home!";
	}
	else $msgred = "Invalid action!";
}

//SHIP MOVEMENT
if($action == "restruct" && ( array_count_values ($amount) || array_key_exists ("allships", $shiptype)) )
{
	//Moving ships loop
	for($x=1; $x <= 5; $x++)
	{
		if( ( is_numeric( $amount[$x] ) || $shiptype[$x]) )
		{
			if( $from_fleet[$x] == "base" ) $from_fleet[$x] = 0;
			if( $from_fleet[$x] == "base" ) $from_fleet[$x] = 0;

			$move = mysql_query("SELECT * FROM uwar_tships WHERE userid='$Userid' AND (fleetnum='$from_fleet[$x]' OR fleetnum='$to_fleet[$x]') AND action !='h'");

			if( !mysql_num_rows( $move ) )
			{
				MoveShips($shiptype[$x], $from_fleet[$x], $to_fleet[$x], $amount[$x]);
			}
			else $msgred = "You cannot move ships between fleets that are not both home!";
		}
	}
}

if(($action == "assignment" && ($x1 && $y1 && $z1) || ($x2 && $y2 && $z2) || ($x3 && $y3 && $z3)) || ($action == "assignment" && ($assignment1 == "recall" || $assignment2 == "recall" || $assignment3 == "recall")))
{
	//Fleet 1
	if(($assignment1) != "Select Assignment")
	{
		if($assignment1 == "recall") $assignment = "Recalling";
		elseif(eregi("a", $assignment1)) $assignment = "Attacking";
		else if(eregi("d", $assignment1)) $assignment = "Defending";
		$fleet = 1;
		$ticks = $assignment1[1];
		if($action == "recall") $ticks = 0;
		$howlong = $ticks;
		LaunchFleet($fleet, $assignment, $howlong, $ticks, $x1, $y1, $z1);
	}
	//Fleet 2
	if(($assignment2) != "Select Assignment")
	{
		if($assignment2 == "recall") $assignment = "Recalling";
		elseif(eregi("a", $assignment2)) $assignment = "Attacking";
		else if(eregi("d", $assignment2)) $assignment = "Defending";
		$fleet = 2;
		$ticks = $assignment2[1];
		if($action == "recall") $ticks = 0;
		$howlong = $ticks;
		LaunchFleet($fleet, $assignment, $howlong, $ticks, $x2, $y2, $z2);
	}
	//Fleet 3
	if(($assignment3) != "Select Assignment")
	{
		if($assignment3 == "recall") $assignment = "Recalling";
		elseif(eregi("a", $assignment3)) $assignment = "Attacking";
		else if(eregi("d", $assignment3)) $assignment = "Defending";
		$fleet = 3;
		$ticks = $assignment3[1];
		if($action == "recall") $ticks = 0;
		$howlong = $ticks;
		LaunchFleet($fleet, $assignment, $howlong, $ticks, $x3, $y3, $z3);
	}
}

if(isset($msggreen))
	print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";
if(isset($msgred))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";


headerDsp("Fleet Composition");
?>
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<tr>
	<td bgcolor="<?=$tdbg1;?>">Ship type</td>
	<td bgcolor="<?=$tdbg1;?>">Base</td>
	<td bgcolor="<?=$tdbg1;?>">Fleet 1</td>
	<td bgcolor="<?=$tdbg1;?>">Fleet 2</td>
   	<td bgcolor="<?=$tdbg1;?>">Fleet 3</td>
</tr>
<?

//PRINTS THE SHIPS
$request = mysql_query("SELECT shipid FROM uwar_fships WHERE amount > 0 AND userid='$Userid' AND ops='n' ORDER by shipid");
for($i=0; $ship = mysql_fetch_array($request); $i++)
{
	if($ship["shipid"] == $shipid) continue;
	$shipid = $ship["shipid"];
	if ($shipid >= $maxShipID || $ShipTypes[$shipid]["Special"] == "OPS") continue;

	?><tr>
	<td bgcolor="<?=$tdbg2;?>"><? print $ShipTypes[$shipid]["Name"]."s"?></td><?

	for($fleetnum = 0; $fleetnum < 4; $fleetnum++)
	{
		$request2 = mysql_query("SELECT * FROM uwar_fships WHERE shipid='$shipid' AND userid='$Userid' AND ops='n' AND amount > 0 AND fleetnum = '$fleetnum' LIMIT 0,1");
		$result2 = mysql_fetch_array($request2);
		if($result2["amount"] == 0) $result2["amount"] = '';
		if($result2["fleetnum"] == $fleetnum) { ?><td bgcolor="<?=$tdbg2;?>"><?=number_format($result2["amount"],0,".",".")?></td><? }
			else { ?><td bgcolor="<?=$tdbg2;?>"></td><? }
	}
	?></tr><?
}
?></table>
</center>
<br>
<?
footerDsp();


headerDsp("Travel Time & Cost");

?>
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<tr>
	<td bgcolor="<?=$tdbg1;?>">Fleet</td><td bgcolor="<?=$tdbg1;?>">System</td><td bgcolor="<?=$tdbg1;?>">Sector</td><td bgcolor="<?=$tdbg1;?>">Universe</td>
</tr>
<?
//$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'") or die(mysql_error());
//$myrow = mysql_fetch_array($request);
$targetSQL = mysql_query("SELECT * FROM uwar_systems WHERE id='$myrow[sysid]'") or print(mysql_error());
$target = mysql_fetch_array($targetSQL);
for ($fleet = 1; $fleet<=3; $fleet++)
{ 
	 //system
	 $TravelTime = CalcTravelTime ($fleet, $target); 
	 $TravelCost = CalcTravelCost ($fleet, $myrow["sysid"]);
	 $SystemCost = round($TravelCost * 0.5);
	 //sector
	 //print "travel time = ".$TravelTime."<br>";
	 $SectorTime = $TravelTime + 4;
	 $SectorCost = round($TravelCost * 0.75);	 
	 //universe
	 $UniverseTime = $SectorTime + 1;
	 $UniverseCost = $TravelCost;

	 if ($TravelTime < 0) $TravelTime = 0;
	 if ($SectorTime < 0) $SectorTime = 0;
	 if ($UniverseTime < 0) $UniverseTime = 0;
	 ?>
<? if ($SectorCost>0) { ?>
	<tr>
	<td bgcolor="<?=$tdbg2;?>">Fleet <?=$fleet;?> (Time)</td>
	<td bgcolor="<?=$tdbg2;?>"><?=$TravelTime;?></td>
	<td bgcolor="<?=$tdbg2;?>"><?=$SectorTime;?></td>
	<td bgcolor="<?=$tdbg2;?>"><?=$UniverseTime;?></td>
	</tr>

	<tr>
	<td bgcolor="<?=$tdbg2;?>">Fleet <?=$fleet;?> (Cost)</td>
	<td bgcolor="<?=$tdbg2;?>"><?=number_format($SystemCost);?></td>
	</td><td bgcolor="<?=$tdbg2;?>"><?=number_format($SectorCost);?></td>
	</td><td bgcolor="<?=$tdbg2;?>"><?=number_format($UniverseCost);?></td>
	</tr>
<? } ?>
<? 
}
?>
	

</table>
<br>

<?
footerDsp();


headerDsp("Fleet Structure");
?>

<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<form action="<?=$PHP_SELF?>?action=restruct" method="post">
<tr>
	<td bgcolor="<?=$tdbg1;?>">Shiptype</td>
	<td bgcolor="<?=$tdbg1;?>">Amount</td>
	<td bgcolor="<?=$tdbg1;?>">From fleet</td>
	<td bgcolor="<?=$tdbg1;?>">To fleet</td>
</tr>
<?
for($x = 1; $x <= 5; $x++)
{
?>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">
			<select name="shiptype[<?=$x?>]">
			<option value="allships">All ships</option>
			<?
				$lastid = -1;
				$request = mysql_query("SELECT shipid FROM uwar_fships WHERE amount > 0 AND userid='$Userid' ORDER by shipid");
				for($i=0; $ship = mysql_fetch_array($request); $i++)
				{
					if($lastid == $ship["shipid"]) continue;
					$lastid = $ship["shipid"];
					if ($lastid >= $maxShipID) continue;
					print "<option value=".$ship["shipid"].">".$ShipTypes[$ship[shipid]]["Name"]."s</option>";
				}

			?>
			</select>
		</td>
		<td bgcolor="<?=$tdbg2;?>"><input type="text" name="amount[<?=$x?>]" size="10" maxlength="20"></td>
		<td bgcolor="<?=$tdbg2;?>">
			<select name="from_fleet[<?=$x?>]">
			<option value="base">Base</option>
			<option value="1">Fleet 1</option>
			<option value="2">Fleet 2</option>
			<option value="3">Fleet 3</option>
			</select>
		</td>
		<td bgcolor="<?=$tdbg2;?>">
			<select name="to_fleet[<?=$x?>]">
			<option value="base">Base</option>
			<option value="1">Fleet 1</option>
			<option value="2">Fleet 2</option>
			<option value="3">Fleet 3</option>
			</select>
		</td>
	</tr>
<? } ?>
<tr>
	<td bgcolor="<?=$tdbg2;?>" colspan="4" align="center"><input type="submit" name="restruct" value="Rearrange fleet"></td>
</tr>
</form>
</table>
</center>
<br>
<?
footerDsp();


headerDsp("Fleet Assignments")
?>
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<form action="<?=$PHP_SELF?>?action=assignment" method="post">
<tr>
	<td bgcolor="<?=$tdbg1;?>">Fleet</td>
	<td bgcolor="<?=$tdbg1;?>">Assignment</td>
	<td bgcolor="<?=$tdbg1;?>">Coordinates</td>
</tr>
<?
for($i = 1; $i <= 3; $i++)
{
	?>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" width="1%" align="center"><?=$i?></td>
		<td bgcolor="<?=$tdbg2;?>">
			<select name="assignment<?=$i?>">
			<option>Select Assignment</option>
			<option value="recall">Recall</option>
			<option value="a1">Attack 1 tick</option>
			<option value="a2">Attack 2 ticks</option>
			<option value="a3">Attack 3 ticks</option>
			<option value="d1">Defend 1 tick</option>
			<option value="d2">Defend 2 ticks</option>
			<option value="d3">Defend 3 ticks</option>
			<option value="d4">Defend 4 ticks</option>
			<option value="d5">Defend 5 ticks</option>
			<option value="d6">Defend 6 ticks</option>
			</select>
			<?
			$request = mysql_query("SELECT * FROM uwar_tships WHERE fleetnum='$i' AND userid='$Userid'");
			$result = mysql_fetch_array($request);
			$targetid = $result["targetid"];
			$target_request = mysql_query("SELECT sysid,z FROM uwar_users WHERE id='$targetid'");
			$target_result = mysql_fetch_array($target_request);
			$sysid = $target_result["sysid"];
			$xy_request = mysql_query("SELECT x,y FROM uwar_systems WHERE id='$sysid'");
			$xy_result = mysql_fetch_array($xy_request);

			$x = $xy_result["x"];
			$y = $xy_result["y"];
			$z = $target_result["z"];


			if($result["action"] == "h") print "&nbsp;&nbsp;Defending homeworld";
			if($result["action"] == "a" )
				print "&nbsp;&nbsp; Attacking (".$x.":".$y.":".$z.") for ".$result["howlong"]." ticks - ETA ".$result["eta"];
			if ($result["action"] == "d")
				print "&nbsp;&nbsp; Defending (".$x.":".$y.":".$z.") for ".$result["howlong"]." ticks - ETA ".$result["eta"];
			if($result["action"] == "r")
				print "&nbsp;&nbsp;Returning home eta $result[eta]" ;

			?>
		</td>
		<td bgcolor="<?=$tdbg2;?>">
			<input type="text" name="x<?=$i?>" size="2" maxlength="3"> :
			<input type="text" name="y<?=$i?>" size="2" maxlength="2"> :
			<input type="text" name="z<?=$i?>" size="2" maxlength="2">
		</td>
	</tr>
	<?
}
?>
	<tr>
		<td bgcolor="<?=$tdbg2;?>" colspan="4" align="center"><input type="submit" name="assignment" value="Confirm Assignments"></td>
	</tr>
</form>
</table>
<br>
<?
footerDsp();


include("footer.php");
?>