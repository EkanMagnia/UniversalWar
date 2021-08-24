<?
error_reporting(1);
$section = "Resources";
include("functions.php");

/*function calcres($number)
{
    global $roids;
    srand ((double) microtime() * 1000000);
    $retval = 0;
    $a = 0;
    if ($a!=$number)
    {
       do
        {
            $a++;
            $tmp = rand(0,1+($roids+$retval));
            if ($tmp<20) $retval++;
        }while($a<$number);
    }
    return $retval;
}*/

function AsteroidScanning($AMPS_NUM, $ASTEROIDS_NUM, $PROBES_LAUNCH_NUM)
{
	/*** Asteroid Scan Formula - Chance in percent of finding an asteroid:
	% Chance = 25 * (1 + (NumberOfAmps / (NumberOfAsteroids * 2)))
	NOTE: The percent chance will be different for each single wave amp (if launching more than one amp)
	because the factors in the formula will change, i.e when finding a roid ***/

	$retval = '';
    global $used;
	if(!$ASTEROIDS_NUM || !isset($ASTEROIDS_NUM))
	{
		$ASTEROIDS_NUM = 1;
	}
	//Executes the following loop as many times as the
    //number of probes waves that the user lanuched
	while($PROBES_LAUNCH_NUM > 0)
	{
	    $used++;
		$SUCCESS_CHANCE = '';
		$ROID_FOUND = '';
		if ($ASTEROIDS_NUM <= 100)
			$SUCCESS_CHANCE = round(25 * (1 + ($AMPS_NUM / $ASTEROIDS_NUM + 1)));
		elseif ($ASTEROIDS_NUM > 100 && $ASTEROID_NUM < 1000)
			$SUCCESS_CHANCE = round(10 * (1 + ($AMPS_NUM / $ASTEROIDS_NUM * 2 + 1)));
		elseif ($ASTEROIDS_NUM >= 1000)
			$SUCCESS_CHANCE = round(2 * (1 + ($AMPS_NUM / $ASTEROIDS_NUM * 4 + 1)));
//			print "Success chance = ".$SUCCESS_CHANCE;
		if ( $SUCCESS_CHANCE > 100 ) $SUCCESS_CHANCE = 100; 	// If % chance > 100%, set it to 100%

//		srand();
		$ROID_FOUND = rand(1 ,100); 	// Generate a number between 1-100
		// Increase $retval(number of found roids) if the number is between 1 - $SUCCES_CHANCE
		if ( $ROID_FOUND <= $SUCCESS_CHANCE ) { $retval++; $ASTEROIDS_NUM++;}
		$PROBES_LAUNCH_NUM--;

	}
	if($retval) return $retval;
    else return 0;
}


function calccost($number)
{
    //Calculates the cost of to initiate next probe
    global $i_roids;
	if ($i_roids <= 1000)
		$cost = 250 * $i_roids+250;
	else $cost = 300 * $i_roids+250;
    return $cost;
}

$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'",$db);
$UserInfo = mysql_fetch_array($request);
$Construction = mysql_query("SELECT * FROM uwar_constructions WHERE userid='$Userid' AND constructionid=1 AND complete>=1",$db);
$request = mysql_query("SELECT * FROM uwar_scans WHERE userid='$Userid' and scanid=1",$db);
$ProbeWaves = mysql_fetch_array($request);
$request = mysql_query("SELECT * FROM uwar_scans WHERE userid='$Userid' AND scanid=0",$db);
$Amps = mysql_fetch_array($request);


if(isset($scannumber) && $scannumber > 0)
{
	//If the user has the scanbuilding and more than 0 probe waves
	if (mysql_num_rows($Construction) > 0 && $ProbeWaves["stock"] > 0 && is_numeric($scannumber))
	{
	   if($scannumber > $ProbeWaves["stock"]) $scannumber = $ProbeWaves["stock"];

	   $found = AsteroidScanning($Amps["stock"], $i_roids, $scannumber);

       $ProbeWaves["stock"] = $ProbeWaves["stock"] - $used;
	    mysql_query("UPDATE uwar_users SET ui_roids=ui_roids+$found WHERE id='$Userid'",$db);
        mysql_query("UPDATE uwar_scans SET stock='$ProbeWaves[stock]' WHERE userid='$Userid' AND scanid=1");
		$ui_roids += $found;
		$roids += $found;
		if($found > 0)$msggreen = "You found $found probes!";
        else $msgred = "You did not find any probes!";
	}
}

