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
$request = mysql_query("SELECT * FROM uwar_users WHERE rank > 0 ORDER BY rank ASC LIMIT 100",$db);

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
			
			if ($myrow["id"] == $ranking["id"])
				$color = "#CCFF00";
			elseif ($myrow["sysid"] == $ranking["sysid"])
				$color = "#CC9900";
			elseif ($myrow["tagid"] == $ranking["tagid"] && $myrow["tagid"] != 0)
				$color = "#0000FF";
			else
				$color = "";
			?>
            <tr>
	            <td bgcolor="<?=$tdbg2;?>" width="1%"><font color="<?=$color;?>"><?=$ranking["rank"]?></td>
	            <td bgcolor="<?=$tdbg2;?>" width="1%"><a href="system.php?action=search&myx=<?=$coords["x"]?>&myy=<?=$coords["y"]?>"><font color="<?=$color;?>"><? print $coords["x"].":".$coords["y"].":".$ranking["z"]; ?></a></td>
	            <td bgcolor="<?=$tdbg2;?>" width="20%"><font color="<?=$color;?>"><? if($ally["tag"] && ($myrow["tagid"] == $ranking["tagid"] || $ally["public"] == 1)) print $ally["tag"]; ?></td>
	            <td bgcolor="<?=$tdbg2;?>" width="48%"><a href="communication.php?action=new&x=<?=$coords["x"]?>&y=<?=$coords["y"]?>&z=<?=$ranking["z"]?>"><font color="<?=$color;?>"><? print $ranking["nick"]." of ".$ranking["planet"]; ?></a></font></td>
	            <td bgcolor="<?=$tdbg2;?>" width="20%"><font color="<?=$color;?>"><?=$score?></td>
	            <td bgcolor="<?=$tdbg2;?>" width="10%"><font color="<?=$color;?>"><?=number_format($totalroids);?></td>
            </tr>
	        <?
		}
		?>
		<tr>
        	<!--<td colspan="6"><font color=green size="1">*<font color="#c0c0c0"> - Commander is online</td>-->
        </tr>
       </table>
<?	  
			}
footerDsp();

headerDsp("Key");
?>
<br>
<center>
<table cellpadding="3" cellspacing="1">
<tr>
	<td bgcolor="<?=$tdbg1;?>">Colour</td>
    <td bgcolor="<?=$tdbg1;?>">Description</td>
    
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>"><font color="#CCFF00">Yellow</font></td>
    <td bgcolor="<?=$tdbg2;?>"><font color="#CCFF00">Your planet</font></td>  
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>"><font color="#CC9900">Dark Yellow</font></td>
    <td bgcolor="<?=$tdbg2;?>"><font color="#CC9900">Member of your system</font></td> 
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>"><font color="0000FF">Blue</font></td>
    <td bgcolor="<?=$tdbg2;?>"><font color="0000FF">Member of your alliance</font></td>
</tr>
</table>
</center>
<br>
<?
footerDsp();
?>
       <br>
	<?
include("footer.php");
?>