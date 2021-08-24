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

$ShipTypes = Array();

CreateShipType(&$ShipTypes[0], 0, "Scout", "Normal", "FI", "FR", "CO", "FI", 6, 30, 30, 1, 2, 3, 50, 10, 2, "These are lightly armed, fast scouting ships used for reconnaissance missions. Their swift movement makes them a hard target to shoot plus their low cost makes them good for overwhelming the enemy. However, Scouts are easily destroyed due to their light armour. Armed with 1 small gun.","4", "0", "1250", "0",  "4", "2", "n");

CreateShipType(&$ShipTypes[1], 1, "Spider", "EMPs", "FI", "CO", "FR", "-", 3, 30, 1, 1, 1, 2, 45, 10, 2, "The Spider is a fighter with an electro-magnetic pulse gun which targets corvettes and frigates. It has a very weak armour and EMP resistance. Armed with 1 gun.", "6", "1250", "0", "0",  "4", "4", "n");

//The Elder People talk about battles won by invisible units. For many centuries this was only a myth, but now with the discovery of the technology that stands behind the Mirage, it became reality. The Mirage is a small but powerful fighter with cloaking abilities. It`s agility, low price and initiative makes a good unit of it.

CreateShipType(&$ShipTypes[2], 2, "Annexer", "Normal", "CO", "CR", "DE", "FR", 4, 25, 10, 1, 10, 10, 65, 25, 3, "A ship created to destroy the frigates and cruisers, the Annexer is armed with 1 powerful gun.", "8", "500", "2000", "0", "4", "6", "n");

//CreateShipType(&$ShipTypes[3], 3, "Spider", "EMPs", "FI", "FI", "CO", "-", 3, 22, 1, 1, 2, 2, 65, 10, 2, "The Spider is a fighter with an electro-magnetic pulse gun which targets corvettes and fighters. It has a very weak armour, but a good percent of EMP resistance. Armed with 1 gun.", "6", "0", "2000", "0", "4", "2", "n");

CreateShipType(&$ShipTypes[3], 3, "Wraith", "CL", "CO", "CR", "DE", "FR", 10, 25, 15, 1, 10, 10, 65, 30, 3, "The Wraith is a cloaked unit with good EMP resistance. Armed with 1 gun, it fires at cruisers, destroyers and frigates.", "8", "1000", "2000", "0",  "4", "8", "n");


CreateShipType(&$ShipTypes[4], 4, "Warfrigate", "Normal", "FR", "CO", "FI", "-", 8, 20, 25, 3, 5, 30, 75, 80, 4, "The warfrigate is a ship close to the heart of Commander Renex, one of the Universal Lords. He made the first design for this unit, and it remained unaltered since. The Warfrigate is the mainstay of any fleet, a ship of medium speed, with powerful guns which fire at corvettes and fighters.", "12", "1500", "5500", "0",  "4", "10", "n");

CreateShipType(&$ShipTypes[5], 5, "Nemesis", "EMPs", "FR", "CO", "FI", "-", 2, 20, 1, 5, 1, 30, 70, 80, 4, "The Nemesis is a special frigate armed with 5 EMP guns that fire at corvettes and fighters. It is the second ship that fire in a battle, after Electrizator.", "12", "5000", "2000", "0",  "4", "12", "n");

CreateShipType(&$ShipTypes[6], 6, "Destroyer", "Normal", "DE", "BS", "CR", "-", 5, 15, 5, 3, 25, 70, 80, 175, 4, "The Destroyer is armed with 5 guns that fire at battleships and cruisers. It fires at biggest ships, the battleships and cruisers.", "16", "4000", "12000", "0",  "4", "14", "n");

CreateShipType(&$ShipTypes[7], 7, "Tempest", "CL", "DE", "BS", "CR", "-", 11, 15, 5, 3, 25, 70, 75, 175, 4, "This ship literally sank into the history books. It is rumored that the captain offended a God and that his ship was cursed to be lost with its crew in oblivion. Then in the War of the Khorasian Empire, it suddenly dissapeared and was never found.<br>Now, after long years have passed, the designs for that unit have been found and the best manufacturers duplicate it in hundreds of thousands of exemplars.", "16", "7000", "9000", "0",  "4", "16", "n");

CreateShipType(&$ShipTypes[8], 8, "Star Cruiser", "Normal", "CR", "FR", "CO", "-", 9, 10, 15, 8, 15, 135, 85, 350, 5, "The Star Cruiser is a powerful unit, armed with 8 guns. It has good armour and EMP resistance, and fires at frigates and corvettes.", "20", "6000", "24000", "0", "4", "18", "n");

