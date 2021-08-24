<?
error_reporting(1);
$section = "Tech Tree";
include("functions.php");
include("header.php");
include("data/construction.php");
include("data/intel.php");

if($action == "moreinfo" && isset($name) && isset($conid))
{
	headerDsp( "Tech Tree - $name" );
	?>
	<br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
		<tr>
			<td bgcolor="<?=$tdbg2?>" colspan="5">Detailed information on <?=$name?></td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg1;?>">Name</td>
			<td bgcolor="<?=$tdbg1;?>">Description</td>
			<td bgcolor="<?=$tdbg1;?>">ETA</td>
			<td bgcolor="<?=$tdbg1;?>">Price</td>
		</tr>
		<?
		foreach($Con[$conid] as $Construction) 
		{
			if($Construction["Name"] != $name) {
				continue;
			}
			$found = "true";
		?>
		<tr>
			<td bgcolor="<?=$tdbg1;?>" align="center"><?=$Construction["Name"]?></td>
			<td bgcolor="<?=$tdbg1;?>"><?=$Construction["Description"]?></td>
			<td bgcolor="<?=$tdbg1;?>" align="center"><?=$Construction["BuildTime"]?></td>
			<td bgcolor="<?=$tdbg1;?>" align="center"><?=number_format($Construction["Mercury"],0,".",".")."m<br>".number_format($Construction["Cobalt"],0,".",".")."c"?></td>
		</tr>
		<?
		}
		if($found != "true") {
			?>
			<tr>
				<td bgcolor="<?=$tdbg1?>" align="center" colspan="4">The construction / research <b><?=$name?></b> was not found!</td>
			</tr>
			<?
		}
		?>
		<tr>
			<td bgcolor="<?=$tdbg1?>" align="center" colspan="4"><a href="<?=$PHP_SELF?>"><< Back to Tech Tree</a></td>
		</tr>
	</table>
	</center>
	<br>
	<?
	footerDsp();
}
else {
headerDsp( "Tech Tree" );
?>
	<br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
		<tr>
			<td bgcolor="<?=$tdbg2?>">Information</td>
		</tr>
		<tr>
			<td bgcolor="<?=$tdbg1?>">
				In the list below your personal tech tree is shown. This tree is a table of all your constructions and researches. To view more information about the construction / research, click on it. Explanation of the colours:<br><br>
				<font color="green">Green</font> - Completed<br>
				<font color="yellow">Yellow</font> - In progress<br>
				<font color="#c0c0c0">Grey</font> - Not available<br><br>
			</td>
		</tr>
	</table>
	</center>
	<br>

	<br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<?
	for($x=0; $x <= 4; $x++)
	{
		if($x == 0) $type = "Mining (Resources)";
		elseif($x == 1) $type = "Inteligence (Scans)";
		elseif($x == 2) $type = "Travel Time";
		elseif($x == 3) $type = "Protection (OPS)";
		elseif($x == 4) $type = "Military (Ships)";
		
		?>
		<tr>
			<td colspan="3" bgcolor="<?=$tdbg2;?>"><!--<img src="images/arrow_down.gif">-->&nbsp;<?=$type?></td>
		</tr>
		<?
		
		$y = 0;
		foreach( $Con[$x] as $y => $Construction) 
		{	
			$font = "";
			$completenumSQL = mysql_query("SELECT * FROM uwar_constructions WHERE userid='$Userid' AND constructionid='$x'");
			$completenum = mysql_fetch_array($completenumSQL);
			if($completenum["complete"] >= $Construction["CompleteRequired"]) $font = "<font color=green>";
			if($completenum["complete"] < $Construction["CompleteRequired"]) $font = "<font color=#c0c0c0>";
			if($completenum["activated"] == 1 && $completenum["complete"] == $Construction["CompleteRequired"]) $font = "<font color=yellow>";
			
			?>
			<tr>
				<td bgcolor="<?=$tdbg1;?>">
					<b><a href="<?=$PHP_SELF?>?action=moreinfo&conid=<?=$x?>&name=<?=$Construction["Name"]?>">
						<?=$font?><?=$Construction["Name"];
						if ($Construction["Type"] == "r")
							print " (Technology)";
						else
							print " (Laboratory) ";

						?></font>
					</a></b>
				</td>
				<td bgcolor="<?=$tdbg1;?>"><?print $Construction["BuildTime"];?></td>
				<td bgcolor="<?=$tdbg1;?>"><?print $Construction["Mercury"];?> m, <?=$Construction["Cobalt"];?> c</td>
			</tr>	
			<?
		}
	}
	?>				
	</table>
	</center>
	<br>
<?
footerDsp();
}
include("footer.php");
?>