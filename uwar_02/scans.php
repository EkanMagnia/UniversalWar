<?
error_reporting(1);
$section = "Intel";
include("functions.php");

include("data/intel.php");

$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'", $db);
$UserInfo = mysql_fetch_array($request);
$ScansAvailable = array();
$ScansAvailable = getAvailableScans($Scans);

function Scanning($AMPS_NUM, $TARGET_AMPS_NUM, $SCANS_LAUNCH_NUM, $TARGET_ROIDS)
{
	/*** Scan Formula - Chance in percent to get through:
	Success chance = 15 * (1 + (player amps / (target amps +1)) %
	NOTE: The percent chance will be different for each single wave amp (if launching more than one amp)
	because the factors in the formula will change, i.e when finding a getting through ***/

	$retval = '';
	$reached = '';
    global $Userid;
	global $scanid;
	global $db;

	//Executes the following loop as many times as the
    //number of probes waves that the user lanuched
	 $request = mysql_query("SELECT asteroid_mercury, asteroid_cobalt, asteroid_helium, ui_roids FROM uwar_users WHERE id='$Userid'");
	 $row = mysql_fetch_row($request);
	 $total_roids = $row[0]+$row[1]+$row[2]+$row[3];
     while($SCANS_LAUNCH_NUM > 0)
	 {
		  $used++;
	      $SUCCESS_CHANCE = '';
          $SCAN_PERMITTED = '';
		
		  $SUCCESS_CHANCE = round(30 * (1 + $AMPS_NUM/$total_roids - $TARGET_AMPS_NUM/$TARGET_ROIDS));
		  $SUCCESS_CHANCE = 1000 * $SUCCESS_CHANCE;
		  if ( $SUCCESS_CHANCE >= 99990) $SUCCESS_CHANCE =  99990;
	      if ( $SUCCESS_CHANCE <= 10) $SUCCESS_CHANCE = 10;

		  mt_srand ((double) microtime() * 1000000);
	      $retval = mt_rand(0, 100000);
		
		  //succesful
	      if ($retval < $SUCCESS_CHANCE )
		  {
				$reached = 1;
				//noticed
			    if (($retval + $scanid * 5000) > $SUCCESS_CHANCE )
				{
			         $reached = 2;
		        }
		//blocked
	      } else 
			$reached = 3;
		
/*		if ($scanid < 7)
		{
			$SUCCESS_CHANCE = round(1 + ($AMPS_NUM / ($TARGET_AMPS_NUM + 1) ));
			if ( $SUCCESS_CHANCE > 100 ) $SUCCESS_CHANCE = 100; 	// If % chance > 100%, set it to 100%

			$SCAN_PERMITTED = rand(1 ,100); 	// Generate a number between 1-100
			// Increase $retval if the number is between 1 and $SUCCESS_CHANCE
			if ( $SCAN_PERMITTED <= $SUCCESS_CHANCE ) { $retval = 1; break; }
			
		}
		else 
		{
			$SUCCESS_CHANCE = ($SCANS_LAUNCH_NUM + $AMPS_NUM * 2) / ($TARGET_AMPS_NUM * 3 +1);
			if ($SUCCESS_CHANCE >= 1) { $retval = 1; break; }
		} */
			$SCANS_LAUNCH_NUM--;
	}
	//Remove the number of scans that were used
    $scansnumber_request = mysql_query("SELECT stock FROM uwar_scans WHERE scanid='$scanid' AND userid='$Userid'");
	$scansnumber_array = mysql_fetch_array($scansnumber_request);
	$scansnumber = $scansnumber_array["stock"] - $used;
//	print "scansnumber = ".$scansnumber."<br>";
	mysql_query("UPDATE uwar_scans SET stock='$scansnumber' WHERE scanid='$scanid' AND userid='$Userid'",$db);
	
	if ($reached == 1) return 1;
	elseif ($reached == 2) return 2;
	else return 3;
}




