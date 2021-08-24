	<?
	//X coord
	$max_sector_request = mysql_query("SELECT MAX(x) FROM uwar_systems",$cn);
	$max_sector = mysql_fetch_array($max_sector_request);
	
	//Loop through the existing sectors for finding a sector with a free spot
	for($sector = 1; $sector <= $max_sector[0]; $sector++)
	{
		//Loop through the systems in the current sector for a free spot, only randoms
		$systems_request = mysql_query("SELECT * FROM uwar_systems WHERE x='$sector' AND systype=1", $cn);
		while( $systems = mysql_fetch_array($systems_request) )
		{
			//Check if the system has a free spot
			$sysid = $systems["id"];
			
			$system_planets_request = mysql_query("SELECT id FROM uwar_users WHERE sysid='$sysid'",$cn);
			$system_planets = mysql_num_rows($system_planets_request);
			//Less than 10 planets -> a free spot
			if($system_planets < 10)
			{
				$lowestfree = $sector;
				//Break the two for-loops
				$sector = $max_sector[0] + 1;
				break;
			}
		}
	}
	//If $lowestfree isn't set, that means there was no sectors with a free spot, create a new sector
	if(!isset($lowestfree)) $lowestfree = $sector;

	//Randomize an x coordinate between the lowest sector with a free spot and the next 2 sectors
	$x = rand($lowestfree, $lowestfree+2);
	?>