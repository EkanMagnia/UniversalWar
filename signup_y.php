	<?
	//Y coord
	$systems_request = mysql_query("SELECT * FROM uwar_systems WHERE x='$x'",$cn);
	$not_possible_systems = array();
	//Loop through the systems in the randomly selected sector
	while($systems = mysql_fetch_array($systems_request))
	{
		//If the user is creating a system, add all existing systems in the current sector into $not_possible_systems
		if($gal == "create") $not_possible_systems[] = $systems["y"];
		else 
		{
			if($systems["systype"] == "1")
			{
				$sysid = $systems["id"];
				$system_planets_request = mysql_query("SELECT id FROM uwar_users WHERE sysid='$sysid'",$cn);
				//Less than 10 plan -> a free spot
				if(mysql_num_rows($system_planets_request) >= 10) $not_possible_systems[] = $systems["y"];
			}
			else $not_possible_systems[] = $systems["y"];
		}
	}

	/*//Randomize an y coordinate for the new system, but only non-used y coordinates are accepted
	while(!is_numeric($y))
	{
		$y = rand(1,5);
		if(in_array($y, $not_possible_systems)) $y = "wrong";
	}*/

	//Randomize an y coordinate for the new system, but only non-used y coordinates are accepted
	for($y=1; $y <= 5; $y++)
	{
		if (!in_array($y, $not_possible_systems)) break;
	}

	//Check if the system already exist, if not create a new system
	$system_request = mysql_query("SELECT id FROM uwar_systems WHERE x='$x' AND y='$y'", $cn);
	if(mysql_num_rows($system_request) == 0)
	{
		if($gal == "create")
		{
			mysql_query("INSERT INTO uwar_systems (x,y, systype, syspword, sysname, sysmotd, syspic, syssize, sysscore, sysrank) VALUES ('$x', '$y', 2, '$syspass', 'A new sunshine', 'Welcome to $x:$y', 'images/system.jpg', '0', '0', '0')",$cn);
			$sysid_request = mysql_query("SELECT id FROM uwar_systems WHERE x='$x' AND y='$y'",$cn);
			$sysid_arr = mysql_fetch_array($sysid_request);
			$sysid = $sysid_arr["id"] + 1;
		}
		else
		{
			mysql_query("INSERT INTO uwar_systems (x,y, systype, sysname, sysmotd, syspic, syssize, sysscore, sysrank) VALUES ('$x', $y, 1, 'The Dark System', 'Welcome to $x:$y', 'images/system.jpg', '0', '0', '0')",$cn);
			$sysid_request = mysql_query("SELECT id FROM uwar_systems WHERE x='$x' AND y='$y'",$cn);
			$sysid_arr = mysql_fetch_array($sysid_request);
			$sysid = $sysid_arr["id"] + 1;
			mysql_query("INSERT INTO uwar_sysfund (sysid,sysmercury,syscobalt,syshelium) VALUES ('$sysid',0,0,0)",$cn);
		}
	}
	?>