<?
error_reporting(1);
ob_start();
include("logindb.php");

$Uwar["name"] = "Universal War";
$Uwar["party"] = "Beta Test";
$Uwar["version"] = "vs0.2";
$Uwar["image"] = "pp.jpg";
$Uwar["plassres"] = "true";
$Uwar["table"] = "uwar_users";
$Uwar["time"] = "+0";
$Uwar["galaxies"] = "uwar_galaxies";
$Uwar["newstable"] = "uwar_news";
$Uwar["mailtable"] = "uwar_mail";
$Uwar["alliance"] = "uwar_tags";
$Uwar["tick"] = "uwar_tick";
$Uwar["order"] = "uwar_order";
$Uwar["round"] = "The begin of the end";
$Uwar["happenings"] = "uwar_happenings";
$Uwar["logging"] = "uwar_logging";
$Uwar["systems"] = "uwar_systems";
$Uwar["sgovernment"] = "uwar_sgovernment";
$Uwar["agovernment"] = "uwar_agovernment";
$tablecookie = "uwar_users";
/*$deltime = "2160";
$sleepmode = "240";
$lastsleeptime = "480";
$vactime = "2160";
$deletenews = "2160";
*/
$tickrequest = mysql_query("SELECT tickertime FROM uwar_modes");
$tickresult = mysql_fetch_array($tickrequest);
$tickertime = $tickresult["tickertime"];
//X h * 3600 = the time in seconds. These seconds / $tickertime is the number of ticks
if($tickertime == 0) $tickertime = 1;
$modesSQL = mysql_query("SELECT * FROM uwar_modes WHERE id='1'");
$modes = mysql_fetch_array($modesSQL);
if ($modes["gametype"] == "F")
	$deltime = 120;
elseif ($modes["gametype"] == "S")
	$deltime = 24;
$Taxeta =  172800 / $tickertime;
$sleepmode = 28800 / $tickertime;
$lastsleeptime = 57600 / $tickertime;
$vactime = 96;
$deletenews = 259200 / $tickertime;


# $mode can be either "inet" or "LAN"
# NB! This currently only works with the user registration(not tags)
$mode = "INET";
$motd = "<font color=\"red\" size=\"2\">Welcome to the first beta round of Universal War v.02<br></font>";
"<font color=\"blue\" size=\"2\"><center>Vote for us at <a href=http://www.topwebgames.com/ >www.topwebgames.com</a></font></center>";

$fp = fopen("data/ticker.dat","r");
$ticktime = fread($fp,100);
fclose($fp);


function addShip ( $Ships, $Shipid, $Name, $Description, $BuildTime, $Mercury, $Cobalt, $Helium, $Type, $TechRequired, $LabRequired ) {
	$Ships = Array(
		"Shipid" => $Shipid,
		"Name" => $Name,
		"Description" => $Description,
		"BuildTime" => $BuildTime,
		"Mercury" => $Mercury,
		"Cobalt" => $Cobalt,
		"Helium" => $Helium,
		"Type" => $Type,
        "TechRequired" => $TechRequired,
		"LabRequired" => $LabRequired,
		"ScoreTotal" => ($Mercury+$Cobalt+$Helium)/10
	);
}

function CreateShipType($Ships, $Shipid, $Name, $Special, $ShipClass, $T1, $T2, $T3, $Init, $Agility, $WP_SP, $Guns, $GunPWR, $Armour, $EMP_res, $Fuel, $Travel, $Description, $BuildTime, $Cobalt, $Mercury, $Helium, $Conid, $Complete ) {
	$Ships = Array(
		"Shipid" => $Shipid,
		"Name" => $Name,
		"Special" => $Special,
		"ShipClass" => $ShipClass,
      	"Target1" => $T1,
       	"Target2" => $T2,
       	"Target3" => $T3,
       	"Init" => $Init,
       	"Agility" => $Agility,
       	"Weap_speed" => $WP_SP,
      	"Guns" => $Guns,
       	"Gunpower" => $GunPWR,
		"Armour" => $Armour,
       	"Emp_res" => $EMP_res,
       	"Fuel" => $Fuel,
       	"Travel" => $Travel,
		"Description" => $Description,
		"BuildTime" => $BuildTime,
		"Mercury" => $Mercury,
		"Cobalt" => $Cobalt,
		"Helium" => $Helium,
       	"Worth" => round(0.1*($Mercury + $Cobalt + $Helium)),
		"Conid" => $Conid,
		"Complete" => $Complete
	);
    }

