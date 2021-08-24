<?
error_reporting(1);
$section = "Administrator Forum";
include("functions.php");
include("header.php");

if ( !isset( $action ) ) {
headerDsp( "Topics" );
$request = mysql_query("SELECT * FROM uwar_aforum WHERE replyid=0 ORDER BY timereply DESC", $db);

$accessSQL = mysql_query("SELECT * FROM uwar_admins WHERE id='$Userid'");
$access = mysql_fetch_array($accessSQL);

if (mysql_num_rows($accessSQL) == 0 || $access["forum"] == "n")
{
	print "<br>You do not have access to administration forum.<br>";
	die();
}

?>
<br><img src="images/arrow_off.gif"> <a href="#newtopic">Post New Topic</a>
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<tr>
<td bgcolor="<?=$tdbg1;?>">Subject</td>
<td bgcolor="<?=$tdbg1;?>">Topic Starter</td>
<td bgcolor="<?=$tdbg1;?>">Posts</td>
<td bgcolor="<?=$tdbg1;?>">Last Reply</td>
<?
if ( mysql_num_rows ( $request ) == 0 ) {
?>
<tr>
<td bgcolor="<?=$tdbg2;?>" colspan="4"><center>There are currently no topics from any creator.</center></td>
<?
}
else {

while ( $topic = mysql_fetch_array ( $request ) ) {
?>
<tr>
<td bgcolor="<?=$tdbg2;?>"><a href="<? print $PHP_SELF; ?>?action=viewtopic&topicid=<? print $topic['id']; ?>"><? print $topic['subject']; ?></a><? if ( $topic['userid'] == $Userid || $access["editforum"] == "y" ) {?> (<a href="<? print $PHP_SELF; ?>?action=remove&postid=<? print $topic['id']; ?>">Remove</a>)<?}?></td>
<td bgcolor="<?=$tdbg2;?>"><? $rplyr = mysql_query("SELECT nick FROM ".$Uwar["table"]." WHERE id=$topic[threadstarter]",$db);$rply = mysql_fetch_array($rplyr);print $rply['nick']; ?></td>
<?
$replySQL = mysql_query("SELECT id FROM uwar_aforum WHERE replyid=$topic[id]", $db);
$replies = mysql_num_rows($replySQL);
?>
<td bgcolor="<?=$tdbg2;?>"><?=$replies+1;?></td>

<td bgcolor="<?=$tdbg2;?>"><? print date("M j, Y H:i:s",$topic['timereply']); ?></td>
<?
}
}
?>
</table>
</center>
<br><br>
<center>
<a name="newtopic">
<form action="<? print $PHP_SELF; ?>?action=newtopic" method="post">
<table border="0" cellpadding="4" cellspacing="0">
<tr>
<td valign="top">Subject:</td>
<td><input type="text" name="subject"></td>
<tr>
<td valign="top">Content:</td>
<td><textarea cols=40 rows=10 name="content"></textarea></td>
<tr>
<td>&nbsp;</td>
<td><input type="submit" value="Post New Topic"></td>
</table>
</form>
</center>
<?
footerDsp();
}
elseif ( $action == "newtopic" ) {
if ( $subject != "" && $content != "" ) {
$subject = strip_tags ( $subject );
$content = strip_tags ( $content );
$content = str_replace( "\n", "<br>", $content );
$time = time();
mysql_query("INSERT INTO uwar_aforum ( id, userid, time, subject, content, threadstarter, sysid, timereply, replyid ) values ( '', $Userid, $time, '$subject', '$content', $Userid, $myrow[sysid], $time, 0 )",$db);
?>
<script language="Javascript">
function moveNow() {
parent.location = '<? print $PHP_SELF; ?>';
}
setTimeout( "moveNow()", 125 );
</script>
<center><b>Topic Posted</b><br>If you are not automatically forwarded to the forum page, <a href="<? print $PHP_SELF; ?>">click here</a></center><br/>
<?
}
else {
?>
<center>You cannot post nothing!<br><a href="<? print $PHP_SELF; ?>">Click here</a> to return to the forum topics</center><br/>
<?
}
}
elseif ( $action == "viewtopic" && isset($topicid) ) {
?>
<a href="#reply">Reply to Topic</a> - <a href="<? print $PHP_SELF; ?>">Back to Topics</a><br><br>
<?
$request = mysql_query("SELECT * FROM uwar_aforum WHERE id = $topicid",$db);
$request2 = mysql_query("SELECT * FROM uwar_aforum WHERE replyid = $topicid", $db);

$topic = mysql_fetch_array( $request );
$request3 = mysql_query("SELECT nick FROM ".$Uwar["table"]." WHERE id = $topic[userid]",$db);

$TopicUsr = mysql_fetch_array( $request3 );
	headerDsp( $topic['subject']." - ".$TopicUsr['nick']." - ".date("M j, Y H:i:s",$topic['time']) );
	?>
	<br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
	<tr>
		<td><?=$topic['content']?></td>
	</tr>
	<?
	if ( $topic['userid'] == $Userid )
	{
		?>
		<tr>
		<td align="right"><a href="<? print $PHP_SELF; ?>?action=edit&postid=<? print $topic['id']; ?>">Edit Post</a> - <a href="<? print $PHP_SELF; ?>?action=remove&postid=<? print $topic[id]; ?>">Remove Post</a></td>
		</tr>
		<?
	} 
	?>
	</table>
	</center>
	<?
	footerDsp();

while ( $reply = mysql_fetch_array( $request2 ) ) {
$request4 = mysql_query("SELECT nick FROM ".$Uwar["table"]." WHERE id = $reply[userid]",$db);
$ReplyUsr = mysql_fetch_array( $request4 );
headerDsp( $reply['subject']." - ".$ReplyUsr['nick']." - ".date("M j, Y H:i:s",$reply['time']) );
?>
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<tr>
	<td><?=$reply['content']?></td>
</tr>
<?
if ( $reply['userid'] == $Userid ) {
?>
<tr>
<td align="right"><a href="<? print $PHP_SELF; ?>?action=edit&postid=<? print $reply['id']; ?>">Edit Post</a> - <a href="<? print $PHP_SELF; ?>?action=remove&postid=<? print $reply[id]; ?>">Remove Post</a></td>
</tr>
<?
}
?></table><?
footerDsp();
}
headerDsp("Reply");
?>
<center>
<a name="reply">
<form action="<? print $PHP_SELF; ?>?action=reply&topicid=<? print $topicid; ?>" method="post">
<table border="0" cellpadding="4" cellspacing="0">
<tr>
<td valign="top">Subject:</td>
<td><input type="text" name="subject" value="RE: <? print $topic['subject']; ?>"></td>
<tr>
<td valign="top">Content:</td>
<td><textarea cols=40 rows=10 name="content"></textarea></td>
<tr>
<td>&nbsp;</td>
<td><input type="submit" value="Post A Reply"></td>
</table>
</form>
</center>
<?
	footerDsp();
}
elseif ( $action == "reply" && isset( $topicid ) ) {
if ( $subject != "" && $content != "" ) {
$subject = strip_tags ( $subject );
$content = strip_tags ( $content );
$content = str_replace( "\n", "<br>", $content );
$time = time();
mysql_query("INSERT INTO uwar_aforum ( id, userid, time, subject, content, threadstarter, sysid, timereply, replyid ) values ( '', $Userid, $time, '$subject', '$content', 0, $myrow[sysid], 0, $topicid )",$db);
mysql_query("UPDATE uwar_aforum SET timereply = $time WHERE id = $topicid",$db);
?>
<script language="Javascript">
function moveNow() {
parent.location = '<? print $PHP_SELF; ?>?action=viewtopic&topicid=<? print $topicid; ?>';
}
setTimeout( "moveNow()", 100 );
</script>
<center><b>Reply Posted</b><br>If you are not automatically forwarded to the topic page, <a href="<? print $PHP_SELF; ?>?action=viewtopic&topicid=<? print $topicid; ?>">click here</a></center><br/>
<?
}
else {
?>
<center>You cannot post nothing!<br><a href="<? print $PHP_SELF; ?>?action=viewtopic&topicid=<? print $topicid; ?>">Click here</a> to return to the topic you were replying to</center><br/>
<?
}
}
elseif ( $action == "remove" && isset($postid) ) {
$request = mysql_query("SELECT * FROM uwar_aforum WHERE id = $postid",$db);
$post = mysql_fetch_array( $request );

if ( ($post['userid'] == $Userid) || ($access['editforum'] == "y" && $replyid == 0 ) ) {
if ( !isset( $confirmed ) ) {
?>
<center>
Are you sure you want to remove this post? <a href="<? print $PHP_SELF; ?>?action=remove&postid=<? print $postid; ?>&confirmed=yes"><font color="#FF0000">Yes</font></a>/<a href="<? print $PHP_SELF; ?>"><font color="#00FF00">No</font></a><br/>
</center>
<?
}
elseif ( $confirmed == "yes" ) {
mysql_query("DELETE FROM uwar_aforum WHERE id = $postid",$db);
mysql_query("DELETE FROM uwar_aforum WHERE replyid = $postid",$db);
?>
<script language="Javascript">
function moveNow() {
parent.location = '<? print $PHP_SELF; ?>';
}
setTimeout( "moveNow()", 100 );
</script>
<center><b>Post Removed</b><br>If you are not automatically forwarded to the government page, <a href="<? print $PHP_SELF; ?>">click here</a></center><br/>
<?
}
}
else {
?>
<center>You are not authorized to remove this post<br><a href="<? print $PHP_SELF; ?>">Click here</a> to return to the government page</center><br/>
<?
}
}
elseif ( $action == "edit" && isset($postid) ) {
$request = mysql_query("SELECT * FROM uwar_aforum WHERE id = $postid",$db);
$post = mysql_fetch_array( $request );

if ( $post['userid'] == $Userid ) {
if ( !isset( $subject ) && !isset( $content ) ) {
headerDsp( "Edit Post" );
?>
<br><br>
<center>
<form action="<? print $PHP_SELF; ?>?action=edit&postid=<? print $postid; ?>" method="post">
<table border="0" cellpadding="4" cellspacing="0">
<tr>
<td valign="top">Subject:</td>
<td><input type="text" name="subject" value="<? print $post['subject']; ?>"></td>
<tr>
<td valign="top">Content:</td>
<td><textarea cols=40 rows=10 name="content"><? print $post['content']; ?></textarea></td>
<tr>
<td>&nbsp;</td>
<td><input type="submit" value="Save Changes"></td>
</table>
</form>
</center>
<?
footerDsp();
}
else {
if ( $subject != "" && $content != "" ) {
$subject = strip_tags ( $subject );
$content = strip_tags ( $content );
$content = str_replace( "\n", "<br>", $content );

mysql_query("UPDATE uwar_aforum SET subject='$subject', content='$content' WHERE id = $postid",$db);
?>
<script language="Javascript">
function moveNow() {
parent.location = '<? print $PHP_SELF; ?>';
}
setTimeout( "moveNow()", 100 );
</script>
<center><b>Changes Saved</b><br>If you are not automatically forwarded to the forum page, <a href="<? print $PHP_SELF; ?>">click here</a></center><br/>
<?
}
else {
?>
<center>You cannot post nothing!<br><a href="<? print $PHP_SELF; ?>?action=viewtopic&topicid=<? print $topicid; ?>">Click here</a> to return to the topic you were replying to</center><br/>
<?
}
}
}
else {
?>
<center>
You cannot edit this post
</center>
<?
}
}
else {
?>
<center>Invalid Action</center>
<?
}
headerDsp("Help Guide");
?><p style="margin: 10px;"><?
echo PrintHelp($section);
?></p><?
footerDsp();
include("footer.php");
?>