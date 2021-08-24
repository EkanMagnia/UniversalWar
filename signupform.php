<form action="<?=$PHP_SELF?>?p=signup&action=signup" method="post">

<table cellpadding="2" cellspacing="1" style="width: 100%;">

<tr>

	<td colspan="2" class="rowtopic"><b>Universal War : New Dawn Signup</td>

</tr>

<?

	$resultSignup = mysql_query("SELECT signup FROM uwar_modes WHERE signup=1");

	if ( mysql_num_rows($resultSignup) ) {

		echo '<tr><td colspan="2">';

		echo "Signup is currently closed! Please try again later.<br /><br />";

		echo "</td></tr>";

	}

	else {

		

		if ($msggreen == "Signup was successfully completed!")  {

			?>

				<tr>

					<td colspan="2">

						<table border="0" cellspacing="2" cellpadding="0">

							<tr>

								<td colspan="2"><font color="green"><?=$msggreen?><br /><br /></td>

							</tr>

							<tr>

								<td colspan="2">

									Your signup for Universal War was successful and your account has now been created. 

									The Universal Lords have found an unhabited planet and they prepared it for your coming.

									They have given you 10000 mercury, 10000 cobalt and 5000 cesium aswell as 5 uninitiated probes to use as you wish.

									<br /><br />Your login details have been sent to:<br /><b><?=$email?></b><br /><br />

								</td>

							</tr>

							<tr>

								<td colspan="2">Login details:</td>

							</tr>

							<tr>

								<td>Username:</td><td><?=$_POST["uname"]?></td>

							</tr>

							<tr>

								<td>Password:</td>

								<td>The password is sent to your e-mail.</td>

							</tr>

							<tr>

								<td colspan="2">

									<br /><br />For help start playing, please read the <a href=http://universalwar.pixelrage.ro/game-manual/index.php?section=Begineer%20Guide&id=6><b>Beginner's guide</b></a> in the manual.

									<br /><br /><a href="index.php"><< Return to startpage</a>

								</td>

							</tr>

						</table>

					</td>

				</tr>

			<?		

		} else {

			

			if( !empty($msgred) ) {	

				?>

				<tr>

					<td colspan="2"><?

					echo '<font color="red">'.sizeof($msgred).' errors were noticed!</font><br />Please hit the back button of your browser and correct them before signup could be completed!</font><br /><br />';

					echo '<ul style="list-style-type: square;">';

					for($i = 0; $i < sizeof($msgred); $i++) {

						echo '<li>'.$msgred[$i].'</li><br />';

					}

					echo '</ul>';

					?>

					</td>

				</tr>

				<?

		} else { 

?>

<!--<tr>

	<td colspan="2"><font face="arial" size="2"><b>Game rules</b></font></td>

</tr>-->

<tr>

	<td colspan="2" style="text-align: center;">

		<br />By signing up I agree to the following rules:<br /><br />

		<textarea rows="10" cols="60">1) The creators take no responsibility for what happens to your accounts. All passwords in the database are stored in an encrypted form so if your hacked, its YOUR fault.

2) No Pornographic images in Galaxy Banners (this game is for minors as well).

3) Try to avoid insulting the admins, we give up our time and money to provide this game.

4) We are still in our testing period, if you find any bugs DO NOT use them to your advantage.

5) All Multi Accounts are deleted (If you have a valid reason for registering more than 1 account on 1 computer/ip address you must inform us at raven@pixelrage.ro). Players are allowed to have ONLY 1 Account at Long Era and 1 Account at Speed Round.

6) Account sharing is illegal, as it gives you an unfair advantages over others.

7) The usage of any program that gives you any form of advantage over other players is illegal, be they used for the control of your planet or simply to warn you when you get incoming, if you are detected using a program other than your browser you will be banned indefinately from the game.

8) Farming (taking probes with the permission of the person who currently has them) is illegal.

