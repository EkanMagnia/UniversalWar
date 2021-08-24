<?php
drawHeader($_GET["page"]);

$query = mysql_query("SELECT * FROM ".$_CFG["db"]["schedule"]." LIMIT 1");
$result = mysql_fetch_array( $query );

$start = $result["start"];
$end = $result["end"];
$havoc = $result["havoc"];

?>

<table border="0" cellspacing="10" cellpadding="0" style="text-align: center;">
	<tr>
		<td style="text-align: left; width: 400px;" colspan="2">
			<font class="subject">Time schedule for current round</font>
		</td>
	</tr>
	<tr>
		<td><b>Round starts:</b></td>
		<td><?=date("Y-m-d", $start)?></td>
	</tr>
	<tr>
		<td><b>Round ends:</b></td>
		<td><?=date("Y-m-d", $end)?></td>
	</tr>
	<tr>
		<td><b>Havoc:</b></td>
		<td><?=date("Y-m-d", $havoc)?></td>
	</tr>
	<tr>
		<td colspan="2"><font class="font">NOTE: Exactly times will be posted as announcements.</font>
	</tr>
</table>


<?
drawFooter();
?>