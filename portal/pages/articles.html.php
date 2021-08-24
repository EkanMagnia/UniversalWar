	<?php
drawHeader("articles");

if ($_GET["action"] == "view" && is_numeric($_GET["id"])) {
	$id = $_GET["id"];
	$query = mysql_query("SELECT * FROM ".$_CFG["db"]["articles"]." WHERE id='$id'");
	$articles = mysql_fetch_array($query);
	if (mysql_num_rows($query) > 0) {
		?>
		<table border="0" cellspacing="10" cellpadding="0" width="100%">
			<tr>
				<td style="text-align: left;"><font class="subject"><?=$articles["subject"]?></font></td>
			</tr>
			<tr>
				<td style="text-align: left;">
					<font class="writtenby">Posted by <b><?=$articles["author"]?></b> <?=date("Y.m.d H:i:s", $articles["date"])?></font>
				</td>		
			</tr>
			<tr>
				<td>
					<font class="font"><b><?=$articles["pretext"]?></b></font>
				</td>
			</tr>
			<tr>
				<td>
					<font class="font"><?=$articles["text"]?></font>
				</td>
			</tr>
			<tr>
				<td>
					<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="5" alt="">&nbsp;&nbsp;&nbsp;
					<a href="<?=$PHP_SELF?>?page=articles">Return to articles</a><br />
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
	$articlessQuery	= mysql_query("SELECT * FROM ".$_CFG["db"]["articles"]." ORDER BY date DESC LIMIT 10");
	while($articless = mysql_fetch_array($articlessQuery)) {
		?>
		<tr>
			<td style="text-align: left;"><font class="subject"><?=$articless["subject"]?></font></td>
		</tr>
		<tr>
			<td style="text-align: left;">
				<font class="writtenby">Posted by <b><?=$articless["author"]?></b> <?=date("Y.m.d H:i:s", $articless["date"])?></font>
			</td>		
		</tr>
		<tr>
			<td>
				<font class="font">"<i><?=$articless["pretext"]?></i>"</font>
			</td>
		</tr>
		<?
		if ($articless["text"] != "") {
			?>
			<tr>
				<td style="text-align: right;"><a href="<?=$PHP_SELF?>?page=articles&action=view&id=<?=$articless["id"]?>">>> Read more</a></td>
			</tr>
			<?
		}
		if (mysql_num_rows($articlessQuery) > 1) {
			?>
			<tr>
				<td><hr noshade /></td>
			</tr>
			<?
		}
	}
	if (mysql_num_rows($articlessQuery) < 1) {
		?>
		<tr>
			<td><font class="font">No articles posted.</font></td>
		</tr>
		<?				
	}
	?>
	</table>
	<?
}
drawFooter();
?>