if (isset($init_submit) && (isset($initmer) || isset($initcob) || isset($inithel)))
{
    $initmer = strip_tags($initmer);
	$initcob = strip_tags($initcob);
	$inithel = strip_tags($inithel);
	if ($initmer < 0) $initmer = 0;
	if ($initcob < 0) $initcob = 0;
	if ($inithel < 0) $inithel = 0;

	if($initmer == 0 && $initcob == 0 && $inithel == 0)
	{
		//do nothing :/
	}
	else 
	{

		$cost = calccost(0);
		$cobalt = $UserInfo["cobalt"];
		if ($cobalt >= $cost)
		{
			//Decides the max number
			$maxInit = floor ($cobalt / $cost);
			if($maxInit > $ui_roids) $maxInit = $ui_roids;

			$initTotal = $initmer + $initcob + $inithel;
			$mercuryinit = 0;
			$cobaltinit = 0;
			$heliuminit = 0;

			while($cobalt >= $cost && $maxInit > $i && $initTotal > 0 && $initTotal > $i && $ui_roids > 0)
			{
				if($ui_roids == 0) break;
				$cost = calccost(0);
				if($initmer > 0 && $cobalt >= $cost && $maxInit > $i && $initTotal > 0 && $initTotal > $i && $ui_roids > 0)
				{
					$ui_roids--;
					$i_roids++;
					$mercuryroid++;
					$cobalt -= $cost;
					mysql_query("UPDATE uwar_users SET cobalt='$cobalt', ui_roids='$ui_roids', asteroid_mercury='$mercuryroid' WHERE id='$Userid'", $db);
					$initmer--;
					$mercuryinit++;
				}
				$cost = calccost(0);
				if($initcob > 0 && $cobalt >= $cost && $maxInit > $i && $initTotal > 0 && $initTotal > $i && $ui_roids > 0)
				{
					$ui_roids--;
					$i_roids++;
					$cobaltroid++;
					$cobalt -= $cost;
					mysql_query("UPDATE uwar_users SET cobalt='$cobalt', ui_roids='$ui_roids', asteroid_cobalt='$cobaltroid' WHERE id='$Userid'", $db);
					$initcob--;
					$cobaltinit++;
				}
				$cost = calccost(0);
				if($inithel > 0 && $cobalt >= $cost && $maxInit > $i && $initTotal > 0 && $initTotal > $i && $ui_roids > 0)
				{
					$ui_roids--;
					$i_roids++;
					$heliumroid++;
					$cobalt -= $cost;
					mysql_query("UPDATE uwar_users SET cobalt='$cobalt', ui_roids='$ui_roids', asteroid_helium='$heliumroid' WHERE id='$Userid'", $db);
					$inithel--;
					$heliuminit++;
				}
				$i++;
			}
			$initiation = "";
			if($mercuryinit > 0) $initiation .= "m";
			if($cobaltinit > 0) $initiation .= "c";
			if($heliuminit > 0) $initiation .= "h";
			$msggreen = "You have initiated";

			switch($initiation)
			{
				case 'm':
					$msggreen .= " $mercuryinit mercury probe(s)!";
				break;

				case 'c':
					$msggreen .= " $cobaltinit cobalt probe(s)!";
				break;

				case 'h':
					$msggreen .= " $heliuminit caesium probe(s)!";
				break;

				case 'mc':
					$msggreen .= " $mercuryinit mercury probe(s) and $cobaltinit cobalt probe(s)!";
				break;
				
				case 'mh':
					$msggreen .= " $mercuryinit mercury probe(s) and $heliuminit caesium probe(s)!";
				break;
				
				case 'ch':
					$msggreen .= " $cobaltinit cobalt probe(s) and $heliuminit caesium probe(s)!";
				break;
				
				case 'mch':
					$msggreen .= " $mercuryinit mercury probe(s), $cobaltinit cobalt probe(s) and $heliuminit caesium probe(s)!";
				break;
			}
		}
		else $msgred = "You do not have enough of cobalt!";
	}
}

