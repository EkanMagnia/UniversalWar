<?
error_reporting(1);
$section = "Ship Production";
include("functions.php");
include("data/ShipTypes.php");

$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'", $db);
$UserInfo = mysql_fetch_array($request);
$ShipsAvailable = array();
$ShipsAvailable = getAvailableShips($ShipTypes, $Userid);

if ( isset($action) )
{
	if ( $action == "build")
    {
		$amountfields = sizeof($amount);
		//Loops through all amount fields
		for($counter=0; $counter < $amountfields; $counter++)
		{
			//If the current amount field is empty or negative number, skip that and continue with next ship and amount field
			if(!$amount[$counter]["amount"] || !is_numeric($amount[$counter]["amount"]) || $amount[$counter]["amount"] < 1) continue;
			

			$shipid = $amount[$counter]["shipid"];

	        //Checks if the user has enough of resources to build one of the current ship
	        if ( $UserInfo["mercury"] >= $ShipTypes[$shipid]["Mercury"] && $UserInfo["cobalt"] >= $ShipTypes[$shipid]["Cobalt"] && $UserInfo["helium"] >= $ShipTypes[$shipid]["Helium"])
	        {
	            //If the user tries to buy more ships than he has afford to, as many ships as possible will be bought.. 
				//So this if case takes care of that stuff. Not very well coded but it works :D

				if ( $UserInfo["mercury"] < $ShipTypes[$shipid]["Mercury"] * $amount[$counter]["amount"] || $UserInfo["cobalt"] < $ShipTypes[$shipid]["Cobalt"] * $amount[$counter]["amount"] || $UserInfo["helium"] < $ShipTypes[$shipid]["Helium"] * $amount[$counter]["amount"])
	            {
					//ship 0 and 1 only costs mercury/cobalt
	                if($shipid == 0 || $shipid == 1)
	                {
	                    if($shipid == 0)
	                    {
	                        $shipstobuy = floor($UserInfo["mercury"] / $ShipTypes[$shipid]["Mercury"]);
	                        $amount[$counter]["amount"] = $shipstobuy;
	                    }
	                    else
	                    {
	                        $shipstobuy = floor($UserInfo["cobalt"] / $ShipTypes[$shipid]["Cobalt"]);
	                        $amount[$counter]["amount"] = $shipstobuy;
	                    }

	                }
	                else
	                {
						//This part is recoded. Suspect the former code here was giving too many ships when entering 999999999....
						//How many ships u can buy from the mercury you have
						$shipstobuy_mercury = floor($UserInfo["mercury"] / $ShipTypes[$shipid]["Mercury"]); 

						//How many ships u can buy from the cobalt you have
						$shipstobuy_cobalt = floor($UserInfo["cobalt"] / $ShipTypes[$shipid]["Cobalt"]); 

						//How many ships u can buy from the caesium you have
//						$shipstobuy_caesium = floor($UserInfo["helium"] / 		$ShipTypes[$shipid]["Helium"]); 
						
						//Decides the final ships amount to buy
						$shipstobuy = min($shipstobuy_mercury, $shipstobuy_cobalt);
	                    $amount[$counter]["amount"] = $shipstobuy;
					}
	            }
				
	            // Subtracts the order costs(shipcost * amount) from the users resources
	            $UserInfo["mercury"] -= $ShipTypes[$shipid]["Mercury"] * $amount[$counter]["amount"];
	            $UserInfo["cobalt"] -= $ShipTypes[$shipid]["Cobalt"] * $amount[$counter]["amount"];
	            $UserInfo["helium"] -= $ShipTypes[$shipid]["Helium"] * $amount[$counter]["amount"];

				// Update the user resources
				$request = mysql_query("UPDATE uwar_users SET mercury=$UserInfo[mercury], cobalt=$UserInfo[cobalt], helium=$UserInfo[helium] WHERE id=$Userid");

				$eta = $ShipTypes[$shipid]['BuildTime'];

				//Select an order of same eta and ship, if there is some
				$request = mysql_query("SELECT * FROM uwar_pships WHERE userid='$Userid' AND shipid='$shipid' AND eta='$eta'");
				//if there was any order of same eta and ship:
	            if($result = mysql_fetch_array($request))
	            {
	                $totalamount = $amount[$counter]["amount"] + $result['amount']; //the new amount that the order will be updated to
	                //Updates the current order into uwar_pships
	                $request = mysql_query("UPDATE uwar_pships SET userid='$Userid', shipid='$shipid', amount='$totalamount', eta='$eta' WHERE userid='$Userid' AND shipid='$shipid' AND eta='$eta'");
	            }
	            else //if there wasn't any order of same eta and ship:
	            {
	                //Inserts a new order into uwar_pships
					$amounttobuild = $amount[$counter]["amount"];
	                $request = mysql_query("INSERT INTO uwar_pships (userid, shipid, amount, eta) VALUES ('$Userid', '$shipid', '$amounttobuild', '$eta')");
	            }
			}
			else
				$msgred = "You have insufficient resources!";
		}
	}
	else
		$msgred = "Invalid action!";
}

