<?
error_reporting(1);
$section = "Administration Send E-mail";
include("functions.php");
include("header.php");
if (isset($Send_Email) && $subject!='' && $content!='')
{
	$request=mysql_query("SELECT * FROM uwar_users ORDER by id");
	while ($myrow = mysql_fetch_array($request))
	{
		$email = $myrow["email"];
		mail($email, $subject, $content, "From: raven@pixelrage.ro");
		$msg = "<font color=\"#33CC00\">Messages sent succesful!</font>";
	}
	print $msg;
}
headerDSP("Send E-mail to all players");
?>
<form name="Send_Email" action="<?=$PHP_SELF;?>?Send_Email" method="post">
<table>
<tr><td>Subject: </td><td><input type="text" name="subject"></td></tr>
<tr><td>Content: </td><td><textarea name="content" cols="40" rows="10"></textarea></td></tr>
<tr><th><center><input type="submit" name="Send_Email" value="Send Email`s"></th>
</table>
</form>
<br>
	<center><a href="administrator.php"><< Back to admin index</a>
	</center>
<br>

<?
footerDSP();
include("footer.php");