include("header.php");

if(isset($msggreen))
	print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";
if(isset($msgred))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";

$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'",$db);
$UserInfo = mysql_fetch_array($request);
$ConSQL = mysql_query("SELECT * FROM uwar_constructions WHERE constructionid='0' and userid='$Userid'",$db);
$con = mysql_fetch_array($ConSQL);

		if ($con["complete"] == 0) $planetmercury = 250;
		elseif ($con["complete"] >= 1 && $con["complete"] < 4) $planetmercury = 500;
		elseif ($con["complete"] >= 4 && $con["complete"] < 7) $planetmercury = 2000;
		elseif ($con["complete"] >= 7 && $con["complete"] < 10) $planetmercury = 5000;
		elseif ($con["complete"] == 10) $planetmercury = 10000;
		if ($UserInfo["commander"] == "1")
			$planetmercury += $planetmercury/10;
		elseif ($UserInfo["commander"] > 1) 
			$planetmercury += $planetmercury/20;

			$mercuryincome = $planetmercury + $UserInfo["asteroid_mercury"] * 450;	
	
			$totalmercury = $planetmercury + $UserInfo["asteroid_mercury"] * 450;	

		if ($con["complete"] < 2)   $planetcobalt = 250;
		elseif ($con["complete"] >= 2 && $con["complete"] < 5) $planetcobalt = 500;
		elseif ($con["complete"] >= 5 && $con["complete"] < 8) $planetcobalt = 2000;
		elseif ($con["complete"] >= 8 && $con["complete"] < 10) $planetcobalt = 5000;
		elseif ($con["complete"] == 10) $planetcobalt = 10000;
		if ($UserInfo["commander"] == 1)
			$planetcobalt += $planetcobalt/10;
		elseif ($UserInfo["commander"] > 1) 
			$planetcobalt += $planetcobalt/20;

			$totalcobalt = $planetcobalt + $cobaltroid * 450;	

		if ($con["complete"] < 3)   $planethelium = 250;
		elseif ($con["complete"] >= 3 && $con["complete"] < 6) $planethelium = 500;
		elseif ($con["complete"] >= 6 && $con["complete"] < 9) $planethelium = 2000;
		elseif ($con["complete"] == 9 ) $planethelium = 5000;
		elseif ($con["complete"] == 10) $planethelium = 10000;
		if ($UserInfo["commander"] == "1")
			$planethelium += $planethelium/10;
		elseif ($UserInfo["commander"] > 1) 
			$planethelium += $planethelium/20;
			$totalhelium = $planethelium + $heliumroid * 450;	
/*
$totalcobalt = $planetcobalt + $cobincomroids;
$totalmercury = $planetmercury + $merincomroids;
$totalhelium = $planethelium + $helincomroids;
*/

if($ProbeWaves["stock"] > 0)
{
	headerDsp("Probes Scaning");
	?>
	<br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<form action="<?=$PHP_SELF?>" method="post">
	    <tr>
	        <td bgcolor="<?=$tdbg1;?>">Probe Waves</td>
	        <td bgcolor="<?=$tdbg1;?>">Number</td>
	        <td bgcolor="<?=$tdbg1;?>">Perform</td>
	    </tr>
	    <tr>
	        <td bgcolor="<?=$tdbg2;?>" width="10%"><?=number_format($ProbeWaves["stock"],0,".",".");?></td>
	        <td bgcolor="<?=$tdbg2;?>" width="10%" align="center"><input type="text" maxlength="7" size="5" name="scannumber" value=""></td>
	        <td bgcolor="<?=$tdbg2;?>" width="20%" align="center"><input type="submit" value="Send Waves"></td>
	    </tr>
	</form>
	</table>
	<br>
	<?
	footerDsp();
}
$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'",$db);
$UserInfo = mysql_fetch_array($request);
		$cobincomroids = $cobaltroid * 450;
		$merincomroids = $mercuryroid * 450;
		$helincomroids = $heliumroid * 450;