function addOPS ( $OPS, $Opsid, $Name, $Description, $BuildTime, $Mercury, $Cobalt, $Helium, $TechRequired, $LabRequired ){
	$OPS = Array(
		"Opsid" => $Opsid,
		"Name" => $Name,
		"Description" => $Description,
		"BuildTime" => $BuildTime,
		"Mercury" => $Mercury,
		"Cobalt" => $Cobalt,
		"Helium" => $Helium,
        "TechRequired" => $TechRequired,
		"LabRequired" => $LabRequired,
		"ScoreTotal" => ($Mercury+$Cobalt+$Helium)/10
	);
}

function addScan ( $Scans, $Scanid, $Name, $Description, $BuildTime, $Mercury, $Cobalt, $Helium, $Conid, $Complete, $Type) {
	$Scans = Array(
		"Scanid" => $Scanid,
		"Name" => $Name,
		"Description" => $Description,
		"BuildTime" => $BuildTime,
		"Mercury" => $Mercury,
		"Cobalt" => $Cobalt,
		"Helium" => $Helium,
		"Conid" => $Conid,
		"Complete" => $Complete,
	    "ScoreTotal" => ($Mercury+$Cobalt+$Helium)/10,
		"Type" => $Type
	);
}

function addTech ( $Techs, $Researchid, $Name, $Description, $BuildTime, $Mercury, $Cobalt, $TechRequired, $LabRequired ) {
	$Techs = Array(
		"Researchid" => $Researchid,
		"Name" => $Name,
		"Description" => $Description,
		"BuildTime" => $BuildTime,
		"Mercury" => $Mercury,
		"Cobalt" => $Cobalt,
		"TechRequired" => $TechRequired,
		"LabRequired" => $LabRequired,
		"ScoreTotal" => ($Mercury+$Cobalt)/10
	);
}

function addLabs ( $Labs, $Constructionid, $Name, $Description, $BuildTime, $Mercury, $Cobalt, $TechRequired, $LabRequired ) {
	$Labs = Array(
		"Constructionid" => $Constructionid,
		"Name" => $Name,
		"Description" => $Description,
		"BuildTime" => $BuildTime,
		"Mercury" => $Mercury,
		"Cobalt" => $Cobalt,
		"TechRequired" => $TechRequired,
		"LabRequired" => $LabRequired,
		"ScoreTotal" => ($Mercury+$Cobalt)/10
	);
}


function getAvailableScans($Scans)
{
	global $Userid;
	$AvailableScans = array();
	foreach ( $Scans as $idx => $scan )
	{
		$completerequired = $scan["Complete"];
		$scanid = $scan["Scanid"];
		$conneeded = mysql_query("SELECT * FROM uwar_constructions WHERE userid='$Userid' AND constructionid='1' AND complete >= '$completerequired'");
		if(mysql_num_rows($conneeded) > 0)
			$AvailableScans[] = $scan["Scanid"];
	}
	return $AvailableScans;
}

