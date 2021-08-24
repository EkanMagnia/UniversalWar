<?
#####################################################################################################
#	This file includes the ticker of Universal War.It is used to run the time in the game and		#
#	includes:																						#
#	* ticker setting																				#
#	* laboratories update																			#
#	* tech update																					#
#	- ships and ops update																			#
#	* intel update																					#
#	- fleets moving																					#
#	- modes update																					#
#	- scores calculation																			#
#	- planet,system,sector and alliance rankings													#
#	- resources giving to commanders																#
#	- battle system																					#
#																									#
#	All rights reserved to Xperience. Created by Renex and Ymer										#
#																									#
#####################################################################################################

/* things required in ticker

	11. planet score and rank calculation
	12. system score and rank calculation
	13. alliance score and rank calculation
	15. delete account
	16. ticker refresh
*/

//	1. ticker settings
include("logindb.php");
$request = mysql_query("SELECT pause,tickertime,tickerpass FROM uwar_modes WHERE id='1'",$db);
$ticker = mysql_fetch_array($request);
$tickertime = $ticker["tickertime"];
echo $ticker["pause"];
if ($ticker["pause"] == 0) print "Ticker is currently stopped";
else
{

$tickertime = $ticker["tickertime"];
$pw = $ticker["tickerpass"];
//$pw = "x";
//if ($password != $pw) die("Password error!");
ob_start();
include("functions.php");
include("data/intel.php");
include("data/construction.php");
include("data/ShipTypes.php");
include("BCcode.php");

if ($tickdif < $tickertime-1 && $override!="true") die("Less than $tickertime seconds since last tick!");

$filename = "data/ticker.dat";
$content = time();
$time1 = time();
echo time()."!";

if(is_writable ($filename))
{
	if (!$handle = fopen($filename, 'w')) {
		echo "Cannot open file ($filename!)"; }

	if (!fwrite($handle, $content)) {
        print "Cannot write to file ($filename)"; }
	fclose($handle);
}

Function dbr($txt) { global $dbr; $dbr = $dbr.$txt; }
$dbr = "";

$ticksql = mysql_query("SELECT number FROM uwar_tick WHERE id='1'");
$tickarray = mysql_fetch_array($ticksql);
$tick = $tickarray["number"]+1;

// reset the score array
$scores = array();

//2. News time decreasing and delete news + user modes (sleep,vacation etc.)
mysql_query("UPDATE uwar_users SET sleep=sleep-1 WHERE sleep>0",$db);
mysql_query("UPDATE uwar_users SET lastsleep=lastsleep-1 WHERE lastsleep>0",$db);
mysql_query("UPDATE uwar_users SET vacation=vacation-1 WHERE vacation>0",$db);
mysql_query("UPDATE uwar_users SET deletemode=deletemode-1 WHERE deletemode>1",$db);
mysql_query("UPDATE uwar_users SET protection=protection-1 WHERE protection>0",$db);
mysql_query("UPDATE uwar_news SET deletetime=deletetime-1 WHERE deletetime>0",$db);
mysql_query("DELETE FROM uwar_news WHERE deletetime=0",$db);
//mysql_query("UPDATE uwar_users SET inactivity=inactivity+1"),$db);
//mysql_query("UPDATE uwar_users SET signupinactivity=signupinactivity+1"),$db);

//3. Delete account
$deleteSQL = mysql_query("SELECT * FROM uwar_users WHERE deletemode=1");
while ($delete = mysql_Fetch_Array($deleteSQL))
{
	$id = $delete["id"];
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
	mysql_query("DELETE FROM uwar_users WHERE userid='$id'",$db);
}


//4. labs/tech update,ETA decrease,enabling ships,ops,scans

//labs/tech update eta of the construction in progress
mysql_query("UPDATE uwar_constructions SET eta=eta-1 WHERE eta>1",$db);
$sql = mysql_query("SELECT * FROM uwar_constructions WHERE eta=1");
if (mysql_num_rows($sql) > 0)
{
    while ($arr = mysql_fetch_array($sql)) 
	{
        mysql_query("UPDATE uwar_constructions SET eta=0, complete=complete+1, activated=0 WHERE eta=1");

		$count = $arr["constructionid"];
		$completed = $arr["complete"];
		$complete = $completed + 1;
		if ($Con[$count][$completed]["Type"] =="c") 
		{
			$subject = "Laboratory completed";
			$news = "Our constructors have completed the construction of ".$Con[$count][$completed]["Name"]." at tick $tick.";
			Add_News($subject, $news, $arr["userid"]); 
		}
		else
		{
			$subject = "Technology completed";
			$news = "Our scientists have completed the research of ".$Con[$count][$completed]["Name"]." at tick $tick.";
			Add_News($subject, $news, $arr["userid"]); 
		}

		$Userid = $arr["userid"];
		//enable new scans
		if ($count == "1")
		{
			foreach ( $Scans as $idx => $scan ) 
			{
				if ($scan[$idx]["complete"] == $complete)
				{
					$scanid = $scan[$idx]["Scanid"];
					mysql_query("INSERT INTO uwar_scans (userid,scanid) VALUES ($Userid, '$scanid')") or die(mysql_error());
				}
			}
		}


		if ($count == "3" or $count == "4")
		{
			foreach ($ShipTypes as $idx => $ship ) 
			{
				//enable ships
				if ($ship[$idx]["Conid"] == "4")
				{
					if ($ship[$idx]["complete"] == $complete)
					{
						$shipid = $ship[$idx]["Shipid"];
						mysql_query("INSERT INTO uwar_fships (userid, shipid, fleetnum, ops) VALUES ('$arr[userid], '$shipid', 0, 'n')");
					}
				}
				elseif ($ship[$idx]["Conid"] == "3")
				{
					if ($ship[$idx]["complete"] == $arr["complete"]+1)
					{
						$shipid = $ship[$idx]["Shipid"];
						mysql_query("INSERT INTO uwar_fships (userid, shipid, fleetnum, ops) VALUES ('$arr[userid], '$shipid', 0, 'y')");
					}
				}
			}
		}
		}
	} //end labs/tech thing



// 5. ships & ops production

$sql3 = mysql_query("SELECT * FROM uwar_pships WHERE eta=1");
while ($row = mysql_fetch_array($sql3))
{
	$request = mysql_query("SELECT * FROM uwar_fships WHERE shipid='$row[shipid]' AND userid='$row[userid]' AND fleetnum=0",$db);
	if ($row["shipid"] < 15) $ops = 'n';
	else $ops='y';

if (mysql_num_rows($request)>0)
	{
		mysql_query("UPDATE uwar_fships SET amount=amount+$row[amount] WHERE userid='$row[userid]' AND shipid='$row[shipid]' AND fleetnum=0");
	}

else   mysql_query("INSERT INTO uwar_fships (userid,shipid,amount,fleetnum,ops) VALUES ('$row[userid]','$row[shipid]','$row[amount]',0,'$ops')");
 mysql_query("UPDATE uwar_pships SET eta=0 WHERE eta=1");
}
mysql_query("DELETE FROM uwar_pships WHERE eta=0");
mysql_query("UPDATE uwar_pships SET eta=eta-1 where eta > 1",$db);


// 5. scans production
$sql3 = mysql_query("SELECT * FROM uwar_pscans WHERE eta=1");
while ($row = mysql_fetch_array($sql3))
{
	$request = mysql_query("SELECT * FROM uwar_scans WHERE scanid='$row[scanid]' AND userid='$row[userid]'",$db);
if (mysql_num_rows($request)>0)
	{
		mysql_query("UPDATE uwar_scans SET stock=stock+$row[amount] WHERE userid='$row[userid]' AND scanid='$row[scanid]'");
	}

else   mysql_query("INSERT INTO uwar_scans (userid,scanid,stock) VALUES ('$row[userid]','$row[scanid]','$row[amount]')");
 mysql_query("UPDATE uwar_pscans SET eta=0 WHERE eta=1");
}
mysql_query("DELETE FROM uwar_pscans WHERE eta=0");
mysql_query("UPDATE uwar_pscans SET eta=eta-1 where eta > 1",$db);

// 6. resources giving
//select all the players accounts
$userSQL = mysql_query("SELECT id FROM uwar_users");
while($userArray = mysql_fetch_array($userSQL))
{
	$Userid = $userArray["id"];
	if ($usrArray["vacation"] == 0)
	{
		$ResourceSQL = mysql_query("SELECT mercury, cobalt, helium, asteroid_mercury, asteroid_cobalt, asteroid_helium FROM uwar_users WHERE id='$Userid'",$db);
		$Resource = mysql_fetch_array($ResourceSQL);

		//We should see what we already have, shouldn't we?
		$mercuryRoid = $Resource["asteroid_mercury"];
		$mercury = $Resource["mercury"];
		$cobaltRoid = $Resource["asteroid_cobalt"];
		$cobalt = $Resource["cobalt"];
		$heliumRoid = $Resource["asteroid_helium"];
		$helium = $Resource["helium"];
		//lets see what Santa gives us this tick (yummy)

		$ConSQL = mysql_query("SELECT * FROM uwar_constructions WHERE constructionid='0' and userid='$Userid'",$db);
		$con = mysql_fetch_array($ConSQL);
		if ($con["complete"] == 0) $planetmercury = 250;
		elseif ($con["complete"] >= 1 && $con["complete"] < 4) $planetmercury = 500;
		elseif ($con["complete"] >= 4 && $con["complete"] < 7) $planetmercury = 1750;
		elseif ($con["complete"] >= 7) $planetmercury = 3750;
		if ($userArray["commander"] == "1")
			$planetmercury += $planetmercury/10;
		elseif ($userArray["commander"] > 1) 
			$planetmercury += $planetmercury/20;
		$mercuryincome = $planetmercury + $mercuryRoid * 456;	
			
		if ($con["complete"] < 2)   $planetcobalt = 250;
		elseif ($con["complete"] >= 2 && $con["complete"] < 5) $planetcobalt = 500;
		elseif ($con["complete"] >= 5 && $con["complete"] < 8) $planetcobalt = 1750;
		elseif ($con["complete"] >= 8) $planetcobalt = 3750;
		if ($userArray["commander"] == "1")
			$planetcobalt += $planetcobalt/10;
		elseif ($userArray["commander"] > 1) 
			$planetcobalt += $planetcobalt/20;

		$cobaltincome = $planetcobalt + $cobaltRoid * 456;	
	
		if ($con["complete"] < 3)   $planethelium = 250;
		elseif ($con["complete"] >= 3 && $con["complete"] < 6) $planethelium = 500;
		elseif ($con["complete"] >= 6 && $con["complete"] < 9) $planethelium = 1750;
		elseif ($con["complete"] >= 9) $planethelium = 3750;
		if ($userArray["commander"] == "1")
			$planethelium += $planethelium/10;
		elseif ($userArray["commander"] > 1) 
			$planethelium += $planethelium/20;

		$heliumincome = $planethelium + $heliumRoid * 300;	

		//Dammit! Maths :p
		// calculate totals
		$Totalm = $mercury + $mercuryincome;
		$Totalc = $cobalt + $cobaltincome;
		$Totalh = $helium + $heliumincome;
		mysql_query("UPDATE uwar_users SET mercury='$Totalm', cobalt='$Totalc', helium='$Totalh' WHERE id='$Userid'",$db);
	}
}	


// 7. Battle System
#LETS START THE WAR
#WHAT WAR???
#UNIVERSAL WAR
#first move armies
$fleets = array();                   // $fleet[userid] = $Fleet (in bcalc format) $Fleet[0(attacker);1(defender)]["Ships";"Totals"][shiptype]["Amount" and such stuff] = number
// bla
$incomers = array();
$ziks=array();
$query = mysql_query("UPDATE uwar_tships SET eta=eta-1 WHERE eta>0");

// now check what we need for fighting -......-
$result = mysql_query("SELECT userid, fleetnum, action, targetid FROM uwar_tships WHERE eta=0 AND (action='d' OR action='a') AND howlong>0");

while ($arr = mysql_fetch_array($result)) {
    if ($arr["action"]=="d")
        $a0_d1 = 1;
    else
        $a0_d1 = 0;
    // store all attackers / defenders
    if (isset($incomers[$arr['targetid']][$a0_d1][0]))
        array_push($incomers[$arr['targetid']][$a0_d1],$arr['userid']);
    else
        $incomers[$arr['targetid']][$a0_d1]=array($arr['userid']);

    $query = "SELECT shipid, amount FROM uwar_fships WHERE userid=".$arr['userid']." AND fleetnum=".$arr['fleetnum']."";
    $innerresult = mysql_query($query);
    while ($row = mysql_fetch_row($innerresult)) {
    	$fleets[$arr['targetid']][$a0_d1]["Ships"][$row[0]]["BeginAmount"] += $row[1];
    	$fleets[$arr['targetid']][$a0_d1]["Ships"][$row[0]]["Amount"] += $row[1];
    	$fleets[$arr['targetid']][$a0_d1]["Ships"][$row[0]]["TickAmount"] += $row[1];
        if ($ShipTypes[$row[0]]["Special"]=="Steal") {
            if (isset($ziks[$arr['targetid']][$a0_d1]["TotalWorth"]))
                $ziks[$arr['targetid']][$a0_d1]["TotalWorth"] += ( $ShipTypes[$row[0]]["Worth"] * $row[1] );
            else
                $ziks[$arr['targetid']][$a0_d1]["TotalWorth"] = ( $ShipTypes[$row[0]]["Worth"] * $row[1] );

            if (isset($ziks[$arr['targetid']][$a0_d1][$arr['userid']]["StealWorth"]))
                $ziks[$arr['targetid']][$a0_d1][$arr['userid']]["StealWorth"] += ( $ShipTypes[$row[0]]["Worth"] * $row[1] );
            else
                $ziks[$arr['targetid']][$a0_d1][$arr['userid']]["StealWorth"] = ( $ShipTypes[$row[0]]["Worth"] * $row[1] );
        }

    }
}
// chcek all attacked planets now
foreach($fleets as $tid => $Fleet) {
    $a_incomers = array();        // $a_incomers[userid][shiptype]["before";"lost";"stunned";"stolen"] = number
    $a_pw = array();               // assizial array with ["userid"] = pod/fleetworth ratio
    $zik=$ziks[$tid];
    // add the fleet of the target planet -..- (fleets) (base) and pds)
    $query = "SELECT s.shipid, s.amount, s.fleetnum, s.userid FROM uwar_fships s, uwar_tships m WHERE ( ( m.userid=$tid AND s.fleetnum=m.fleetnum AND m.action='h' AND m.eta=0 ) OR ( s.fleetnum=0 AND s.ops='n' AND m.fleetnum=0 ) OR ( s.ops='y' AND m.fleetnum=0 ) ) AND s.userid=$tid AND m.userid=$tid" or die(mysql_error());
	$result = mysql_query($query);
    while ($row = mysql_fetch_row($result)) {
        if (isset($Fleet[1]["Ships"][$row[0]]["BeginAmount"]))
            $Fleet[1]["Ships"][$row[0]]["BeginAmount"] += $row[1];
        else
            $Fleet[1]["Ships"][$row[0]]["BeginAmount"] = $row[1];

        if (isset($Fleet[1]["Ships"][$row[0]]["Amount"]))
            $Fleet[1]["Ships"][$row[0]]["Amount"] += $row[1];
        else
            $Fleet[1]["Ships"][$row[0]]["Amount"] = $row[1];

        if (isset($Fleet[1]["Ships"][$row[0]]["TickAmount"]))
            $Fleet[1]["Ships"][$row[0]]["TickAmount"] += $row[1];
        else
            $Fleet[1]["Ships"][$row[0]]["TickAmount"] = $row[1];

        // stealing for home
        if ($ShipTypes[$row[0]]["Special"]=="Steal") {
            if (isset($zik[1]["TotalWorth"]))
                $zik[1]["TotalWorth"] += ( $ShipTypes[$row[0]]["Worth"] * $row[1] );
            else
                $zik[1]["TotalWorth"] = ( $ShipTypes[$row[0]]["Worth"] * $row[1] );

            if (isset($zik[1][$tid]["StealWorth"]))
                $zik[1][$tid]["StealWorth"] += ( $ShipTypes[$row[0]]["Worth"] * $row[1] );
            else
                $zik[1][$tid]["StealWorth"] = ( $ShipTypes[$row[0]]["Worth"] * $row[1] );
        }
    }

    // now add his roids
  	$query = mysql_query("SELECT asteroid_mercury, asteroid_cobalt, asteroid_helium, ui_roids FROM uwar_users WHERE id='$tid'",$db);
	$row = mysql_fetch_row($query);
    // defenders m roids
    $Fleet[1]["Ships"][20]["BeginAmount"] = $row[0];
    $Fleet[1]["Ships"][20]["Amount"] = $row[0];
    $Fleet[1]["Ships"][20]["TickAmount"] = $row[0];
    // defenders c roids
    $Fleet[1]["Ships"][21]["BeginAmount"] = $row[1];
    $Fleet[1]["Ships"][21]["Amount"] = $row[1];
    $Fleet[1]["Ships"][21]["TickAmount"] = $row[1];
    // defenders e roids
    $Fleet[1]["Ships"][22]["BeginAmount"] = $row[2];
    $Fleet[1]["Ships"][22]["Amount"] = $row[2];
    $Fleet[1]["Ships"][22]["TickAmount"] = $row[2];
    // defenders u roids
    $Fleet[1]["Ships"][23]["BeginAmount"] = $row[3];
    $Fleet[1]["Ships"][23]["Amount"] = $row[3];
    $Fleet[1]["Ships"][23]["TickAmount"] = $row[3];

    $total_ships = array();
     // that is adding each shiptypedata in each element the fleet
    foreach($Fleet as $a_d => $a_d_fleet) {
        $ships = $a_d_fleet["Ships"];
        foreach ($ships as $shipid => $shipdata) {
            $shipdata["Shipid"] = $ShipTypes[$shipid];    //<-- add it :)
            $Fleet[$a_d]["Ships"][$shipid] = $shipdata; //<-- and save :)
            // add for totals
            if (($ShipTypes[$shipid]["Special"]!="Roid") && ($shipdata["BeginAmount"] > 0))
                $total_ships[$a_d] += $shipdata["BeginAmount"];
        }
    }
    $Fleet[0]["Totals"]["TotalShips"]["Amount"] = $total_ships[0];
    $Fleet[1]["Totals"]["TotalShips"]["Amount"] = $total_ships[1];
// ATTTACCCKKKK :)
    MainLoop(1);

    // the changes of the incomers total fleet will be written in there
    $home_lost=array();
    $home_before=array();
    $home_stunned=array();
    $home_stolen=array();

 //start to update target's planet
    $query = "SELECT s.amount,s.shipid,s.fleetnum FROM uwar_fships s, uwar_tships m WHERE ( ( m.userid=$tid AND s.fleetnum=m.fleetnum AND m.action='h' AND m.eta=0 ) OR ( s.fleetnum=0 AND s.ops='n' AND m.fleetnum=0 ) OR ( s.ops='y' AND m.fleetnum=0 ) ) AND s.userid=$tid AND m.userid=$tid";
	$result = mysql_query($query);
    while ($row = mysql_fetch_row($result)) {
        if ($row[0]>0) {
            $lost = round( ( $Fleet[1]["Ships"][$row[1]]["BeginAmount"] - $Fleet[1]["Ships"][$row[1]]["Amount"] + $Fleet[0]["Ships"][$row[1]]["Stolen"] ) * ( $row[0] / $Fleet[1]["Ships"][$row[1]]["BeginAmount"] ) );

            if (isset($home[$row[1]]["stunned"]))
                $home[$row[1]]["stunned"] += round( $Fleet[1]["Ships"][$row[1]]["Stunned"] * $Fleet[1]["Ships"][$row[1]]["BeginAmount"] / $row[0] ) ;
            else
                $home[$row[1]]["stunned"] = round( $Fleet[1]["Ships"][$row[1]]["Stunned"] * $Fleet[1]["Ships"][$row[1]]["BeginAmount"] / $row[0] ) ;

            if (isset($home[$row[1]]["lost"]))
                $home[$row[1]]["lost"] += $lost;
            else
                $home[$row[1]]["lost"] = $lost;

            if (isset($home[$row[1]]["before"]))
                $home[$row[1]]["before"] += $row[0];
            else
                $home[$row[1]]["before"] = $row[0];
        }
        else
            $lost = 0;

        if ( isset($zik[1][$tid]["StealWorth"]) && ($zik[1][$tid]["StealWorth"]>0) && ($zik[1][$tid][$row[1]]!="stolen") ) {
            $zik[1][$tid][$row[1]]="stolen";
            $stolen = round( $Fleet[0]["Ships"][$row[1]]["Stolen"] * ( $zik[1][$tid]["StealWorth"] / $zik[1]["TotalWorth"] ) );
            $lost = $lost - $stolen;
            if (isset($home[$row[1]]["stolen"]))
                $home[$row[1]]["stolen"] += $stolen;
            else
                $home[$row[1]]["stolen"] = $stolen;
        }

        if ($lost<>0) {
            $inquery = "UPDATE uwar_fships SET amount=amount-$lost WHERE userid=$tid AND fleetnum=$row[2] AND eta=0 AND shipid=$row[1]";
            mysql_query($inquery);
        }
    }

    $roids_lost_m = - ( $Fleet[1]["Ships"][20]["BeginAmount"] - $Fleet[1]["Ships"][20]["Amount"] );
    $roids_lost_c = - ( $Fleet[1]["Ships"][21]["BeginAmount"] - $Fleet[1]["Ships"][21]["Amount"] );
    $roids_lost_e = - ( $Fleet[1]["Ships"][22]["BeginAmount"] - $Fleet[1]["Ships"][22]["Amount"] );
    $roids_lost_u = - ( $Fleet[1]["Ships"][23]["BeginAmount"] - $Fleet[1]["Ships"][23]["Amount"] );
    change_roids($tid,$roids_lost_m,$roids_lost_c,$roids_lost_e,$roids_lost_u,0,0,0,0);

	print "M roids:".$roids_lost_m."<br>";
	print "C roids:".$roids_lost_c."<br>";
	print "E roids:".$roids_lost_e."<br>";
	print "U roids:".$roids_lost_u."<br>";
	print "Target ID:".$tid."<br>";
    change_roids($tid,$roids_lost_m,$roids_lost_c,$roids_lost_e,$roids_lost_u,0,0,0,0);

	$subject = "Military Report";
	$news = format_combat($tid,$tid,$Fleet,$home);
	add_news($subject, $news, $tid);
	//end to update target's planet

    foreach($incomers[$tid] as $i_a_d => $incomerp) {
        $incomerp = array_unique($incomerp);
        foreach($incomerp as $iid) {
            $query = "SELECT s.amount,s.shipid,s.fleetnum FROM uwar_tships m, uwar_fships s WHERE m.userid=$iid AND s.userid=$iid AND m.fleetnum=s.fleetnum AND (action='a' OR action='d') AND targetid=$tid AND m.eta=0";  
			$result = mysql_query($query);
            // the changes of the incomers total fleet will be written in there
            $incomer=array("before_worth" => 0);
            while ($row = mysql_fetch_row($result)) {
                if ($row[0]>0) {
                    $lost = round( ( $Fleet[$i_a_d]["Ships"][$row[1]]["BeginAmount"] - $Fleet[$i_a_d]["Ships"][$row[1]]["Amount"] + $Fleet[(1-$i_a_d)]["Ships"][$row[1]]["Stolen"] ) * ( $row[0] / $Fleet[$i_a_d]["Ships"][$row[1]]["BeginAmount"] ) );

                    if (isset($incomer[$row[1]]["lost"]))
                        $incomer[$row[1]]["lost"] += $lost;
                    else
                        $incomer[$row[1]]["lost"] = $lost;

                    if (isset($incomer[$row[1]]["before"]))
                        $incomer[$row[1]]["before"] += $row[0];
                    else
                        $incomer[$row[1]]["before"] = $row[0];

                    if (isset($incomer[$row[1]]["stunned"]))
                        $incomer[$row[1]]["stunned"] += round( $Fleet[$i_a_d]["Ships"][$row[1]]["Stunned"] * $row[0] / $Fleet[$i_a_d]["Ships"][$row[1]]["BeginAmount"] ) ;
                    else
                        $incomer[$row[1]]["stunned"] = round( $Fleet[$i_a_d]["Ships"][$row[1]]["Stunned"] * $row[0] /$Fleet[$i_a_d]["Ships"][$row[1]]["BeginAmount"] ) ;

                    $incomer["before_worth"] += $ShipTypes[$row[1]]["Worth"] * $row[0];
                }
                else
                    $lost = 0;

                if (isset($zik[$i_a_d][$iid]["StealWorth"]) && ($zik[$i_a_d][$iid]["StealWorth"]>0) && ($zik[$i_a_d][$iid][$row[1]]!="stolen")) {
                    $zik[$i_a_d][$iid][$row[1]]="stolen";
                    $stolen = round( $Fleet[(1-$i_a_d)]["Ships"][$row[1]]["Stolen"] * ( $zik[$i_a_d][$iid]["StealWorth"] / $zik[$i_a_d]["TotalWorth"] ) );
                    $lost = $lost - $stolen;
                    if (isset($incomer[$row[1]]["stolen"]))
                        $incomer[$row[1]]["stolen"] += $stolen;
                    else
                        $incomer[$row[1]]["stolen"] = $stolen;
                }

                if ($lost<>0) {
                    $inquery = "UPDATE uwar_fships SET amount=amount-$lost WHERE userid=$iid AND fleetnum=$row[2] AND eta=0 AND shipid=$row[1]";    // amount >0 taken out for stealing
                    mysql_query($inquery);
                }
            }
/*            if ($i_a_d==0) {
                $roids_gain_m = round( ( $Fleet[1]["Ships"][54]["BeginAmount"] - $Fleet[1]["Ships"][54]["Amount"] ) * ( $incomer_before_worth / $Fleet[0]["Totals"]["TotalShips"]["Worth"] ) ) ;
                $incomer_before[54] = $roids_gain_m;
                $roids_gain_c = round( ( $Fleet[1]["Ships"][55]["BeginAmount"] - $Fleet[1]["Ships"][55]["Amount"] ) * ( $incomer_before_worth / $Fleet[0]["Totals"]["TotalShips"]["Worth"] ) ) ;
                $incomer_before[55] = $roids_gain_c;
                $roids_gain_e = round( ( $Fleet[1]["Ships"][56]["BeginAmount"] - $Fleet[1]["Ships"][56]["Amount"] ) * ( $incomer_before_worth / $Fleet[0]["Totals"]["TotalShips"]["Worth"] ) ) ;
                $incomer_before[56] = $roids_gain_e;
                $roids_gain_u = round( ( $Fleet[1]["Ships"][57]["BeginAmount"] - $Fleet[1]["Ships"][57]["Amount"] ) * ( $incomer_before_worth / $Fleet[0]["Totals"]["TotalShips"]["Worth"] ) ) ;
                $incomer_before[57] = $roids_gain_u;
                change_roids($iid,$roids_gain_m,$roids_gain_c,$roids_gain_e,$roids_gain_u,0,0,0,0);
            }*/
            if ($i_a_d==1) 
			{
       			add_news("Military Report", format_combat($tid,0,$Fleet,$incomer), $iid);       
            }
            else {
                $a_incomers["$iid"] = $incomer;
                if ($incomer["before_worth"] > 0)
                    $a_pw["$iid"] = ( $incomer[2]["before"] - $incomer[2]["stunned"] ) / $incomer["before_worth"];
                else
                    $a_pw["$iid"] = 0;
            }
        }
    }
    asort($a_pw,SORT_NUMERIC);
    $total_gain[1] = -$roids_lost_m;
    $total_gain[2] = -$roids_lost_c;
    $total_gain[3] = -$roids_lost_e;
    $total_gain[4] = -$roids_lost_u;
    $total_gain[0] = $total_gain[1] + $total_gain[2] + $total_gain[3] + $total_gain[4];
    $total_attackers_worth = $Fleet[0]["Totals"]["TotalShips"]["Worth"];
    foreach ($a_pw as $key => $value) {
        if ($value > 0) {
            $act_gain = $total_gain[0] * ( $a_incomers[$key]["before_worth"] / $total_attackers_worth );
            $max_gain = $a_incomers[$key][2]["before"] - $a_incomers[$key][2]["stunned"];
            $tp_gain = min($act_gain, $max_gain);
            $pc_gain = $tp_gain / $total_gain[0];
            for ($rt=0;$rt<=4;$rt++) {
                $gain = round($total_gain[$rt] * $pc_gain);
                $total_gain[$rt] -= $gain;
                if ($rt>0) {
                    $user_gain[$rt] = $gain;
                    $a_incomers[$key][(19+$rt)]["stolen"] = $gain;
                }
            }
            change_roids($key,$user_gain[1],$user_gain[2],$user_gain[3],$user_gain[4],0,0,0,0);
        }
        $news_string=format_combat($tid,0,$Fleet,$a_incomers[$key]);

        // debugg
        $news_string.="\n<br><font size=-2>Paste this in case of bugs ";
        foreach ($a_pw as $dkey => $dvalue) {
            $news_string.="($dkey,$dvalue)";
        }
        $news_string.=" v:$value-ag:$act_gain-mg:$max_gain-tg:$total_gain[0]-taw:$total_attackers_worth";
        $news_string.="</font>";
        // end of debugg

        $total_attackers_worth -= $a_incomers[$key]["before_worth"];
		add_news("Military Report", $news_string, $key);
    }

}

//8. Move fleets
mysql_query("UPDATE uwar_tships SET howlong=howlong-1 WHERE eta=0 and action!='h'",$db);
$request = mysql_query("SELECT r_eta FROM uwar_tships WHERE (action='a' OR action='d') AND eta=0 AND howlong=0",$db);
$myrow = mysql_fetch_array($request);
$r_eta = $myrow["r_eta"];
mysql_query("UPDATE uwar_tships SET action='r', eta=$r_eta, targetid=0 WHERE (action='a' OR action='d') AND eta=0 AND howlong=0",$db);
mysql_query("UPDATE uwar_tships SET action='h' WHERE action='r' AND eta=0",$db);


/* 9. planet score calculation
score = ships[resources] * amount / 10 + scans[resources] * amount / 10 + roids * 2000 + resources / 25
*/
$request = mysql_query("SELECT * FROM uwar_users");
while ($myrow = mysql_fetch_array($request))
{
	$m_roids = $myrow["asteroid_mercury"];
	$c_roids = $myrow["asteroid_cobalt"];
	$h_roids = $myrow["asteroid_helium"];
	//resource score
	$resource = ($myrow["mercury"] + $myrow["cobalt"] + $myrow["helium"]) / 100;
	//roids score
	$roids = ($m_roids + $c_roids + $h_roids) * 2000;
	//ships score
	$Userid = $myrow["id"];
	$shipscore = 0;
	$shipsSQL = mysql_query("SELECT * FROM uwar_fships WHERE userid='$Userid' order by shipid");
	while ($ships = mysql_fetch_array($shipsSQL))
	{
		$shipid = $ships["shipid"];
		$shipscore += $ShipTypes[$shipid]["Worth"] * $ships["amount"];
 	}
	//Scans score

	$scanscore = 0;
	$scansSQL = mysql_query("SELECT * FROM uwar_scans WHERE userid='$Userid' order by scanid");
	while ($scan = mysql_fetch_array($scansSQL))
	{
		$Scanid = $scan["scanid"];
		$scanscore += $Scans[$Scanid]["ScoreTotal"] * $scan["amount"];
 	}
/*	print "Scan score:";
	print $scanscore."<br>";
	print "Ships score:";
	print $shipscore."<br>"; */
	//calculate totals
	$totalscore = $resource + $roids + $shipscore + $scanscore;
	if ($totalscore<0) $totalscore=0;
	$size = $m_roids + $c_roids + $h_roids + $myrow["ui_roids"];
	if ($size<0) $size=0;
	mysql_query("UPDATE uwar_users SET score='$totalscore', size='$size'  WHERE id='$Userid'");
}

//10. planet ranking
$RankSQL = mysql_query("SELECT * FROM uwar_users ORDER by score DESC");
$rank = 1;
while ($ranks = mysql_fetch_array($RankSQL))
{
	$id = $ranks["id"];
	mysql_query("UPDATE uwar_users SET rank='$rank' WHERE id='$id'");
	$rank++;
}

//11. system score
$sysSQL = mysql_query("SELECT * FROM uwar_systems ORDER by id");
while ($sys = mysqL_fetch_array($sysSQL))
{
	$sysid = $sys["id"];
	$sysscore = 0;
	$syssize = 0;
	$userSQL = mysql_query("SELECT * FROM uwar_users WHERE sysid='$sysid'");
	while ($user = mysql_fetch_array($userSQL))
	{
		$sysscore += $user["score"];
		$syssize += $user["size"];
	}
	mysql_query("UPDATE uwar_systems SET syssize='$syssize', sysscore='$sysscore' WHERE id='$sysid'");
}

//12. system ranking
$Rank2SQL = mysql_query("SELECT * FROM uwar_systems ORDER by sysscore DESC");
$rank = 1;
while ($ranks = mysql_fetch_array($Rank2SQL))
{
	$id = $ranks["id"];
	mysql_query("UPDATE uwar_systems SET sysrank='$rank' WHERE id='$id'");
	$rank++;
}

//13. alliance score
$allySQL = mysql_query("SELECT * FROM uwar_tags ORDER by id");
while ($tag = mysqL_fetch_array($allySQL))
{
	$tagid = $tag["id"];
	$allyscore = 0;
	$allysize = 0;
	$userSQL = mysql_query("SELECT * FROM uwar_users WHERE tagid='$tagid'");
	while ($user = mysql_fetch_array($userSQL))
	{
		$allyscore += $user["score"];
		$allysize += $user["size"];
	}
	mysql_query("UPDATE uwar_tags SET size='$allysize', score='$allyscore' WHERE id='$tagid'");
}

//14. alliance ranking
$Rank3SQL = mysql_query("SELECT * FROM uwar_tags ORDER by score DESC");
$rank = 1;
while ($ranks = mysql_fetch_array($Rank3SQL))
{
	$id = $ranks["id"];
	mysql_query("UPDATE uwar_tags SET allyrank='$rank' WHERE id='$id'");
	$rank++;
}

$resultT = mysql_query("SELECT number FROM uwar_tick WHERE id = '1'",$db);
$mr2 = mysql_fetch_array($resultT);
$counter = $mr2["number"] + 1;
$resultT = mysql_query("UPDATE uwar_tick SET number='".$counter."'",$db);
}

if ($os=="windows") echo "<meta HTTP-EQUIV=\"Refresh\" content=\"$tickertime\"><br>";
echo "Tick " . $counter . " done in ".(time()-$time1)." seconds.";
?>