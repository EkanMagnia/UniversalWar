<?
error_reporting(1);
$section = "PoWWa of Meter"; 
include("functions.php");
include("header.php");

headerDsp( "PoWWa of Meter" );

if(isset($BuildTime) && isset($Eta) && $BuildTime > 0 && $Eta > 0)
{
	$EtaDone = $BuildTime - $Eta;
	$PercentDone = (($EtaDone / $BuildTime) * 100);
	$PercentDone = round($PercentDone);
	$PercentDone = $PercentDone < 0 ? 0 : $PercentDone;
}
?>
<BR>
<CENTER>
<?
if(isset($PercentDone) && $PercentDone != 0) 
{
?>
	<TABLE style="BORDER-COLLAPSE: collapse" borderColor="#808080" height="10" width="100" border="1" bgcolor="#000000" cellspacing="0" cellpadding="0">
		<TR>
			<TD>
				<? 
				print "<img src=images/".$color.".jpg width=".$PercentDone." height=10 alt=".$PercentDone."% is done>";
				?>
			</TD>
		</TR>
	</TABLE>
<? 
}
?>
<BR><BR>
<form action="meter.php" method=post>
<TABLE style="BORDER-COLLAPSE: collapse" align=center height="10" border="0" cellspacing="0" cellpadding="0">
	<TR>
		<TD>Buildtime of the ship:&nbsp;&nbsp;</TD><TD><input type=text name=BuildTime></TD>
	</TR>
	<TR>
		<TD>Current eta (Remaining ETA):&nbsp;&nbsp;</TD><TD><input type=text name=Eta></TD>
	</TR>
	<TR>
		<TD>Choose color of the meter:&nbsp;&nbsp;</TD>
		<TD>
			<select name=color>
			<option value="black">Black</option>
			<option value="blue">Blue</option>
			<option value="copper">Copper</option>
			<option value="green">Green</option>
			<option value="orange">Orange</option>
			<option value="purple">Purple</option>
			<option value="red">Red</option>
			</select>
		</TD>
	</TR>
	<TR>
		<TD align=center colspan=2><br><input type=submit value="GO!"></TD>
	</TR>
</TABLE>
</form>
</CENTER>
<BR>
<?
footerDsp();
include("footer.php");
?>