<?php
drawHeader("columns");

if ($_GET["action"] == "write" ) {
	
	if ($_SESSION["userlogged"] == 1) {
		
			if (isset($_POST["submit"])) {
				if ($_POST["subject"] && $_POST["pretext"]) {
					$subject = $_POST["subject"];
					$pretext .= "...";
					$pretext = nl2br($_POST["pretext"]);
					$text = nl2br($_POST["text"]);
					$author = $_SESSION["username"];
					$date = time();
					$groupid = $_POST["section"];
					
					mysql_query("INSERT INTO ".$_CFG["db"]["columns"]." (groupid, subject, 	pretext, text, author, date, accepted) VALUES ('$groupid', '$subject', '$pretext', '$text', '$author', '$date', 0)");
					echo '<table border="0" cellspacing="10" cellpadding="0" width="100%"><tr><td>';
					echo "<font class=\"font\">Column post was written! The administrators will now judge if the post will be accepted and published.</font>";
					echo '</td></tr></table>';
					
				} else {
					echo "<font class=\"error\">&nbsp;&nbsp;&nbsp;&nbsp;You have not filled in all required fields.</font>";	
				}
			} else {
					if (isset($_POST["preview"])) {
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
				<form action="<?=$PHP_SELF?>?page=columns&action=write" method="post">
				<tr>
					<td colspan="2"><font class="font">NOTE: Field <i>Text</i> is not required, if you leave it empty no <i>Read more</i> link will be shown.</font></td>
				</tr>
				<tr>
					<td width="50"><font class="font">Subject:</font></td>
					<td><input type="text" name="subject" value="<?=$_POST["subject"]?>" style="width: 200px;" /></td>
				</tr>
				<tr>
					<td width="50"><font class="font">Section:</font></td>
					<td>
						<select name="section">
							<option value="0" <? if ($_POST["section"] == 0) echo 'selected="selected"'; ?>>Alliance</option>
							<option value="1" <? if ($_POST["section"] == 1) echo 'selected="selected"'; ?>>Strategy</option>
							<option value="2" <? if ($_POST["section"] == 2) echo 'selected="selected"'; ?>>Stories</option>
							<option value="3" <? if ($_POST["section"] == 3) echo 'selected="selected"'; ?>>Divertissement</option>
						</select>
					</td>			
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
	} else {
		echo '<table border="0" cellspacing="10" cellpadding="0" width="100%"><tr><td>';
		echo '<font color="red">You must be logged in to be able to write column posts!</font>';	
		echo '</td></tr></table>';
	} 
	
} elseif ($_GET["action"] == "view" && is_numeric($_GET["id"]) && is_numeric($_GET["groupid"]) ) {
	
	if ($_GET["groupid"] == 0 || $_GET["groupid"] == 1 || $_GET["groupid"] == 2 || $_GET["groupid"] == 3 ) {
	
		if ($_GET["groupid"] == 0 ) $section = "Alliances";
		if ($_GET["groupid"] == 1 ) $section = "Strategy";
		if ($_GET["groupid"] == 2 ) $section = "Stories";
		if ($_GET["groupid"] == 3 ) $section = "Divertissements";
		$id = $_GET["id"];
		$groupid = $_GET["groupid"];
		$query = mysql_query("SELECT * FROM ".$_CFG["db"]["columns"]." WHERE id='$id' AND groupid='$groupid' AND accepted=1");
		$columns = mysql_fetch_array($query);
		if (mysql_num_rows($query) > 0) {
			?>
			<table border="0" cellspacing="10" cellpadding="0" width="100%">
				<tr>
					<td style="text-align: left;"><font class="subject2"><?=$section." - ".$columns["subject"]?></font></td>
				</tr>
				<tr>
					<td style="text-align: left;">
						<font class="writtenby">Written by <b><?=$columns["author"]?></b> <?=date("Y.m.d H:i:s", $columns["date"])?></font>
					</td>		
				</tr>
				<tr>
					<td>
						<font class="font"><b><?=$columns["pretext"]?></b></font>
					</td>
				</tr>
				<tr>
					<td>
						<font class="font"><?=$columns["text"]?></font>
					</td>
				</tr>
				<tr>
					<td>
						<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="5" alt="">&nbsp;&nbsp;&nbsp;
						<a href="<?=$PHP_SELF?>?page=columns&groupid=<?=$groupid?>">Return to column posts</a><br />
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
	} else {
		?>
		<table border="0" cellspacing="10" cellpadding="0" style="width: 100%;">
			<tr>
				<td><font class="font">Invalid section!</font></td>
			</tr>
		</table>
		<?			
		
	}
	
} elseif ( is_numeric($_GET["groupid"]) ) {
	
	if ($_GET["groupid"] == 0 || $_GET["groupid"] == 1 || $_GET["groupid"] == 2 || $_GET["groupid"] == 3 ) {
		
		if ($_GET["groupid"] == 0 ) $section = "Alliances";
		if ($_GET["groupid"] == 1 ) $section = "Strategy";
		if ($_GET["groupid"] == 2 ) $section = "Stories";
		if ($_GET["groupid"] == 3 ) $section = "Divertissements";
		?>
		<table border="0" cellspacing="10" cellpadding="0" style="width: 100%;">
		<tr>
			<td style="text-align: left;"><font class="subject"><?=$section?></font></td>
		</tr>
		<?
		$columnsQuery = mysql_query("SELECT * FROM ".$_CFG["db"]["columns"]." WHERE groupid=".$_GET["groupid"]." AND accepted=1 ORDER BY date DESC");
		while($columns = mysql_fetch_array($columnsQuery)) {
			?>
			<tr>
				<td style="text-align: left;"><font class="subject"><?=$columns["subject"]?></font></td>
			</tr>
			<tr>
				<td style="text-align: left;">
					<font class="writtenby">Written by <b><?=$columns["author"]?></b> <?=date("Y.m.d H:i:s", $columns["date"])?></font>
				</td>		
			</tr>
			<tr>
				<td>
					<font class="font">"<i><?=$columns["pretext"]?></i>"</font>
				</td>
			</tr>
			<?
			if ($columns["text"] != "") {
				?>
				<tr>
					<td style="text-align: right;"><a href="<?=$PHP_SELF?>?page=columns&action=view&id=<?=$columns["id"]?>&groupid=<?=$_GET["groupid"]?>">>> Read more</a></td>
				</tr>
				<?
			}
			?>
				<tr>
					<td><hr noshade /></td>
				</tr>
			<?
		}
			?>
				<tr>
					<td>
						<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="5" alt="">&nbsp;&nbsp;&nbsp;
						<a href="<?=$PHP_SELF?>?page=columns">Return to columns page</a><br />
					</td>
				</tr>			
			
			<?
		if (mysql_num_rows($columnsQuery) < 1) {
			?>
			<tr>
				<td style="text-align: left;"><font class="subject"><?=$section?></font></td>
			</tr>			
			<tr>
				<td><font class="font">No column posts written.</font></td>
			</tr>
			<?				
		}
		?>
		</table>
		<?
	} else {
		?>
		<table border="0" cellspacing="10" cellpadding="0" style="width: 100%;">
			<tr>
				<td><font class="font">Invalid section!</font></td>
			</tr>
		</table>
		<?			
	}
} else {
	echo '<table border="0" cellspacing="8" cellpadding="0" style="width: 100%;">';
	echo '<tr><td><a href="'.$PHP_SELF.'?page=columns&action=write"><b>Write a column posts<br /></b></a><br /></td></tr>';	
	echo '<tr><td>Please choose a section:</td></tr>';	
	echo '<tr><td><img src="'.$_CFG["paths"]["img"].'arrow.gif" width="7" height="6" alt="">&nbsp;<a href="'.$PHP_SELF.'?page=columns&groupid=0">Alliances</a></td></tr>';	
	echo '<tr><td><img src="'.$_CFG["paths"]["img"].'arrow.gif" width="7" height="6" alt="">&nbsp;<a href="'.$PHP_SELF.'?page=columns&groupid=1">Stratgey</a></td></tr>';	
	echo '<tr><td><img src="'.$_CFG["paths"]["img"].'arrow.gif" width="7" height="6" alt="">&nbsp;<a href="'.$PHP_SELF.'?page=columns&groupid=2">Stories</a></td></tr>';	
	echo '<tr><td><img src="'.$_CFG["paths"]["img"].'arrow.gif" width="7" height="6" alt="">&nbsp;<a href="'.$PHP_SELF.'?page=columns&groupid=3">Disvertissement</a></td></tr>';	
	
	echo '</table>';
	
	
}
drawFooter();
?>