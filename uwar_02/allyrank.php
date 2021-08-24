<?
error_reporting(1);
$section = "Alliance Ranking";
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

headerDsp( "Alliance Ranking" );
$request = mysql_query("SELECT * FROM uwar_tags WHERE allyrank > 0 ORDER BY allyrank ASC",$db);
$tickSQL = mysql_query("SELECT * FROM uwar_tick WHERE id='1'");
$tick = mysql_fetch_array($tickSQL);
$ticks = $tick["number"];
if($ticks == 0)
{
	print "<br><center>The Universal Lords have not started the time in Universe yet!</center><br>";
}
else
{
	?>
    <br><img src="images/arrow_off.gif">Alliance ranking
	<br><br>
	<center>
    <table border="0" cellpadding="4" cellspacing="1" width="90%">
	    <tr>
	        <td bgcolor="<?=$tdbg1;?>">Rank</td>
	        <td bgcolor="<?=$tdbg1;?>">Tag</td>
			<td bgcolor="<?=$tdbg1;?>">Name</td>
			<td bgcolor="<?=$tdbg1;?>">Members</td>
	        <td bgcolor="<?=$tdbg1;?>">Score</td>
	        <td bgcolor="<?=$tdbg1;?>">Size</td>
	        <td bgcolor="<?=$tdbg1;?>">Average Score</td>
	        <td bgcolor="<?=$tdbg1;?>">Average Size</td>

	    </tr>
	    <?
		while ($ranking = mysql_fetch_array($request))
		{
			if($ranking["members"] < 1) continue;
        	$score = number_format($ranking["score"]);
			$avscore = $ranking["score"] / $ranking["members"];
			$avgscore = number_format($avscore);

			$size = number_format($ranking["size"]);
			$avsize = $ranking["size"] / $ranking["members"];
			$avgsize = number_format($avsize);
			?>
            <tr>
	            <td bgcolor="<?=$tdbg2;?>" width="1%"><?=$ranking["allyrank"]?></td>
	            <td bgcolor="<?=$tdbg2;?>" width="20%"><a href="allysearch.php?action=search&found=yes&tag=<?=$ranking["tag"];?>"><?print $ranking["tag"];?></a></td>
	            <td bgcolor="<?=$tdbg2;?>" width="40%"><?print $ranking["allyname"]?></td>
	            <td bgcolor="<?=$tdbg2;?>" width="1%"><?print $ranking["members"];?></td>
				<td bgcolor="<?=$tdbg2;?>" width="15%"><?print $score; ?></td>
				<td bgcolor="<?=$tdbg2;?>" width="4%"><?print $size; ?></td>
				<td bgcolor="<?=$tdbg2;?>" width="15%"><?print $avgscore; ?></td>
				<td bgcolor="<?=$tdbg2;?>" width="4%"><?print $avgsize; ?></td>

            </tr>
	        <?
		}
		?>
       </table>

       <br>
	<?
}
footerDsp();
include("footer.php");
?>