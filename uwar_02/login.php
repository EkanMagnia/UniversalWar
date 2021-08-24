<?
$section = "Login";
include("header.php");
?>
<p style="margin: 20px;">

	<table cellspacing="6" cellpadding="0" border="0" width="90%">
		<? if($action == "recovery" && $emailaddress){ 
			
			/*** Database settings ***/

			//OLD db settings
//			$_DB_host = '62.70.14.32';
//			$_DB_username = 'univers_beta';
//			$_DB_password = 'betatest';
//			$_DB_database = 'univers_beta';

			//www.universawar.net db settings
			/*$_DB_host = 'mysql.domeneshop.no';
			$_DB_username = 'universalwar';
			$_DB_password = 'z7xKKd3';
			$_DB_database = 'universalwar';*/

//localhost db settings
			$_DB_host = 'localhost';
			$_DB_username = 'universalwar';
			$_DB_password = 'AnDr334SUCs';
			$_DB_database = 'pixelrage_universalwar_speed';



//			$_DB_username = 'dragons';
//			$_DB_password = 'mySQLdR4g0N';
//			$_DB_database = 'pixelrage_dragons_universalwar';

			# Syntax:
			# mysql_connect("host", "username", "password");
			$cn = mysql_connect($_DB_host, $_DB_username, $_DB_password);
			mysql_select_db($_DB_database,$cn);

			$query = mysql_query("SELECT * FROM uwar_users WHERE email='$emailaddress'") or die(mysql_error);
			$result = mysql_fetch_array($query);
			if ( sizeof($result) > 1 ) {
				$username = $result["username"];
				$password = $result["password"];
				$realname = $result["name"];
				
				$subject = "Password request - Universal War - Days of Revolution";
				$message .= "Hello ".$realname." !\n\n";
				$message .= "Someone has requested the password to your account on Universal War. If you feel that you have received this email by misstake, please just delete it.\n";
				$message .= "Your account details are:\n\n";
				$message .= "\tUsername: \t".$username."\n";
				$message .= "\tPassword: \t".$password."\n\n";
				$message .= "Thanks for playing Universal War! Good luck!\n\n";
				$message .= "//Universal War Crew\n";
				$message .= "http://www.universalwar.net/";
			
				if( mail($emailaddress, $subject, $message, "From : RecoverSystem@universalwar.net" ))
				{
					$msg = "Your password has been sent to: <b>".$emailaddress."</b>";
				}
				else $msg = "<font color=red>The password couldn't be sent to <b>".$emailaddress."</b>, please verify the email address.</font>";
			} else $msg = "<font color=red><b>".$emailaddress."</b> does not exists in our database, please verify the email address.</font>";
			?>
			<tr>
				<td colspan="2"><h3>Forgot your password?</h3></td>
			</tr>
			<tr>
				<td colspan="2"><?=$msg?></td>
			</tr>
		<? } elseif($action == "notlogged") { ?>
			<tr>
				<td colspan="2"><h3>Not logged in!</h3></td>
			</tr>
			<tr>
				<td>
					You cannot access any ingame files while aren't logged in. 
					<br><br>
					Possible reasons are:
						<ul>
							<!--<li>Your account was closed by an administrator</li>-->
							<li>You did not specify username and password when logging in</li>
							<li>You have provided a wrong username or password when logging in</li>
							<li>You did not access the game through the login page.</li>
							<br/><br/>
							<li>Your login cookie might have expired. The cookie automatically expires after 2 hours. Re-login please.</li>
							<li>Your browser does not accept cookies. Check your browser settings.</li>
							<li>Your firewall blocks cookies</li>
							<li>Your pc clock aint set right</li>
						</ul>
				</td>
			</tr>
			
		<? } elseif($action == "closed") { ?>
			<tr>
				<td colspan="2"><h3>Account closed!</h3></td>
			</tr>
			<tr>
				<td>
					You have broken one of more rules and you are not allowed to access the game. 
					<br><br>
					The Universal War rules are:
						<ul>
							<li>A. Players are allowed to have only 1 Account at Speed Round and 1 Account at Long Round. </li>
							<li>B. More than 1 Account at Speed Round or more than 1 Account at Long Round is NOT Allowed.</li>
							<li>C. Login on an account from different IP's is Allowed.</li>
							<li>D. Login on with more than one account on an IP it's NOT Allowed. One account per household unless there is no interaction (trading, battling, etc.) between accounts. For using 2 or more accounts on 1 IP you need to contact FaithRaven (irc.quakenet.org #universalwar, in-game mail to 1:1:1, Private Message on this forum). </li>
							<li>E. Account sharing is NOT Allowed.</li>
							<li>F. Offensive language against other players is strictly forbidden.</li>
							<li>G. System Images that contain pornographic/violent content are strictly forbidden.</li>
						</ul>
					Send an e-mail to <a href="mailto:raven@pixelrage.ro">raven@pixelrage.ro</a> for more information on your account closure.

				</td>
			</tr>
			
		<? } elseif($action == "loggedout") { ?>
			<tr>
				<td colspan="2"><h3>Logged out</h3></td>
			</tr>
			<tr>
				<td colspan="2">You are now logged out and all cookies are cleared.<br><br>
					Thanks for playing Universal War!<br><br>
					-----------------------------------------------
					<br>
				</td>
			</tr>
			<tr>
				<td colspan="2">Please visit our sponsor:<br><br><br>
					<a href="http://www.pixelrage.ro" target="_blank"><img style="border-color: #FFFFFF;" src="img/sponsor_1.gif" border="1" alt="pixelrage.ro - your daily downloads, news & articles" /></a>
				</td>
			</tr>
		<? } else { ?>
				<form action="uwar_02/index.php" method="post">
					<? if($action == "error") { ?>
					<tr>
						<td colspan="2"><font color="red">Invalid username/password!<br><br></td>
					</tr>
					<? } ?>
					<? if($action == "vacation" && $eta) { ?>
					<tr>
						<td colspan="2"><font color="red">You cannot log in while you are in vacation mode. The vacation mode will end in <?=$eta?> ticks.<br><br></td>
					</tr>
					<? } ?>
					<tr>
						<td colspan="2"><h3><?=$section?></h3></td>
					</tr>
					<tr>
						<td>Username:</td>
						<td><input type="text" name="username"></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="password" name="password"></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" name="submit" value="Login" style="height: 20px;"><br /><br /></td>
					</tr>
					</form>
					<tr>
						<td colspan="2"><h3>Forgot your password?</h3></td>
					</tr>
					<tr>
						<form action="<?=$PHP_SELF?>?action=recovery" method="post">
						<td>Email:</td>
						<td><input type="text" name="emailaddress"></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" name="submit" value="Recover" style="height: 20px;"></td>
					</tr>
				<? } ?>
	</table>
</p>
<?
include("footer.php");
?>