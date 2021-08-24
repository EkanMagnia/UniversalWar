<?
error_reporting(1);
$section = "News";
include("functions.php");
include("header.php");

headerDsp("News") ;

if (isset($deleteall))
{
    $result = mysql_query("UPDATE uwar_news SET hide=1 WHERE userid='$Userid'",$db);
    Header("Location: news.php");
    die();
}
if (isset($deletet))
{
    $result = mysql_query("UPDATE uwar_news SET hide=1 WHERE userid='$Userid' AND newsid=$deletet",$db);
    Header("Location: news.php");
    die();
}

$result = mysql_query("UPDATE ".$Uwar["newstable"]." SET seen=1 WHERE userid='$Userid'",$db);
$result = mysql_query("SELECT * FROM uwar_news WHERE userid='$Userid' AND hide=0 ORDER BY time DESC",$db);
if(mysql_num_rows($result) !=0)
{
	?>
    <br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr><td bgcolor="<?=$tdbg2;?>">Private planet news</td></tr>
    <?
    for($count = 0; $myrow = mysql_fetch_array($result); $count++)
    {
     //   if(strstr($myrow['header'], 'Hostiles')) $prefix = "<font color=red>";
	//	if(strstr($myrow['header'], 'Allies')) $prefix = "<font color=green>"; ?>
        <tr>
			<td bgcolor="<?=$tdbg1;?>">
				<table cellspacing="5" cellpadding="0" border="0" width="100%">
					<tr>
						<td width="3%"align="center" style="vertical-align: top;"><img src="images/news.gif"></td>
						<td width="97%">
							<b><? if(isset($prefix)) { print $prefix; } print $myrow["header"]; ?></font></b>
							 - <font size="1"><i><? print strftime("%d/%m-20%y %H:%M:%S",$myrow["time"]); ?></i></font>
							(<a href="news.php?deletet=<?=$myrow["newsid"] ?>">Remove</a>)<br/>
							<? print $myrow["news"]; ?> 
						</td>
					</tr>
				</table>
            </td>
		</tr>


	<? } ?>
		 <tr>
		 	<td align=center colspan="3"><a href="<?=$PHP_SELF?>?deleteall=yes">Delete all news</a></td>
		 </tr>
	</table>
	</center>
    <?
    }
else print "<br><center>You don't have any news!</center><br>";
footerDsp();


include("footer.php");
?>