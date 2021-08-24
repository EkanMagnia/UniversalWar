<?php
drawHeader($_GET["page"]);

if ($_SESSION["userlogged"] != 1) {
	header("Location: ".$PHP_SELF."?p=login");	
}

if ($_GET["action"] == "change") {

	if ($_GET["change"] != "true") {
	?>

		<table border="0" cellspacing="20" cellpadding="0" style="width: 100%; text-align: center;">
			<tr>
				<td style="text-align: left;">	
				<table border="0" cellspacing="2" cellpadding="2" style="width: 100%; text-align: center;">
					<form action="<?=$PHP_SELF?>?page=profile&action=change&change=true" method="post">
					<tr>
						<td class="rowTopic" style="text-align: left;" colspan="2">Change your password</td>
					</tr>			
					<tr>
						<td class="row1" style="width: 150px; text-align: left;">&nbsp;New password:</td>
						<td class="row1" style="text-align: left;">
							<input type="password" name="newpassword" />
						</td>
					</tr>
					<tr>
						<td class="row1" style="width: 150px; text-align: left;">&nbsp;Confirm password:</td>
						<td class="row1" style="text-align: left;">
							<input type="password" name="newpasswordconfirm" />
						</td>
					</tr>
					<tr>
						<td class="row1" style="width: 150px; text-align: left;">&nbsp;</td>
						<td class="row1" style="text-align: left;">
							<input type="submit" value="Change password"">
						</td>
					</tr>			
					</form>
				</table>
				</td>
			</tr>
		</table>
	<?
	} elseif ($_GET["change"] == "true") {
		
		if ($_POST["newpassword"] == $_POST["newpasswordconfirm"] && $_POST["newpassword"]) {
			
			$newpassword = $_POST["newpassword"];
			mysql_query( "UPDATE users SET password='".md5($newpassword)."' WHERE id='$_SESSION[userid]'" );
			echo '&nbsp;&nbsp;&nbsp;Your password is now updated.<br />';
			
		} else {
			$errormsg = 'The confirmation password does not match.';
			echo '&nbsp;&nbsp;&nbsp;<font color="red">'.$errormsg.'</font><br />';	
			echo '&nbsp;&nbsp;&nbsp;Please return and control your input.<br />';
		}
		
	} else {
		$errormsg = 'An unexpected error has occured.';
		echo '&nbsp;&nbsp;&nbsp;<font color="red">'.$errormsg.'</font><br />';
		echo '&nbsp;&nbsp;&nbsp;Please try again later.<br />';
	}
	
} else {

?>

<table border="0" cellspacing="20" cellpadding="0" style="width: 100%; text-align: center;">
	<tr>
		<td style="text-align: left;">
			<font class="subject">Settings of <?=$_SESSION["username"]?></font>
		</td>
	</tr>
	<tr>
		<td style="text-align: left;">
			<ul style="list-style-type: square;">
				<li><a href="<?=$PHP_SELF?>?page=profile&action=change">Change my password</a>
				<li><a href="<?=$PHP_SELF?>?page=logout">Logout</a>
			</ul>
		</td>
	</tr>
</table>


<?
}
drawFooter();
?>