CreateShipType(&$ShipTypes[9], 9, "Electrizator", "EMPs", "CR", "CR", "DE", "FR", 1, 8, 1, 6, 1, 135, 70, 350, 5, "The Electrizator is a fairly specialized ship which uses a small tractor beam. The tractor beam targets the ship and immobilizes it, disabling the ship for the rest of the tick.", "20", "12000", "14000", "0", "4", "20", "n");

//CreateShipType(&$ShipTypes[10], 10, "Converter", "Steals roids", "CR", "RO", "-", "-", 14, 25, 1, 3, 1, 20, 40, 100, 7, "An upgrade to the Pirates, the Converters are better armoured and have better EMP resistance and agility. However they fire after Pirates and require way more fuel to travel.", "18", "12000", "12000", "0", "4", "14", "n");

CreateShipType(&$ShipTypes[10], 10, "Scorpion", "CL", "BS", "FI", "CO", "-", 12, 5, 30, 100, 2, 350, 85, 700, 5, "Designed to be a very powerful ship, the Scorpion targets the fighters and converttes. One of the supreme ships in the universe, the Scorpion has low agility, but it has tough armour and powerful weapons.", "24", "33000", "53000", "0",  "4", "22", "n");

CreateShipType(&$ShipTypes[11], 11, "Interstellar Annihilator", "Normal", "BS", "FI", "CO", "-", 7, 5, 30, 100, 2, 400, 90, 700,5, "The most powerful ship in the universe, nothing shall stand between this giant and the enemy. In the words of commander Vestberg, 'A fleet would not be a fleet without Annihilators'. As the name says, this unit annihilates everything it has in its way. Its class has been developed by some of the best known manufacturers all around the universe. Because of its powerful armour and weapons, the agility and speed of this unit are low.", "24", "16000", "70000", "0", "4", "24", "y");

CreateShipType(&$ShipTypes[12], 12, "Pirates", "Steals roids", "FR", "RO", "-", "-", 13, 18, 1, 1, 1, 12, 65, 125, 4, "One of the most important ships in the universe, the Pirates only targets the enemy probes. They are controlled by a central computer with the sole purpose to get the probes. After stealing them, the Pirates are teleported at base, where they dump the probes and auto-destroy. Pirates can only steal 1 probe each.", "10", "1000", "2500", "0",  "4", "2", "n");

//CreateShipType(&$ShipTypes[12], 12, "Dweomer", "CL", "FR", "PU", "FI", "-", 10, 22, 31, 12, 15, 70, 65, 75, 4, "In the Krimolian legends, it is said that the last ship that escaped from Earth was a Dweomer. In respect to the race that populated the entire universe, the Dweomer is still produced in many empires, but its design was changed in a lot of points from than. It is now a fast frigate, with cloaking abilities which fires at protection units and fighters. ", "16", "8000", "10000", "0", "4", "4");

//CreateShipType(&$ShipTypes[13], 13, "Warfrigate", "Normal", "FR", "DE", "CR", "-", 12, 21, 29, 25, 16, 100, 80, 100, 4, "The warfrigate is a ship close to the heart of Commander Renex, one of the Universal Lords. He made the first design for this unit, and it remained unaltered since. The Warfrigate is the mainstay of any fleet, a ship of medium speed, with powerful guns which fires at cruisers and destructors. ", "16", "15000", "25000", "0", "4", "8");

//CreateShipType(&$ShipTypes[14], 14, "Scorpion", "Normal", "CR", "FI", "FR", "-", 11, 18, 27, 27, 12, 90, 70, 125, 5, "Designed to be a very powerful ship, the succesor of the War Frigates,the Scorpion targets the fighters and frigates.One of the supreme ships of the universe, the Scorpion has low agility, but it is well armoured and has powerful weapons.", "18", "25000", "30000", "0",  "4", "6");

//CreateShipType(&$ShipTypes[15], 15, "Tempest", "CL", "CR", "ST", "EMP", "-", 13, 15, 23, 30, 15, 125, 80, 150, 5, "This ship literally sank into history books. It is rumored that the captain offended a God and that his ship was cursed to be lost with its crew in the infinite universe. However this did not happen for long time. Then in the War of the Khorasian Empire, it suddently dissapeard and it was never found.<br>Now, after long years have passed, the designs for that unit have been found and the best manufactures duplicate it in hundreds of thousands of exemplars.", "20", "35000", "40000", "0",  "4", "9");