function getAvailableOPS($ShipTypes, $Userid)
{
//	global $Userid;
	$AvailableOPS = array();
	foreach ( $ShipTypes as $idx => $def )
	{
		if ($def["Conid"] != "3") continue;
		$completerequired = $def["Complete"];
		$opsid = $def["Shipid"];
		$conneeded = mysql_query("SELECT * FROM uwar_constructions WHERE userid='$Userid' AND constructionid='3' AND complete >= '$completerequired'");
		if(mysql_num_rows($conneeded) > 0)
			$AvailableOPS[] = $def["Shipid"];
	}
	return $AvailableOPS;
}

function cancel_research ($constructionid, $completeval)
{
	global $Userid, $db, $Con;
	$request = mysql_query("SELECT * FROM uwar_constructions WHERE constructionid='$constructionid' AND userid='$Userid'",$db);
	$cons = mysql_fetch_array($request);
	
	//give resources back
	$mercury = floor($Con[$constructionid][$completeval]["Mercury"] / 	$Con[$constructionid][$completeval]["BuildTime"]) * ($cons["eta"]-1);
	$cobalt = floor($Con[$constructionid][$completeval]["Cobalt"] / $Con[$constructionid][$completeval]["BuildTime"]) * ($cons["eta"]-1);
	mysql_query("UPDATE uwar_users SET mercury=mercury+$mercury, cobalt=cobalt+$cobalt WHERE id='$Userid' ",$db) or print(mysql_error());

	//update construction
	mysql_query("UPDATE uwar_constructions SET activated=0, eta=0 WHERE userid='$Userid'",$db);
}

function getAvailableShips($ShipTypes, $Userid)
{
//	global $Userid;
	$AvailableShips = array();
	foreach ( $ShipTypes as $idx => $ship )
	{
		if ($ship["Conid"] != "4") continue;
		$completerequired = $ship["Complete"];
		$shipid = $ship["Shipid"];
		$conneeded = mysql_query("SELECT * FROM uwar_constructions WHERE userid='$Userid' AND constructionid='4' AND complete >= '$completerequired'");
		if(mysql_num_rows($conneeded) > 0)
		{
			$AvailableShips[] = $ship["Shipid"];
		}
	}
	return $AvailableShips;
}


function seconds_for_tick($time)
{
	$minutes = floor($time/60);
	$seconds = $time - ($minutes*60);
	if ($minutes > 0)
			$transformed .= $minutes." minutes ";
	$transformed .= $seconds." seconds ";
	return $transformed;
}
function UST($tick)
{
	//calculate year
	$year = floor($tick/168);
	$left = $tick - $year*168;
	$month = floor($left/24);
	$day = ($left - $month*24)+1;

	switch ($month) 
	{
		case 0: 
			$month = "January";
			break;
		case 1: 
			$month = "February";
			break;
		case 2:
			$month = "March";
			break;
		case 3:
			$month = "April";
			break;
		case 4: 
			$month = "May";
			break;
		case 5:
			$month = "June";
			break;
		case 6:
			$month = "July";
			break;
		case 7:
			$month = "August";
			break;
	}
	
	if ($tick == 0 || $day == 1)
		$day = 1;
	else $day = $day-1;
	$date = $day." ".$month.", Year ".$year;
	return $date;
// print $day." ".$month.", Year ".$year."<br>";
}

