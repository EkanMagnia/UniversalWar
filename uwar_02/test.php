<?php
include("logindb.php");
include("functions.php");
include("data/ShipTypes.php");
$request = mysql_query("SELECT id FROM uwar_users");
while ($myrow = mysql_fetch_array($request))
{
/*	for ($i=0; $i<20; $i++)
	{
		$request1 =	mysql_query("INSERT INTO uwar_constructions (userid,constructionid,complete,activated) VALUES ($id,$i,1,1)");
	}

	for ($j=0; $j < 17; $j++)
	{
		 $request2 = mysql_query("INSERT INTO uwar_research (userid,researchid,complete,activated) VALUES ($id,$j,1,1)");
	}	

	for ($k=0; $k<22; $k++)
	{
		$request3 = mysql_query("UPDATE uwar_constructions SET complete=1 WHERE constructionid='$k'",$db);
		$request4 = mysql_query("UPDATE uwar_research SET complete=1 WHERE researchid='$k'",$db);
	}

	for ($l=0; $l<8; $l++)
	{
		$request5 = mysql_query("INSERT INTO uwar_scans (userid,scanid,stock) VALUES (1,$l,100)");
	}
*/
//	for ($g=0; $g<=20; $g++);
	$i=0;
	while ($i <= $maxUnits)
	{
		if ($i < $maxShipID)
		{
			$ops = 'n';
			$fleetnum = '1';
		}
		else
		{
			$ops = 'y';
			$fleetnum = '0';
		}

		if ($i == 2) $amount = 100000;
		else $amount=10000;

		$request6 = mysql_query("INSERT INTO uwar_fships (userid, shipid, fleetnum, amount, ops) VALUES ('$myrow[id]', '$i', '$fleetnum', $amount, '$ops')") or die(mysql_error());
		$i++;
	}

/*
	for ($n=0; $n<4; $n++)
	{
		$request7 = mysql_query("INSERT INTO uwar_tships (userid,fleetnum,action) VALUES ($id,$n,'h')");
	}
*/
}	
if ($request1) print "Labs created<br>";
if ($request2) print "Tech created<br>";
if ($request3 && $request4) print "Labs & tech completed<br>";
if ($request5) print "Scans created.<br>";
if ($request6) print "Ships created.<br>";
if ($request7) print "Fleets created.";

?>
