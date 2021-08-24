<?
function addCon ( $Con, $Constructionid, $Name, $Description, $BuildTime, $Mercury, $Cobalt, $CompleteRequired, $Type) 
{
	$Con = Array(
		"Constructionid" => $Constructionid,
		"Name" => $Name,
		"Description" => $Description,
		"BuildTime" => $BuildTime,
		"Mercury" => $Mercury,
		"Cobalt" => $Cobalt,
		"CompleteRequired" => $CompleteRequired,
		"Type" => $Type,
	);
}

/*function Types ( $Types, $Typeid, $Name) 
{
	$Types = Array(
		"Id" => $Constructionid,
		"Name" => $Name,
	);
}

$Types = Array();
Types(&$Types[0], 0, "Resources Increasing");
Types(&$Types[1], 1, "Scanning Research");
Types(&$Types[2], 2, "Traveltime Reducing");
Types(&$Types[3], 3, "OPS Development");
Types(&$Types[4], 4, "Ship Development");*/

$Con = Array();

//resources
// complete = 1
addCon(&$Con[0][], 0, "Mercury Refineries", "Increases mercury mines income by 250 units.", "8", "3500", "3500", "0", "r");
//2
addCon(&$Con[0][], 0, "Cobalt Mining", "Increases cobalt mines income by 250 units.", "12", "5000", "5000", "1", "c");
//3
addCon(&$Con[0][], $count, "Cesium Laboratories", "Increases caesium mines income by 250 units.", "16", "10000", "10000", "2", "r");
//4 
addCon(&$Con[0][], 0, "Advanced Refineries", "Increases mercury mines income by 1500 units.", "20", "15000", "10000", "3", "c");
//5
addCon(&$Con[0][], 0, "Advanced Mining", "Increases cobalt mines income by 1500 units.", "24", "20000", "20000", "4", "r");
//6
addCon(&$Con[0][], 0, "Improved Caesium Extraction", "Increases the planetary caesium income with an additional 1500 units each tick.", "32", "25000", "30000", "5", "c");
//7
addCon(&$Con[0][], 0, "Mercury Strip Refinery", "Our scientist have discovered that with the help of a special strip refinery they can extract an additional 3000 mercury. If this refinery works your miners will be able to extract a good amount of mercury every time they explore it.", "36", "35000", "25000", "6", "r");
//8
addCon(&$Con[0][], 0, "Advanced Cobalt Mining", "Our scientist are working on a new technology in the planetary mining sphere. If they succeed in this attempt to increase the resources gathered from mining then we will be able to extract an additional 3000 cobalt every tick.", "48", "75000", "50000", "7", "c");
//9
addCon(&$Con[0][], 0, "Hectra Stations", "Our scientists have discovered that by constructing Hectra Stations to replace Caesium Labs they could extract an additional 3000 cesium each day.", "72", "100000", "100000", "8", "r");
//10
addCon(&$Con[0][], 0, "Resources Harvest", "Increase the income of each resources by 5000 units.", "96", "150000", "150000", "9", "c");

//scans
//1
addCon(&$Con[1][], 1, "Probe Laboratories", "Enables production of probes, used for extraction of resources.", "12", "4000", "2500", "0", "c");
//2
addCon(&$Con[1][], 1, "Planetary Scan Studies", "Enables the planetary telescope which can gather general information about the target.", "16", "7500", "5000", "1", "r");
//3
addCon(&$Con[1][], 1, "Unit Scan Studies", "Enables the production of Unit Scans.", "20", "10000", "15000", "2", "c");
//4
addCon(&$Con[1][], 1, "Defence Spy Centre", "Enables production of Defence Scans.", "32", "25000", "15000", "3", "r");
//5
addCon(&$Con[1][], 1, "Log Theft", "Enables History Scans.", "48", "40000", "50000", "4", "c");
//6
addCon(&$Con[1][], 1, "Fleet Spy Centre", "Enables Fleet Scans.", "72", "100000", "100000", "5", "r");
//7
addCon(&$Con[1][], 1, "Thievery", "Enables the Thievery offensive operation.", "48", "40000", "50000", "6", "c");
//8
//addCon(&$Con[1][], 1, "Ships Stealing", "Enables the Converting offensive operation.", "96", "100000", "100000", "7", "r");

// Travel Time
// 1
addCon(&$Con[2][], 2, "Space Portal", "Using a space portal, we could decrease the travel time outside our system by 1 day.", "12", "5000", "5000", "0", "c");
// 2
addCon(&$Con[2][], 2, "Star Gate", "Our scientists have developed a Star Gate, which can help us decrease the travel time outside our system by 2 days.", "24", "15000", "15000", "1", "r");
// 3
addCon(&$Con[2][], 2, "Vortex", "The astronoms of our planet have discovered a vortex. If we learn how to use this to our advantage, we will be able to decrease travel time outside our system by 3 days.", "48", "25000", "25000", "2", "c");
//4
addCon(&$Con[2][], 2, "Teleportation", "Teleportation was always an interesting subject. Now our scientists have studied a way which could help units teleport on large distances. This would reduce travel time by 4 days.", "72", "50000", "50000", "3", "r");


