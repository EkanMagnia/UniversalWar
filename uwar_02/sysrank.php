<?
error_reporting(1);
$section = "System Ranking";
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
$request = mysql_query("SELECT * FROM uwar_systems WHERE sysrank > 0 ORDER BY sysrank ASC LIMIT 50",$db);
if(mysql_num_rows($request) == 0 || $ticktime == 0)
{
	print "<br><center>The Universal Lords have not started the time in Universe yet!</center><br>";
}
else
{
	?>
    <br><img src="images/arrow_off.gif">System ranking
	<br><br>
	<center>
    <table border="0" cellpadding="4" cellspacing="1" width="90%">
	    <tr>
	        <td bgcolor="<?=$tdbg1;?>">Rank</td>
	        <td bgcolor="<?=$tdbg1;?>">Coords</td>
	        <td bgcolor="<?=$tdbg1;?>">Name</td>
	        <td bgcolor="<?=$tdbg1;?>">Score</td>
	        <td bgcolor="<?=$tdbg1;?>">Size</td>
	    </tr>
	    <?
		while ($ranking = mysql_fetch_array($request))
		{
        	$score = number_format($ranking["sysscore"]);
			?>
            <tr>
	            <td bgcolor="<?=$tdbg2;?>" width="1%"><?=$ranking["sysrank"]?></td>
	            <td bgcolor="<?=$tdbg2;?>" width="1%"><a href="system.php?action=search&myx=<?=$ranking["x"]?>&myy=<?=$ranking["y"]?>"><? print $ranking["x"].":".$ranking["y"]; ?></a></td>
	            <td bgcolor="<?=$tdbg2;?>"><?=$ranking["sysname"]?></td>
	            <td bgcolor="<?=$tdbg2;?>"><?=$score?></td>
	            <td bgcolor="<?=$tdbg2;?>"><?=$ranking["syssize"]?></td>
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