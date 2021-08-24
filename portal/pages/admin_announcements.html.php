<?php
drawHeader("administrate announcements");

if ($_SESSION["logged"] != 1) {
	header("Location: ".$PHP_SELF."?page=login");
}

if ($_GET["action"] == "add") {
	if (isset($_POST["submit"])) {
		if ($_POST["subject"] && $_POST["pretext"]) {
			$subject = $_POST["subject"];
			$pretext .= "...";
			$pretext = nl2br($_POST["pretext"]);
			$text = nl2br($_POST["text"]);
			$author = $_SESSION["username"];
			$date = time();
			mysql_query("INSERT INTO ".$_CFG["db"]["announcements"]." (subject, pretext, text, author, date) VALUES ('$subject', '$pretext', '$text', '$author', '$date')");
			echo "<font class=\"font\">Announcement was posted! You will now be re-directed to the announcement page.</font>";
			header("Location: ".$PHP_SELF."?page=main");
		} else {
			echo "<font class=\"error\">&nbsp;&nbsp;&nbsp;&nbsp;You have not filled in all required fields.</font>";	
		}
	}
	elseif (isset($_POST["preview"])) {
		$pretext = nl2br($_POST["pretext"]);
		$text = nl2br($_POST["text"]);
		?>
		<table border="0" cellspacing="10" cellpadding="0" width="100%">
		<tr>
			<td style="text-align: left;"><font class="font">Preview:</font></td>
		</tr>
		<tr>
			<td style="text-align: left;"><font class="subject"><?=$_POST["subject"]?></font></td>
		</tr>
		<tr>
			<td style="text-align: left;">
				<font class="writtenby">Posted by <b><?=$_SESSION["username"]?></b> <?=date("Y.m.d H:i:s", time())?></font>
			</td>		
		</tr>
		<tr>
			<td>
				<font class="font"><b><?=$pretext?>...</b></font>
			</td>
		</tr>
		<tr>
			<td>
				<font class="font"><?=$text?></font>
			</td>
		</tr>
		<tr>
			<td><hr noshade /></td>
		</tr>
		</table>
		<?
	}
	?>
	<table border="0" cellspacing="10" cellpadding="0">
	<form action="<?=$PHP_SELF?>?page=admin_announcements&action=add" method="post">
	<tr>
		<td colspan="2"><font class="font">NOTE: Field <i>Text</i> is not required, if you leave it empty no <i>Read more</i> link will be shown.</font></td>
	</tr>
	<tr>
		<td width="50"><font class="font">Subject:</font></td>
		<td><input type="text" name="subject" value="<?=$_POST["subject"]?>" style="width: 200px;" /></td>
	</tr>
	<tr>
		<td width="50" style="vertical-align: top;"><font class="font">Pre-text:</font></td>
		<td><textarea name="pretext"><?=$_POST["pretext"]?></textarea></td>
	</tr>
	<tr>
		<td width="50" style="vertical-align: top;"><font class="font">Text:</font></td>
		<td><textarea name="text" style="width: 200px; height: 100px;"><?=$_POST["text"]?></textarea></td>
	</tr>
	<tr>
		<td width="50" style="vertical-align: top;">&nbsp;</td>
		<td width="210" style="vertical-align: top;">
			<input type="submit" value="Submit" name="submit" style="width: 99px;" />&nbsp;
			<input type="submit" value="Preview" name="preview" style="width: 99px;" />
		</td>
	</tr>	
	</table>
	<?
}
else {
	?>
	<table border="0" cellspacing="10" cellpadding="0" style="width: 100%;">
	<tr>
		<td>
			<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">&nbsp;&nbsp;&nbsp;
			<a href="<?=$PHP_SELF?>?page=admin_announcements&action=add">Add announcement</a><br />
			<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">&nbsp;&nbsp;&nbsp;
			<a href="<?=$PHP_SELF?>?page=admin_announcements&action=edit">Edit announcement</a><br />
			<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">&nbsp;&nbsp;&nbsp;
			<a href="<?=$PHP_SELF?>?page=admin_announcements&action=remove">Remove announcement</a><br /><br /><br /><br />
			<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">&nbsp;&nbsp;&nbsp;
			<a href="<?=$PHP_SELF?>?page=login">Return to admin index</a><br />

		</td>
	</tr>
	</table>
	<?
}
drawFooter();
?>