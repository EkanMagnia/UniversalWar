<?php
drawHeader($_GET["page"]);
?>

<table border="0" cellspacing="20" cellpadding="0" style="width: 100%; text-align: center;">
	<tr>
		<td>
		
		<?
		/*	if ($_GET["action"] == "register") {
				
				if ($_POST["name"] && $_POST["email"] && $_POST["username"] && $_POST["password"]) {
					
					if( eregi( "^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$", $_POST["email"]) ) {
						
						$name = $_POST['name'];
						$email = $_POST['email'];
						$username = $_POST['username'];
						$password = $_POST['password'];
						$request = mysql_query("SELECT id FROM users WHERE email='$email' OR username='$username'");
						if ( mysql_num_rows($request) < 1 ) {
						
							mysql_query( "INSERT INTO users (name, email, username, password) VALUES ('$name', '$email', '$username', '".md5($password)."') ") or die(mysql_error());
	
							$subject = "Welcome to the Universal War Community and Portal!";
							$message = "Hi ".$_POST['name']."!\n\n";
							$message .= "Your Universal War Portal registration was successful.\n";
							$message .= "Your login details to the portal are:\n\n";
							$message .= "\tUsername: \t".$_POST['username'];
							$message .= "\tPassword: \t".$_POST['password']."\n\n";
							$message .= "Thanks for being a part of the Universal War Community!\n\n";
							$message .= "Universal War Crew";
				
							mail($_POST["email"], $subject, $message);						
							
							echo 'Congratulations '.$_POST["name"].'!<br /><br />';
							echo 'Your account on the portal is now created and you can login immediately.<br />';
							echo 'Your login details was also sent to <b>'.$_POST["email"].'</b>';
							
						} else {
								$errormsg = 'Either the username or the email address you stated exists in the database.';
								echo '<font color="red">'.$errormsg.'</font><br />';	
								echo 'Please return and control your input.';								
						}
						
					} else {
						$errormsg = 'Invalid e-mail address.';
						echo '<font color="red">'.$errormsg.'</font><br />';	
						echo 'Please return and control your input.';
					}
					
				} else {
					$errormsg = 'All fields not filled in.';
					echo '<font color="red">'.$errormsg.'</font><br />';	
					echo 'Please return and control your input.';
				}
				
			} else {
			*/
		
		?>
			<font class="font">To participate activly in the Universal War and the portal you need complete a simple registration.
			This portal is integrated with our <a href="http://universalwar.pixelrage.ro/forum/" target="_blank"><b>forum</b></a>, by registering on the forum you are automatically a member of the portal with same user details. 
			<br /><br /><center><a href="http://universalwar.pixelrage.ro/forum/profile.php?mode=register"><b>Register on the forums and the portal</b></a></center><br /><br />
			The following features will be enabled by register and login to the portal:
			<ul style="list-style-type: square;">
				<li> Participate in poll votings.
				<li> Non-annoymous comments to announcements, calendar events, articles, columns and poll votings.
				<li> Access to write column-articles that will posted under 'Columns' after administrators approval.
				<li> Access to add Universal War related events on the calendar after administrators approval.
			</ul>
			</font>
	</td>
	</tr>
	<!--<tr>
		<td style="text-align: left;">
			<table border="0" cellspacing="2" cellpadding="2" width="100%">
				<form action="<?=$PHP_SELF?>?page=register&action=register" method="post">
				<tr>
					<td colspan="2" class="rowTopic" style="text-align: left;">&nbsp;Portal registration</td>
				</tr>
				<tr>
					<td class="row1" style="width: 150px; text-align: left;">&nbsp;Name:</td>
					<td class="row1">&nbsp;<input type="text" name="name" style="width: 150px;"></td>
				</tr>
				<tr>
					<td class="row1" style="width: 150px; text-align: left;">&nbsp;E-mail:</td>
					<td class="row1">&nbsp;<input type="text" name="email" style="width: 150px;"></td>
				</tr>
				<tr>
					<td class="row1" style="width: 150px; text-align: left;">&nbsp;Username:</td>
					<td class="row1">&nbsp;<input type="text" name="username" style="width: 150px;"></td>
				</tr>
				<tr>
					<td class="row1" style="width: 150px; text-align: left;">&nbsp;Password:</td>
					<td class="row1">&nbsp;<input type="password" name="password" style="width: 150px;"></td>
				</tr>				
				<tr>
					<td class="row1" style="width: 150px; text-align: left;">&nbsp;</td>
					<td class="row1">&nbsp;<input type="submit" value="Register"></td>
				</tr>
				</form>
			</table>
			<?
			//}
			?>
		</td>
	</tr>-->
</table>


<?
drawFooter();
?>