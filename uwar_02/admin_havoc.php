<?
error_reporting(1);
$section = "Admin - Havoc Management";
include("functions.php");
include("header.php");
 
if (isset($dohavoc))
{ 
	/* In havoc everyone gets:
		300.000.000 resources of each type
		10.000 k probes of each type
		all labs/tech completed 
		ticker speed changes to 30 seconds
	*/
	mysql_query("UPDATE uwar_users SET mercury=mercury+$addmercury");
	mysql_query("UPDATE uwar_users SET cobalt=cobalt+$addcobalt");
	mysql_query("UPDATE uwar_users SET helium=helium+$addhelium");
	mysql_query("UPDATE uwar_users SET asteroid_cobalt=asteroid_cobalt+$add_croids");
	mysql_query("UPDATE uwar_users SET asteroid_mercury=asteroid_mercury+$add_mroids");
	mysql_query("UPDATE uwar_users SET asteroid_helium=asteroid_helium+$add_hroids");
	mysql_query("UPDATE uwar_constructions SET complete=10 WHERE constructionid=0");
	mysql_query("UPDATE uwar_constructions SET complete=7 WHERE constructionid=1");
	mysql_query("UPDATE uwar_constructions SET complete=4 WHERE constructionid=2");
	mysql_query("UPDATE uwar_constructions SET complete=5 WHERE constructionid=3");
	mysql_query("UPDATE uwar_constructions SET complete=24 WHERE constructionid=4");
	mysql_query("UPDATE uwar_constructions SET activated=0");
	mysql_query("UPDATE uwar_modes SET tickertime=$tickertime");
	mysql_Query("UPDATE uwar_users SET protection=0");
	mysql_Query("UPDATE uwar_users SET sleep=0");
	mysql_Query("UPDATE uwar_users SET vacation='0'");
	print "<center>Havoc done succesfully!</center>";
}
headerDsp("Havoc Management");
?><center><form action="<?=$PHP_SELF;?>?dohavoc" method="post"><table><td bgcolor="<?=$tdbg1;?>" colspan="2">In this section you can give havoc to every player.</td></tr>
<tr>
	<td bgcolor="<?=$tdbg1;?>">Mercury</td><td bgcolor="<?=$tdbg1?>"><input type="text" name="addmercury"></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg1;?>">Cobalt</td><td bgcolor="<?=$tdbg1?>"><input type="text" name="addcobalt"></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg1;?>">Caesium</td><td bgcolor="<?=$tdbg1?>"><input type="text" name="addhelium"></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg1;?>">Mercury Probes</td><td bgcolor="<?=$tdbg1?>"><input type="text" name="add_mroids"></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg1;?>">Cobalt Probes</td><td bgcolor="<?=$tdbg1?>"><input type="text" name="add_croids"></td>
</tr>
<tr>
	<td bgcolor="<?=$tdbg1;?>">Caesium Probes</td><td bgcolor="<?=$tdbg1?>"><input type="text" name="add_hroids"></td>
</tr>
<!--<tr>
	<td bgcolor="<?=$tdbg1;?>">Ticker speed</td><td bgcolor="<?=$tdbg1?>"><input type="text" name="tickertime"></td>
</tr>
-->
<tr><td colspan="2" bgcolor="<?=$tdbg1;?>"><center><input type="submit" name="dohavoc" value="Do havoc"></td></table></center>

<br>
	<center><a href="administrator.php"><< Back to admin index</a>
	</center>
<br>
<?

footerDsp();
include("footer.php");

