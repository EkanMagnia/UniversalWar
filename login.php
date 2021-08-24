<table cellpadding="2" cellspacing="1" style="width: 100%;">
<tr>
	<td colspan="2" class="rowtopic">User login</td>
</tr>
<?
	if (is_numeric( $_GET["error"] )) {

		if ($_GET["error"] == 1) {
			$errorMsg = "Invalid username or password!";
		} elseif ($_GET["error"] == 2) {
			
		} elseif ($_GET["error"] == 3) {
			
		} elseif ($_GET["error"] == 4) {	
			
			$errorMsg = 'Unexpected login failure!</font><br /><br />';
			$errorMsg .= 'Possible reasons are:';
			$errorMsg .= '<ul style="list-style-type: square;">';
			$errorMsg .= '<li>Your login cookies has expired. The cookies automatically expires after 1 hour. Re-login please.</li>';
			$errorMsg .= '<li>You did not accept the game through the logn page.</li>';
			$errorMsg .= '<li>Your browser does not accept cookies. Check your browser settings.</li>';
			$errorMsg .= '<li>Your firewall blocks cookies.</li>';
			$errorMsg .= '<li>Your PC system clock is not set right.</li>';
			$errorMsg .= '</ul>';
			$errorMsg .= 'Still problems? Contact an administrator.';
			
		}
		
		echo '<tr><td><font color="red">'.$errorMsg.'<br /><br /></font></td></tr>';
		echo '<tr><td><a href="'.$PHP_SELF.'?p=login"><< Return to login page</a></td></tr>';
				
	} else {
?>

	<form action="uwar_02/index.php" method="post">
	<tr>
		<td class="row1">&nbsp;Username:</td>
		<td class="row1" style="text-align: right;"><input type="text" name="username"></td>
	</tr>
	<tr>
		<td class="row2">&nbsp;Password:</td>
		<td class="row2" style="text-align: right;"><input type="password" name="password"></td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: right;"><input type="submit" name="login" value="Login" style="height: 18px; width: 100px;"></td>
	</tr>
	</form>
	
	<tr>
		<td colspan="2" class="rowtopic">Password request</td>
	</tr>
	<tr>
		<td class="row1">&nbsp;E-mail:</td>
		<td class="row1" style="text-align: right;"><input type="text" name="email"></td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: right;"><input type="submit" name="login" value="Request" style="height: 18px; width: 100px;"></td>
	</tr>

<?
	}
?>