if ($username!="")
{

    $result = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'",$db);
    if ($users = mysql_fetch_array($result))
    {
		$cobalt = $users["cobalt"];
        $mercury = $users["mercury"];
		$helium = $users["helium"];
        $score = $users["score"];
        $mercuryroid = $users["asteroid_mercury"];
        $cobaltroid = $users["asteroid_cobalt"];
		$heliumroid = $users["asteroid_helium"];
        $ui_roids = $users["ui_roids"];
        $roids = $mercuryroid + $cobaltroid + $heliumroid + $ui_roids;
        $i_roids = $mercuryroid + $cobaltroid + $heliumroid;
		$rank = $users["rank"];
		if ($users["design"]==0)
		{
			$headerfile = "header.php";
			$footerfile = "footer.php";
		}
		elseif ($users["design"]==1)
		{
			$headerfile = "header2.php";
			$footerfile = "footer2.php";
		}
/*
        $planetmercury = 500 ;
        $planetcobalt = 500 ;
		$planethelium = 500 ;
		*/
		$cobincomroids = $cobaltroid * 450;
		$merincomroids = $mercuryroid * 450;
		$helincomroids = $heliumroid * 450;

	    $result = mysql_query("SELECT * FROM ".$Uwar["table"]." WHERE id='$Userid'",$db);
        $vars = mysql_fetch_array($result);

function MercuryIncome($Userid)
{
			$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'",$db);
			$myrow = mysql_fetch_array($request);
			$mercuryroid= $myrow["asteroid_mercury"];
			$ConSQL = mysql_query("SELECT * FROM uwar_constructions WHERE constructionid='0' and userid='$Userid'",$db);
			$con = mysql_fetch_array($ConSQL);
			if ($con["complete"] == 0) $planetmercury = 250;
			if ($con["complete"] >= 1) $planetmercury = 500;
			if ($con["complete"] >= 4) $planetmercury = 1750;
			if ($con["complete"] >= 7) $planetmercury = 3750;
			$mercuryincome = $planetmercury + $mercuryroid * 456;	
			
			if ($myrow["commander"]==1) $TotalMercury = $mercuryincome + ($mercuryincome / 5);
			elseif($myrow["commander"]==2 || $myrow["commander"]==3 || $myrow["commander"]==4) $TotalMercury = $mercuryincome + $mercuryincome / 10;
			else $TotalMercury = $mercuryincome;
			
return $TotalMercury;
}
function CobaltIncome($Userid)
{	
			$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'",$db);
			$myrow = mysql_fetch_array($request);
			$cobaltroid= $myrow["asteroid_cobalt"];
			$ConSQL = mysql_query("SELECT * FROM uwar_constructions WHERE constructionid='0' and userid='$Userid'",$db);
			$con = mysql_fetch_array($ConSQL);
			if ($con["complete"] < 2)   $planetcobalt = 250;
			if ($con["complete"] >= 2) $planetcobalt = 500;
			if ($con["complete"] >= 5) $planetcobalt = 1750;
			if ($con["complete"] >= 8) $planetcobalt = 3750;
			$cobaltincome = $planetcobalt + $cobaltroid * 456;	


			if ($myrow["commander"]==1) $Totalcobalt = $cobaltincome + $cobaltincome / 5;
			elseif($myrow["commander"]==2 || $myrow["commander"]==3 || $myrow["commander"]==4) $Totalcobalt = $cobaltincome + $cobaltincome / 10;
			else $Totalcobalt = $cobaltincome;

return $Totalcobalt;
}

function HeliumIncome($Userid)
{
			$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'",$db);
			$myrow = mysql_fetch_array($request);
			$heliumroid= $myrow["asteroid_helium"];
			$ConSQL = mysql_query("SELECT * FROM uwar_constructions WHERE constructionid='0' and userid='$Userid'",$db);
			$con = mysql_fetch_array($ConSQL);
			if ($con["complete"] < 3)   $planethelium = 250;
			if ($con["complete"] >= 3) $planethelium = 500;
			if ($con["complete"] >= 6) $planethelium = 1750;
			if ($con["complete"] >= 9) $planethelium = 3750;
			$heliumincome = $planethelium + $heliumroid * 456;	

			if ($myrow["commander"]==1) $Totalhelium = $heliumincome + $heliumincome / 5;
			elseif($myrow["commander"]==2 || $myrow["commander"]==3 || $myrow["commander"]==4) $Totalhelium = $heliumincome + $heliumincome / 10;
			else $Totalhelium = $heliumincome;

return $Totalhelium;
}


    }
    $tmp = time();
    $result2 = mysql_query("UPDATE ".$Uwar["table"]." SET timer='$tmp' WHERE id=$Userid",$db);
}