headerDsp("Resource Management");
?>
<br>
<center>
<? print "<b>".number_format($roids,0,".",".")."</b> probes in total"; ?>
<br><br>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>">Type:</td>
		<td bgcolor="<?=$tdbg1;?>">Number:</td>
		<td bgcolor="<?=$tdbg1;?>">Probes Income:</td>
		<td bgcolor="<?=$tdbg1;?>">Planet Income:</td>
		<td bgcolor="<?=$tdbg1;?>">Total Income:</td>
    </tr>
    <tr>
	    <td bgcolor="<?=$tdbg2;?>">Mercury Probes:</td>
	    <td bgcolor="<?=$tdbg2;?>"><?=number_format($mercuryroid,0,".",".")?></td>
	    <td bgcolor="<?=$tdbg2;?>"><?=number_format($merincomroids,0,".",".")?></td>
	    <td bgcolor="<?=$tdbg2;?>"><?=number_format($planetmercury,0,".",".")?></td>
	    <td bgcolor="<?=$tdbg2;?>"><?=number_format($totalmercury,0,".",".")?></td>
    </tr>
    <tr>
	    <td bgcolor="<?=$tdbg2;?>">Cobalt Probes:</td>
	    <td bgcolor="<?=$tdbg2;?>"><?=number_format($cobaltroid,0,".",".")?></td>
	    <td bgcolor="<?=$tdbg2;?>"><?=number_format($cobincomroids,0,".",".")?></td>
	    <td bgcolor="<?=$tdbg2;?>"><?=number_format($planetcobalt,0,".",".")?></td>
	    <td bgcolor="<?=$tdbg2;?>"><?=number_format($totalcobalt,0,".",".")?></td>
    </tr>
    <tr>
	    <td bgcolor="<?=$tdbg2;?>">Caesium Probes:</td>
	    <td bgcolor="<?=$tdbg2;?>"><?=number_format($heliumroid,0,".",".")?></td>
	    <td bgcolor="<?=$tdbg2;?>"><?=number_format($helincomroids,0,".",".")?></td>
	    <td bgcolor="<?=$tdbg2;?>"><?=number_format($planethelium,0,".",".")?></td>
	    <td bgcolor="<?=$tdbg2;?>"><?=number_format($totalhelium,0,".",".")?></td>
    </tr>
    <tr>
	    <td bgcolor="<?=$tdbg2;?>">Uninitiated Probes:</td>
	    <td bgcolor="<?=$tdbg2;?>" colspan="5"><?=number_format($ui_roids,0,".",".")?></td>
	</tr>
</table>
<br>
<?
footerDsp();

if ($ui_roids > 0)
{
	headerDsp( "Initiate Probes" );
	?>
    <br>
	<center>
	<? print "Cost to initiate next probe:<b> ".calccost(0)." </b>cobalt"; ?>
    <br><br>
    <table border="0" cellpadding="4" cellspacing="1" width="90%">
    <form action="<?=$PHP_SELF?>" method="post">
		<tr>
	        <td bgcolor="<?=$tdbg1;?>">Type:</td>
	        <td bgcolor="<?=$tdbg1;?>">Number:</td>
        </tr>
        <tr>
	        <td bgcolor="<?=$tdbg2;?>">Mercury:</td>
	        <td bgcolor="<?=$tdbg2;?>"><input type="text" size="9" name="initmer" value="0"></td>
        </tr>
        <tr>
	        <td bgcolor="<?=$tdbg2;?>">Cobalt:</td>
	        <td bgcolor="<?=$tdbg2;?>"><input type="text" size="9" name="initcob" value="0"></td>
        </tr>
        <tr>
	        <td bgcolor="<?=$tdbg2;?>">Caesium:</td>
	        <td bgcolor="<?=$tdbg2;?>"><input type="text" size="9" name="inithel" value="0"></td>
        </tr>
        <tr>
			<td bgcolor="<?=$tdbg2;?>" colspan="2" align="center"><input type="Submit" name="init_submit" value="Initiate Probes"></td>
        </tr>
	</form>
    </table>
    <br>
	</center>
<?
footerDsp();
}
include("footer.php");
?>