//OPS
//1
addCon(&$Con[3][], 3, "Solar Protection", "Enables production of Solar Shields.", "16", "2500", "2500", "0", "c");
//2
addCon(&$Con[3][], 3, "Plasma Weapons", "This technology conduces to the discovering of the plasma weapons which are used in the creation of Plasma Bolt Units.", "24", "15000", "10000", "1", "r");
//3
//addCon(&$Con[3][], 3, "Probes Protection", "Enables the production of the Exploder Mines,which protect your probes from the enemy pirates.", "32", "40000", "50000", "2", "c");
//3
addCon(&$Con[3][], 3, "Defenders Centre", "Enables production of the Defenders.", "48", "30000", "25000", "2", "c");
//4
addCon(&$Con[3][], 3, "Electrons Factory", "Enables production of the Electrodes.", "56", "50000", "50000", "3", "r");
//5
addCon(&$Con[3][], 3, "Photon Cannons", "Studies in the branch of defence, enables the photon cannons, leading to the production of Photon Turrets.", "72", "75000", "75000", "4", "c");


//Ships
//1
addCon(&$Con[4][], 4, "Military Facilities", "Studies in the arts of war. Enables your fleets.", "12", "2500", "2500", "0", "r");
//2
addCon(&$Con[4][], 4, "Shipyard Project", "Constructs the Ship Yard Level 1. Enables production of Scouts and Pirates.", "12", "5000", "4000", "1", "c");
//3
addCon(&$Con[4][], 4, "EMP technologies", "A basic research into creation of electro magnectic pulse.", "8", "12500", "7500", "2", "r");
//4
addCon(&$Con[4][], 4, "Spiders Shipyard", "Constructs Ship Yard Level 2. Enables production of Spiders.", "8", "18500", "13500", "3", "c");
//5
addCon(&$Con[4][], 4, "Hardened metals", "Research into advanced metals used for units production.", "16", "5000", "20000", "4", "r");
//6
addCon(&$Con[4][], 4, "Corvette Factory", "Constructs Level 1 Factory. Enables production of Annexers.", "16", "7500", "30000", "5", "c");
//7
addCon(&$Con[4][], 4, "Cloaking Devices", "Research into cloaking technologies. Enables further research on ships.", "12", "10000", "20000", "6", "r");
//8
addCon(&$Con[4][], 4, "Cloacked Ships Factory", "Constructs the Level 2 Factory. Enables production of Wraiths.", "12", "15000", "30000", "7", "c");
//9
addCon(&$Con[4][], 4, "Composite Materials", "An advanced research into creation of composite materials, used for bigger and better ships.", "24", "15000", "55000", "8", "r");
//10
addCon(&$Con[4][], 4, "Warfrigate Factory", "Constructs Level 3 Factory. Enables production of Warfrigates.", "24", "22500", "82500", "9", "c");
//11
addCon(&$Con[4][], 4, "War Academy", "An advanced research into creation of composite materials, used for bigger and better units.", "16", "50000", "20000", "10", "r");
//12
addCon(&$Con[4][], 4, "Nemesis Factory", "Constructs Level 4 Factory. Enables production of Nemesis.", "16", "75000", "30000", "11", "c");
//13
addCon(&$Con[4][], 4, "Foundry Laboratories", "Research into the studies of advanced military constructions. Enables the laboratory Destroyer Foundry.", "32", "40000", "120000", "12", "r");
//14
addCon(&$Con[4][], 4, "Destroyer Foundry", "Enables production of destroyers.", "32", "60000", "180000", "13", "c");
//15
addCon(&$Con[4][], 4, "Ion Weapons", "Research into the studies of Ion Weapons. Enables the laboratory Tempest Foundry.", "24", "70000", "90000", "14", "r");
//16
addCon(&$Con[4][], 4, "Tempest Foundry", "Enables production of Tempests.", "24", "105000", "135000", "15", "c");
//17
addCon(&$Con[4][], 4, "Advanced infrastructure", "Research into technologies used for advanced infrastructures. Enables the Laboratory Cruiser Foundry.", "48", "60000", "240000", "16", "r");
//18
addCon(&$Con[4][], 4, "Cruiser Factory", "Enables Star Cruisers production.", "48", "90000", "360000", "17", "c");
//19
addCon(&$Con[4][], 4, "EMP Frequencies", "An advanced research into creation of electro magnectic pulse weapons.", "32", "120000", "140000", "18", "r");
//20
addCon(&$Con[4][], 4, "Electrizator Foundry", "Enables production of Electrizators.", "32", "180000", "210000", "19", "c");
//21
addCon(&$Con[4][], 4, "BattleShip Project", "A new step into the military technology, this research enables the tools needed for the production of the Battle Ships.", "72", "330000", "530000", "20", "r");
//22
addCon(&$Con[4][], 4, "Scorpion Factory", "One of the ultimate powers of the universe, the Scorpion is the test of the new technologies created on the Battle Ship Project. This laboratory enables the production of this units.", "72", "495000", "795000", "21", "c");
//23
addCon(&$Con[4][], 4, "Capitall Hulls", "Research into powerful hulls and advanced weapons, leading to the creation of Annihilators.", "72", "160000", "700000", "22", "r");
//24
addCon(&$Con[4][], 4, "Orbital Destructors", "It is said that 'A dream can become reality. All you need to do is to fight for it'. The technology that stands behind the most powerful force of the Universe is now here before us. Do we have the strength to build the Annihilators and become one of the ultimate empires of the Universe?", "72", "240000", "1050000", "23", "c");

?>