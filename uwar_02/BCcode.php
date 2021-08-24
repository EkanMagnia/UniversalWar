<?
	include "data/ShipTypes.php";

   	/* fleet data */
   	//$Fleet = Array();

   	/* battlereport input data */
   	$ShipBattleRep = null;

   	/* Accuracy data */
   	$overall_accuracy = null;

   	/* Warning msg string */
   	$Warning = "";

   	/* calc log buffer */
   	$InitStrBuffer = "";

   	/* for calc log view depth */
   	$DepthLog = 2;

	/* Chance to grab roids, and amount of roids grabbed */
	$RoidChance = 0;
	$RoidChanceHistory = array();
	$RoidTotal = 0;
	$RoidsGrabbed = 0;

  	/* Paste text */
   	$PasteText = "";

	function CreateShipGroup( $ShipGroup, $ShipType, $BeginAmount)
    {
        $ShipGroup["Shipid"] = $ShipType;
        $ShipGroup["BeginAmount"] = $BeginAmount;
        $ShipGroup["Amount"] = $BeginAmount;
        $ShipGroup["Killed"] = 0;
        $ShipGroup["Hits"] = 0;
        $ShipGroup["ToBeKilled"] = 0;
        $ShipGroup["ToBeStunned"] = 0;
        $ShipGroup["Stunned"] = 0;
        $ShipGroup["TargetNr"] = 0;
    }

	function CalcLog( $InputString, $deepness = 1 )
	{
		global $CalcLog, $DepthLog;

		if ( $deepness == $DepthLog )
			$CalcLog .= $InputString;
	}

	function AddTotals ( $Type, $Total, $Amount )
	{
		$Total["Amount"]  += $Amount;
		$Total["Fuel"]    += $Amount * $Type["Fuel"];
		$Total["Mercury"] += $Amount * $Type["Mercury"];
		$Total["Cobalt"]   += $Amount * $Type["Cobalt"];
		$Total["Helium"]  += $Amount * $Type["Helium"];
		$Total["Worth"]   += $Amount * ( $Type["Mercury"] + $Type["Cobalt"] + $Type["Helium"] ) / 10;
		if ( $Type["ShipClass"] == "RO" && $Type["Name"] != "Uninitiated probe" )
			$Total["Worth"] += $Amount * 1500;
	}

	function CalcTotals ( $Flt, $t, $Totals )
	{
		AddTotals( $Flt["Ships"][$t]["Shipid"], &$Totals["TotalShips"], $Flt["Ships"][$t]["BeginAmount"] );
		AddTotals( $Flt["Ships"][$t]["Shipid"], &$Totals["TotalLost"], $Flt["Ships"][$t]["BeginAmount"]- $Flt["Ships"][$t]["Amount"] ); /* Removed " + $Flt[$t]["Gained"]" */
		AddTotals( $Flt["Ships"][$t]["Shipid"], &$Totals["TotalStunned"], $Flt["Ships"][$t]["Stunned"] );

	}

    function MainLoop ( $NumCalcs )
    {
        global $ShipTypes, $Fleet, $CalcLogBuffer;

        for( $t = 0 ; $t < $NumCalcs ; $t++ )
		{
      		$CalcLogBuffer[0] = "<br><center><span class=title>TICK ". ($t+1) ."</span></center>";

		    ClearHitsStuns( &$Fleet[0]["Ships"] );
		    ClearHitsStuns( &$Fleet[1]["Ships"] );

		    for( $InitCount = 0; $InitCount < 16; $InitCount++ )
	        {
	            ActInitiative( &$Fleet[0], &$Fleet[1], $InitCount, 0 );
	            ActInitiative( &$Fleet[1], &$Fleet[0], $InitCount, 1 );
	            CleanUp( &$Fleet[0] );
    	        CleanUp( &$Fleet[1] );
	        }

			$Fleet[0]["Totals"] = null;
			$Fleet[1]["Totals"] = null;

			for ( $x = 0; $x < count($Fleet[0]["Ships"]); $x++ )
       			CalcTotals( $Fleet[0], $x, &$Fleet[0]["Totals"] );
			for ( $x = 0; $x < count($Fleet[1]["Ships"]); $x++ )
       			CalcTotals( $Fleet[1], $x, &$Fleet[1]["Totals"] );

			if ( $Fleet[0]["PlanetScore"] != 0 )
				$Fleet[0]["PlanetScore"] -= round($Fleet[0]["Totals"]["TotalLost"]["Worth"]);

			if ( $Fleet[0]["PlanetScore"] < 0 )
				$Fleet[0]["PlanetScore"] = 1;

			if ( $Fleet[1]["PlanetScore"] != 0 )
				$Fleet[1]["PlanetScore"] -= round($Fleet[1]["Totals"]["TotalLost"]["Worth"]);

			if ( $Fleet[1]["PlanetScore"] < 0 )
				$Fleet[1]["PlanetScore"] = 1;

			if ( $CalcLogBuffer[0] )
				CalcLog($CalcLogBuffer[0]."<center>No combat this tick, no valid target available for any ship</center>",2);
	    }

    }

    function ActInitiative ( $AttFlt, $DefFlt, $InitCount, $who )
    {
/*
	1. $AttFlt = $Fleet[0];
	   $DefFlt = $Fleet[1];
	   $InitCount = InitCount;
	   $who = 0/1 - side
*/
    	global $CalcLogBuffer;

    	$Att = &$AttFlt[Ships];
    	$Def = &$DefFlt[Ships];

        for( $t = 0; $t < count($Att); $t++ )
        {
            if ( $Att[$t]["Shipid"]["Init"] == $InitCount && $Att[$t]["Amount"] - $Att[$t]["Stunned"] > 0 )
            {
           		$TotalGuns = $Att[$t]["Shipid"]["Guns"] * ( $Att[$t]["Amount"] - $Att[$t]["Stunned"] );

           		$CalcLogBuffer[1] = "<br><b>Acting out initiative $InitCount :</b><br>";

           		CalcLog ( "<br>Acting out initiative $InitCount : ($Att[Side]) ". $Att[$t]["Shipid"]["Name"] ." <br>");
           		CalcLog ( "Total Guns : $Guns<br>");

	           	$CalcLogBuffer[3] = "";
	           	$Done = false;

           		/* primary targets */
           		$GunsLeft = $TotalGuns;
           		while ( !$Done )
           		{
	           		if ( $CalcLogBuffer[3] == "Restshots!" )
		           		$CalcLogBuffer[2] = "<b>Primary targets (rest shots):</b><br>";
		           	else
		           		$CalcLogBuffer[2] = "<b>Primary targets:</b><br>";

	           		list( $GunsLeft, $Done) = AttackTargets( &$AttFlt, &$Att[$t] , &$DefFlt, &$Def, $Att[$t]["Shipid"]["Target1"], $GunsLeft );
	           		CalcLog ( "<br>Guns left after target1 : $GunsLeft<br>");
		           	$CalcLogBuffer[3] = "Restshots!";
	           	}

	           	$CalcLogBuffer[3] = "";
	           	$Done = false;


           		/* secondary targets */
				print "Done = ".$Done."<br>";
				print "GunsLeft = ".$GunsLeft."<br>";
           		while ( !$Done && $GunsLeft > 0 )
           		{
	           		if ( $CalcLogBuffer[3] == "Restshots!" )
		           		$CalcLogBuffer[2] = "<b>Secondary targets (rest shots):</b><br>";
		           	else
	           			$CalcLogBuffer[2] = "<b>Secondary targets:</b><br>";
	           		list( $GunsLeft, $Done) = AttackTargets( &$AttFlt, &$Att[$t] , &$DefFlt, &$Def, $Att[$t]["Shipid"]["Target2"], $GunsLeft );
	           		print "<br>Guns left after target2 : $GunsLeft<br>";
		           	$CalcLogBuffer[3] = "Restshots!";
	           	}

	           	$CalcLogBuffer[3] = "";
	           	$Done = false;

           		/* what's left after that */
           		while ( !$Done && $GunsLeft > 0 )
           		{
	           		if ( $CalcLogBuffer[3] == "Restshots!" )
		           		$CalcLogBuffer[2] = "<b>Tertiary targets (rest shots):</b><br>";
		           	else
	           			$CalcLogBuffer[2] = "<b>Tertiary targets:</b><br>";

	           		list( $GunsLeft, $Done) = AttackTargets( &$AttFlt, &$Att[$t] , &$DefFlt, &$Def, $Att[$t]["Shipid"]["Target3"], $GunsLeft );
	           		CalcLog ( "<br>Guns left after target3 : $GunsLeft<br>");
		           	$CalcLogBuffer[3] = "Restshots!";
	           	}
			if ($Att[$t]["Shipid"]["Special"] == "Anti PDS" && $who==0) {
				$Att[$t]["ToBeKilled"] += $Att[$t]["Amount"];
				CalcLog ( "Failed Missile exploded<br>", 2 );

			}

	            if ( $InitCount == 0 )
	            {
		            CleanUp( &$AttFlt );
		            CleanUp( &$DefFlt );
		        }
            }
        }
    }

    function IsTarget ( $AttType, $DefType, $Target )
    {
    	global $EmpTargets;

        if ( $DefType["ShipClass"] == $Target || $Target == "*" )
        {

        	if ( $AttType["Special"] != "Steals roids" && $DefType["ShipClass"] == "RO" )
        		return false;

	    	switch ( $AttType["Special"] )
	    	{
	    		case "Steals resources" : return false;
	    		case "EMPs"				: if ( $DefType["Special"] == "OPS" ) return false; 
								  else return true;
	    		case "Anti PDS"			: if ( $DefType["Special"] != "OPS" ) return false;
								  else return true;
				case "OPS"				: if ($DefType["Special"] == "OPS") return false;
								  else return true;
	    		default 				: return true;
	    	}
	    }

	    return false;
	}

    function AttackTargets( $AttFlt, $Att, $DefFlt, $Def, $Target, $inGuns )
    {

	/*
		$AttFlt = $Fleet[0];
		$Att = $Att[t];
		$DefFlt = $DefFlt;
		$Def = $Def;
		$Target = $Att[$t]["Shipid"]["Target3"];
		$inGuns = $GunsLeft;
	*/
		global $CalcType, $Warning, $CalcLogBuffer, $RoidChance, $RoidChanceHistory, $CapRule, $DefenderScore;

		/* Return Done if shooter is an EMPer and targeting "*" */

        if ( $inGuns == 0 || $Target == "-" )
	       	return array( $inGuns, true );

		$TargetCount = 0;
		$MaxGrabCount = 0;
		$DefFlt["PlanetScore"] = $DefenderScore;
//		print "DefFlt[planetscore] = ".$DefFlt["PlanetScore"]."<br>";
//			print "Fleet score=".$AttFlt["Totals"]["TotalShips"]["Worth"]."<br>";
    	/* roid calculation rules */
		//print "DefFlt[PlanetScore]=".$DefFlt["PlanetScore"]."<br>";
    	if ( $DefFlt["PlanetScore"] > 0 )
    	{
			$RoidChance = ($DefFlt["PlanetScore"]/$AttFlt["Totals"]["TotalShips"]["Worth"]) / 10;
           	if ( $RoidChance > 0.15 )
           		$RoidChance = 0.15;
           	$RoidChanceHistory[] = $RoidChance;
    	}
       	elseif( $AttFlt["PlanetScore"] <= 0 )
    	{
			$RoidChance = 0;
			print "Attention: You need to provide the planetscore for roid loss calculations.";
    	}

		/* Count all available targets */
        for( $t = 0; $t < count($Def); $t++ )
        {
            $Def[$t]["TargetNr"] = 0;
            if ( IsTarget( $Att["Shipid"], $Def[$t]["Shipid"], $Target ) )
           	{
           		/* target found */
                if ( $Att["Shipid"]["Special"] == "EMPs" )
                {
                	$TargetCount += $Def[$t]["Amount"] - $Def[$t]["Stunned"] - $Def[$t]["ToBeStunned"];
               		$Def[$t]["TargetNr"] = $Def[$t]["Amount"] - $Def[$t]["Stunned"] - $Def[$t]["ToBeStunned"];
               	}
                elseif ( $Att["Shipid"]["Special"] == "Steals roids" )
                {
                	$TargetCount += $Def[$t]["Amount"] - $Def[$t]["ToBeKilled"];
               		$Def[$t]["TargetNr"] = $Def[$t]["Amount"] - $Def[$t]["ToBeKilled"];
					$Def[$t]["MaxGrab"] = floor($RoidChance * ($Def[$t]["Amount"] - $Def[$t]["ToBeKilled"]));
               		$MaxGrabCount += $Def[$t]["MaxGrab"];
                }
              	else
               	{
                	$TargetCount += $Def[$t]["Amount"] - $Def[$t]["ToBeKilled"];
               		$Def[$t]["TargetNr"] = $Def[$t]["Amount"] - $Def[$t]["ToBeKilled"];
               	}
       	    }
        }



/*		if ( $Att["Type"]["Special"] == "Steals roids" && floor($TotalRoids * $RoidChance) > $TargetCount)
		{
			$restroids = floor($TotalRoids * $RoidChance) - $TargetCount;
	        for( $t = 0; $t < count($Def) && $restroids > 0; $t++ )
    	    {
 	           	if ( IsTarget( $Att["Type"], $Def[$t]["Type"], $Target ) )
           		{
					$Def[$t]["TargetNr"]++;
					$restroids--;
					$TargetCount++;
				}
			}
        } */


		/* Return if no targets found */
        if ( $TargetCount == 0 )
	       	return array( $inGuns, true );

		if ( $Att["Shipid"]["Special"] == "Steals roids" && $MaxGrabCount == 0 )
	       	return array( $inGuns, true );

		/* Resolve Normal Shots */
	    for( $t = 0; $t < count($Def); $t++ )
	    {
	   		if ( $Def[$t]["TargetNr"] > 0 && $Def[$t]["Amount"] != 0 )
	   		{
				/* Check for dead apods (rounding catchup) */
	  			if ( $Att["Shipid"]["Special"] == "Steals roids" )
					if ( $Att["Amount"] - $Att["ToBeKilled"] - $Att["Stunned"] < $Def[$t]["TargetNr"] )
					{
	//						$TotalCount -= $Def[$t]["TargetNr"] - $Att["Amount"] - $Att["ToBeKilled"] - $Att["Stunned"];
						$Def[$t]["TargetNr"] = $Att["Amount"] - $Att["ToBeKilled"] - $Att["Stunned"];
						if ( $Def[$t]["TargetNr"] <= 0 )
							continue;
					}


	   			/* calc shot going this way */
	   			if ( $Att["Shipid"]["Special"] == "Steals roids" )
	   				$FiringOnThese = round( $inGuns * ( $Def[$t]["MaxGrab"] / $MaxGrabCount ));
				else
	   				$FiringOnThese = round( $inGuns * ( $Def[$t]["TargetNr"] / $TargetCount ));


	  			if ( $FiringOnThese > $inGuns )
	   				$FiringOnThese = 0; /* bug catcher */

				CalcLog ( "Shooting : ". $Att["Shipid"]["Name"] ." on ". $Def[$t]["Shipid"]["Name"] ." with $FiringOnThese out of $inGuns<br>");

				if ( $FiringOnThese > 0 )
				{
					CalcLog ( $CalcLogBuffer[0] .  $CalcLogBuffer[1] .  $CalcLogBuffer[2] ."$AttFlt[Side] shooting : ". $Att["Shipid"]["Name"] ." on ". $Def[$t]["Shipid"]["Name"] ." with $FiringOnThese out of $inGuns guns, ", 2);
					$CalcLogBuffer[0] = null;
					$CalcLogBuffer[1] = null;
					$CalcLogBuffer[2] = null;
				}
	    		$ShotsFiredNow = ResolveAvgShots( $FiringOnThese, &$Att, &$Def[$t], &$Def, $RoidChance);
	    		$ShotsUsed += $ShotsFiredNow;

	    		if ( $ShotsFiredNow < $FiringOnThese )
	    			CalcLog( ", ".( $FiringOnThese - $ShotsFiredNow )." guns unused.<br>", 2 );
	    		elseif ( $FiringOnThese > 0 )
	   				CalcLog( ".<br>", 2 );

	    		CalcLog ( "Shots used: $ShotsUsed<br>");
	    	}
		}


        /* check for faulty roundings  */
        if ( $inGuns-$ShotsUsed < 0 )
        	return array( 0, true);


        /* check for no-shooters (usually error) */
        if ( $ShotsUsed == 0 )
        	return array( $inGuns, true );

       	/* if there were left-over shots */
       	if ( $Att["Shipid"]["Special"] == "Steals roids" )
	    	return array($inGuns-$ShotsUsed, true);

       	/* if there were left-over shots */
	    return array($inGuns-$ShotsUsed, false);
    }

	function ResolveAvgShots( $FiringOnThese, $AttShips, $DefShips, $Def, $RoidChance )
	{
		global $CalcLogBuffer;

		/* returns shots fired, calcs & adds casualties */

		if ( $FiringOnThese == 0 )
			return 0;

    	if ( $AttShips["Shipid"]["Special"] == "EMPs" )
		{
			/* ------ EMP Shot resolving ------ */
			$ToStunOne = 100 / ( 100 - $DefShips["Shipid"]["Emp_res"] );
			$ToStunAll = ceil ( $DefShips["TargetNr"] * ( $ToStunOne ) );

			CalcLog ( "ToStunOne: $ToStunOne<br>\n");
			CalcLog ( "ToStunAll: $ToStunAll<br>\n");

			if ( $ToStunAll < $FiringOnThese )
			{
				$ExcessShots = $FiringOnThese - $ToStunAll;
				$Diff = abs($DefShips["Amount"] - $DefShips["Stunned"] - $DefShips["ToBeStunned"]);
				$DefShips["ToBeStunned"] += $Diff;
				CalcLog ( "ExcessShots : $ExcessShots, ToStunAll : $ToStunAll<br>");
				CalcLog ( "ToBeStunned: ". $DefShips["ToBeStunned"] ."<br>\n");
				CalcLog ( "Guns fired : $FiringOnThese<br>");

				CalcLog ( "stunning <b>". $Diff . "</b> ". $DefShips["Shipid"]["Name"] ."(s)", 2);

				return $ToStunAll;
			}
			else
			{
				$Diff = floor( $FiringOnThese / $ToStunOne );
				$DefShips["ToBeStunned"] += $Diff;

				CalcLog ( "ToBeStunned: ". $DefShips["ToBeStunned"] ."<br>\n");
				CalcLog ( "Guns fired : $FiringOnThese<br>");

				CalcLog ( "stunning <b>". $Diff . "</b> ". $DefShips["Shipid"]["Name"] ."(s)", 2);

				return $FiringOnThese;
			}
		}
		elseif ( $AttShips["Shipid"]["Special"] == "Steals roids" )
		{
			if ( $DefShips["MaxGrab"] < $FiringOnThese )
			{
				$ExcessShots = $FiringOnThese - $DefShips["MaxGrab"];

				/* Kill the apods who capped */
				$AttShips["ToBeKilled"] += $DefShips["MaxGrab"];

				if ( $AttShips["Amount"] - $AttShips["ToBeKilled"] - $AttShips["Stunned"] > 0 )
					$DefShips["ToBeKilled"] = $DefShips["MaxGrab"];


				CalcLog ( "ExcessShots : $ExcessShots, ToGetAll : $ToGetAll<br>");
				CalcLog ( "ToBeGotten: ". $DefShips["ToBeKilled"] ."<br>\n");
				CalcLog ( "(grab)Guns fired : $FiringOnThese<br>");

				CalcLog ( "grabbing <b>". $DefShips["ToBeKilled"] . "</b> ". $DefShips["Shipid"]["Name"] ."(s)", 2);

				return $DefShips["MaxGrab"];
			}
			else
			{
				$DefShips["ToBeKilled"] += floor( $FiringOnThese );

				/* Kill the apods who capped */
				$AttShips["ToBeKilled"] += floor( $FiringOnThese );


				CalcLog ( "ToBeGotten: ". $DefShips["ToBeKilled"] ."<br>\n");
				CalcLog ( "(grab)Guns fired : $FiringOnThese<br>");

				CalcLog ( "grabbing <b>". $DefShips["ToBeKilled"] . "</b> ". $DefShips["Shipid"]["Name"] ."(s)", 2);

				return $FiringOnThese;
			}
		}
		else
		{
			/* ------ Normal Shot resolving ------ */

			CalcLog( "<b>Hits: $DefShips[Hits]</b><br>");

			$HitChance = ( 25 + $AttShips["Shipid"]["Weap_speed"] - $DefShips["Shipid"]["Agility"] )/100;

			if ( $HitChance > 0 )
			{
				$ToKillTheFirst = ceil( ($DefShips["Shipid"]["Armour"] - $DefShips["Hits"]) / $AttShips["Shipid"]["Gunpower"]  / $HitChance );
				$HitsToKillOne = ceil( $DefShips["Shipid"]["Armour"] / $AttShips["Shipid"]["Gunpower"] );
				$ToKillOne =  $HitsToKillOne / $HitChance;

				$ToKillAll = ceil($ToKillTheFirst + ( $DefShips["TargetNr"] - 1 ) * $ToKillOne);
			}
			else
			{
				CalcLog ( "killing <b>0</b> ". $DefShips["Shipid"]["Name"] ."(s)", 2);
				return $FiringOnThese;
			}

			CalcLog ( "ToKillOne: ". round($ToKillOne) ."<br>\n");
			CalcLog ( "HitsToKillOne: $HitsToKillOne<br>\n");
			CalcLog ( "ToKillAll: $ToKillAll<br>\n");
			CalcLog ( "ToKillTheFirst: $ToKillTheFirst<br>\n");

			if ( $ToKillAll < $FiringOnThese )
			{
				$ExcessShots = $FiringOnThese - $ToKillAll;
				$Diff =  $DefShips["Amount"] - $DefShips["ToBeKilled"];
				$DefShips["ToBeKilled"] = $DefShips["Amount"];
				CalcLog ( "ExcessShots : $ExcessShots, ToKillAll : $ToKillAll<br>");
				CalcLog ( "ToBeKilled: ". $DefShips["ToBeKilled"] ."<br>\n");
				CalcLog ( "Guns fired : $FiringOnThese<br>");

//				if ( $CalcLogBuffer[3] == "Restshots!" )
					CalcLog ( "killing <b>". $Diff . "</b> ". $DefShips["Shipid"]["Name"] ."(s)", 2);
//				else
//					CalcLog ( "killing <b>". $DefShips["ToBeKilled"] . "</b> ". $DefShips["Type"]["Name"] ."(s)", 2);

				return $ToKillAll;
			}
			elseif ( $ToKillTheFirst > $FiringOnThese )
			{
				$DefShips["Hits"] +=  floor($FiringOnThese * $AttShips["Shipid"]["Gunpower"] * $HitChance);
				CalcLog ( "ToBeKilled: ". $DefShips["ToBeKilled"] ."<br>\n");
				CalcLog ( "Hits: ". $DefShips["Hits"] ."<br>\n");
				CalcLog ( "Guns fired : $FiringOnThese<br>");

//				if ( $CalcLogBuffer[3] == "Restshots!" )
					CalcLog ( "killing <b>0</b> ". $DefShips["Shipid"]["Name"] ."(s)", 2);
//				else
//					CalcLog ( "killing <b>". $DefShips["ToBeKilled"] . "</b> ". $DefShips["Type"]["Name"] ."(s)", 2);

				return $FiringOnThese;
			}
			else
			{
				$Diff = 1 + floor( ( $FiringOnThese - $ToKillTheFirst ) / $ToKillOne );
				$DefShips["ToBeKilled"] += $Diff;

				/* Check if it's not-able to hit overflow, or not being able to hit strongly enough overflow */
				if ( $AttShips["Shipid"]["Gunpower"] * ( ( $FiringOnThese - $ToKillTheFirst ) % $ToKillOne) < $DefShips["Shipid"]["Armour"] )
					$DefShips["Hits"] = $AttShips["Shipid"]["Gunpower"] * ( ( $FiringOnThese - $ToKillTheFirst ) % $ToKillOne);
				else
					$DefShips["Hits"] = 0;

				CalcLog ( "ToBeKilled: ". $DefShips["ToBeKilled"] ."<br>\n");
				CalcLog ( "Hits: ". $DefShips["Hits"] ."<br>\n");
				CalcLog ( "Guns fired : $FiringOnThese<br>");

//				if ( $CalcLogBuffer[3] == "Restshots!" )
					CalcLog ( "killing <b>". $Diff ."</b> ". $DefShips["Shipid"]["Name"] ."(s)", 2);
//				else
//					CalcLog ( "killing <b>". $DefShips["ToBeKilled"] . "</b> ". $DefShips["Type"]["Name"] ."(s)", 2);

				return $FiringOnThese;
			}
		}
	}

    function ResolveShot( $AttShips, $DefShips )
    {
	    $RandomNr= rand(0, 100);

		if ( $AttShips["Shipid"]["Special"] == "EMPs" )
        {
            if ( $RandomNr < 100 - $DefShips["Shipid"]["Emp_res"] )
               	$DefShips["ToBeStunned"]++;
        }
		else if ( $RandomNr < 25 + $AttShips["Shipid"]["Weap_speed"] - $DefShips["Shipid"]["Agility"] )
        {
            $DefShips["ShotsOn"]++;
            $DefShips["Hits"] += $AttShips["Shipid"]["Gunpower"];
        }

//		if ( $AttShips["Type"]["Special"] == "Steals ships" )
//            $DefShips["BeingStolen"] = true;
    }

    function CleanUp( $DefFlt )
    {
    	$Def = &$DefFlt["Ships"];
    	$DefFlt["Totals"] = null;

		for( $t = 0; $t < count($Def); $t++ )
        {
            if ( $Def[$t]["Amount"] > 0 && ( $Def[$t]["ToBeKilled"] > 0 || $Def[$t]["ToBeStunned"] > 0 ))
            {
            	if ( true )
            	{
			        CalcLog ( "<br>Cleaning up casualties<br>\n");
			        CalcLog ( "Name :". $Def[$t]["Shipid"]["Name"] . "<br>\n");
			        CalcLog ( "TargetNr :". $Def[$t]["TargetNr"] . "<br>\n");
					CalcLog ( "Hits :". $Def[$t]["Hits"] . "<br>\n");
					CalcLog ( "Armour :". $Def[$t]["Shipid"]["Armour"] . "<br>\n");
					CalcLog ( "Amount :". $Def[$t]["Amount"] . " of which Stunned :". $Def[$t]["Stunned"] . "<br>\n");
					CalcLog ( "ToBeKilled :". $Def[$t]["ToBeKilled"] . "<br>\n");
					CalcLog ( "ToBeStunned :". $Def[$t]["ToBeStunned"] . "<br><br>\n");
				}

				$Def[$t]["Amount"] -= $Def[$t]["ToBeKilled"];

				if ($Def[$t]["Amount"] < 0 )
					$Def[$t]["Amount"] = 0;

				$Def[$t]["Stunned"] += $Def[$t]["ToBeStunned"];

				if ( $Def[$t]["Stunned"] > $Def[$t]["BeginAmount"] )
					$Def[$t]["Stunned"] = $Def[$t]["BeginAmount"];

	        }
			$Def[$t]["ToBeKilled"] = 0;
	        $Def[$t]["ToBeStunned"] = 0;
	        $Def[$t]["TargetNr"] = 0;

	        CalcTotals( &$DefFlt, $t, &$DefFlt["Totals"] );
        }
    }

    function ClearHitsStuns ( $Flt )
    {
		for ( $t = 0; $t < count($Flt); $t++ )
        {
        	$Flt[$t]["Stunned"] = 0;
        	$Flt[$t]["Hits"] = 0;
        }
    }
?>