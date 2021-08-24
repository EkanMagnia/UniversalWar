<?
error_reporting(1);
$section = "Send Messages";
include("functions.php");
include("header.php");


if ($myrow["access"]<1) die("Cannot access page.You are not an universal war admin or creator!");
if (isset($sendmsg))
{
	$request = mysql_query("SELECT id FROM uwar_users",$db);
	while ($myrow = mysql_fetch_array($request))
	{
		$to = $myrow["id"];
		Add_Mail($subject, $content, $to, $Userid);           
	}	
}
headerDsp("Send Message to entire Universe:");
?>
Creators Communication Center:
<center><table><td><form action="<?print $PHP_SELF;?>" METHOD="POST" name="sendmsg">Subject:<input type="text" name="subject">
</td><tr><td>Text:<textarea name="content" cols="40" rows="7">This message was sent to the whole universe so please don't reply to it:</textarea>
</td><tr><td><input type="Submit" name="sendmsg" value="Send"></form></table></center>
<br>
	<center><a href="administrator.php"><< Back to admin index</a>
	</center>
<br>

<?
footerDsp();
include("footer.php");
?>