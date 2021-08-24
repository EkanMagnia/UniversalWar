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
addScan(&$Scans[++$count], $count, "Waves Augmenter", "The Wave Augmenters make your signals more powerful and help you to get successfull scans easier. The number of them depend the success of your scans. This type of scan cannot be launched.", "5", "0", "0", "1000", "1", "1", "Amps");
//1
addScan(&$Scans[++$count], $count, "Probes Wave", "Probes Wave help you getting Probes without attacking. This wave search through the universe after Undiscovered Probes that you can control. These probe scans are launched in resources section.", "8", "0", "0", "1000", "1", "1", "Roids");
//2
addScan(&$Scans[++$count], $count, "Planetary Scan", "The Planetary Scan gives you general informations about the target such as resources,probes number of each type,number of ships and defensive units,planet status(vacantion,protection,sleep mode).", "10", "0", "0", "2500", "1", "2", "Scanning");
//3
addScan(&$Scans[++$count], $count, "Unit Spy", "Ships Scan gives you informations about the number of every ship type that the target has.", "12", "0", "0", "3500", "1", "3", "Scanning");
//4
addScan(&$Scans[++$count], $count, "OPS Scan", " OPS scan go into the Planet and look at his OPS, comming back and tell you all info about his defensive system.", "15", "0", "0", "5000", "1", "4", "Scanning");
//5
addScan(&$Scans[++$count], $count, "Cloaked Infiltration", "Gives you informations about the cloaked ships of the scaned target.", "18", "0", "0", "6000", "1", "6", "Scanning");
//6
addScan(&$Scans[++$count], $count, "Army Signal", "Army Signals Give you the millitary status of the target. This show you exactly where that target's ships are.", "20", "0", "0", "7500", "1", "7", "Scanning");
//7
addScan(&$Scans[++$count], $count, "History Scan", "History Scan give you the History of your target. Here you can see if your target has any friendly or hostile incoming.", "24", "0", "0", "9000", "1", "5", "Scanning");
?>