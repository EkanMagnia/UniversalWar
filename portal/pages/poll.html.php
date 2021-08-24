<?php
drawHeader($_GET["page"]);

?>
<table border="0" cellspacing="10" cellpadding="0" style="width: 100%; text-align: center;">
<?

if ($_GET["action"] == "viewformer" && is_numeric($_GET["id"])) {

		$pollQuery = mysql_query("SELECT * FROM ".$_CFG["db"]["poll"]." WHERE id='".$_GET["id"]."'");
		$poll = mysql_fetch_array($pollQuery);
		
		if ($poll["option2"]) $options = 2;
		if ($poll["option3"]) $options = 3;
		if ($poll["option4"]) $options = 4;
		if ($poll["option5"]) $options = 5;
		$totals = $poll["answers1"] + $poll["answers2"] + $poll["answers3"] + $poll["answers4"] + $poll["answers5"];		
		?>
		<tr>
			<td colspan="2"><font class="subject"><?=$poll["question"]?> (<b><?=$totals?></b> votes)</font></td>
		</tr>

		<?
		$percentage[1] = 100*($poll["answers1"] / $totals);
		$percentage[2] = 100*($poll["answers2"] / $totals);
		$percentage[3] = 100*($poll["answers3"] / $totals);
		$percentage[4] = 100*($poll["answers4"] / $totals);
		$percentage[5] = 100*($poll["answers5"] / $totals);
		
			for( $x = 1; $x <= $options; $x++ ) {
				
				//$percentage = 100*($poll["answers$x"]/$totals);
				echo '<tr><td width="30%"><b>';
				echo $poll["option$x"].'</b>: ('.round($percentage[$x]).'%) </td><td>';
				echo '<img src="'.$_CFG["paths"]["img"].'option'.$x.'.jpg" width="'.$percentage[$x].'%" height="10" alt="'.round($percentage[$x]).'%">';
				echo '</td></tr>';
				
			}
		echo '<tr><td>Poll ended on '.date("Y.m.d - H:i:s", $poll["date"]).'</td></tr>';
		echo '<tr><td><br /><br /></td></tr>';			
		
} elseif ($_GET["action"] == "vote" && $_POST["option"] ) {

	if ($_SESSION["userlogged"] == 1) {

		$cn2 = mysql_connect($_CFG["db"]["host2"], $_CFG["db"]["username2"], $_CFG["db"]["password2"]);
		mysql_select_db($_CFG["db"]["dbname2"], $cn2);	

		$userQuery = mysql_query("SELECT voted FROM ".$_CFG["db"]["users"]." WHERE user_id='".$_SESSION["userid"]."'", $cn2);
		$user = mysql_fetch_array($userQuery);
		
		if ($user["voted"] == 1) {
			echo '<tr><td style="text-align: left;"><font color="red">You have already voted in this poll.</font></td></tr>';
		}
		$votedon = $_POST["option"];
		
		$cn = mysql_connect($_CFG["db"]["host"], $_CFG["db"]["username"], $_CFG["db"]["password"]);
		mysql_select_db($_CFG["db"]["dbname"], $cn);	
		
		$pollQuery = mysql_query("SELECT * FROM ".$_CFG["db"]["poll"]." WHERE active=1", $cn);
		$poll = mysql_fetch_array($pollQuery);
		
		if ($poll["option2"]) $options = 2;
		if ($poll["option3"]) $options = 3;
		if ($poll["option4"]) $options = 4;
		if ($poll["option5"]) $options = 5;
		$totals = $poll["answers1"] + $poll["answers2"] + $poll["answers3"] + $poll["answers4"] + $poll["answers5"];
		
		if ($user["voted"] == 0) {
						
			mysql_query( "UPDATE ".$_CFG["db"]["poll"]." SET answers".$votedon."='".$answers."' WHERE active=1" ) or die("Error.");
			
			$cn2 = mysql_connect($_CFG["db"]["host2"], $_CFG["db"]["username2"], $_CFG["db"]["password2"]);
			mysql_select_db($_CFG["db"]["dbname2"], $cn2);
			
			mysql_query( "UPDATE ".$_CFG["db"]["users"]." SET voted=1 WHERE user_id='".$_SESSION["userid"]."'", $cn2 ) or die("Error!");
			mysql_close($cn2);
		}
		
		$totals = $poll["answers1"] + $poll["answers2"] + $poll["answers3"] + $poll["answers4"] + $poll["answers5"];
		if ($user["voted"] == 0) {
			echo '<tr><td style="text-align: left;">Thanks for participating in this poll.</td></tr>';
		}
		?>
			<tr>
				<td colspan="2"><font class="subject">Active poll voting</font></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;"><font class="subject2"><?=$poll["question"]?> (<b><?=$totals?></b> votes)</font></td>
			</tr>
			<?

			$percentage[1] = 100*($poll["answers1"] / $totals);
			$percentage[2] = 100*($poll["answers2"] / $totals);
			$percentage[3] = 100*($poll["answers3"] / $totals);
			$percentage[4] = 100*($poll["answers4"] / $totals);
			$percentage[5] = 100*($poll["answers5"] / $totals);
			
				for( $x = 1; $x <= $options; $x++ ) {
					
					//$percentage = 100*($poll["answers$x"]/$totals);
					echo '<tr><td width="30%"><b>';
					echo $poll["option$x"].'</b>: ('.round($percentage[$x]).'%) </td><td>';
					echo '<img src="'.$_CFG["paths"]["img"].'option'.$x.'.jpg" width="'.$percentage[$x].'%" height="10" alt="'.round($percentage[$x]).'%">';
					echo '</td></tr>';
					
				}
			echo '<tr><td><br /><br /></td></tr>';
	} else {
		echo '<tr><td style="text-align: left;"><font color="red">You must be logged in to participate in the poll votings.</font></td></tr>';	
	}
	
}

?>


	<tr>
		<td colspan="2"><font class="subject">Former poll votings</font></td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: left;">
			<table border="0" cellspacing="2" cellpadding="2" width="100%">
				<tr>
					<td class="rowTopic" style="width: 60%; text-align: left;">Question</td>
					<td class="rowTopic" style="text-align: left;">&nbsp;Votes</td>
					<td class="rowTopic" style="text-align: left;">&nbsp;Date finsihed</td>
				</tr>
				<?
				$cn = mysql_connect($_CFG["db"]["host"], $_CFG["db"]["username"], $_CFG["db"]["password"]);
				mysql_select_db($_CFG["db"]["dbname"], $cn);				
				$pollQuery = mysql_query("SELECT * FROM ".$_CFG["db"]["poll"]." WHERE active!=1", $cn);
				
				if ( mysql_num_rows($pollQuery) ) {
					while ($poll = mysql_fetch_array($pollQuery) ) {
						?>
						<tr>
							<td class="row1" style="text-align: left;"><a href="<?=$PHP_SELF;?>?page=poll&action=viewformer&id=<?=$poll["id"]?>"><?=$poll["question"]?></a></td>
							<td class="row1" style="text-align: left;"><?=$poll["answers1"]+$poll["answers2"]+$poll["answers3"]+$poll["answers4"]+$poll["answers5"]?></td>
							<td class="row1" style="text-align: left;"><?=date("Y.m.d - H:i:s", $poll["date"])?></td>
						</tr>
						<?				
					}
				} else {
					?>
						<tr>
							<td class="row1" style="text-align: left;" colspan="3">There are no former poll votings at the moment.</td>
						</tr>
					<?	
				}
				?>
			</table>
		</td>
	</tr>

	
	
</table>
<?
drawFooter();
?>