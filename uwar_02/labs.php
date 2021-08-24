<?
error_reporting(1);
$section = "Laboratories";
include("functions.php");

//Includes each ships specefic info
include("data/construction.php");

$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'", $db);
$UserInfo = mysql_fetch_array($request);

if (isset($action) && isset($constructionid) && $action == "cancel" && is_numeric($constructionid) && isset($complete))
{
	cancel_research($constructionid, $complete);
}

if (isset($action) && isset($constructionid) && $action == "build" && is_numeric($constructionid))
{
	$completeRequest = mysql_query("SELECT complete FROM uwar_constructions WHERE userid='$Userid' AND constructionid='$constructionid'"); $completeVal = mysql_fetch_array($completeRequest);
	$completeVal = $completeVal["complete"];

	//If there is any research in progress, select it
	$otherinprogress = "false";
	$inprogress = mysql_query("SELECT * FROM uwar_constructions WHERE userid='$Userid' AND activated=1");
	if(mysql_num_rows($inprogress) > 0)
	{
		while( $conprogress = mysql_fetch_array($inprogress) )
		{
			$conid = $conprogress["constructionid"];
			$completenum = $conprogress["complete"];
			$contype = $Con[$conid][$completenum];
			if($contype["Type"] == "c")	$otherinprogress = "true";
		}
	}

	if($constructionid >= 0 && $otherinprogress !="true")
	{
		// Checks if the user has enough of resources to build
		if($UserInfo["mercury"] >= $Con[$constructionid][$completeVal]["Mercury"] && $UserInfo["cobalt"] >= $Con[$constructionid][$completeVal]["Cobalt"])
		{
			// Subtracts the costs from the users resources
			$UserInfo["mercury"] -= $Con[$constructionid][$completeVal]["Mercury"];
			$UserInfo["cobalt"] -= $Con[$constructionid][$completeVal]["Cobalt"];

			// Update the user resources
			$mercurycost = $UserInfo['mercury'];
			$cobaltcost = $UserInfo['cobalt'];
			$request = mysql_query("UPDATE uwar_users SET mercury='$mercurycost', cobalt='$cobaltcost' WHERE id='$Userid'") or die("Error!");

			//Update the lab, set 'activated' to 1 (true) and the eta to the research eta
			$eta = $Con[$constructionid][$completeVal]['BuildTime']+1;
			$request = mysql_query("UPDATE uwar_constructions SET activated=1, eta='$eta', complete='$completeVal' WHERE userid='$Userid' AND constructionid='$constructionid'");
			$msggreen = "Laboratory started!";
		}
		else
			$msgred = "You have insufficient resources!";
	}
	else
		$msgred = "Invalid action!";
}

include("header.php");

if(isset($msggreen))
	print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";
if(isset($msgred))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";

	headerDsp( "Laboratories " );
	?>
	<br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>">Name</td>
		<td bgcolor="<?=$tdbg1;?>">Description</td>
		<td bgcolor="<?=$tdbg1;?>">ETA</td>
		<td bgcolor="<?=$tdbg1;?>">Cost</td>
		<td bgcolor="<?=$tdbg1;?>">Status</td>
	</tr>
	<tr>
		<td colspan="5" bgcolor="<?=$tdbg2;?>">&nbsp;</td>
	</tr>
	<?
	for($x=0; $x <= 4; $x++)
	{
		//Loops through all constructions
		$y = 0;
		foreach( $Con[$x] as $y => $Construction) 
		{
			//Only print the laboratories
			if($Construction["Type"] == "c")
			{
				$completeRequest = mysql_query("SELECT complete FROM uwar_constructions WHERE userid='$Userid' AND constructionid='$x'"); $completeVal = mysql_fetch_array($completeRequest);

				//Only laboratories with required constructions completed
				if($Construction["CompleteRequired"] <= $completeVal["complete"])
				{
					$comp = $Construction["CompleteRequired"];
					$inprogress = mysql_query("SELECT * FROM uwar_constructions WHERE userid='$Userid' AND activated=1 AND constructionid='$x' AND complete='$comp' AND eta > 0");

					$otherinprogress = "false";
					$otherinprogress_request = mysql_query("SELECT * FROM uwar_constructions WHERE userid='$Userid' AND activated=1 AND constructionid !='$x' AND eta > 0");
					if(mysql_num_rows($otherinprogress_request) > 0)
					{
						while( $conprogress = mysql_fetch_array($otherinprogress_request) )
						{
							$conid = $conprogress["constructionid"];
							$completenum = $conprogress["complete"];
							$contype = $Con[$conid][$completenum];
							if($contype["Type"] == "c")	$otherinprogress = "true";
						}
					}
					?>
					<tr>
						<td bgcolor="<?=$tdbg2;?>" align="center"><?=$Construction["Name"]?></td>
						<td bgcolor="<?=$tdbg2;?>"><?=$Construction["Description"]?></td>
						<td bgcolor="<?=$tdbg2;?>" align="center"><?=$Construction["BuildTime"]?></td>
						<td bgcolor="<?=$tdbg2;?>" align="center"><?=number_format($Construction["Mercury"],0,".",".")."m<br>".number_format($Construction["Cobalt"],0,".",".")."c"?></td>
						<td bgcolor="<?=$tdbg2;?>" align="center">
							<?
							if(mysql_num_rows($inprogress) > 0)
							{
								$progress = mysql_fetch_array($inprogress);
								$EtaDone =  $Construction['BuildTime'] - $progress['eta']; //Gets the eta that is left of the research
								$PercentDone = round($EtaDone / $Construction['BuildTime'] *100); // Calcualte the percent
								?>
								<br><TABLE style="BORDER-COLLAPSE: collapse" borderColor="#808080" height="10" width="50" border="1" bgcolor="#000000" cellspacing="0" cellpadding="0">
								<TR>
									<TD><? 	print "<img src=images/red.jpg width=".$PercentDone /2 ." height=10 alt=".$PercentDone."% is done>";?></TD>
								</TR>
								</TABLE>
								<font size=1><?
								$eta2 = $progress['eta']-1;		
									print $eta2; ?> days left
									<?
										$compVal = $completeVal["complete"];
									print "<a href=".$PHP_SELF."?action=cancel&constructionid=".$x."&complete=".$compVal."><font color=yellow>Cancel</font></a>"; ?>
									</font>
								<?
							}
							elseif($y < $completeVal["complete"]) print "<font color=green>Completed</font>";
							elseif($otherinprogress == "true")
									print "<font size=1>Other in progress</font>";
							else 
							{
								print "<a href=".$PHP_SELF."?action=build&constructionid=".$x."><font color=red>Research</font></a>";
							}
							?>
						</td>
					</tr>
					<?
				}
			}
		}
	}
	?>
<tr><td colspan="5" bgcolor="<?=$tdbg2;?>">&nbsp;</td></tr>
</table>
<br>
<?
footerDsp();
include("footer.php");
?>