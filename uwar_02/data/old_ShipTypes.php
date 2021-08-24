<?
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
$ShipTypes2 = Array();

CreateShipType(&$ShipTypes2[0], 0, "Scout", "Normal", "FI", "FI", "ST", "-", 1, 48, 47, 2, 2, 7, 75, 10, 2, "These are lightly armed, fast scouting ships used for reconnaissance missions. Their speed makes them a hard target to shoot plus their low cost make them good for overwhelming the enemy. However, Scouts are destroyed very easy when they are hit. Armed with 1 small gun.","4", "1000", "0", "0",  "4", "2");

CreateShipType(&$ShipTypes2[1], 1, "Spider", "Normal", "FI", "ST", "CL", "*", 6, 40, 29, 2, 2, 6, 60, 20, 2, "These lighlty armed ships target the thiefs primarily. Their speed and agility again, makes them a hard target to hit, armed with 1 small gun.", "6", "0", "1000", "0",  "4", "3");

CreateShipType(&$ShipTypes2[2], 2, "Pirate", "Steals roids", "ST", "RO", "-", "-", 40, 24, 0, 1, 0, 12, 68, 35, 3, "The Pirate's only target are the probes of the enemy. They are controlled by a central computer that have 1 mission: to get the probes.", "8", "500", "2000", "0",  "4", "1");

CreateShipType(&$ShipTypes2[3], 3, "Annexer", "Normal", "FR", "FI", "ST", "-", 12, 40, 62, 4, 3, 43, 58, 70, 3, "These ships are most probably the bulk of anyones fleet. They target the Ionizator as their primary target.", "16", "1000", "3500", "0", "4", "7");

CreateShipType(&$ShipTypes2[4], 4, "Cobra", "EMPs", "CL", "CR", "FR", "*", 4, 24, 6, 3, 5, 20, 65, 60, 3, "These ships are considered the most preciouse in the game as they block the stealers. They have 1 electronic cannon which is controlled by a computer to catch the stealers.", "12", "2500", "5000", "0",  "4", "4");

CreateShipType(&$ShipTypes2[5], 5, "Thief", "Steals Resources", "ST", "**", "--", "--", 13, 31, 0, 0, 0, 45, 60, 95, 2, "Like the Pirates, the Thief steals the enemy's resources. During the attack the Thief will grab your enemy's resources and send them back to your base.(Battle code not implemented)", "12", "1500", "4000", "0", "4", "9");

CreateShipType(&$ShipTypes2[6], 6, "Holocast", "Normal", "FR", "FR", "CR", "-", 14, 33, 37, 5, 4, 52, 82, 85, 3, "This ship is more powerful then the spider and the scouts. Its armed with 1 laser cannon which has more power then the spiders gun and targets the Converters.", "16", "2500", "5000", "0",  "4", "5");

CreateShipType(&$ShipTypes2[7], 7, "Converter", "Steal", "TI", "CL", "FR", "-", 22, 24, 30, 5, 5, 47, 65, 105, 4, "These are very special ships, during battle they will transport people into enemy ships, kill all crew and take over the ship; and giving you control over them.(Battle code not implemented)", "16", "6500", "8000", "0",  "4", "10");

CreateShipType(&$ShipTypes2[8], 8, "Ionizators", "EMPs", "CL", "FI", "CL", "*", 5, 25, 7, 10, 4, 36, 77, 120, 4, "Ionizators are cloaked ships armed with 2 plasma cannons that are able to fire against Annexers and Cobras.", "12", "3000", "6000", "0",  "4", "8");

CreateShipType(&$ShipTypes2[9], 9, "Assault Cruiser", "Normal", "CR", "CR", "FR", "-", 20, 15, 25, 9, 3, 54, 70, 150, 4, "These ships attack the mighty Scorpions. The weapon systems are very powerful and have the armour to compliment it.", "16", "3000", "8000", "0", "4", "6");

CreateShipType(&$ShipTypes2[10], 10, "Electrizator", "EMPs", "CL", "TI", "FR", "CR", 6, 23, 45, 12, 2, 60, 72, 170, 4, "The most powerful cloaked ships, the Electrizator is armed with 5 plasma cannons and target the Holocast, Converters and Annexers.", "16", "3500", "6000", "0", "4", "11");

CreateShipType(&$ShipTypes2[11], 11, "Scorpion", "Normal", "FR", "CR", "FR", "-", 24, 9, 18, 15, 3, 81, 76, 205, 5, "Designed to be a very powerful ship, the succesor of the assault cruiser, it targets the death cruiser. It the most advanced weapon systems in the universe, and also has a very tough armour.", "15", "8000", "10000", "0",  "4", "12");

