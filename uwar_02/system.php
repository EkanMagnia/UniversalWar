<?
error_reporting(1);
$section = "System";
include("functions.php");
include("header.php");


if ($action == "search")
{
	if (is_numeric($myx) && is_numeric($myy))
    {
	    if (isset($lower))
	    {
			if ($myy=="1") { $myy = 5; $myx--;}
            else $myy--;
	    }
	    if (isset($higher))
	    {
	        if ($myy=="5") { $myy = 1; $myx++; }
			else $myy++;
	    }
		//Selects the lowest and highest X,Y coords
		$requestminx = mysql_query("SELECT Min(x) as x FROM uwar_systems",$db);
		$requestminy = mysql_query("SELECT Min(y) as y FROM uwar_systems",$db);
		$requestmaxx = mysql_query("SELECT Max(x) as x FROM uwar_systems",$db);
		$requestmaxy = mysql_query("SELECT Max(y) as y FROM uwar_systems",$db);
		$resultminx = mysql_fetch_array($requestminx);
		$resultminy = mysql_fetch_array($requestminy);
		$resultmaxx = mysql_fetch_array($requestmaxx);
		$resultmaxy = mysql_fetch_array($requestmaxy);
		$xMin = $resultminx["x"];
		$yMin = $resultminy["y"];
		$xMax = $resultmaxx["x"];
		$yMax = $resultmaxy["y"];

        $sysrequest = mysql_query("SELECT * FROM uwar_systems WHERE x='$myx' and y='$myy'",$db);
		while(mysql_num_rows($sysrequest) == 0)
        {
		    if (isset($lower))
		    {
				if($myx <= $xMin && $myy <= $yMin)
				{
					unset($lower);
					unset($higher);
					break;
				}
				if ($myy=="1") { $myy = 5; $myx--;}
				else $myy--;
			}
			if (isset($higher))
			{
				if($myx >= $xMax && $myy >= $yMax)
				{
					unset($lower);
					unset($higher);
					break;
				}
				if ($myy=="5") { $myy = 1; $myx++; }
				else $myy++;
		    }
	        $sysrequest = mysql_query("SELECT * FROM uwar_systems WHERE x='$myx' and y='$myy'",$db);
	    }
		
		if ( (!isset($lower)) && (!isset($higher)) && (mysql_num_rows($sysrequest) == 0) )
		{
			$msgred = "Invalid system!";
			$result = mysql_query("SELECT * FROM uwar_systems WHERE id='$myrow[sysid]'",$db);
		    $gal = mysql_fetch_array($result);
		    $myx = $gal["x"];
			$myy = $gal["y"];
	        $sysid = $gal["id"];
	        $sysmembersrequest = mysql_query("SELECT * FROM uwar_users WHERE sysid='$sysid' ORDER BY z",$db);
		}
		else
		{		
	        $gal = mysql_fetch_array($sysrequest);
	        $systemid = $gal["id"];
	        $sysmembersrequest = mysql_query("SELECT * FROM uwar_users WHERE sysid='$systemid' ORDER BY z",$db);
		}
    }
    else
		$msgred = "Invalid coordinates!";
}
else
{
    //If not coords is stated, select default own gal
    $result = mysql_query("SELECT * FROM ".$Uwar["systems"]." WHERE id='$myrow[sysid]'",$db);
    $gal = mysql_fetch_array($result);
    $myx = $gal["x"];
	$myy = $gal["y"];
    $sysmembersrequest = mysql_query("SELECT * FROM ".$Uwar["table"]." WHERE sysid='$myrow[sysid]' ORDER BY z",$db) or die("nils");
}

if(isset($msgred))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";

headerDsp("System");
?>
<br><img src="images/arrow_off.gif">System Lookup
<br><br>
<center>
<form action="<?=$PHP_SELF?>?action=search" method="post">
<table border="0" cellpadding="2" cellspacing="0" width="90%">
	<tr>
        <td align="center">
            <input type="text" size="5" name="myx" value="<?=$myx?>">&nbsp;:
            <input type="text" size="5" name="myy" value="<?=$myy?>">&nbsp;&nbsp;            
		</td>
    </tr>
    <tr>
        <td align="center"">
		<input type="submit" name="search" value="Search">
		</td>
    </tr>
    <tr>
        <td align="center">
		<input type="submit" name="lower" value="<<">&nbsp;
		<input type="submit" name="higher" value=">>">
		</td>
    </tr>
	<tr>
		<? $sysbanner = $gal["syspic"]; ?>
        <td align=center colspan=2><? if(isset($sysbanner)) { ?><img src="<?=$sysbanner ?>"><? } ?></td>
    </tr>
</table>
<br>
<table border="0" cellpadding="4" cellspacing="1" width="90%" bgcolor="<?=$tdbg1;?>">
    <tr>
		<td align=center colspan=2><?=$gal["sysname"]?>&nbsp;&nbsp;<b>Score:</b> <?=number_format($gal["sysscore"])?>&nbsp;&nbsp;<b>Size:</b> <?=number_format($gal["syssize"])?></td>
    </tr>
