<?
if(!$testtesttest)
{
	print "Signup is closed at the moment!";
}
else
{
include("functions.php");
$request = mysql_query("SELECT * FROM uwar_users WHERE id='$Userid'",$db);
$myrow = mysql_fetch_array($request);
if ($myrow["design"]==0) $headerfile = "header.php";
elseif ($myrow["design"]==1) $headerfile = "header2.php";
include($headerfile);

if ($Username!=""){
    $result = mysql_query("SELECT * FROM uwar_users WHERE username='$Username' AND password='$Password' AND closed=0",$db);
    if (mysql_num_rows($result)==1) {  }
    else
    {
        Setcookie("Username","");
        Setcookie("Password","");
        Setcookie("Userid","");
        Setcookie("Access","");
        $Username = "";
        $Userid = "";
        $Access = "";
        Header("Location: signup.php");
        die();
    }
}

if ($signup)
{

	$msgred = array();
    if ($realname =="") { $msgred[] = "Name not specified!"; }
    if ($country == "") { $msgred[] = "Country not selected!"; }
    if ($city =="") { $msgred[] = "City not specified!"; }
    if ($gender =="") { $msgred[] = "Gender not specified!"; }
    if (!isset($email) || !strlen($email) > 5 || !eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+([a-z]{2,3}$)",$email))
	{ $msgred[] = "Email invalid!"; }
    if ($uname =="") { $msgred[] = "Username not specified!"; }
    if ($pword =="" || $pword2=="") { $msgred[] = "Password not specified!"; }
    if ($pword != $pword2) { $msgred[] = "Passwords does not match!"; }
    if ($cname =="") { $msgred[] = "Commander name not specified!"; }
    if ($pname =="") { $msgred[] = "Planet name not specified!"; }
    if ($gal =="") { $msgred[] = "System option not specified!"; }
    if ($gal =="private" && $galpword == "") { $msgred[] = "System password not specified!"; }

	if(empty($msgred)) //If no errors so far, continue signup
	{
	    $result = mysql_query("SELECT * FROM uwar_users WHERE username='$uname'",$db);
	    if ($myrow = mysql_fetch_array($result)) { $msgred[] = "Username '$uname' is already taken!"; }
	    $result = mysql_query("SELECT * FROM uwar_users WHERE nick='$cname'",$db);
	    if ($myrow = mysql_fetch_array($result)) { $msgred[] = "Commander '$cname' already exist!"; }
	    $result = mysql_query("SELECT * FROM uwar_users WHERE planet='$pname'",$db);
	    if ($myrow = mysql_fetch_array($result)) { $msgred[] = "Planet '$pname' already exist!"; }
	    $result = mysql_query("SELECT * FROM uwar_users WHERE email='$email'",$db);
	    if ($myrow = mysql_fetch_array($result)) { $msgred[] = "E-mail address '$email' is already registered!"; }
	}

	if($gal == "random")
	{
	    if(empty($msgred)) //If no errors so far, continue signup
	    {
	        $result = mysql_query("SELECT Min(oid) as oid FROM uwar_order WHERE used=0",$db);
	        $myrow = mysql_fetch_array($result);
	        $oid = $myrow['oid'];
	        $result = mysql_query("SELECT * FROM uwar_order WHERE oid=$oid",$db);
	        $myrow = mysql_fetch_array($result);
	        $x = $myrow['x'];
	        $y = $myrow['y'];
	        $z = $myrow['z'];
	        $coords = "false";
	        while($coords == "false")
	        {
	            $y = rand(1,5);
	            $z = rand(1,10);
	            $request = mysql_query("SELECT * FROM uwar_order WHERE x='$x' AND y='$y' AND z='$z' AND used=0");
	            if(mysql_num_rows($request) == 1) $coords = "test";
	        }

	        $request = mysql_query("SELECT * FROM uwar_systems WHERE x='$x' AND y='$y'",$db);
	        $sysid_arr = mysql_fetch_array($request);
	        $sysid = $sysid_arr['sysid'];

	        if (!$sysid) //If id is empty, (no sys with coords X:Y), create a new sys
	        {
	            $request = mysql_query("SELECT id FROM uwar_systems ORDER BY id desc LIMIT 0,1");
	            $sysid_array = mysql_fetch_array($request);
	            $sysid = $sysid_array["id"];
	            $sysid++;

	            mysql_query("INSERT INTO uwar_systems (id,x,y, systype, sysname, sysmotd, syssize, sysscore, sysrank) VALUES ('$newid','$x','$y', 1, 'The Dark Empire', 'Welcome to $x:$y', '0', '0', '0')");
				mysql_query("INSERT INTO uwar_sysfund (sysid,sysmercury,syscobalt,syshelium) VALUES ('$sysid',0,0,0)");
	        }
	        $sql = mysql_query("INSERT INTO uwar_users (username, password, nick, planet, name, email, city, country, gender, sysid, z, mercury, cobalt, helium, score, asteroid_mercury, asteroid_cobalt, asteroid_helium, ui_roids, rank, timer, sleep, lastsleep, closed, protection, commander, vote, vacation, activity, online) VALUES
	        ('$uname', '$pword', '$cname', '$pname', '$realname', 'email', '$city', '$country', '$gender', '$sysid', '$z', 10000, 10000, 5000, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 100, 0, 0, 0, 0, 0)") or die("Could not insert to database!");

			$requesTT = mysql_query("SELECT * FROM uwar_users WHERE username='$uname' and password='$pword'");
			$sql = mysql_fetch_array($requesTT);
			$id = $sql["id"];
			mysql_query("INSERT INTO uwar_constructions (userid,constructionid) VALUES ($id,0)");
			mysql_query("INSERT INTO uwar_constructions (userid,constructionid) VALUES ($id,1)");
			mysql_query("INSERT INTO uwar_constructions (userid,constructionid) VALUES ($id,2)");

			mysql_query("INSERT INTO uwar_research (userid,researchid) VALUES ($id,0)");
			mysql_query("INSERT INTO uwar_research (userid,researchid) VALUES ($id,1)");
			mysql_query("INSERT INTO uwar_research (userid,researchid) VALUES ($id,2)");

			mysql_query("INSERT INTO uwar_tships (userid,fleetnum) VALUES ('$id',0)");
			mysql_query("INSERT INTO uwar_tships (userid,fleetnum) VALUES ('$id',1)");
			mysql_query("INSERT INTO uwar_tships (userid,fleetnum) VALUES ('$id',2)");
			mysql_query("INSERT INTO uwar_tships (userid,fleetnum) VALUES ('$id',3)");
			
	        //Logging("register",$sql);
	        mysql_query("UPDATE uwar_order SET used=1 WHERE x='$x' AND y='$y' AND z='$z'",$db);

			$subject = "Welcome to Universal War!";
			$message = "Hi $name!\n\n";
			$message .= "Your signup for an Universal War planet was successful and your account is now created.\n";
			$message .= "Your login details are:\n\n";
			$message .= "\tUsername: \t$uname\n";
			$message .= "\tPassword: \t$pword\n\n";
			$message .= "You can start playing now! For help to start playing, please read the Beginner's guide in the manual at http://www.universalwar.witch.fm/manual/ . Thanks for signup!\n\n";
			$message .= "Good Luck!\nUniversal War Crew";

			mail($email, $subject, $message);

	        if(empty($msgred))
		            $msggreen = "Signup was successfully completed!";
		
	    }
		}
		else if($gal == "private")
		{
		    if(empty($msgred)) //If no errors so far, continue signup
			{
	            $request = mysql_query("SELECT * FROM uwar_systems WHERE syspword='$galpword'");
	            $result = mysql_fetch_array($request);
	            $sysid = $result["id"];
	            $request2 = mysql_query("SELECT id FROM uwar_users WHERE sysid='$sysid'");
	            if(mysql_num_rows($request) == 0) $msgred = "System password is incorrect!";
	            if(mysql_num_rows($request2) >= 10) $msgred = "System is already full!";

	            if(empty($msgred)) //If no errors so far, continue signup
	            {
	                $x = $result["x"];
	                $y = $result["y"];
	                $z = mysql_num_rows($request2) + 1;
	                $sql = mysql_query("INSERT INTO uwar_users (username, password, nick, planet, name, email, city, country, gender, sysid, z, mercury, cobalt, helium, score, asteroid_mercury, asteroid_cobalt, asteroid_helium, ui_roids, rank, timer, sleep, lastsleep, closed, protection, commander, vote, vacation, activity, online) VALUES
	                ('$uname', '$pword', '$cname', '$pname', '$realname', 'email', '$city', '$country', '$gender', '$sysid', '$z', 10000, 10000, 5000, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 100, 0, 0, 0, 0, 0)") or die("Could not insert to database!");

			$requesTT = mysql_query("SELECT * FROM uwar_users WHERE username='$uname' and password='$pword'");
			$sql = mysql_fetch_array($requesTT);
			$id = $sql["id"];
			mysql_query("INSERT INTO uwar_constructions (userid,constructionid) VALUES ($id,0)");
			mysql_query("INSERT INTO uwar_constructions (userid,constructionid) VALUES ($id,1)");
			mysql_query("INSERT INTO uwar_constructions (userid,constructionid) VALUES ($id,2)");

			mysql_query("INSERT INTO uwar_research (userid,researchid) VALUES ($id,0)");
			mysql_query("INSERT INTO uwar_research (userid,researchid) VALUES ($id,1)");
			mysql_query("INSERT INTO uwar_research (userid,researchid) VALUES ($id,2)");

			mysql_query("INSERT INTO uwar_tships (userid,fleetnum) VALUES ('$id',0)");
			mysql_query("INSERT INTO uwar_tships (userid,fleetnum) VALUES ('$id',1)");
			mysql_query("INSERT INTO uwar_tships (userid,fleetnum) VALUES ('$id',2)");
			mysql_query("INSERT INTO uwar_tships (userid,fleetnum) VALUES ('$id',3)");
			


	                //Logging("register",$sql);
	                mysql_query("UPDATE uwar_order SET used=1 WHERE x='$x' AND y='$y' AND z='$z'",$db);

			$subject = "Welcome to Universal War!";
			$message = "Hi $name!\n\n";
			$message .= "Your signup for an Universal War planet was successful and your account is now created.\n";
			$message .= "Your login details are:\n\n";
			$message .= "\tUsername: \t$uname\n";
			$message .= "\tPassword: \t$pword\n\n";
			$message .= "You can start playing now! For help to start playing, please read the Beginner's guide in the manual at http://www.universalwar.witch.fm/manual/ . Thanks for signing up!\n\n";
			$message .= "Good Luck!\nUniversal War Crew";

			mail($email, $subject, $message);

       	            if(empty($msgred))
	                $msggreen = "Signup was successfully completed!";
	            }
			}
		}
		else if($gal == "create")
		{
		    if(empty($msgred)) //If no errors so far, continue signup
			{
   	            $request = mysql_query("SELECT id FROM uwar_systems ORDER BY id desc LIMIT 0,1");
	            $sysid_array = mysql_fetch_array($request);
	            $sysid = $sysid_array["id"];
	            $sysid++;
				$i = 0;

				while(!$something)
				{
	                $request = mysql_query("SELECT Min(oid) as oid FROM uwar_order WHERE oid>$i AND used=0 AND z=1",$db);
					$result = mysql_fetch_array($request);
					$oid = $result["oid"];

					$request = mysql_query("SELECT * FROM uwar_order WHERE oid='$oid'");
					$result = mysql_fetch_array($request);
	                $x = $result["x"];
	                $y = $result["y"];

	                $request = mysql_query("SELECT oid FROM uwar_order WHERE used=0 AND x='$x' AND y='$y'") or die("usch");
					$result = mysql_fetch_array($request);

					if(mysql_num_rows($request) == 10) break;
                    $i++;
				}

				$request = mysql_query("SELECT * FROM uwar_order WHERE used=0 AND x='x' AND y='y'");
				$result = mysql_fetch_array($request);

				$syspass = "sys".substr(md5(time()),0,9);	//Generate unique syspassword

	            mysql_query("INSERT INTO uwar_systems (id,x,y, syscreator, systype, syspword, sysname, sysmotd, syssize, sysscore, sysrank) VALUES ('$sysid','$x','$y', '$uname', 2, '$syspass','A new sunrise', 'Welcome to $x:$y', 0, 0, 0)") or die("Could not insert to database!");
				mysql_query("INSERT INTO uwar_sysfund (sysid,sysmercury,syscobalt,syshelium) VALUES ('$sysid',0,0,0)");


	                $sql = mysql_query("INSERT INTO uwar_users (username, password, nick, planet, name, email, city, country, gender, sysid, z, mercury, cobalt, helium, score, asteroid_mercury, asteroid_cobalt, asteroid_helium, ui_roids, rank, timer, sleep, lastsleep, closed, protection, commander, vote, vacation, activity, online) VALUES
	                ('$uname', '$pword', '$cname', '$pname', '$realname', 'email', '$city', '$country', '$gender', '$sysid', '1', 10000, 10000, 5000, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 100, 0, 0, 0, 0, 0)") or die("Could not insert to database!!");
			$requesTT = mysql_query("SELECT * FROM uwar_users WHERE username='$uname' and password='$pword'");
			$sql = mysql_fetch_array($requesTT);
			$id = $sql["id"];
			mysql_query("INSERT INTO uwar_constructions (userid,constructionid) VALUES ($id,0)");
			mysql_query("INSERT INTO uwar_constructions (userid,constructionid) VALUES ($id,1)");
			mysql_query("INSERT INTO uwar_constructions (userid,constructionid) VALUES ($id,2)");

			mysql_query("INSERT INTO uwar_research (userid,researchid) VALUES ($id,0)");
			mysql_query("INSERT INTO uwar_research (userid,researchid) VALUES ($id,1)");
			mysql_query("INSERT INTO uwar_research (userid,researchid) VALUES ($id,2)");

			mysql_query("INSERT INTO uwar_tships (userid,fleetnum) VALUES ('$id',0)");
			mysql_query("INSERT INTO uwar_tships (userid,fleetnum) VALUES ('$id',1)");
			mysql_query("INSERT INTO uwar_tships (userid,fleetnum) VALUES ('$id',2)");
			mysql_query("INSERT INTO uwar_tships (userid,fleetnum) VALUES ('$id',3)");
			

	                //Logging("register",$sql);
	                mysql_query("UPDATE uwar_order SET used=1 WHERE x='$x' AND y='$y' AND z='$z'",$db);
			//	mysql_query("UPDATE uwar_order SET systype=2 WHERE x='$x' AND y='$y'",$db);

			$subject = "Welcome to Universal War!";
			$message = "Hi $name!\n\n";
			$message .= "Your signup for an Universal War planet was successful and your account is now created.\n";
			$message .= "Your login details are:\n\n";
			$message .= "\tUsername: \t$uname\n";
			$message .= "\tPassword: \t$pword\n\n";
			$message .= "You have created a new system in the universe with coordonate $x:$y .\n";
			$message .= "\System Password = \t$syspass\n\n";
			$message .= "You can start playing now! For help to start playing, please read the Beginner's guide in the manual at http://www.universalwar.witch.fm/manual/ . Thanks for signup!\n\n";
			$message .= "Good Luck!\nUniversal War Crew";

			mail($email, $subject, $message);

	            if(empty($msgred))
	                $msggreen = "Signup was successfully completed!";
			}
		}
}


if($msggreen)
{

	headerDsp("Universal War 0.2 - Alpha/Beta testing signup");
	?>
	<table style="BORDER-COLLAPSE: collapse" bordercolor="#222222" border="0" cellpadding="4" cellspacing="0" width="100%">
   	<tr>
		<td>
			<?
            print "<font color=green>Signup was succesfully completed and your account is created!</font><br><br>";
			print "Your login details have also been sent to <b>$email</b><br>";
            print "<br>";
			print "Your login details are:<br><br>";
            print "Username:&nbsp;<b>$uname</b><br>";
            print "Password:&nbsp;<b>$pword</b><br><br>";
			print "For help to start playing, please read the <a href=#><b>Beginner's guide<b></a> in the manual.";
			?>
		</td>
	</tr>
	</table>
	<?
	footerDsp();
}
else
{
	headerDsp("Universal War 0.2 - Alpha/Beta testing signup");
	?>
	<table style="BORDER-COLLAPSE: collapse" bordercolor="#222222" border="1" cellpadding="4" cellspacing="0" width="100%">
	<?
	if($msgred!="")
	{
        print "<font color=\"red\">".sizeof($msgred)." errors were noticed! Please correct them before signup could be completed!</font><br><br>";
        foreach($msgred as $error)
        {
            print "<font color=\"red\">$error</font><br>";
        }
	}
	?>
	<form method="post" action="<?=$PHP_SELF?>">
	<tr>
	    <td bgcolor="#222222" colspan="2">Personal Information</td>
	</tr>
	<tr>
	    <td bgcolor="#333333">Name:</td>
	    <td bgcolor="#333333"><input type="Text" name="realname" value="<?=$realname?>"></td>
	</tr>
	<tr>
	    <td bgcolor="#333333">Country:</td>
	    <td bgcolor="#333333"><select name="country">
	        <option selected>Select your country</option>
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
	    <td bgcolor="#333333">City:</td>
	    <td bgcolor="#333333"><input type="Text" name="city" value="<?=$city?>"></td>
	</tr>
	<tr>
	    <td bgcolor="#333333">Gender:</td>
	    <td bgcolor="#333333"><input type="radio" name="gender" value="m" <? if($gender=="m") { ?>checked<? } ?> >Male<input type="radio" name="gender" value="f" <? if($gender=="f") { ?>checked<? } ?> > Female</td>
	</tr>
	<tr>
	    <td bgcolor="#333333">E-Mail:</td>
	    <td bgcolor="#333333"><input type="Text" name="email" value="<?=$email?>"></td>
	</tr>
	<tr>
	    <td bgcolor="#222222" colspan="2">Player Information</td>
	</tr>
	<tr>
	    <td bgcolor="#333333">Username:</td>
	    <td bgcolor="#333333"><input type="Text" name="uname" value="<?=$uname?>"></td>
	</tr>
	<tr>
	    <td bgcolor="#333333">Password:</td>
	    <td bgcolor="#333333"><input type="Password" name="pword" value="<?=$pword?>"></td>
	</tr>
	<tr>
	    <td bgcolor="#333333">Confirm Password:</td>
	    <td bgcolor="#333333"><input type="Password" name="pword2" value="<?=$pword2?>"></td>
	</tr>
	<tr>
	    <td bgcolor="#333333">Commander:</td>
	    <td bgcolor="#333333"><input type="Text" name="cname" value="<?=$cname?>"></td>
	</tr>
	<tr>
	    <td bgcolor="#333333">Planet:</td>
	    <td bgcolor="#333333"><input type="Text" name="pname" value="<?=$pname?>"></td>
	</tr>
	<tr>
	    <td bgcolor="#333333">System:</td>
	    <td bgcolor="#333333"><input type="radio" name="gal" value="private" <? if($gal=="private") { ?>checked<? } ?>> Join a private system - System password: <input type="text" name="galpword" size="12" maxlength="12" value="<?=$galpword?>"><br>
	    <input type="radio" name="gal" value="random" <? if($gal=="random") { ?>checked<? } ?>> Join a random system<br>
	    <input type="radio" name="gal" value="create" <? if($gal=="create") { ?>checked<? } ?>> Create a private system</tr>
	</tr>
	<tr>
	    <td bgcolor="#333333" colspan="2" align="center">
	        <input type="submit" name="signup" value="Sign me up">
	    </td>
	</tr>
	</form>
	</table>
	<?
	footerDsp();
}
include("footer.php");
}
?>