CreateShipType(&$ShipTypes2[12], 12, "Death Cruiser", "Normal", "CR", "ST", "FR", "-", 23, 7, 15, 10, 7, 135, 70, 245, 5, "These ships were designed to do 1 thing: target the most deadliest ship of the universe, the Interstellar Annihilator. Its weapon systems had to be cut short due to the mount of time they had in creating this, the speed is very slow due to its size of the weapons needed to fire such a fast and powerful shot.", "15", "8000", "10000", "0", "4", "13");

CreateShipType(&$ShipTypes2[13], 13, "Warfrigate", "Normal", "FR", "CR", "CL", "-", 26, 12, 15, 12, 10, 120, 87, 275, 6, "One of the most powerfull ships from the world,the warfrigates are armed with 6 tachyon broadcaster cannons and targets the cruisers ship class.", "20", "15000", "25000", "0", "4", "14");

CreateShipType(&$ShipTypes2[14], 14, "Interstelar Annihilator", "Normal", "CR", "FI", "CL", "FR", 27, 8, 19, 15, 15, 195, 92, 325, 6, "The Insterstelar Annihilators are the most powerfull ships in the universe. Armed with 20 guns, 15 laser cannons, 10 plasma bolts and 5 tachyon broadcaster cannons. This ship can destroy almost the entire enemy fleet.", "24", "30000", "45000", "0", "4", "15"); 

CreateShipType(&$ShipTypes2[15], 15, "Solar Shield", "OPS", "FI", "FI", "ST", "-", 15, 30, 48, 1, 10, 30, 100, 0,0, "The solar protection shield is the first defense unit. It protect your planet by activating a solar shield which don't let the enemy ship to station at your planet.", "4", "2000", "1000", "500", "3", "1");

CreateShipType(&$ShipTypes2[16], 16, "Plasma Bolt", "OPS", "CL", "ST", "CL", "-", 15, 24, 50, 1, 14, 60, 100, 0, 0, "Plasma bolts are medium OPS units which are able to fire against cloacked ships.", "8", "2000", "2500", "1500", "3", "2");

CreateShipType(&$ShipTypes2[17], 17, "Exploder Mine", "OPS", "FI", "FR", "TI", "*", 15, 22,36, 1,18, 98, 100, 0, 0, "The exloder mines are specially units which protect your probes by being atacked from the out space. They act as a bomb when the ships are hitting them by destroying their hull. Very used for kamikaze strategies.", "12", "4500", "4500", "4500", "3", "3");

CreateShipType(&$ShipTypes2[18], 18, "Defender", "OPS", "FR", "CR", "FR", "*", 15, 12, 30, 1, 40, 138, 100, 0, 0, "A powerfull OPS unit able to fire against frigates with a tachyon beam disrupter.", "16", "5000", "4500", "3000", "3", "4");

CreateShipType(&$ShipTypes2[19], 19, "Electrone", "OPS", "CR",  "CR", "FR", "-", 15, 6, 24, 1, 80, 197, 100, 0, 0, "The most powerfull OPS unit,the electrones are using the same engine like the electrizatores. They are armed with 5 laser beams and target the frigate ship class.", "20", "10000", "10000", "5000", "3", "5");

//roids
CreateShipType(&$ShipTypes2[20], 20, "Mercury refinerie", "Roid", "RO", "-", "-", "-", 0, 0, 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0, "", "");
CreateShipType(&$ShipTypes2[21], 21, "Cobalt Mine", "Roid", "RO", "-", "-", "-", 0, 0, 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0,"", "");
CreateShipType(&$ShipTypes2[22], 22, "Caesium Station", "Roid", "RO", "-", "-", "-", 0, 0, 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0,"", "");
CreateShipType(&$ShipTypes2[23], 23, "Uninitiated Probe", "Roid", "RO", "-", "-", "-", 0, 0, 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0, "", "");

//resources
CreateShipType($ShipType2[24], 24, "Mercury", "Resource", "Resource", "-", "-", "-", 0, 0, 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0, "","");
CreateShipType($ShipType2[25], 25, "Cobalt", "Resource", "Resource", "-", "-", "-", 0, 0, 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0, "","");
CreateShipType($ShipType2[26], 26, "Caesium", "Resource", "Resource", "-", "-", "-", 0, 0, 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, 0, "","");
 


	$TypeReal["FI"] = "Fighter";
	$TypeReal["TI"] = "Thief";
    $TypeReal["FR"] = "Frigate";
	$TypeReal["CR"] = "Cruiser";
	$TypeReal["ST"] = "Stealer";
	$TypeReal["CL"] = "Cloacked";
	$TypeReal["RO"] = "Asteroid";
	$TypeReal["DE"] = "Destroyer";
    $TypeReal["-"]  = "None";
	$TypeReal["*"]  = "Any class";
	$TypeReal["**"] = "Resources";

	?>