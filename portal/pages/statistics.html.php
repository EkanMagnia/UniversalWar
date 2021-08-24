<?php
drawHeader($_GET["page"]);

$_DB_host = 'localhost';
$_DB_username = 'root';
$_DB_password = '';
$_DB_database = 'universalwar';

$game_db_cn = mysql_connect($_DB_host, $_DB_username, $_DB_password );
mysql_select_db( $_DB_database, $game_db_cn );

	$request = mysql_query("SELECT * FROM uwar_tick",$game_db_cn);
	$tick = mysql_fetch_array($request);
	$ticktime = $tick["number"];

	$UserCountSQL = mysql_query("SELECT id FROM uwar_users",$game_db_cn);
	$UserCount = mysql_num_rows($UserCountSQL);
	$OnlineCountSQL = mysql_query("SELECT id FROM uwar_users WHERE timer>".(time()-600),$game_db_cn);
	$UserOnline = mysql_num_rows($OnlineCountSQL);
	
	$comrank1SQL = mysql_query("SELECT id, z, sysid, nick, planet FROM uwar_users WHERE rank=1", $game_db_cn);
	$comrank1 = mysql_fetch_array($comrank1SQL);
	$systemcoordsSQL = mysql_query("SELECT x,y FROM uwar_systems WHERE id='$comrank1[sysid]'");
	$systemcoords = mysql_fetch_array($systemcoordsSQL);
	
	$sysrank1SQL = mysql_query("SELECT x, y, sysname FROM uwar_systems WHERE sysrank=1", $game_db_cn);
	$sysrank1 = mysql_fetch_array($sysrank1SQL);

	$allyrank1SQL = mysql_query("SELECT allyname, tag FROM uwar_tags WHERE allyrank=1", $game_db_cn);
	$allyrank1 = mysql_fetch_array($allyrank1SQL);	

	$avgDataSQL = mysql_query( "SELECT AVG(score), AVG(asteroid_mercury), AVG(asteroid_cobalt), AVG(asteroid_helium) FROM uwar_users", $game_db_cn );
	$avgData = mysql_fetch_array($avgDataSQL);
	
?>

<table border="0" cellspacing="20" cellpadding="0" style="width: 100%; text-align: center;">
	<tr>
		<td style="text-align: left; width: 400px;" colspan="2">
			<font class="subject">General statistics from current round</font>
		</td>
	</tr>
	<tr>
		<td style="text-align: left;">
			<table border="0" cellspacing="2" cellpadding="2" width="100%">
				<tr>
					<td class="rowTopic">Quantity</td>
					<td class="rowTopic">Value</td>
				</tr>
				<tr>
					<td class="row1">&nbsp;Current tick:</td>
					<td class="row1">&nbsp;<?=$ticktime?></td>
				</tr>
				<tr>
					<td class="row1">&nbsp;Commanders:</td>
					<td class="row1">&nbsp;<?=$UserCount?></td>
				</tr>
				<tr>
					<td class="row1">&nbsp;Commanders online:</td>
					<td class="row1">&nbsp;<?=$UserOnline?></td>
				</tr>
				<tr>
					<td class="row1">&nbsp;Planet #1:</td>
					<td class="row1">&nbsp;<?=$comrank1["nick"]?> of <?=$comrank1["planet"]?> (<?=$systemcoords["x"]?>:<?=$systemcoords["y"]?>: <?=$comrank1["z"]?>)</td>
				</tr>
				<tr>
					<td class="row1">&nbsp;System #1:</td>
					<td class="row1">&nbsp;<?=$sysrank1["sysname"]?> (<?=$sysrank1["x"]?>:<?=$sysrank1["y"]?>)</td>
				</tr>
				<tr>
					<td class="row1">&nbsp;Alliance #1:</td>
					<td class="row1">&nbsp;<?=$allyrank1["allyname"]?> <?=$allyrank1["tag"]?></td>
				</tr>
				<tr>
					<td class="row1">&nbsp;Average score:</td>
					<td class="row1">&nbsp;<?=number_format( round($avgData[0]) )?></td>
				</tr>
				<tr>
					<td class="row1">&nbsp;Average mercury probes:</td>
					<td class="row1">&nbsp;<?=round($avgData[1])?></td>
				</tr>
				<tr>
					<td class="row1">&nbsp;Average cobalt probes:</td>
					<td class="row1">&nbsp;<?=round($avgData[2])?></td>
				</tr>
				<tr>
					<td class="row1">&nbsp;Average caesium probes:</td>
					<td class="row1">&nbsp;<?=round($avgData[3])?></td>
				</tr>	
			</table>
		</td>
	</tr>
</table>

<?
drawFooter();
?>