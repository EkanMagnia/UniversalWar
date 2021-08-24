<?
error_reporting(1);
$section = "IRC";
include("functions.php");
include("header.php");

headerDsp("IRC");
?>
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>">What is IRC?</td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">"IRC (Internet Relay Chat) is a virtual meeting place where people from all over the world can meet and talk; you'll find the whole diversity of human interests, ideas, and issues here, and you'll be able to participate in group discussions on one of the many thousands of IRC channels, on hundreds of IRC networks, or just talk in private to family or friends, wherever they are in the world." <i>- from <a href="http://www.mirc.com" target="_blank">http://www.mirc.com/</a></i></td>
	</tr>
</table>
</center>
<br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>">What do I need to use IRC?</td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">To use IRC you need the IRC client, mIRC. Lastest versions are available for download on mIRCs official website. <a href="http://www.mirc.com" target="_blank">http://www.mirc.com/</a></td>
	</tr>
</table>
</center>
<br>
<? 
footerDsp();

headerDsp("Universal War on IRC");
?>
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td bgcolor="<?=$tdbg1;?>">Universal War on IRC</td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">
			Of course the Universal War game community has its own irc channel. The channel name is #universalwar and is currently located on the network quakenet.<br><br>Once you have downloaded and installed the irc client, click on the link below to connect to our irc channel. You can also start mirc and join us by writing <i>/server irc.quakenet.org</i> followed by <i>/join #universalwar</i><br><br>
			<center><h3><a href="irc://irc.quakenet.org/universalwar">#universalwar at irc.quakenet.org</a></h3></center>
		</td>
	</tr>
</table>
</center>
<br>
<? 
footerDsp();
include("footer.php");
?>