Function Logging($filename, $string, $author, $toid)
{

    global $Uwar,$REMOTE_ADDR,$Username,$Userid,$db;
#$fp = fopen("logging/$filename.txt","a");
#fputs($fp,strftime("%d/%m-20%y %H:%M:%S",strtotime($PA["time"]." hours"))." - $REMOTE_ADDR\n$Username\n$string\n--------------\n");
#fclose($fp);
#mysql_query("INSERT INTO pa_logging (to,author,text,stamp) VALUES ('$')",$db);
    mysql_query("INSERT INTO uwar_logging (toid,type,author,text,stamp,ip) VALUES ('$toid','$filename','$author','$string','".strtotime($Uwar["time"]." hours")."','$REMOTE_ADDR')",$db);
}

/*Function Logging($userid, $section, $content, $ip)
{
	$request = mysql_query("SELECT id FROM uwar_logging ORDER BY id desc LIMIT 0,1");
	$myrow = mysql_fetch_array($request);
	$logid = $myrow["id"];
	$logid++;

	mysql_query("INSERT INTO uwar_logging (id, userid, section, content, ip, time) VALUES ('$logid', '$userid', '$section', '$content', '$ip', '".strtotime($Uwar["time"]." hours")."')");
}
*/
Function Add_news ($header,$txt,$user)
{
    global $db,$Uwar;
	//get that lastest newsid
    $request = mysql_query("SELECT newsid FROM ".$Uwar["newstable"]." ORDER BY newsid DESC LIMIT 0,1");
    $result = mysql_fetch_array($request);
    $newsid = $result['newsid'];
    $newsid++;
	$modesSQL = mysql_query("SELECT * FROM uwar_modes WHERE id=1");
	$modes = mysql_fetch_array($modesSQL);
	if ($modes["gametype"] == "F") $deletenews = 1440;
	else 	$deletenews = 240;
	$time = time()-7200;
//	mysql_query("INSERT INTO ".$Uwar["newstable"]." VALUES ('$newsid', '$user','$header','$txt','$time hours")."', 0, '$deletenews' )",$db);
	mysql_query("INSERT INTO ".$Uwar["newstable"]." VALUES ('$newsid', '$user', '$header', '$txt', '$time', 0, '$deletenews', 0)",$db) or print(mysql_error());
}
Function Add_mail ($header,$txt,$user,$author)
{
    global $db,$Uwar;
   	//get that lastest mailid
    $request = mysql_query("SELECT mailid FROM ".$Uwar["mailtable"]." ORDER BY mailid DESC LIMIT 0,1");
    $result = mysql_fetch_array($request);
    $mailid = $result['mailid'];
    $mailid++;

    mysql_query("INSERT INTO ".$Uwar["mailtable"]." VALUES ('$mailid','$user','$author','$header','$txt','".strtotime($Uwar["time"]." hours")."', 0)",$db);
}

