<?php
drawHeader($_GET["page"]);

if (isset($_POST["username"]) && isset($_POST["password"])) {
	mysql_connect($_CFG["db"]["host2"], $_CFG["db"]["username2"], $_CFG["db"]["password2"]);
	mysql_select_db($_CFG["db"]["dbname2"]);	
	$query = mysql_query("SELECT * FROM ".$_CFG["db"]["users"]." WHERE username='$_POST[username]' AND user_password='".md5($_POST["password"])."'");

	if(mysql_num_rows($query) > 0 || $_SESSION["logged"] == 1) {
		
		$user = mysql_fetch_array($query);
		
		
		$_SESSION["username"] = $user["username"];
		$_SESSION["userlogged"] = 1;
		$_SESSION["userid"] = $user["user_id"];
		?>
			<table border="0" cellspacing="10" cellpadding="0" style="width: 100%;">
				<tr>
					<td><font class="font">Welcome back to the portal, <b><?=$_SESSION["username"]?></b></font></td>
				</tr>
				<tr>
					<td>
						<font class="font">You are now logged in, and register-required features are enabled.<br />You are now being re-directed to the startpage.</font>
					</td>
				</tr>
			</table>
		<?
		
		header("Location: ".$PHP_SELF."?page=main");
		
	} else {
		$errormsg = 'Invalid username/password!';
		echo '<table border="0" cellspacing="10" cellpadding="0" style="width: 100%;">';
		echo '<tr><td><font color="red">'.$errormsg.'</font><br />';	
		echo 'Please return and control your input.</td></tr>';
		echo '</table>';
	}
} else {

?>

<table border="0" cellspacing="10" cellpadding="0" style="width: 100%;">
	<tr>
		<td colspan="2">
			<font class="font">Login to get full access on the Universal War Portal.
			Not registered? <a href="<?=$PHP_SELF?>?page=register">Signup here!</a></font>
		</td>
	</tr>
	<form action="<?=$PHP_SELF?>?page=login&action=login" method="post">	
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