CreateShipType(&$ShipTypes[13], 13, "Solar Shield", "OPS", "FI", "CO", "FI", "-", 0, 5, 30, 1, 2, 10, 100, 0, 0, "The solar protection shield is your first defense unit. It protects your planet by activating a solar shield which fires at corvettes and fighters.", "4", "350", "350", "350", "3", "1", "y");

//CreateShipType(&$ShipTypes[13], 13, "Solar Shield", "Normal", "CO", "FI", "CO", "-", 0, 25, 60, 1, 1, 10, 0, 0,0, "", "4", "2000", "1000", "500", "3", "1", "y");

CreateShipType(&$ShipTypes[14], 14, "Plasma Bolt Turret", "OPS", "FI", "FR", "CO", "-", 0, 5, 25, 1, 10, 20, 100, 0, 0, "Plasma Bolts are medium OPS units which are able to fire at frigates and corvettes.", "8", "1000", "1000", "1000", "3", "2", "y");

//CreateShipType(&$ShipTypes[15], 15, "Exploder Mine", "OPS", "LP", "ST", "FR", "-", 4, 15, 18, 3,125, 20, 75, 0, 0, "The exloder mines are specially units which protect your probes by being atacked from the out space. They act as a bomb when the ships are hitting them by destroying their hull. Very used for kamikaze strategies.", "12", "4500", "4500", "4500", "3", "3");

CreateShipType(&$ShipTypes[15], 15, "Defender", "OPS", "CO", "DE", "FR", "-", 0, 5, 20, 1, 20, 40, 100, 0, 0, "A powerful OPS unit able to fire against destroyers and frigates with a tachyon beam disruptor.", "16", "2000", "2000", "2000", "3", "3", "y");

CreateShipType(&$ShipTypes[16], 16, "Electrode", "OPS", "CO", "CR", "DE", "-", 0, 5, 15, 1, 50, 80, 100, 0, 0, "The second most powerful OPS units, the electrodes are using the same engine like the electrizatores. They are armed with powerful laser beams and target the cruisers and destroyers.", "20", "3500", "3500", "3500", "3", "4", "y");

CreateShipType(&$ShipTypes[17], 17, "Photon Turret", "OPS", "FR", "BS", "CR", "-", 0, 5, 5, 1, 75, 160, 100, 0, 0, "The most powerful OPS unit, the Photon Turret uses a photon cannon to fire at battleships and cruisers. Its powerful weapons and tough armour make of this unit the most powerful OPS.", "20", "5000", "5000", "5000", "3", "5", "y");

//roids
CreateShipType(&$ShipTypes[20], 20, "Mercury refinery", "Roid", "RO", "-", "-", "-", -1, 0, 0, 0, 1, 1, 0, 0, 0, "", 0, 0, 0, 0, "", "");
CreateShipType(&$ShipTypes[21], 21, "Cobalt Mine", "Roid", "RO", "-", "-", "-", -1, 0, 0, 0, 0, 0, 1, 1, 0, "", 0, 0, 0, 0,"", "");
CreateShipType(&$ShipTypes[22], 22, "Caesium Station", "Roid", "RO", "-", "-", "-", -1, 0, 0, 0, 0, 1, 1, 0, 0, "", 0, 0, 0, 0,"", "");
CreateShipType(&$ShipTypes[23], 23, "Uninitiated Probe", "Roid", "RO", "-", "-", "-", -1, 0, 0, 0, 1, 1, 0, 0, 0, "", 0, 0, 0, 0, "", "");

//resources
CreateShipType($ShipType[24], 24, "Mercury", "Resource", "Resource", "-", "-", "-", -2, 0, 0, 0, 0, 1, 1, 0, 0, "", 0, 0, 0, 0, "","");
CreateShipType($ShipType[25], 25, "Cobalt", "Resource", "Resource", "-", "-", "-", -2, 0, 0, 0, 0, 1, 1, 0, 0, "", 0, 0, 0, 0, "","");
CreateShipType($ShipType[26], 26, "Caesium", "Resource", "Resource", "-", "-", "-", -2, 0, 0, 0, 0, 1, 1, 0, 0, "", 0, 0, 0, 0, "","");
 
	$MaxInit = max($MaxInit,$ShipTypes["Init"]);


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

$maxShipID = 13;
$maxUnits = 17;
	?>