Function PrintHelp($section)
{
	$help["Base"] = "";
	$help["War Status"] = "In this section you can see if someone from your system is under attack or someone is defending him as well as you can see all the outgoing and returning fleets sent by your system friends.";
	$help["Government"] = "The government section is a private forum where only you and the other commanders from your system can discuss. You can discuss anything here, from strategies and attacks planning to player information. Note that spam is not allowed. All the spam posts will be deleted and the commander that spammed might be punished.";
	$help["Senate"] = "The Senate is also known as the system management section. In here you can elect a System Leader. Then he can elect the other 3 political positions available in the game: Fleet Commander (FC), Senator of Communication (SoC) and Minister of Finance (MoF). He can also change the system picture, name and if the system is private then he can see the system password. All the ministers and the SL can change the message of the day.";
	$help["Finance"] = "The finance section is useful for donations between system members and for donations to the system fund. The Minister of Finance can donate from the system fund to any commander from the system.";
	$help["News"] = "News is important for your planet. Incoming, outgoing fleets, battle reports, labs/tech completion will be displayed here.";
	$help["Messages"] = "Communication in Universal War is very important. In this section you can send and read received messages from other commanders.";
	$help["Laboratories"] = "Laboratories are very important for the future of your planet. Constructing labs will unlock new ships, OPS, scans, technologies as well as an advancing in resource income and traveltime decrease.";
	$help["Technologies"] = "As the laboratories, the technologies are very important for the future of your planet. Developing techs will unlock new ships, OPS, scans, labs as well as an advancing in resource income and traveltime decrease.";
	$help["Resources"] = "Resources are important for everything you do. You will need them for labs, tech, ships, OPS, scans, military operations, in one word: for everything. This section represents the management of your resources.";
	$help["Ship Production"] = "Here you can produce space units, which you will use in both defensive and offensive missions.";
	$help["OPS"] = "The Orbital Protection System represents the first defence against the enemy. They are very good units for protection of your planet, but different from the ships as they are static.";
	$help["Intel"] = "Knowledge is very important for the future of anyone. Knowing what your enemy has will help you plan your attacks better and with the help of the Thievery operation you can weaken your opponent by stealing a part of his resources.";
	$help["Military"] = "We stand now before your Military Base. You have here 3 fleets waiting at any time to be launched in offensive or defensive missions. The formula for ETA is: 4 + Ship ETA (look at ship stats) + place time (system - 1; sector - 3; universe - 5) - construction (look at tech tree) - 2(if defensive mission)";
	$help["Alliance Index"] = "";
	$help["Alliance War Status"] = "In this section you can see if someone from your alliance is under attack or someone is defending him as well as you can see all the outgoing and returning fleets sent by your alliance friends.";
	$help["Documents"] = "The government section is a private forum where only you and the other commanders from your alliance can discuss. You can discuss anything here, from strategies and attacks planning to player info. Note that spam is not allowed here. All the spam posts will be deleted and the commander that spammed might be punished.";
	$help["System"] = "The map is vital for finding other commanders in the Universe. Typing the right coordinates will take you to the place you want to view, but noone knows where will you be taken if you type wrong coordonates.";
	$help["Planet Ranking"] = "";
	$help["System Ranking"] = "";
	$help["Sector Ranking"] = "";
	$help["Alliance Ranking"] = "";
	$help["Preferences"] = "";
	$help["Bugs Report"] = "In this section you can report the bugs you found on the game. You can also discuss and provide additional information on bugs that other people have reported. Don't forget to write on what section you found the bug, describe it as good as you can and if you have a screenshot uploaded on a server please post the url to it here.";

	print $help[$section];
}





Function DateDiff ($interval, $date1,$date2) {

    // get the number of seconds between the two dates
    $timedifference =  $date2 - $date1;

    switch ($interval) {
    case "w":
        $retval  = bcdiv($timedifference ,604800);
        break;
    case "d":
        $retval  = bcdiv( $timedifference,86400);
        break;
    case "h":
        $retval = bcdiv ($timedifference,3600);
        break;
    case "n":
        $retval  = bcdiv( $timedifference,60);
        break;
    case "s":
        $retval  = $timedifference;
        break;

    }
    return $retval;

}

function DateAdd ($interval,  $number, $date) {

    $date_time_array  = getdate($date);

    $hours =  $date_time_array["hours"];
    $minutes =  $date_time_array["minutes"];
    $seconds =  $date_time_array["seconds"];
    $month =  $date_time_array["mon"];
    $day =  $date_time_array["mday"];
    $year =  $date_time_array["year"];

    switch ($interval) {

    case "yyyy":
        $year +=$number;
        break;
    case "q":
        $year +=($number*3);
        break;
    case "m":
        $month +=$number;
        break;
    case "y":
    case "d":
    case "w":
        $day+=$number;
        break;
    case "ww":
        $day+=($number*7);
        break;
    case "h":
        $hours+=$number;
        break;
    case "n":
        $minutes+=$number;
        break;
    case "s":
        $seconds+=$number;
        break;

    }
    $timestamp =  mktime($hours ,$minutes, $seconds,$month ,$day, $year);
    return $timestamp;
}

