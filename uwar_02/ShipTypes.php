<?

	include "bc/safemode_fudge.php";
	$shmh = shm_attach(514187,512000);
	$ShipTypes = @shm_get_var($shmh,0);
	$ShipInits = @shm_get_var($shmh,1);
	$ShipClasses = @shm_get_var($shmh,2);
	$MaxInit = @shm_get_var($shmh,3);
	$FireMatrix = @shm_get_var($shmh,4);
	$ShipTargets = @shm_get_var($shmh,5);
	$Date = @shm_get_var($shmh,6);
  
	$newdate = date( "dmyHis",filemtime("ShipTypes.php"));

	if (( $Date != $newdate ) || ($reconstruct==1))
	{
		@shm_remove_var($shmh,0);
		@shm_remove_var($shmh,1);
		@shm_remove_var($shmh,2);
		@shm_remove_var($shmh,3);
		@shm_remove_var($shmh,4);
		@shm_remove_var($shmh,5);
		@shm_remove_var($shmh,6);
		unset($ShipTypes);
		unset($ShipClasses);
		unset($ShipInits);
		unset($MaxInit);
		unset($FireMatrix);
		unset($ShipTargets);
	}


if ( !isset($ShipTypes) || empty($ShipTypes) || !isset($ShipClasses) || !isset($ShipInits) || !isset($MaxInit) )
{
	echo "Reconstructing shared variables<br>\n";
	$count = -1;
/*
function CreateShipType($Ships, $Shipid, $Name, $Special, $ShipClass, $T1, $T2, $T3, $Init, $Agility, $WP_SP, $Guns, $GunPWR, $Armour, $EMP_res, $Fuel, $Travel, $Description, $BuildTime, $Mercury, $Cobalt, $Helium, $Conid, $Complete ) {
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
		"Conid" => $Conid,
		"Complete" => $Complete
	);
    }
*/
		$MaxInit = max($MaxInit,$Init);

		$ShipInits[$Init][0]++;
		$ShipInits[$Init][$ShipInits[$Init][0]] = $TypeNr;
	
		$ShipClasses[$ShipClass][0]++;
		$ShipClasses[$ShipClass][$ShipClasses[$ShipClass][0]] = $TypeNr;
		if ( $ShipClass != "Ro" ) {
			$ShipClasses["*"][0]++;
			$ShipClasses["*"][$ShipClasses["*"][0]] = $TypeNr;
		}
    }

$ShipTypes = Array();

CreateShipType(&$ShipTypes[0], 0, "Scout", "Normal", "FI", "ST", "FI", "-", 1, 36, 16, 3, 4, 10, 65, 10, 1, "These are lightly armed, fast scouting ships used for reconnaissance missions. Their speed makes them a hard target to shoot plus their low cost make them good for overwhelming the enemy. However, Scouts are destroyed very easy when they are hit. Armed with 1 small gun.","4", "1000", "250", "0",  "4", "2");

CreateShipType(&$ShipTypes[1], 1, "Spider", "Normal", "FI", "ST", "CL", "CR", 2, 35, 15, 2, 15, 15, 60, 20, 1, "These lighlty armed ships target the thiefs primarily. Their speed and agility again, makes them a hard target to hit, armed with 1 small gun.", "6", "250", "1000", "0",  "4", "3");

CreateShipType(&$ShipTypes[2], 2, "Pirate", "Capture", "ST", "RO", "-", "-", 14, 27, 0, 3, 0, 45, 65, 35, 2, "The Pirate's only target are the probes of the enemy. They are controlled by a central computer that have 1 mission: to get the probes.", "8", "500", "2000", "0",  "4", "1");

CreateShipType(&$ShipTypes[3], 3, "Annexer", "Normal", "FR", "FR", "ST", "-", 10, 13, 31, 35, 20, 57, 75, 350, 5, "These ships are most probably the bulk of anyones fleet. They target the Ionizator as their primary target.", "16", "1000", "3500", "0", "4", "7");