</table>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
	    <td bgcolor="<?=$tdbg1;?>" width="1%">ID</td>
		<td bgcolor="<?=$tdbg1;?>">Tag</td>
	    <td bgcolor="<?=$tdbg1;?>" width="50%">Commander</td>
	    <td bgcolor="<?=$tdbg1;?>">Size</td>
	    <td bgcolor="<?=$tdbg1;?>"><center>Score</center></td>
	    <td bgcolor="<?=$tdbg1;?>" width="1%"><center>Scanning</center></td>
	</tr>
    <?
	while($sysmembers = mysql_fetch_array($sysmembersrequest))
    {
		$AllySQL = mysql_query("SELECT * FROM uwar_tags WHERE id='$sysmembers[tagid]'",$db);
		$ally = mysql_fetch_array($AllySQL);

		$tagSQL = mysql_query("SELECT tag FROM uwar_tags WHERE id='$myrow[tagid]'", $db);
		$mytag = mysql_fetch_array($tagSQL);
        ?>
        <tr>
	        <td bgcolor="<?=$tdbg2;?>" width="1%"><?=$sysmembers["z"]?></td>
			<td bgcolor="<?=$tdbg2;?>"><? if($ally["tag"] && ($myrow["tagid"] == $sysmembers["tagid"] || $ally["public"] == 1)) print $ally["tag"]; ?></td>
	        <td bgcolor="<?=$tdbg2;?>" width="50%">
			<?
			if ($myrow["sysid"] == $sysmembers["sysid"] || $myrow["tagid"] == $sysmembers["tagid"])
			{
				if (time()-$sysmembers["timer"]<600)  print "*";
			}
				if ($sysmembers["protection"] >= 1) print"{";
				if ($sysmembers["vacation"] >= 1) print "[";
			   	if ($sysmembers["sleep"] >= 1) print "(";
				
				$sysid = $sysmembers["sysid"];
				$request = mysql_query("SELECT x,y FROM uwar_systems WHERE id='$sysid'");
				$result = mysql_fetch_array($request);
				$x = $result["x"];
				$y = $result["y"];
			?><a href="communication.php?action=new&x=<?=$x?>&y=<?=$y?>&z=<?=$sysmembers["z"]?>"><?
					
				if ($sysmembers["commander"] == 1){ $commander = "<font color=blue>".$sysmembers["nick"]." of ".$sysmembers["planet"]."</font>"; }
				elseif ($sysmembers["commander"] == 2){ $commander = "<font color=red>".$sysmembers["nick"]." of ".$sysmembers["planet"]."</font>"; }
	            elseif ($sysmembers["commander"] == 3){ $commander = "<font color=green>".$sysmembers["nick"]." of ".$sysmembers["planet"]."</font>"; }
	            elseif ($sysmembers["commander"] == 4){ $commander = "<font color=yellow>".$sysmembers["nick"]." of ".$sysmembers["planet"]."</font>"; }
	            elseif ($sysmembers["id"] == $myrow["id"]){ $commander = "<font color=#CC00FF>".$sysmembers["nick"]." of ".$sysmembers["planet"]."</font>"; }
	            else { $commander = $sysmembers["nick"]." of ".$sysmembers["planet"]; }

                print $commander;
				?></a><?

	            if ($sysmembers["protection"] >= 1) print "}";
	            if ($sysmembers["vacation"] >= 1) print "]";
	            if ($sysmembers["sleep"] >= 1) print ")";
			if ($myrow["sysid"] == $gal["id"] || $myrow["tagid"] == $sysmembers["tagid"])
			{

				if (time()-$sysmembers["timer"]<600)  print "*";
			}
			?>
			</td>
	        <td bgcolor="<?=$tdbg2;?>"><? $totalsize = $sysmembers["asteroid_mercury"] + $sysmembers["asteroid_cobalt"] + $sysmembers["asteroid_helium"] + $sysmembers["ui_roids"]; print $totalsize; ?></td>
	        <td bgcolor="<?=$tdbg2;?>"><center><?
			$score = number_format($sysmembers["score"]); print $score; ?></center></td>
	        <td bgcolor="<?=$tdbg2;?>"><center><a href="scans.php?scanx=<?=$myx?>&scany=<?=$myy?>&scanz=<?=$sysmembers["z"]?>">Scan</a></center></td>
        </tr>
        <?
    }
    ?>
</table>
<br><br>
<?
footerDsp();
headerDsp("Key");
?>
<br>
<center>
<table cellpadding="3" cellspacing="1">
<tr>
	<td bgcolor="<?=$tdbg1;?>">Colour</td>
    <td bgcolor="<?=$tdbg1;?>">Description</td>
    <td>&nbsp;</td>
    <td bgcolor="<?=$tdbg1;?>">Symbol</td>
    <td bgcolor="<?=$tdbg1;?>">Description</td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>"><font color="blue">Blue</font></td>
    <td bgcolor="<?=$tdbg2;?>"><font color="blue">System Leader</font></td>
    <td>&nbsp;</td>
    <td bgcolor="<?=$tdbg2;?>">{ }</td>
    <td bgcolor="<?=$tdbg2;?>">Universal Lords Protection</td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>"><font color="red">Red</font></td>
    <td bgcolor="<?=$tdbg2;?>"><font color="red">Fleet Commander</font></td>
    <td>&nbsp;</td>
	<td bgcolor="<?=$tdbg2;?>">[ ]</td>
    <td bgcolor="<?=$tdbg2;?>">Vacation Mode</td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>"><font color="green">Green</font></td>
    <td bgcolor="<?=$tdbg2;?>"><font color="green">Senator of Communication</font></td>
    <td>&nbsp;</td>
	<td bgcolor="<?=$tdbg2;?>">( )</td>
    <td bgcolor="<?=$tdbg2;?>">Sleep Mode</td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>"><font color="yellow">Yellow</font></td>
    <td bgcolor="<?=$tdbg2;?>"><font color="yellow">Minister of Finance</font></td>
    <td>&nbsp;</td>
	<td bgcolor="<?=$tdbg2;?>">* *</td>
    <td bgcolor="<?=$tdbg2;?>">User is online</td>
</tr>
</table>
</center>
<br>
<?
footerDsp();
include("footer.php");
?>