$tickdif = DateDiff ("s",$ticktime,time());

function format_combat($tid,$userid,$Fleet, $you)
{
include_once("data/ShipTypes.php");
global $ShipTypes;

$you_total = array(
    "before" => 0,
    "lost" => 0,
    "stunned" => 0,
    "stolen" => 0);

$output = "";
$output .= "<table width=100% border=1 cellspacing=1 cellpadding=2 class=content>";
$output .= "<tr align=center>";
$output .= "<td colspan=11 class=contentheader>";


$userSQL = mysql_query("SELECT nick, planet, sysid, z FROM uwar_users WHERE id='$tid'");
$user = mysql_fetch_array($userSQL);
$sysSQL = mysql_query("SELECT x, y FROM uwar_systems WHERE id='$user[sysid]'");
$sys = mysql_fetch_array($sysSQL);

$output .= "Combat Report at $user[nick] of $user[planet] ($sys[x]:$sys[y]:$user[z])";
$output .= "<br></td>";
$output .= "</tr>";
$output .= "<tr align=center>";
$output .= "<td class=header>&nbsp;</td>";
$output .= "<th colspan=3 class=header>Attackers</th>";
$output .= "<th colspan=3 class=header>Defenders</th>";
$output .= "<th colspan=4 class=header>You</th>";
$output .= "</tr>";
$output .= "<tr align=center>";
$output .= "<td>Units</td>";
$output .= "<td>Total</td>";
$output .= "<td>Lost</td>";
$output .= "<td>Frozen</td>";
$output .= "<td>Total</td>";
$output .= "<td>Lost</td>";
$output .= "<td>Frozen</td>";
$output .= "<td>Total</td>";
$output .= "<td>Lost</td>";
$output .= "<td>Frozen</td>";
$output .= "<td>Stolen</td>";
//$output .= "<td>Cap</td>";
$output .= "</tr>";

//check every shiptype -..-
foreach($ShipTypes as $idx => $ship)
{
 /*   if($typedata['Special']!="Roid") */
 {
        // check if this ship was used
        if ($Fleet[0]["Ships"][$idx]["BeginAmount"] + $Fleet[1]["Ships"][$idx]["BeginAmount"] > 0)
		{
            if ($typedata['Special']="Steal roids")
			   $class = "Probes Stealer";

			$output .= "<tr align=right>";

			//prints the shipname
            $output .= "<td>".$ship['Name']."s</td>";
            if ($ship['Special']!="OPS")
			{
				//Attackers Total
                if (!empty($Fleet[0]["Ships"][$idx]["BeginAmount"]))
                    $output .= "<td>".number_format($Fleet[0]["Ships"][$idx]["BeginAmount"])."</td>";
                else
                    $output .= "<td>0</td>";
				//Attackers Lost
				$output .= "<td>".number_format($Fleet[0]["Ships"][$idx]["BeginAmount"]-$Fleet[0]["Ships"][$idx]["Amount"]+$Fleet[1]["Ships"][$idx]["Stolen"])."</td>";
				//Attackers frozen
                if (!empty($Fleet[0]["Ships"][$idx]["Stunned"]))
                    $output .= "<td>".number_format($Fleet[0]["Ships"][$idx]["Stunned"])."</td>";
                else
                    $output .= "<td>0</td>";
            }
            else
			{
                $output .= "<td>&nbsp;</td>";
                $output .= "<td>&nbsp;</td>";
                $output .= "<td>&nbsp;</td>";
  


			}
			//Defenders Total
            if (!empty($Fleet[1]["Ships"][$idx]["BeginAmount"]))
                $output .= "<td>".number_format($Fleet[1]["Ships"][$idx]["BeginAmount"])."</td>";
            else
                $output .= "<td>0</td>";
			//Defenders Lost	
            $output .= "<td>".number_format($Fleet[1]["Ships"][$idx]["BeginAmount"]-$Fleet[1]["Ships"][$idx]["Amount"]+$Fleet[0]["Ships"][$idx]["Stolen"])."</td>";
            $output .= "<td>".number_format($Fleet[1]["Ships"][$idx]["Stunned"])."</td>";

            if (($ship['Special']!="OPS") || ($userid == $tid))
			{
                if (!empty($you[$idx]["before"]))
				{
                    $output .= "<td>".number_format($you[$idx]["before"])."</td>";
                    $you_total["before"] += $you[$idx]["before"];
                }
                else
                    $output .= "<td>0</td>";

                if (!empty($you[$idx]["lost"]))
				{
                    $output .= "<td>".number_format($you[$idx]["lost"])."</td>";
                    $you_total["lost"] += $you[$idx]["lost"];
                }
                else
                    $output .= "<td>0</td>";

                if (!empty($you[$idx]["stunned"]))
				{
                    $output .= "<td>".number_format($you[$idx]["stunned"])."</td>";
                    $you_total["stunned"] += $you[$idx]["stunned"];
                }
                else
                    $output .= "<td>0</td>";

                if (!empty($you[$idx]["stolen"]))
				{
                    $output .= "<td>".number_format($you[$idx]["stolen"])."</td>";
                    $you_total["stolen"] += $you[$idx]["stolen"];
                }
                else
                    $output .= "<td></td>";

                $output .= "</tr>";
            }
        }
    }
}
$output .= "<tr align=right>";
$output .= "<td align=center>Totals</td>";
$output .= "<td>".number_format($Fleet[0]["Totals"]["TotalShips"]["Amount"])."</td>";
$output .= "<td>".number_format($Fleet[0]["Totals"]["TotalLost"]["Amount"]+$Fleet[1]["Totals"]["TotalStolen"]["Amount"])."</td>";
$output .= "<td>".number_format($Fleet[0]["Totals"]["TotalStunned"]["Amount"])."</td>";
$output .= "<td>".number_format($Fleet[1]["Totals"]["TotalShips"]["Amount"])."</td>";
$output .= "<td>".number_format($Fleet[1]["Totals"]["TotalLost"]["Amount"]+$Fleet[0]["Totals"]["TotalStolen"]["Amount"])."</td>";
$output .= "<td>".number_format($Fleet[1]["Totals"]["TotalStunned"]["Amount"])."</td>";
$output .= "<td>".number_format($you_total["before"])."</td>";
$output .= "<td>".number_format($you_total["lost"])."</td>";
$output .= "<td>".number_format($you_total["stunned"])."</td>";
if ($you_total["stolen"]>0)
    $output .= "<td>".number_format($you_total["stolen"])."</td>";
else
    $output .= "<td>0</td>";
$output .= "</tr>";
$output .= "</table>";

return $output;
}


function change_roids($userid,$m_change,$c_change,$e_change,$u_change,$m,$c,$e,$u)
{
/*	print "<hr>";
	print $userid."<br>";
	print $m_change."<br>";
	print $c_change."<br>";
	print $e_change."<br>";
	print $u_change."<br>";
	print "<hr>"; */

	$m += $m_change;
	$c += $c_change;
	$e += $e_change;
	$u += $u_change;

	$query = mysql_query("UPDATE uwar_users SET asteroid_mercury=asteroid_mercury+$m_change, asteroid_cobalt=asteroid_cobalt+$c_change, asteroid_helium=asteroid_helium+$e_change, ui_roids=ui_roids+$u_change WHERE id='$userid'") or die(mysql_error());
	return $query;
	die();
}
?>