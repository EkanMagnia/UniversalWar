<?
error_reporting(1);
$section = "Economy";
include("functions.php");
include("header.php");

/*1. set your tax rate
  2. allow/disable donation
  2. MoF table
  3. MoF donation
	The maximum amount of resources a MoF can donate to a player is the double resources the player donated to the fund.
  4. resource transaction with universal fund
*/
if ($UserInfo["protection"]>0) 
{
	headerDsp("Protection Mode");
	Print "<br><center>You can not access the Economy section while you are in Universal Lords Protection.</center><br>";
	footerDsp();
}
else
{
	if (isset($ChangeTax)
	{
		$request = mysql_query("SELECT * FROM uwar_users WHERE id=$Userid AND taxeta=0");
		if (mysql_num_rows($request) != 0)
		{
			mysql_query("UPDATE uwar_users SET taxeta=$taxeta AND taxrate=$taxrate WHERE id=$Userid");
		}
	}

	// Donate from fund to sysmember - Checks the variables, if they are accepted, add the new values to db
	if (isset($donate))
	{
		if(isset($mAmount3) || isset($cAmount3) || isset($hAmount3))
		{
			if(is_numeric($mAmount3) && is_numeric($cAmount3) && is_numeric($hAmount3))
			{
	
				if($mAmount3 > $SysFundInfo["sysmercury"] || $cAmount3 > $SysFundInfo["syscobalt"] || $hAmount3 > $SysFundInfo["syshelium"])
				{
					$msgred = "The system fund does not have enough resources to donate that amount to the chosen system member!";
				}

				elseif ($mAmount3>=0 && $cAmount3>=0 && $hAmount3>=0)
				{
					$request3 = mysql_query("SELECT * FROM uwar_users WHERE id=$sysmember");
					$AidUserInfo = mysql_fetch_array($request3);

					$SysFundInfo["sysmercury"] -= $mAmount3;
					$SysFundInfo["syscobalt"] -= $cAmount3;
					$SysFundInfo["syshelium"] -= $hAmount3;
					$AidUserInfo["mercury"] += $mAmount3;
					$AidUserInfo["cobalt"] += $cAmount3;
					$AidUserInfo["helium"] += $hAmount3;
					$msggreen = "The choosen amount is donated from the system fund to the chosen system member!";

					mysql_query("UPDATE uwar_users SET mercury=$AidUserInfo[mercury], cobalt=$AidUserInfo[cobalt], helium=$AidUserInfo[helium], mercurydonated=mercurydonated-$mAmount3, cobaltdonated=cobaltdonated-$cAmount3, heliumdonated=heliumdonated-$hAmount3 WHERE id=$sysmember");
					mysql_query("UPDATE uwar_sysfund SET sysmercury=$SysFundInfo[sysmercury], 	syscobalt=$SysFundInfo[syscobalt], syshelium=$SysFundInfo[syshelium] WHERE sysid=$AidUserInfo[sysid]");

		            //Gives the user a notice about the donation
			        $news = "You have received a donation of $mAmount3 mercury, $cAmount3 cobalt and $hAmount3 caesium, from the system fund";
				    Add_News("Received Donation!",$news, $sysmember);

					//Get the new values
					$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'", $db);
					$UserInfo = mysql_fetch_array($request);
					$request2 = mysql_query("SELECT * FROM uwar_sysfund WHERE sysid=$UserInfo[sysid]", $db);
					$SysFundInfo = mysql_fetch_array($request2);
				}
			}
		}	
	}