Breaking any of the above rules will result in the closure of your account. 

Closed accounts have 3 days to contact an administrator (raven@pixelage.ro) and resolve the matter. 

After that time has passes, the account will be automaticaly deleted. Planets deleted cannot be recovered.

Everything you do once in the game is logged, if you break a rule, we will catch you.

		</textarea>

		<br /><br />

	</td>

</tr>

<tr>

	<td colspan="2">Items marked with an * are required unless stated otherwise. Please don't use special characters like ~!@#$%^&*"|\ etc. Use only normal characters and numbers. Spaces are allowed.<br /><br /></td>

</tr>

<tr>

	<td colspan="2"><font face="arial" size="2"><b>Player Information</b></font></td>

</tr>

<tr>

	<td class="row1">&nbsp;*Username:</td>

	<td class="row1" style="text-align: right;"><input type="text" name="uname" value="<?=$_POST["uname"]?>"></td>

</tr>

<tr>

	<td class="row2">&nbsp;*E-mail:</td>

	<td class="row2" style="text-align: right;"><input type="text" name="email" value="<?=$_POST["email"]?>"></td>

</tr>

<tr>

	<td class="row1">&nbsp;*Password:</td>

	<td class="row1" style="height: 15px; text-align: right;">Will be sent by e-mail</td>

</tr>

<tr>

	<td class="row2">&nbsp;*Commander name:</td>

	<td class="row2" style="text-align: right;"><input type="text" name="cname" value="<?=$_POST["cname"]?>"></td>

</tr>

<tr>

	<td class="row1">&nbsp;*Planet name:</td>

	<td class="row1" style="text-align: right;"><input type="text" name="pname" value="<?=$_POST["pname"]?>"></td>

</tr>

<tr>

	<td colspan="2">&nbsp;</td>

</tr>

<tr>

	<td colspan="2"><font face="arial" size="2"><b>Game Information</b></font></td>

</tr>

<tr>

	<td class="row2">&nbsp;*Join mode:</td>

	<td class="row2" style="text-align: right;">

		<select name="gal">

			<option value="random" <? if ($_POST["gal"] == "random") { ?>selected="selected"<? }?> >Random system</option>

			<option value="private" <? if ($_POST["gal"] == "private") { ?>selected="selected"<? }?> >Join Private system</option>

			<option value="create" <? if ($_POST["gal"] == "create") { ?>selected="selected"<? }?> >Create system</option>

		</select>

	</td>

</tr>

<tr>

	<td class="row1">&nbsp;*System password (private systems only):</td>

	<td class="row1" style="text-align: right;"><input type="password" name="galpass" value="<?=$_POST["galpass"]?>"></td>

</tr>

<tr>

	<td colspan="2">&nbsp;</td>

</tr>

<tr>

	<td colspan="2"><font face="arial" size="2"><b>Personal Information</b></font></td>

</tr>

<tr>

	<td class="row2">&nbsp;*Full name:</td>

	<td class="row2" style="text-align: right;"><input type="text" name="realname" value="<?=$_POST["uname"]?>"></td>

</tr>