//This also checks that the user has the scan type available
if(isset($x) && isset($y) && isset($z) && isset($scan_type) && is_numeric($scan_type))
{

	$scanid = $Scans[$scan_type]["Scanid"];
	$sysSQL = mysql_query("SELECT id FROM uwar_systems WHERE x='$x' AND y='$y'");
	$userSQL = mysql_query("SELECT id FROM uwar_users WHERE z='$z'");
	$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'", $db);
	$myrow = mysql_fetch_array($request);

//	print "stealth = ".$myrow["stealth"];
if ($myrow["stealth"] >= 10)
{	
	if (mysql_num_rows($sysSQL) > 0 && mysql_num_rows($userSQL) > 0)
	{
		$hasScans = mysql_query("SELECT * FROM uwar_scans WHERE userid='$Userid' AND scanid='$scanid' AND stock > 0");
		if ( is_numeric($x) && is_numeric($y) && is_numeric($z) )
		{
			if (mysql_num_rows($hasScans) > 0)
			{
				$stealth = $myrow["stealth"];			
				//if $number is not a number, set it to 1 or if $number is bigger than the user has, set it to max...
				$hasScans = mysql_query("SELECT * FROM uwar_scans WHERE userid='$Userid' AND scanid='$scanid' AND stock >= $number");
				if(!is_numeric($number) || $number < 1) $number = 1;
				if(mysql_num_rows($hasScans) == 0) $number = $hasScans["stock"];

				//Get how many scans the user has
				$yourscans_request = mysql_query("SELECT stock FROM uwar_scans WHERE scanid='0' AND userid='$Userid'");
				$yourscans = mysql_fetch_array($yourscans_request);
				//Get how many scans the target has
				$sysid_request = mysql_query("SELECT id FROM uwar_systems WHERE x='$x' AND y='$y'");
				$sysid_array = mysql_fetch_array($sysid_request);
				$sysid = $sysid_array["id"];
				$targetinfo_request = mysql_query("SELECT * FROM uwar_users WHERE sysid='$sysid' AND z='$z'");
				$targetinfo = mysql_fetch_array($targetinfo_request);
				$target_probes = $targetinfo["asteroid_mercury"]+$targetinfo["asteroid_cobalt"]+ $targetinfo["asteroid_helium"]+$targetinfo["ui_roids"];
				$targetid = $targetinfo["id"];
				$targetscans_request = mysql_query("SELECT stock FROM uwar_scans WHERE scanid='0' AND userid='$targetid'");
				$targetscans = mysql_fetch_array($targetscans_request);

		        //If the user scans him/her self just one scan will be launched and the success chance is automatically 100%
			    if($targetinfo["id"] != $Userid)
				{
					$number2 = $number;
					$EXECUTE_SCAN = Scanning($yourscans["stock"], $targetscans["stock"], $number, $target_probes);
				}
	    		else 
				{	
					$EXECUTE_SCAN = 0;
					$used = 0;
					$msgred = "You can not scan / initiate an offensive operation on your own planet!";
				}
				include("scanfunction.php");
				if($EXECUTE_SCAN == 1)
					scanNow($scan_type, $targetinfo, $Scans, $x, $y, $z);
				elseif ($EXECUTE_SCAN == 2)
				{
					scanNow($scan_type, $targetinfo, $Scans, $x, $y, $z);
					$subject = "Scan noticed";
					$news = "We have noticed a ".$Scans[$scan]["Name"]." originating from ".$myrow["nick"]." of ".$myrow["planet"]." (".$gal["x"].":".$gal["y"].":".$myrow["z"].").";		
		            Add_News($subject, $news, $targetinfo["id"]);
					$msgred = "Scan has been noticed!";
				}
				elseif ($EXECUTE_SCAN == 3)
				{
					$subject = "Scan blocked";
					$news = "We have blocked a ".$Scans[$scan]["Name"]." originating from ".$myrow["nick"]." of ".$myrow["planet"]." (".$gal["x"].":".$gal["y"].":".$myrow["z"].").";		
		            Add_News($subject, $news, $targetinfo["id"]);
					$msgred = "Scan blocked by target!";
				}
			}
			else
				$msgred = "You do not have any scan of that type!";
		}
       else
			$msgred = "The coordinates you have specified are not of numeric format!";
	}
	else
		$msgred = "The planet you are trying to scan doesn't exist!";
}
else 
	$msgred = "Your stealth rate is less than 10%. You can not perform a scan until it raises!";
}
if ( isset($action) )
{

if ( $action == "build")
    {
		//Loops through all amount fields
		for($counter=0; $counter < count($amount); $counter++)
		{
	        //If the current amount field is empty, skip that and continue with next ship and amount field
	        if(!$amount[$counter])
	            continue;

	        //Checks if the user has enough of resources to build one of the current scan
	        if ( $UserInfo["mercury"] >= $Scans[$counter]["Mercury"] && $UserInfo["cobalt"] >= $Scans[$counter]["Cobalt"] && $UserInfo["helium"] >= $Scans[$counter]["Helium"])
	        {
	            //If the user tries to buy more scans than he has afford to, buy as many scans as possible
	            //So this if case takes care of that stuff. Not very well coded but it works :D
	            if ( $UserInfo["mercury"] < $Scans[$counter]["Mercury"] * $amount[$counter] || $UserInfo["cobalt"] < $Scans[$counter]["Cobalt"] * $amount[$counter] || $UserInfo["helium"] < $Scans[$counter]["Helium"] * $amount[$counter])
	            {

					$scanstobuy_mercury = floor($UserInfo["mercury"] / $Scans[$counter]["Mercury"]);
					$scanstobuy_cobalt = floor($UserInfo["cobalt"] / $Scans[$counter]["Cobalt"]);
	                $scanstobuy_helium = floor($UserInfo["helium"] / $Scans[$counter]["Helium"]);
					
					if ($counter == 1)
					{
						$scanstobuy = floor($UserInfo["mercury"] / $Scans[$counter]["Mercury"]);
                        $amount[$counter] = $scanstobuy;

					}
					else
					{
						$scanstobuy = floor($UserInfo["helium"] / $Scans[$counter]["Helium"]);
		                $amount[$counter] = $scanstobuy;
					}
//						$scanstobuy = 
//					$scanstobuy = min($scanstobuy_mercury, $scanstobuy_cobalt, $scanstobuy_helium);

	            }

	        // Subtracts the order costs(shipcost * amount) from the users resources
	        $UserInfo["mercury"] -= $Scans[$counter]["Mercury"] * $amount[$counter];
	        $UserInfo["cobalt"] -= $Scans[$counter]["Cobalt"] * $amount[$counter];
	        $UserInfo["helium"] -= $Scans[$counter]["Helium"] * $amount[$counter];

	        // Update the user resources
	        $request = mysql_query("UPDATE uwar_users SET mercury=$UserInfo[mercury], cobalt=$UserInfo[cobalt], helium=$UserInfo[helium] WHERE id=$Userid");

			$eta = $Scans[$counter]['BuildTime'];
			//Select an order of same eta and ship, if there is some
			$request = mysql_query("SELECT * FROM uwar_pscans WHERE userid='$UserInfo[id]' AND scanid='$counter' AND eta='$eta'");

	        //if there was any order of same eta and ship:
	        if($result = mysql_fetch_array($request))
	        {
	            $totalamount = $amount[$counter] + $result['amount']; //the new amount that the order will be updated to
	            //Updates the current order into uwar_pships
	            $request = mysql_query("UPDATE uwar_pscans SET userid='$Userid', scanid='$counter', amount='$totalamount', eta='$eta' WHERE userid='$Userid' AND scanid='$counter' AND eta='$eta'");
	        }
	        else //if there wasn't any order of same eta and ship:
	        {
	            //Inserts a new order into uwar_pships
	            $request = mysql_query("INSERT INTO uwar_pscans (userid, scanid, amount, eta) VALUES ('$Userid', '$counter', '$amount[$counter]', '$eta')") or die("snail");
	        }
		}
        else
	       	$msgred = "You have insufficient resources!";
  	}
}
else
	$msgred= "Invalid Action!";
}

