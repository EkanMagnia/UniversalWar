<?
error_reporting(1);
$section = "Alpha testing";
include("functions.php");
include("header.php");


if (isset($report))
{
	if(isset($name) && isset($age) && isset($country) && isset($pc) && isset($composition))
	{
		$time = time();
		$result = mysql_query("INSERT INTO uwar_alpha (name,age,country,pc,composition,time) VALUES	('$name','$age','$country','$pc','$composition','$time')");
		$msggreen = "Application succesfully submited!";
	}
	else $msgred = "All necessary fields not stated!";
}

if(isset($msggreen))
	print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";
if(isset($msgred))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";

headerDsp("Alpha testing");
?>
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<form action="<?=$PHP_SELF?>" name="report" method="post">
<tr>
	<td bgcolor="<?=$tdbg1;?>" colspan="2">Alpha applications</td>
</tr>
<tr>
	<td width="40%" bgcolor="<?=$tdbg2;?>">Name:</td>
	<td bgcolor="<?=$tdbg2;?>"><input type="text" name="name"></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>">Age:</td>
	<td bgcolor="<?=$tdbg2;?>"><input type="text" name="age"></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>">Country:</td>
	<td bgcolor="<?=$tdbg2;?>"><input type="text" name="country"></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>">Computer Specs:</td>
	<td bgcolor="<?=$tdbg2;?>"><input type="text" name="pc"></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>">Composition</td>
	<td bgcolor="<?=$tdbg2;?>"><textarea name="composition" rows="10" cols="35"></textarea></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg2;?>" colspan="2" align="center"><input type="submit" name="report" value="Submit application"></td>
</tr>
</form>
</table>
</center>
<br>
<? 
footerDsp();
include("footer.php");
?>