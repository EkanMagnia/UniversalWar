<?
error_reporting(1);
$section = "Planet Ranking";
include("functions.php");
include("header.php");

headerDsp( "Universe" );
?>
	<br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	    <tr align=center>
	        <td bgcolor="<?=$tdbg1;?>"><a href="universe.php">Planet Ranking</a></td>
	        <td bgcolor="<?=$tdbg1;?>"><a href="sysrank.php">System Ranking</a></td>
	        <td bgcolor="<?=$tdbg1;?>"><a href="secrank.php">Sector Ranking</a></td>
	        <td bgcolor="<?=$tdbg1;?>"><a href="allyrank.php">Alliance Ranking</a></td>
	    </tr>
	</table>
    <br>
<?
footerDsp();

headerDsp( "Planet Ranking" );
$request = mysql_query("SELECT * FROM uwar_users WHERE rank > 0 ORDER BY rank",$db);

$tickSQL = mysql_query("SELECT * FROM uwar_tick WHERE id='1'");
$tick = mysql_fetch_array($tickSQL);
$ticks = $tick["number"];
if(mysql_num_rows($request) == 0 || $ticks == 0)
{
	print "<br><center>The Universal Lords have not started the time in Universe yet!</center><br>";
}
else
{
	?>
    <br><img src="images/arrow_off.gif">Planet ranking
	<br><br>
	<center>
    <table border="0" cellpadding="4" cellspacing="1" width="90%">
	    <tr>
	        <td bgcolor="<?=$tdbg1;?>">Rank</td>
	        <td bgcolor="<?=$tdbg1;?>">Coordinates</td>
	        <td bgcolor="<?=$tdbg1;?>">Alliance</td>
	        <td bgcolor="<?=$tdbg1;?>">Commander</td>
	        <td bgcolor="<?=$tdbg1;?>">Score</td>
	        <td bgcolor="<?=$tdbg1;?>">Size</td>
	    </tr>
	    <?
		while ($ranking = mysql_fetch_array($request))
		{
            $coordsq = mysql_query("SELECT x,y FROM uwar_systems WHERE id='$ranking[sysid]'",$db);
            $coords = mysql_fetch_array($coordsq);
        	$score = number_format($ranking["score"]);
            $totalroids = $ranking["asteroid_mercury"] + $ranking["asteroid_cobalt"] + $ranking["asteroid_helium"] + $ranking["ui_roids"];
			$AllySQL = mysql_query("SELECT * FROM uwar_tags WHERE id='$ranking[tagid]'",$db);
			$ally = mysql_fetch_array($AllySQL);
			?>
            <tr>
	            <td bgcolor="<?=$tdbg2;?>" width="1%"><?=$ranking["rank"]?></td>
	            <td bgcolor="<?=$tdbg2;?>" width="1%"><a href="system.php?action=search&myx=<?=$coords["x"]?>&myy=<?=$coords["y"]?>"><? print $coords["x"].":".$coords["y"].":".$ranking["z"]; ?></a></td>
	            <td bgcolor="<?=$tdbg2;?>" width="20%"><? if($ally["tag"] && ($myrow["tagid"] == $ranking["tagid"] || $ally["public"] == 1)) print $ally["tag"]; ?></td>
	            <td bgcolor="<?=$tdbg2;?>" width="48%"><a href="communication.php?action=new&x=<?=$coords["x"]?>&y=<?=$coords["y"]?>&z=<?=$ranking["z"]?>"><? print $ranking["nick"]." of ".$ranking["planet"]; ?></a><? if (time()-$ranking["timer"]<600) { print "<font color=green>*</font>"; } ?></td>
	            <td bgcolor="<?=$tdbg2;?>" width="20%"><?=$score?></td>
	            <td bgcolor="<?=$tdbg2;?>" width="10%"><?=number_format($totalroids);?></td>
            </tr>
	        <?
		}
		?>
		<tr>
        	<td colspan="6"><font color=green size="1">*<font color="#c0c0c0"> - Commander is online</td>
        </tr>
       </table>

       <br>
	<?
}
footerDsp();
include("footer.php");
?>