include_once("header.php");

if(isset($msgred))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";

if(isset($msgreen))
	print "<CENTER><FONT face=Arial size=2 color=#009900><B>".$msgred."</B></FONT></CENTER><BR>";	
headerDsp("Stealth");
?><center><table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td align="center">Stealth rate = 
		<? 
		$request32 = mysql_query("SELECT stealth FROM uwar_users WHERE id='$Userid'");
		$st = mysql_fetch_array($request32);
		$stealth = $st["stealth"];
		print $stealth;
		$PercentDone = $stealth * 3;
		?> %</td>
		<TR>		
		<TD align="center">
			<TABLE style="BORDER-COLLAPSE: collapse" borderColor="#808080" height="20" width="300" border="1" bgcolor="#000000" cellspacing="0" cellpadding="0">
				<TR>
					<TD><? 	print "<img src=images/red.jpg width=".$PercentDone." height=10 alt=".$stealth."% is done>";?>
					</TD>
				</TR>
			</TABLE>
		</TD>
		</TR>
	</TABLE>
<?
footerDsp();
$scansSQL = mysql_query("SELECT stock FROM uwar_scans WHERE userid='$Userid' AND stock > 0 AND scanid !=0 AND scanid !=1");
if (mysql_fetch_array($scansSQL))
{
headerDsp("Intel");
?>
<br><img src="images/arrow_off.gif">Scanning
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>">Scan type</td>
		<td bgcolor="<?=$tdbg1;?>">Coordinates</td>
		<td bgcolor="<?=$tdbg1;?>">Number</td>
		<td bgcolor="<?=$tdbg1;?>">Perform</td>
	</tr>
	<tr>
		<form action="<? print $PHP_SELF; ?>" method=post>
		<td bgcolor="<?=$tdbg2;?>" align="center">
			<select name=scan_type>
			<?
			foreach($ScansAvailable as $idx => $scan)
			{
				if($idx == "0" || $idx == "1") continue; //scan 0 and 1 are not scans used against targets

				$scanid = $Scans[$scan]["Scanid"];
				$request = mysql_query("SELECT * FROM uwar_scans WHERE userid='$Userid' AND scanid='$scanid'");
				$hasScans = mysql_fetch_array($request);
				if($hasScans["stock"] < 1) continue;
				?><option value=<?=$Scans[$scan]["Scanid"]?>><?=$Scans[$scan]["Name"]?></option><?
			}
			?>
			</select>
		</td>
		<td bgcolor="<?=$tdbg2;?>" align="center">
			<input type="text" maxlength="2" size=3 name=x value="<?if(isset($scanx) && is_numeric($scanx))print $scanx;?>"> :
			<input type="text" maxlength="2" size=3 name=y value="<?if(isset($scany) && is_numeric($scany))print $scany;?>"> :
			<input type="text" maxlength="2" size=3 name=z value="<?if(isset($scanz) && is_numeric($scanz))print $scanz;?>">
		</td>
		<td bgcolor="<?=$tdbg2;?>" align="center"><input type="text" maxlength="8" size="5" name="number" value="1"></td>
		<td bgcolor="<?=$tdbg2;?>" align="center"><input type="submit" value="Scan target"></td>
		</form>
	</tr>
</table>
</center>
<br>
<?
footerDsp();
}
headerDsp("Scans");
?>

