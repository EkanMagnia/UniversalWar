<?php
drawHeader($_GET["page"]);

if (isset($_POST["username"]) && isset($_POST["password"]) || $_SESSION["logged"] == 1) {
	$query = mysql_query("SELECT * FROM ".$_CFG["db"]["admins"]." WHERE username='$_POST[username]' AND password='".md5($_POST["password"])."'");
	if(mysql_num_rows($query) > 0 || $_SESSION["logged"] == 1) {
		$admin = mysql_fetch_array($query);
		if ($_SESSION["logged"] !=1) {
			$_SESSION["username"] = $admin["username"];
		}
		$_SESSION["logged"] = 1;
		?>
			<table border="0" cellspacing="10" cellpadding="0" style="width: 100%;">
				<tr>
					<td><font class="font">Welcome back, <b><?=$_SESSION["username"]?></b></font></td>
				</tr>
				<tr>
					<td>
						<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">&nbsp;&nbsp;&nbsp;
						<a href="<?=$PHP_SELF?>?page=admin_announcements">Announcements</a><br />
						<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">&nbsp;&nbsp;&nbsp;
						<a href="<?=$PHP_SELF?>?page=admin_calendar">Calendar</a>
					</td>
				</tr>
			</table>
		<?
	} else {
		header("Location: ".$PHP_SELF."?page=login");
	}
}
else {
	?>
	<table border="0" cellspacing="10" cellpadding="0" style="width: 100%;">
	<form action="<?=$PHP_SELF?>?page=adminlogin&action=login" method="post">
	<tr>
		<td style="text-align: left;" width="20"><font class="font">username:</font></td>
		<td><input type="text" name="username" /></td>
	</tr>
	<tr>
		<td style="text-align: left;"><font class="font">password:</font></td>
		<td><input type="password" name="password" /></td>
	</tr>
	<tr>
		<td style="text-align: left;">&nbsp;</td>
		<td><input type="submit" value="login"></td>
	</tr>
	</form>
	</table>
	<?
}

drawFooter();
?>