CreateShipType(&$ShipTypes[4], 4, "Cobra", "EMP", "CL", "ST", "CR", "-", 1, 22, 24, 9, 19, 81, 70, 235, 3, "These ships are considered the most preciouse in the game as they block the stealers. They have 1 electronic cannon which is controlled by a computer to catch the stealers.", "12", "2500", "5000", "0",  "4", "4");

CreateShipType(&$ShipTypes[5], 5, "Thief", "Harvest", "ST", "**", "--", "--", 13, 31, 0, 0, 0, 45, 60, 95, 2, "Like the Pirates, the Thief steals the enemy's resources. During the attack the Thief will grab your enemy's resources and send them back to your base.", "12", "1500", "4000", "0", "4", "9");

CreateShipType(&$ShipTypes[6], 6, "Holocast", "Normal", "FI", "CR", "ST", "-", 5, 33, 13, 4, 13, 20, 55, 30, 2, "This ship is more powerful then the spider and the scouts. Its armed with 1 laser cannon which has more power then the spiders gun and targets the Converters.", "16", "2500", "5000", "0",  "4", "5");

CreateShipType(&$ShipTypes[7], 7, "Converter", "Steal", "ST", "FI", "CL", "*", 6, 29, 21, 2, 31, 45, 70, 175, 2, "These are very special ships, during battle they will transport people into enemy ships, kill all crew and take over the ship; and giving you control over them.", "16", "6500", "8000", "0",  "4", "10");

CreateShipType(&$ShipTypes[8], 8, "Ionizators", "EMP", "CL", "FR", "CL", "-", 1, 23, 19, 6, 17, 68, 100, 225, 3, "Ionizators are cloaked ships armed with 2 plasma cannons that are able to fire against Annexers and Cobras.", "12", "3000", "6000", "0",  "4", "8");

CreateShipType(&$ShipTypes[9], 9, "Assault Cruiser", "Normal", "CR", "ST", "FR", "FI", 7, 19, 19, 20, 5, 67, 75, 250, 4, "These ships attack the mighty Scorpions. The weapon systems are very powerful and have the armour to compliment it.", "16", "3000", "8000", "0", "4", "6");

CreateShipType(&$ShipTypes[10], 10, "Electrizator", "EMP", "CL", "FI", "ST", "FR", 1, 25, 22, 5, 14, 55, 80, 80, 3, "The most powerful cloaked ships, the Electrizator is armed with 5 plasma cannons and target the Holocast, Converters and Annexers.", "16", "3500", "6000", "0", "4", "11");

CreateShipType(&$ShipTypes[11], 11, "Scorpion", "Normal", "CR", "FI", "CR", "-", 8, 21, 27, 12, 12, 89, 95, 265, 4, "Designed to be a very powerful ship, the succesor of the assault cruiser, it targets the death cruiser. It the most advanced weapon systems in the universe, and also has a very tough armour.", "15", "8000", "10000", "0",  "4", "12");

CreateShipType(&$ShipTypes[12], 12, "Death Cruiser", "Normal", "CR", "CL", "ST", "-", 9, 16, 31, 29, 15, 100, 95, 275, 5, "These ships were designed to do 1 thing: target the most deadliest ship of the universe, the Interstellar Annihilator. Its weapon systems had to be cut short due to the mount of time they had in creating this, the speed is very slow due to its size of the weapons needed to fire such a fast and powerful shot.", "15", "8000", "10000", "0", "4", "13");

CreateShipType(&$ShipTypes[13], 13, "Warfrigate", "Normal", "FR", "CR", "CL", "-", 11, 10, 29, 41, 16, 135, 85, 375, 6, "One of the most powerfull ships from the world,the warfrigates are armed with 6 tachyon broadcaster cannons and targets the cruisers ship class.", "20", "15000", "25000", "0", "4", "14");

