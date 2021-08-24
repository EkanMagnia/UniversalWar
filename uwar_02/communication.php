<?
error_reporting(1);
$section = "Messages";
include("functions.php");

$result = mysql_query("UPDATE ".$Uwar["mailtable"]." SET seen=1 WHERE userid='$Userid'",$db);
if(isset($action))
{
	if ( $action == "reply" && isset( $mailid ) )
	{
	    if ( isset($subject) && isset($content) && $subject !="" && $content!="")
	    {
	        $subject = strip_tags ( $subject );
	        $content = strip_tags ( $content );
			$subject = htmlentities($subject);
			$content = htmlentities($content);
	        $content = str_replace( "\n", "<br>", $content );
			$content = str_replace( "'", "`", $content );

	        $selectauthorid = mysql_query("SELECT authorid FROM ".$Uwar["mailtable"]." WHERE mailid='$mailid'");
	        $authorid = mysql_fetch_array($selectauthorid);
	        $selectauthor = mysql_query("SELECT id FROM ".$Uwar["table"]." WHERE id='$authorid[authorid]'");
	        $author = mysql_fetch_array($selectauthor);

	        Add_Mail($subject, $content, $author['id'], $Userid);
	        header("Location: communication.php");
	        ?>
	        <center><b>Reply Mailed</b><br>If you are not automatically forwarded to the mail page, <a href="<? print $PHP_SELF; ?>">click here to return</a></center>
	        <?
	    }
	    else
	    {
			include("header.php");
	        $selectmail = mysql_query("SELECT * FROM ".$Uwar["mailtable"]." WHERE mailid='$mailid'");
	        $mail = mysql_fetch_array($selectmail);
            headerDsp("Reply to message");
	        ?>
	        <center>
	        <a name="reply">
	        <form action="<? print $PHP_SELF; ?>?action=reply&mailid=<? print $mailid; ?>" method="post">
	        <table border="0" cellpadding="4" cellspacing="0">
	        <tr>
	        <td valign="top">Subject:</td>
	        <td><input type="text" name="subject" value="RE: <? print $mail['header']; ?>"></td>
	        <tr>
	        <td valign="top">Content:</td>
	        <td><textarea cols=40 rows=10 name="content"></textarea></td>
	        <tr>
	        <td>&nbsp;</td>
	        <td><input type="submit" value="Mail Reply"></td>
	        </table>
	        </form>
	        </center>
	        <?
            footerDsp();
	    }
	}
	elseif ( $action == "remove" && isset($mailid) )
	{
	    mysql_query("DELETE FROM ".$Uwar["mailtable"]." WHERE mailid='$mailid'",$db);
	    header("Location: communication.php");
	    ?>
	    <center><b>Mail Removed</b><br>If you are not automatically forwarded to the mail page, <a href="<? print $PHP_SELF; ?>">click here</a></center>
	    <?
	}
	elseif ( $action == "new")
	{
	    if ( isset($subject) && isset($content) && $subject !="" && $content!="" && is_numeric($x) && is_numeric($y) && is_numeric($z))
	    {
	            $subject = strip_tags ( $subject );
	            $content = strip_tags ( $content );
				$subject = htmlentities($subject);
				$content = htmlentities($content);
	            $content = str_replace( "\n", "<br>", $content );
				$content = str_replace( "'", "`", $content );

	            //get receivers id
	            $getsysidq = mysql_query("SELECT id FROM ".$Uwar["systems"]." WHERE x='$x' AND y='$y'");
				if(mysql_num_rows($getsysidq) == 0) $invalid = "true";
	            $getsysid = mysql_fetch_array($getsysidq);
	            $getreceiveridq = mysql_query("SELECT id FROM ".$Uwar["table"]." WHERE sysid='$getsysid[id]' AND z='$z'");
				if(mysql_num_rows($getreceiveridq) == 0) $invalid = "true";
	            $getreceiverid = mysql_fetch_array($getreceiveridq);

				if($invalid == "true") { header("Location: communication.php?action=new&incorrect=yes"); }
				else 
				{
					Add_Mail($subject, $content, $getreceiverid['id'], $Userid);
					header("Location: communication.php");
				}
				?>
	            <center><b>Mail Sent</b><br>If you are not automatically forwarded to the mail page, <a href="<? print $PHP_SELF; ?>">click here to return</a></center>
	            <?
	    }
	    else
	    {
			include("header.php");
			if(isset($incorrect)) { $msgred = "Invalid coordinates!"; }
			if(isset($msgred))
				print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";
			
			headerDsp("New message");
	        ?>
            <br>
	        <center>
	        <a name="new">
	        <form action="<? print $PHP_SELF; ?>?action=new" method="post">
	        <table border="0" cellpadding="4" cellspacing="0">
	            <tr>
	                <td valign="top">To:</td>
	                <td>
	                    <input type="text" maxlength="2" size="3" name=x value="<?if(isset($x)) print $x;?>"> :
	                    <input type="text" maxlength="2" size="3" name=y value="<?if(isset($y)) print $y;?>"> :
	                    <input type="text" maxlength="2" size="3" name=z value="<?if(isset($z)) print $z;?>">
	                </td>
	            </tr>
	            <tr>
	                <td valign="top">Subject:</td>
	                <td><input type="text" name="subject" value=></td>
	            </tr>
	            <tr>
	                <td valign="top">Content:</td>
	                <td><textarea cols="40" rows="10" name="content"></textarea></td>
	            </tr>
	            <tr>
	                <td>&nbsp;</td>
	                <td><input type="submit" value="Send Mail"></td>
	            </tr>
	        </table>
	        </form>
	        </center>
	        <?
           	footerDsp();
	    }
	}

	elseif ( $action == "SoCmail")
	{
	    if ( isset($subject) && isset($content) && $subject !="" && $content!="" && $myrow["commander"] == 3)
	    {
	            $subject = strip_tags ( $subject );
	            $content = strip_tags ( $content );
				$subject = htmlentities($subject);
				$content = htmlentities($content);
	            $content = str_replace( "\n", "<br>", $content );
				$content = str_replace( "'", "`", $content );

	            //get receivers id
				$request = mysql_query("SELECT * FROM uwar_users WHERE sysid='$myrow[sysid]'",$db);
				while ($user = mysql_fetch_array($request))
				{
					$id = $user["id"];
					Add_Mail($subject, $content, $id, $Userid);
					//header("Location: communication.php");
				}
				?>
	            <center><b>Mail Sent</b><br>If you are not automatically forwarded to the communication page, <a href="<? print $PHP_SELF; ?>">click here to return</a></center>
	            <?
	    }
	    else
	    {
			include("header.php");
			if(isset($msgred))
				print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";
			
			headerDsp("New message");
	        ?>
            <br>
	        <center>
	        <a name="new">
	        <form action="<? print $PHP_SELF; ?>?action=SoCMail" method="post">
	        <table border="0" cellpadding="4" cellspacing="0">
	            <tr>
	                <td valign="top">Subject:</td>
	                <td><input type="text" name="subject" value=></td>
	            </tr>
	            <tr>
	                <td valign="top">Content:</td>
	                <td><textarea cols="40" rows="10" name="content">This message was sent to the entire system:</textarea></td>
	            </tr>
	            <tr>
	                <td>&nbsp;</td>
	                <td><input type="submit" value="Send Mail"></td>
	            </tr>
	        </table>
	        </form>
	        </center>
	        <?
           	footerDsp();
	    }
	}

	elseif ( $action == "deleteall")
	{
		include("header.php");
	    if (!isset( $confirmed ) )
	    {
	        ?>
	        <center>
	        Are you sure you want to delete all your mails?
	        <a href="<? print $PHP_SELF; ?>?action=deleteall&delete=true&confirmed=yes"><font color="#FF0000">Yes</font></a>/
	        <a href="<? print $PHP_SELF; ?>"><font color="#00FF00">No</font></a>
	        </center>
	        <?
	    }
	    elseif ( $confirmed == "yes" )
	    {
	        mysql_query("DELETE FROM ".$Uwar["mailtable"]." WHERE userid='$Userid'",$db) or die("doh");
	        Header("Location: communication.php");
	    }
	}
	else
	{
	    header("Location: communication.php");
	    ?>
	    <center><b><font color=red>Invalid Action!</font></b><br>If you are not automatically forwarded to the mail page, <a href="<? print $PHP_SELF; ?>">click here</a></center>
	    <?
	}
}
else
{
	include("header.php");
    $request = mysql_query("SELECT * FROM ".$Uwar["mailtable"]." WHERE userid='$Userid' ORDER BY time DESC");
    ?><a href="<?=$PHP_SELF?>?action=new">New Mail</a> - <a href="<?=$PHP_SELF?>?action=deleteall">Delete all mails</a><?
	if ($myrow["commander"] == 3) 
	{
		?> - <a href="<?=$PHP_SELF;?>?action=SoCmail">Mail all the System Members</a><?
	}
	?><br><br><?

	if(mysql_num_rows($request) == 0)
    {
    	headerDsp("Messages");
    	?><br><center>You do not have any messages.</center><br> <?
       	footerDsp();
    }
	else
    {
	    while($mails = mysql_fetch_object($request))
	    {
	        $authorquery = mysql_query("SELECT nick, planet, sysid, z FROM ".$Uwar["table"]." WHERE id='$mails->authorid'");
	        $authorresult = mysql_fetch_array($authorquery);
	        $authornick = $authorresult['nick'];
	        $authorplanet = $authorresult['planet'];
			$authorSystemSQL = mysql_query("SELECT * FROM uwar_systems WHERE id='$authorresult[sysid]'");
			$authorSystem = mysql_fetch_array($authorSystemSQL);
	        headerDsp( $mails->header." - ".$authornick." of ".$authorplanet." (".$authorSystem["x"].":". $authorSystem["y"].":".$authorresult["z"].") - ".date("M j, Y H:i:s",$mails->time));
	        ?><p style="margin: 10px;"><?
			print $mails->mail;
	        ?>
			</p>
	        <br>
			<table border="0" cellpadding="10" cellspacing="0" width="100%">
	        <tr>
	        <td align="right"><a href="<? print $PHP_SELF; ?>?action=reply&mailid=<? print $mails->mailid; ?>">Reply to Mail</a> - <a href="<? print $PHP_SELF; ?>?action=remove&mailid=<? print $mails->mailid; ?>">Remove Mail</a></td>
	        </table>
	        <?
	        footerDsp();
	    }
    }
}

include("footer.php");
?>