include("header.php");
if(isset($msgred))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";

headerDsp( "Ships" );
?>
<br><img src="images/arrow_off.gif">Ship Production
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<tr>
<td bgcolor="<?=$tdbg1;?>">Name</td>
<td bgcolor="<?=$tdbg1;?>">Description</td>
<td bgcolor="<?=$tdbg1;?>">ETA</td>
<td bgcolor="<?=$tdbg1;?>">Price</td>
<td bgcolor="<?=$tdbg1;?>">Stock</td>
<td bgcolor="<?=$tdbg1;?>">Order</td>
</tr>

<form action="<? print $PHP_SELF; ?>?action=build" method="post">
<?
//Prepare for printing the stock
$stocks = array();
foreach ( $ShipsAvailable as $x => $shipid )
{
	$request = mysql_query("SELECT * FROM uwar_fships WHERE userid='$Userid' AND shipid='$shipid' ORDER BY shipid");
	if(mysql_num_rows($request) > 1)
	{
		$shipsamount = 0;
		while($result = mysql_fetch_array($request))
			$shipsamount += $result["amount"];
		$stocks[]["amount"] = $shipsamount;
	}
	else $stocks[] = mysql_fetch_array($request);
}

//Loops trhough all ships, and prints name, desc, eta, costs, stock
foreach ( $ShipsAvailable as $idx => $ship )
{
	/*if($idx > sizeof($ShipsAvailable)-1)
		break;
	if ($idx >= 15) continue;*/
	?>
	<tr>
	<td bgcolor="<?=$tdbg2;?>"><center><?=$ShipTypes[$ship]["Name"]?></center></td>
	<td bgcolor="<?=$tdbg2;?>"><?=$ShipTypes[$ship]["Description"]?></td>
	<td bgcolor="<?=$tdbg2;?>"><center><?=$ShipTypes[$ship]["BuildTime"]?></center></td>
	<td bgcolor="<?=$tdbg2;?>">
	<? if ($ShipTypes[$ship]['Mercury'] != 0) {print $ShipTypes[$ship]["Mercury"]."m<br>";} ?>
	<? if ($ShipTypes[$ship]['Cobalt'] != 0) {print $ShipTypes[$ship]["Cobalt"]."co<br>";} ?>
	<? if ($ShipTypes[$ship]['Helium'] != 0) {print $ShipTypes[$ship]["Helium"]."ca";} ?>
	</td>
	<td bgcolor="<?=$tdbg2;?>"><center>
	<?
		if($stocks[$idx] > 0)
			print number_format($stocks[$idx]["amount"],0,".",".");
		else print "0";
	?>
	</center></td>
	<td bgcolor="<?=$tdbg2;?>"><center>
	<input type="text" size="10" name="amount[<?=$idx?>][amount]" value=""></center></td>
	<?	$shipid = $ShipTypes[$ship]["Shipid"]; ?>
	<input type="hidden" name="amount[<?=$idx?>][shipid]" value=<?=$shipid?>>
	<?
}
?>
</tr>
<tr>
<td colspan="6" bgcolor="<?=$tdbg2;?>" align="center"><input type="submit" size="3" value="Build Ships"></center></td>
</tr>
</table>
</form>
</center>
<img src="images/arrow_off.gif">Current Production
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<tr>
<td bgcolor="<?=$tdbg1;?>"><center>Ship Name</center></td>
<?
for ( $i=1;$i<=24;$i++ )
{

?>
<td bgcolor="<?=$tdbg1;?>"><center><?=$i?></center></td>
<?
}
?>
</tr>
<?
//Loops trough all ships
foreach ( $ShipsAvailable as $idx => $ship ) 
{
	//Makes an array with as many elements as the eta of the current ship
	$orders = array();
	for($i=0; $i < $ShipTypes[$ship]["BuildTime"]; $i++) $orders[$i] = 0;

	//Fill the elements with orders of the current ship, as far as possible, fill ordered by eta
	$request = mysql_query("SELECT * FROM uwar_pships WHERE userid='$Userid' AND shipid='$ship' order by eta");
	for($i=0; $orders[$i] = mysql_fetch_array($request);$i++) {}
	$j = 0;
	?>
	<tr>
	<td bgcolor="<?=$tdbg2;?>"><? print $ShipTypes[$ship]["Name"]; ?></td>
	<?
	//Makes X cells for each tick depending on how long eta the current ship has
	for( $i=1; $i<=$ShipTypes[$ship]["BuildTime"]; $i++ ) 
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