CreateShipType(&$ShipTypes[14], 14, "Interstelar Annihilator", "Normal", "FR", "FI", "CR", "FR", 12, 8, 26, 45, 14, 250, 100, 425, 7, "The Insterstelar Annihilators are the most powerfull ships in the universe. Armed with 20 guns, 15 laser cannons, 10 plasma bolts and 5 tachyon broadcaster cannons. This ship can destroy almost the entire enemy fleet.", "24", "30000", "45000", "0", "4", "15"); 

CreateShipType(&$ShipTypes[15], 15, "Solar Shield", "OPS", "FI", "FI", "CL", "*", 15, 25, 3, 1, 8, 25, 90, 0,0, "The solar protection shield is the first defense unit. It protect your planet by activating a solar shield which don't let the enemy ship to station at your planet.", "4", "2000", "1000", "500", "3", "1");

CreateShipType(&$ShipTypes[16], 16, "Plasma Bolt", "OPS", "FI", "CL", "ST", "*", 15, 20, 6, 1, 16, 50, 90, 0, 0, "Plasma bolts are medium OPS units which are able to fire against cloacked ships.", "8", "2000", "2500", "1500", "3", "2");

CreateShipType(&$ShipTypes[17], 17, "Exploder Mine", "OPS", "CL", "FR", "CR", "-", 15, 15,4, 1,125, 90, 75, 0, 0, "The exloder mines are specially units which protect your probes by being atacked from the out space. They act as a bomb when the ships are hitting them by destroying their hull. Very used for kamikaze strategies.", "12", "4500", "4500", "4500", "3", "3");

CreateShipType(&$ShipTypes[18], 18, "Defender", "OPS", "CR", "CR", "FR", "*", 15, 10, 9, 1, 60, 85, 90, 0, 0, "A powerfull OPS unit able to fire against frigates with a tachyon beam disrupter.", "16", "5000", "4500", "3000", "3", "4");

CreateShipType(&$ShipTypes[19], 19, "Electrone", "OPS", "FR", "ST", "FI", "*", 15, 5, 12, 1, 60, 100, 90, 0, 0, "The most powerfull OPS unit,the electrones are using the same engine like the electrizatores. They are armed with 5 laser beams and target the frigate ship class.", "20", "10000", "10000", "5000", "3", "5");

//roids
CreateShipType(&$ShipTypes[20], 20, "Mercury refinerie", "Roid", "RO", "-", "-", "-", 0, 0, 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0, "", "");
CreateShipType(&$ShipTypes[21], 21, "Cobalt Mine", "Roid", "RO", "-", "-", "-", 0, 0, 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0,"", "");
CreateShipType(&$ShipTypes[22], 22, "Caesium Station", "Roid", "RO", "-", "-", "-", 0, 0, 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0,"", "");
CreateShipType(&$ShipTypes[23], 23, "Uninitiated Probe", "Roid", "RO", "-", "-", "-", 0, 0, 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0, "", "");

//resources
CreateShipType($ShipType[24], 24, "Mercury", "Resource", "Resource", "-", "-", "-", 0, 0, 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0, "","");
CreateShipType($ShipType[25], 25, "Cobalt", "Resource", "Resource", "-", "-", "-", 0, 0, 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0, "","");
CreateShipType($ShipType[26], 26, "Caesium", "Resource", "Resource", "-", "-", "-", 0, 0, 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0, "","");
 	shm_put_var($shmh,0,$ShipTypes);
	shm_put_var($shmh,1,$ShipInits);
	shm_put_var($shmh,2,$ShipClasses);
	shm_put_var($shmh,3,$MaxInit);


