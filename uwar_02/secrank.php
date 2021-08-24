<?
error_reporting(1);
$section = "Sector Ranking";
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

headerDsp( "Sector Ranking" );
if(mysql_num_rows($request) == 0 || $ticktime == 0)
{
	print "<br><center>The Universal Lords have not started the time in Universe yet!</center><br>";
}
else
{
	?>
    <br><img src="images/arrow_off.gif">Sector ranking
	<br><br>
	<center>
    <table border="0" cellpadding="4" cellspacing="1" width="90%">
	    <tr>
	        <td bgcolor="<?=$tdbg1;?>">Rank</td>
	        <td bgcolor="<?=$tdbg1;?>">Sector</td>
	        <td bgcolor="<?=$tdbg1;?>">Score</td>
	        <td bgcolor="<?=$tdbg1;?>">Size</td>
	    </tr>
	    <?
	    $query = mysql_query( "SELECT max(x) FROM uwar_systems", $db );
	    $maxX = mysql_result($query, 0);
	    $sectors = array();
	    
	    for($x = 0; $x <= $maxX; $x++ ) {
	    	
	    	$query = mysql_query("SELECT * FROM uwar_systems WHERE x='$x'");
	    	while($systems = mysql_fetch_array( $query ) ) {
	    		
	    		$sectors[$x]["score"] += $systems["sysscore"];
	    		$sectors[$x]["roids"] += $systems["syssize"];
	    		$sectors[$x]["sector"] = $x;
	    			    		
	    	}
	    	
	    }

	    usort($sectors, create_function('$b,$a','return $a["score"] - $b["score"];')); 
		
	    foreach( $sectors as $x => $sector ) {
	    	?>
	        <tr>
	        	<td bgcolor="<?=$tdbg2;?>" width="1%"><?=$x+1?></td>
	            <td bgcolor="<?=$tdbg2;?>" width="1%">#<?=$sector["sector"]?></td>
	            <td bgcolor="<?=$tdbg2;?>"><?=number_format($sector["score"])?></td>
	            <td bgcolor="<?=$tdbg2;?>"><?=number_format($sector["roids"])?></td>
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