<tr>

	<td class="row1">&nbsp;*Country:</td>

	<td class="row1" style="text-align: right;">

		<select name="country">

	        <option selected value="None">Country</option>

	        <option value="AF">Afghanistan</option>

	        <option value="AL">Albania</option>

	        <option value="DZ">Algeria</option>

	        <option value="AS">American Samoa</option>

	        <option value="AD">Andorra</option>

	        <option value="AO">Angola</option>

	        <option value="AI">Anguilla</option>



	        <option value="AQ">Antarctica</option>

	        <option value="AG">Antigua And Barbuda</option>

	        <option value="AR">Argentina</option>

	        <option value="AM">Armenia</option>

	        <option value="AW">Aruba</option>

	        <option value="AU">Australia</option>



	        <option value="AT">Austria</option>

	        <option value="AZ">Azerbaijan</option>

	        <option value="BS">Bahamas</option>

	        <option value="BH">Bahrain</option>

	        <option value="BD">Bangladesh</option>

	        <option value="BB">Barbados</option>



	        <option value="BY">Belarus</option>

	        <option value="BE">Belgium</option>

	        <option value="BZ">Belize</option>

	        <option value="BJ">Benin</option>

	        <option value="BM">Bermuda</option>

	        <option value="BT">Bhutan</option>



	        <option value="BO">Bolivia</option>

	        <option value="BA">Bosnia and Herzegovina</option>

	        <option value="BW">Botswana</option>

	        <option value="BV">Bouvet Island</option>

	        <option value="BR">Brazil</option>

	        <option value="IO">British Indian Ocean Territory</option>



	        <option value="BN">Brunei</option>

	        <option value="BG">Bulgaria</option>

	        <option value="BF">Burkina Faso</option>

	        <option value="BI">Burundi</option>

	        <option value="KH">Cambodia</option>

	        <option value="CM">Cameroon</option>



	        <option value="CA">Canada</option>

	        <option value="CV">Cape Verde</option>

	        <option value="KY">Cayman Islands</option>

	        <option value="CF">Central African Republic</option>

	        <option value="TD">Chad</option>

	        <option value="CL">Chile</option>



	        <option value="CN">China</option>

	        <option value="CX">Christmas Island</option>

	        <option value="CC">Cocos (Keeling) Islands</option>

	        <option value="CO">Columbia</option>

	        <option value="KM">Comoros</option>

	        <option value="CG">Congo</option>



	        <option value="CK">Cook Islands</option>

	        <option value="CR">Costa Rica</option>

	        <option value="CI">Cote D'Ivoire (Ivory Coast)</option>

	        <option value="HR">Croatia (Hrvatska)</option>

	        <option value="CU">Cuba</option>

	        <option value="CY">Cyprus</option>



	        <option value="CZ">Czech Republic</option>

	        <option value="KP">D.P.R. Korea</option>

	        <option value="CD">Dem Rep of Congo (Zaire)</option>

	        <option value="DK">Denmark</option>

	        <option value="DJ">Djibouti</option>

	        <option value="DM">Dominica</option>



	        <option value="DO">Dominican Republic</option>

	        <option value="TP">East Timor</option>

	        <option value="EC">Ecuador</option>

	        <option value="EG">Egypt</option>

	        <option value="SV">El Salvador</option>

	        <option value="GQ">Equatorial Guinea</option>



	        <option value="ER">Eritrea</option>

	        <option value="EE">Estonia</option>

	        <option value="ET">Ethiopia</option>

	        <option value="FK">Falkland Islands (Malvinas)</option>

	        <option value="FO">Faroe Islands</option>

	        <option value="FJ">Fiji</option>



	        <option value="FI">Finland</option>

	        <option value="FR">France</option>

	        <option value="GF">French Guiana</option>

	        <option value="PF">French Polynesia</option>

	        <option value="TF">French Southern Territories</option>

	        <option value="GA">Gabon</option>



	        <option value="GM">Gambia</option>

	        <option value="GE">Georgia</option>

	        <option value="DE">Germany</option>

	        <option value="GH">Ghana</option>

	        <option value="GI">Gibraltar</option>

	        <option value="GR">Greece</option>



	        <option value="GL">Greenland</option>

	        <option value="GD">Grenada</option>

	        <option value="GP">Guadeloupe</option>

	        <option value="GU">Guam</option>

	        <option value="GT">Guatemala</option>

	        <option value="GN">Guinea</option>



	        <option value="GW">Guinea-Bissau</option>

	        <option value="GY">Guyana</option>

	        <option value="HT">Haiti</option>

	        <option value="HM">Heard and McDonald Islands</option>

	        <option value="HN">Honduras</option>

	        <option value="HK">Hong Kong SAR, PRC</option>



	        <option value="HU">Hungary</option>

	        <option value="IS">Iceland</option>

	        <option value="IN">India</option>

	        <option value="ID">Indonesia</option>

	        <option value="IR">Iran</option>

	        <option value="IQ">Iraq</option>



	        <option value="IE">Ireland</option>

	        <option value="IL">Israel</option>

	        <option value="IT">Italy</option>

	        <option value="JM">Jamaica</option>

	        <option value="JP">Japan</option>

	        <option value="JO">Jordan</option>



	        <option value="KZ">Kazakhstan</option>

	        <option value="KE">Kenya</option>

	        <option value="KI">Kiribati</option>

	        <option value="KR">Korea</option>

	        <option value="KW">Kuwait</option>

	        <option value="KG">Kyrgyzstan</option>



	        <option value="LA">Laos</option>

	        <option value="LV">Latvia</option>

	        <option value="LB">Lebanon</option>

	        <option value="LS">Lesotho</option>

	        <option value="LR">Liberia</option>

	        <option value="LY">Libya</option>



	        <option value="LI">Liechtenstein</option>

	        <option value="LT">Lithuania</option>

	        <option value="LU">Luxembourg</option>

	        <option value="MO">Macao</option>

	        <option value="MK">Macedonia</option>

	        <option value="MG">Madagascar</option>



	        <option value="MW">Malawi</option>

	        <option value="MY">Malaysia</option>

	        <option value="MV">Maldives</option>

	        <option value="ML">Mali</option>

	        <option value="MT">Malta</option>

	        <option value="MH">Marshall Islands</option>



	        <option value="MQ">Martinique</option>

	        <option value="MR">Mauritania</option>

	        <option value="MU">Mauritius</option>

	        <option value="YT">Mayotte</option>

	        <option value="MX">Mexico</option>

	        <option value="FM">Micronesia</option>



	        <option value="MD">Moldova</option>

	        <option value="MC">Monaco</option>

	        <option value="MN">Mongolia</option>

	        <option value="MS">Montserrat</option>

	        <option value="MA">Morocco</option>

	        <option value="MZ">Mozambique</option>



	        <option value="MM">Myanmar</option>

	        <option value="NA">Namibia</option>

	        <option value="NR">Nauru</option>

	        <option value="NP">Nepal</option>

	        <option value="NL">Netherlands</option>

	        <option value="AN">Netherlands Antilles</option>



	        <option value="NC">New Caledonia</option>

	        <option value="NZ">New Zealand</option>

	        <option value="NI">Nicaragua</option>

	        <option value="NE">Niger</option>

	        <option value="NG">Nigeria</option>

	        <option value="NU">Niue</option>



	        <option value="NF">Norfolk Island</option>

	        <option value="MP">Northern Mariana Islands</option>

	        <option value="NO">Norway</option>

	        <option value="OM">Oman</option>

	        <option value="PK">Pakistan</option>

	        <option value="PW">Palau</option>



	        <option value="PA">Panama</option>

	        <option value="PG">Papua new Guinea</option>

	        <option value="PY">Paraguay</option>

	        <option value="PE">Peru</option>

	        <option value="PH">Philippines</option>

	        <option value="PN">Pitcairn Island</option>



	        <option value="PL">Poland</option>

	        <option value="PT">Portugal</option>

	        <option value="PR">Puerto Rico</option>

	        <option value="QA">Qatar</option>

	        <option value="RE">Reunion</option>

	        <option value="RO">Romania</option>



	        <option value="RU">Russia</option>

	        <option value="RW">Rwanda</option>

	        <option value="SH">Saint Helena</option>

	        <option value="KN">Saint Kitts And Nevis</option>

	        <option value="LC">Saint Lucia</option>

	        <option value="PM">Saint Pierre and Miquelon</option>



	        <option value="VC">Saint Vincent And The Grenadines</option>

	        <option value="WS">Samoa</option>

	        <option value="SM">San Marino</option>

	        <option value="ST">Sao Tome and Principe</option>

	        <option value="SA">Saudi Arabia</option>

	        <option value="SN">Senegal</option>



	        <option value="SC">Seychelles</option>

	        <option value="SL">Sierra Leone</option>

	        <option value="SG">Singapore</option>

	        <option value="SK">Slovak Republic</option>

	        <option value="SI">Slovenia</option>

	        <option value="SB">Solomon Islands</option>



	        <option value="SO">Somalia</option>

	        <option value="ZA">South Africa</option>

	        <option value="GS">South Georgia</option>

	        <option value="ES">Spain</option>

	        <option value="LK">Sri Lanka</option>

	        <option value="SD">Sudan</option>



	        <option value="SR">Suriname</option>

	        <option value="SJ">Svalbard And Jan Mayen Islands</option>

	        <option value="SZ">Swaziland</option>

	        <option value="SE">Sweden</option>

	        <option value="CH">Switzerland</option>

	        <option value="SY">Syria</option>



	        <option value="TW">Taiwan Region</option>

	        <option value="TJ">Tajikistan</option>

	        <option value="TZ">Tanzania</option>

	        <option value="TH">Thailand</option>

	        <option value="SI">The South Sandwich Islands</option>

	        <option value="TG">Togo</option>

	        <option value="TK">Tokelau</option>



	        <option value="TO">Tonga</option>

	        <option value="TT">Trinidad And Tobago</option>

	        <option value="TN">Tunisia</option>

	        <option value="TR">Turkey</option>

	        <option value="TM">Turkmenistan</option>

	        <option value="TC">Turks And Caicos Islands</option>



	        <option value="TV">Tuvalu</option>

	        <option value="UG">Uganda</option>

	        <option value="UA">Ukraine</option>

	        <option value="AE">United Arab Emirates</option>

	        <option value="UK">United Kingdom</option>

	        <option value="US">United States</option>



	        <option value="UM">United States Minor Outlying Islands</option>

	        <option value="UY">Uruguay</option>

	        <option value="UZ">Uzbekistan</option>

	        <option value="VU">Vanuatu</option>

	        <option value="VA">Vatican City State (Holy See)</option>

	        <option value="VE">Venezuela</option>



	        <option value="VN">Vietnam</option>

	        <option value="VG">Virgin Islands (British)</option>

	        <option value="VI">Virgin Islands (US)</option>

	        <option value="WF">Wallis And Futuna Islands</option>

	        <option value="EH">Western Sahara</option>

	        <option value="YE">Yemen</option>



	        <option value="YU">Yugoslavia</option>

	        <option value="ZM">Zambia</option>

	        <option value="ZW">Zimbabwe</option>

		</select>		

	</td>

</tr>

<tr>

	<td class="row2">&nbsp;Sex:</td>

	<td class="row2" style="text-align: right;">

		<select name="gender">

			<option value="0" <?  if ($_POST["gender"] == "0") { ?>selected="selected"<? } ?> >Male</option>

			<option value="1" <?  if ($_POST["gender"] == "1") { ?>selected="selected"<? } ?> >Female</option>

		</select>		

	</td>

</tr>

<tr>

	<td class="row1">&nbsp;City:</td>

	<td class="row1" style="text-align: right;"><input type="text" name="city" value="<?=$_POST["city"]?>"></td>

</tr>

<tr>

	<td style="text-align: center;" align="center"><input type="checkbox" name="rules" style="width: 12px; height: 12px;" CHECKED>

	I have read and accepted the End User Agreement above.

	</td>

</tr>

<tr>

	<td colspan="2" style="text-align: center;"><input type="submit" name="submit" value="Signup" style="height: 18px; width: 100px;"></td>

</tr>

</form>	



	<?

		}

		}

		}

	?>