if (( !isset($ShipTargets) || empty($ShipTargets) || !isset($FireMatrix) || empty($FireMatrix) ) && $Checker ) {
	echo "Reconstructing second set of shared variables<br>\n";

	function _IsTarget ( $Attacker, $TargetClass, $Target )
    {
		if (( $TargetClass != $Target["ShipClass"] ) && ( $TargetClass != "*" )) 
			return false;

		switch ($Attacker["Special"])
		{
			case "Harvest"	: return false;
			case "Capture"	: return ( $TargetClass == "Ro" );
			case "EMP"		: return ( $Target["Special"] != "PDS" );
			case "Steal"	: return ( $Target["Special"] != "PDS" );
			case "PDS"		: return ( $Target["Special"] != "PDS" );
			default			: return true;
		}

	}

	function _GenTargetMatrix($Ship, $Target, $target_type,$fd) {
		global $ShipTypes, $ShipInits, $ShipClasses, $ShipTargets, $FireMatrix;
		$attnum = $Ship["t"];
		$defnum = $Target["t"];
		if ( $Ship["Special"] == "EMP" )
		{
			$FireMatrix[$attnum][$defnum][cth] = 1 - $Target["Emp_res"]/100;
			$FireMatrix[$attnum][$defnum][htk] = 1;
		}
		else
		{
			$FireMatrix[$attnum][$defnum][cth] = min(max((25.0 + $Ship["Weap_speed"] - $Target["Agility"]), 0) / 100,1.0);
			$FireMatrix[$attnum][$defnum][htk] = ceil($Target["Armour"] / $Ship["Gunpower"]);
		}
		$FireMatrix[$attnum][$defnum][stk] = ($FireMatrix[$attnum][$defnum][cth] == 0)?0:ceil($FireMatrix[$attnum][$defnum][htk] / $FireMatrix[$attnum][$defnum][cth]);
		if ( $fd != -1 )
		{
			fwrite( $fd, "   \$FireMatrix[$attnum][$defnum][cth] = ".$FireMatrix[$attnum][$defnum][cth].";\n" );
			fwrite( $fd, "   \$FireMatrix[$attnum][$defnum][htk] = ".$FireMatrix[$attnum][$defnum][htk].";\n" );
			fwrite( $fd, "   \$FireMatrix[$attnum][$defnum][stk] = ".$FireMatrix[$attnum][$defnum][stk].";\n" );
		}
	}

	function GenTargets($fd)
	{
		global $ShipTypes, $ShipInits, $ShipClasses, $ShipTargets;
		foreach ($ShipTypes as $t => $Ship)
		{
			$alltarg = "";
			for ( $target_type = 1; $target_type <= 3; $target_type++ )
			{
				$name_target_type = "Target".$target_type;
				$ShipTargets[$t][$target_type][0] = 0;
				for ($targetcount = 1; $targetcount <= $ShipClasses[$Ship[$name_target_type]][0]; $targetcount++)
				{
					$TargetNR = $ShipClasses[$Ship[$name_target_type]][$targetcount];
					$Target = $ShipTypes[$TargetNR];
					if ( _IsTarget($Ship,$Ship[$name_target_type],$Target) )
					{
						$temp = ++$ShipTargets[$t][$target_type][0];
						$ShipTargets[$t][$target_type][$temp] = $Target["t"];
						if ( $fd != -1 )
						{
							fwrite( $fd, "   \$ShipTargets[$t][$target_type][$temp] = ".$Target["t"].";\n" );
						}
						_GenTargetMatrix($Ship,$Target,$target_type,$fd);
					}
				}
			}
		}
	}

	GenTargets(-1);
	shm_put_var($shmh,4,$FireMatrix);
	shm_put_var($shmh,5,$ShipTargets);

}
if ( $Date != $newdate )
{
	$Date = $newdate;
	shm_put_var($shmh,6,$Date);
}

	shm_detach($shmh);


	$TypeReal["FI"] = "Fighter";
	$TypeReal["FR"] = "Frigate";
	$TypeReal["CR"] = "Cruiser";
	$TypeReal["ST"] = "Stealer";
	$TypeReal["CL"] = "Cloacked";
	$TypeReal["RO"] = "Asteroid";
	$TypeReal["-"]  = "None";
	$TypeReal["*"]  = "Any class";
	$TypeReal["**"] = "Resources";

	?>