<br><img src="images/arrow_off.gif">Scan Production
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<tr>
<td bgcolor="<?=$tdbg1;?>">Name</td>
<td bgcolor="<?=$tdbg1;?>">Description</td>
<td bgcolor="<?=$tdbg1;?>">ETA</td>
<td bgcolor="<?=$tdbg1;?>">Cost</td>
<td bgcolor="<?=$tdbg1;?>">Stock</td>
<td bgcolor="<?=$tdbg1;?>">Order</td>
</tr>

<form action="<? print $PHP_SELF; ?>?action=build" method="post">
<?

//Prepare for printing the stock
$stocks = array();
foreach ( $ScansAvailable as $x => $scanid )
{
	$request = mysql_query("SELECT * FROM uwar_scans WHERE userid='$Userid' AND scanid='$scanid' ORDER BY scanid");
	$stocks[] = mysql_fetch_array($request);
}

//Loops trhough all ships, and prints name, desc, eta, costs, stock
foreach ( $ScansAvailable as $idx => $scan )
{
	/*if($idx > sizeof($ShipsAvailable)-1)
		break;
	if ($idx >= 15) continue;*/
?>
<tr>
<td bgcolor="<?=$tdbg2;?>"><center><?=$Scans[$scan]["Name"]?></center></td>
<td bgcolor="<?=$tdbg2;?>"><?=$Scans[$scan]["Description"]?></td>
<td bgcolor="<?=$tdbg2;?>"><center><?=$Scans[$scan]["BuildTime"]?></center></td>
<td bgcolor="<?=$tdbg2;?>">
<? if ($Scans[$scan]['Mercury'] != 0) {print $Scans[$scan]["Mercury"]."m";}
if ($Scans[$scan]['Cobalt'] != 0) {print $Scans[$scan]["Cobalt"]."co";}
	if ($Scans[$scan]['Helium'] != 0) {print $Scans[$scan]["Helium"]."ca";} ?>
</td>
<td bgcolor="<?=$tdbg2;?>"><center>
<?
	if($stocks[$idx] > 0)
			print number_format($stocks[$idx]["stock"],0,".",".");
		else print "0";
?>
</center></td>
<td bgcolor="<?=$tdbg2;?>"><center>
<input type="text" size="5" name="amount[<?=$idx?>]" value=""></center></td>
	<?	$scanid = $Scans[$scan]["Scanid"]; ?>
<!--	<input type="hidden" name="amount[<?=$idx?>][scanid]" value=<?=$scanid?>> -->
<?
}
?>
</tr>
<tr>
<td colspan="6" bgcolor="<?=$tdbg2;?>" align="center"><input type="submit" size="3" value="Build Scans"></center></td>
</tr>
</table>
</form>
</center>
<img src="images/arrow_off.gif">Current Production
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<tr>
<td bgcolor="<?=$tdbg1;?>"><center>Scan Name</center></td>
<?
for ( $i=1;$i<=20;$i++ ) {
?>
<td bgcolor="<?=$tdbg1;?>"><center><?=$i?></center></td>
<?
}
?>
</tr>
<?
//Loops trough all ships
foreach ( $ScansAvailable as $idx => $scan ) 
{
	//Makes an array with as many elements as the eta of the current ship
	$orders = array();
	for($i=0; $i < $Scans[$scan]["BuildTime"]; $i++) $orders[$i] = 0;

	//Fill the elements with orders of the current ship, as far as possible, fill ordered by eta
	$request = mysql_query("SELECT * FROM uwar_pscans WHERE userid='$Userid' AND scanid='$scan' order by eta");
	for($i=0; $orders[$i] = mysql_fetch_array($request);$i++) {}
	$j = 0;
	?>
	<tr>
	<td bgcolor="<?=$tdbg2;?>"><? print $Scans[$scan]["Name"]; ?></td>
	<?
	//Makes X cells for each tick depending on how long eta the current ship has
	for( $i=1; $i<=$Scans[$scan]["BuildTime"]; $i++ ) 
	{
		?><td bgcolor="<?=$tdbg2;?>"><center><?
		//When the eta is = $i, print out the amount and increase $j with 1 so next order of the current ship will be used
		if($orders[$j]['eta'] == $i)
		{
			print number_format($orders[$j]['amount'],0,".",".");
			$j++;
		}
		?></td></center><?
	}
}
?>
</tr>
</table><br>
</center>
<?
footerDsp();
include("footer.php");
?>