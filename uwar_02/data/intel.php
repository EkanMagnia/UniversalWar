<?
/*
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
*/
$count = -1;
$Scans = Array();
//0
addScan(&$Scans[++$count], $count, "Waves Augmenter", "Scanning and Offensive Operations are very important for every commander. However, the number of Waves Augmenters is crucial for the success of the scan or operation and for the blocking of incoming scans/operations.", "4", "0", "0", "1000", "1", "1", "Amps");
//1
addScan(&$Scans[++$count], $count, "Asteroid", "Resource exploration is very important for the economy of your planet. The best way to get resources is to mine probes. These are asteroids captured from the universe and brought back to your planet, where they are mined in special laboratories. This operation collects the asteroids, and then transforms them into probes in Resources.", "6", "1000", "0", "0", "1", "1", "Roids");
//2
addScan(&$Scans[++$count], $count, "Planetary Scan", "The Planetary Scan provides you with general informations about the target such as resources, amount of each probe type, number of ships and OPS and planet status(vacation, protection, sleep mode).", "8", "0", "0", "2000", "1", "2", "Scan");
//3
addScan(&$Scans[++$count], $count, "Unit Scan", "Before going to war, it is very important to know who you attack and what ships he/she has. With this operation you can send a number of spies to infiltrate an enemy planet and return the amount and type of the ships your target has.", "10", "0", "0", "4000", "1", "3", "Scan");
//4
addScan(&$Scans[++$count], $count, "OPS Scan", "Similar to the Unit Scan, this operation sends your spies to infiltrate the target planet and returns information about his defences.", "12", "0", "0", "4000", "1", "4", "Scan");
//5
addScan(&$Scans[++$count], $count, "History Scan", "The History Scan lists recent events of your target. Here you can see if your target has any friendly or hostile incoming.", "16", "0", "0", "7000", "1", "5", "Scan");
//6
addScan(&$Scans[++$count], $count, "Fleet Scan", "Fleet scans returns the military status of the target. This shows you exactly where the target's ships are.", "20", "0", "0", "8000", "1", "6", "Scan");
//7
addScan(&$Scans[++$count], $count, "Thievery Operation", "Resources are vital for the survival of your planet. You can get them from your mines and probes, but you can also steal them. To do this, you have to send a number of thieves to the enemy territories. A higher number of spies gives you a better chance of stealing more resources and returning them to your base. This operation trains the necessary thieves to be sent in the thievering operation.", "8", "0", "0", "3000", "1", "7", "Offensive Operation");
//8
//addScan(&$Scans[++$count], $count, "Converting", "The most known way to achieve your glory in Universal War is to have mighty fleets that can defeat all of your enemies. In order to have a good fleet, you must have good ships which you can get by producing or stealing. This operation trains special agents, that infiltrate in your enemies fleet, kills the crew and overtakes the ship, returning it to your base.", "16", "0", "0", "10000", "1", "8", "Offensive Operation");
//9
//addScan(&$Scans[++$count], $count, "Coma", "The old saying says that a planet can not decide its future without its commander. This operation sends a number of your criminals to kill another commander. However all the commanders are immortals, so your criminals can only get the enemy commander into a temporary coma. This means his planet will be set in vacation mode for a number of Universal days. The number of days depends on the number of your criminals you send.", "16", "0", "0", "10000", "1", "6", "Offensive Operation");

?>