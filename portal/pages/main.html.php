<?php
drawHeader("announcements");
	include_once( $_CFG["paths"]["includes"].'comment.php' );
	
if ($_GET["action"] == "add" && is_numeric($_GET["id"])) {
	
	//check that post exists
	$id = $_GET["id"];	
	$query = mysql_query("SELECT * FROM ".$_CFG["db"]["announcements"]." WHERE id='$id'");
	$announcement = mysql_fetch_array($query);
	if (mysql_num_rows($query) > 0) {
		$comment = new Comment($_GET["page"], $_GET["id"]);
		
		if ($_GET["insert"] == "true" && $_POST["comment"]) {

			$commenttext = nl2br( $_POST["comment"] );
			$comment->addComments($commenttext);
			
		} else {
			?>
			<table border="0" cellspacing="20" cellpadding="0" style="width: 100%; text-align: center;">
				<tr>
					<td style="text-align: left;">			
						<form method="post" action="<?=$PHP_SELF?>?page=main&action=add&id=<?=$id?>&insert=true">
							<table border="0" cellspacing="2" cellpadding="2" width="100%">
								<tr>
									<td class="rowTopic" colspan="2" style="text-align: left;">Add comment to post:</td>
								</tr>
								<tr>
									<td class="row1" style="vertical-align: top;">Comment:</td>
									<td class="row1">
										<textarea name="comment" style="width: 250px; height: 70px;"></textarea>
									</td>							
								</tr>
								<tr>
									<td class="row1">&nbsp;</td>
									<td class="row1"><input type="submit" value="Add comment" /></td>
								</tr>
							</table>
						</form>
					</td>
				</tr>
			</table>
			<?	
		}
			
	} else {
		echo 'Invalid ID';	
	}
	
} elseif ($_GET["action"] == "view" && is_numeric($_GET["id"])) {
	$id = $_GET["id"];
	$query = mysql_query("SELECT * FROM ".$_CFG["db"]["announcements"]." WHERE id='$id'");
	$announcement = mysql_fetch_array($query);
	if (mysql_num_rows($query) > 0) {
		$comment = new Comment($_GET["page"], $_GET["id"]);
		?>
		<table border="0" cellspacing="10" cellpadding="0" width="100%">
			<tr>
				<td style="text-align: left;"><font class="subject"><?=$announcement["subject"]?></font></td>
			</tr>
			<tr>
				<td style="text-align: left;">
					<font class="writtenby">Posted by <b><?=$_SESSION["username"]?></b> <?=date("Y.m.d H:i:s", $announcement["date"])?></font>
				</td>		
			</tr>
			<tr>
				<td>
					<font class="font"><b><?=$announcement["pretext"]?></b></font>
				</td>
			</tr>
			<tr>
				<td>
					<font class="font"><?=$announcement["text"]?></font>
				</td>
			</tr>
			<tr>
				<td><hr noshade></td>
			</tr>
			<tr>
				<td style="text-align: left;"><a name="comments"><font class="subject">User-contributed comments</font></a></td>
			</tr>
			<tr>
				<td style="text-align: left;">
					<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="5" alt="">
					&nbsp;&nbsp;&nbsp;<a href="<?=$PHP_SELF?>?page=main&action=add&id=<?=$announcement["id"]?>">Add comment</a>
				</td>	
			</tr>
			<tr>
				<td><?=$comment->printComments()?></td>
			</tr>
			<tr>
				<td>
					<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="5" alt="">&nbsp;&nbsp;&nbsp;
					<a href="<?=$PHP_SELF?>?page=main">Return to announcements</a><br />
				</td>
			</tr>
		</table>
		<?	
	} else {
		?>
		<tr>
			<td><font class="error">Invalid action!</font></td>
		</tr>
		<?			
	}
}
else {
	?>
	<table border="0" cellspacing="10" cellpadding="0" style="width: 100%;">
	<?

	$announcementsQuery	= mysql_query("SELECT * FROM ".$_CFG["db"]["announcements"]." ORDER BY date DESC LIMIT 10");
	while($announcements = mysql_fetch_array($announcementsQuery)) {

		$commentsQuery = mysql_query("SELECT id FROM ".$_CFG["db"]["comments"]." WHERE belongto='".$announcements["id"]."'");
		$numberofcomments = mysql_num_rows($commentsQuery);
		?>
		<tr>
			<td style="text-align: left;"><font class="subject"><?=$announcements["subject"]?></font></td>
		</tr>
		<tr>
			<td style="text-align: left;">
				<font class="writtenby">Posted by <b><?=$announcements["author"]?></b> <?=date("Y.m.d H:i:s", $announcements["date"])?></font>
			</td>		
		</tr>
		<tr>
			<td>
				<font class="font">"<i><?=$announcements["pretext"]?>...</i>"</font>
			</td>
		</tr>
		<?
		if ($announcements["text"] != "") {
			?>
			<tr>
				<td style="text-align: right;"><a href="<?=$PHP_SELF?>?page=main&action=view&id=<?=$announcements["id"]?>#comments"><b><?=$numberofcomments?></b> comments</a> | <a href="<?=$PHP_SELF?>?page=main&action=view&id=<?=$announcements["id"]?>"> Read more</a></td>
			</tr>
			<?
		}
		if (mysql_num_rows($announcementsQuery) > 1) {
			?>
			<tr>
				<td><hr noshade /></td>
			</tr>
			<?
		}
	}
	if (mysql_num_rows($announcementsQuery) < 1) {
		?>
		<tr>
			<td><font class="font">No announcements posted.</font></td>
		</tr>
		<?				
	}
	?>
	
	</table>
	<?
}
drawFooter();
?>