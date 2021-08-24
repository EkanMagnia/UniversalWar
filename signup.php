<?php

/*** Define error array ***/
	$msgred = array();
	
/*** Seeds the randomizing generator ***/
	srand ((float) microtime() * 10000000);
	$pword = substr(md5(time()),0,10);
	
	if($_GET["action"] == "signup" && isset($_POST["submit"]))	
	{
	
		/*** Control input ***/	
		validateSignupInput();
		stripSignupInput();
		checkSignupInput();

		if ($_POST["gal"] == "random") {
			
			if( empty($msgred))
			{
				$x = generateX();
				$y = generateY($x);
				$z = generateZ($x, $y);
				
				$resultSysid = mysql_query("SELECT id FROM uwar_systems WHERE x='$x' AND y='$y'");
				$sysid = mysql_fetch_array($resultSysid);
				$sysid = $sysid["id"];				
				
				$signupdate = time();
				$sql = mysql_query("INSERT INTO uwar_users (username, password, nick, planet, name, email, city, ip, country, gender, sysid, z, mercury, cobalt, helium, score, asteroid_mercury, asteroid_cobalt, asteroid_helium, ui_roids, rank, timer, sleep, lastsleep, closed, protection, commander, vote, vacation, activity, online, tag, signupdate) VALUES
				('$uname', '$pword', '$cname', '$pname', '$realname', '$email', '$city', '$REMOTE_ADDR', '$country', '$gender', '$sysid', '$z', 10000, 10000, 5000, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 100, 0, 0, 0, 0, 0, '', '$signupdate')") or die("Could not insert to database!");

                $request = mysql_query("SELECT * FROM uwar_users WHERE username='$uname' and password='$pword'");
				$result = mysql_fetch_array($request);
				$userid = $result["id"];
				
				for ($i=0;$i<=4;$i++)
				mysql_query("INSERT INTO uwar_constructions (userid,constructionid) VALUES ($userid,$i)");

				mysql_query("INSERT INTO uwar_tships (userid,fleetnum,action) VALUES ('$userid',0,'h')");
				mysql_query("INSERT INTO uwar_tships (userid,fleetnum,action) VALUES ('$userid',1,'h')");
				mysql_query("INSERT INTO uwar_tships (userid,fleetnum,action) VALUES ('$userid',2,'h')");
				mysql_query("INSERT INTO uwar_tships (userid,fleetnum,action) VALUES ('$userid',3,'h')");

				//Logging("register",$sql);

				sendMail($email, $realname, $uname, $pword, "N/A");
					
				if(empty($msgred))
						$msggreen = "Signup was successfully completed!";
            }
		}
		else if($gal == "private")
		{
			if(empty($msgred)) //If no errors so far, continue signup
			{
				$request = mysql_query("SELECT * FROM uwar_systems WHERE syspword='$galpass'");
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

					$request = mysql_query("SELECT id FROM uwar_users ORDER BY id desc LIMIT 0,1");
					$userid_array = mysql_fetch_array($request);
					$userid = $userid_array["id"];
					$userid++;
					$signupdate = time();
					$sql = mysql_query("INSERT INTO uwar_users (id, username, password, nick, planet, name, email, city, ip, country, gender, sysid, z, mercury, cobalt, helium, score, asteroid_mercury, asteroid_cobalt, asteroid_helium, ui_roids, rank, timer, sleep, lastsleep, closed, protection, commander, vote, vacation, activity, online, tag, signupdate) VALUES
					('$userid', '$uname', '$pword', '$cname', '$pname', '$realname', '$email', '$city', '$REMOTE_ADDR', '$country', '$gender', '$sysid', '$z', 10000, 10000, 5000, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 100, 0, 0, 0, 0, 0, '', '$signupdate')") or die("Could not insert to database!!");

					$request = mysql_query("SELECT * FROM uwar_users WHERE username='$uname' and password='$pword'");
					$result = mysql_fetch_array($request);
					$userid = $result["id"];
					for ($i=0;$i<=4;$i++)
					mysql_query("INSERT INTO uwar_constructions (userid,constructionid) VALUES ($userid,$i)");

					mysql_query("INSERT INTO uwar_tships (userid,fleetnum,action) VALUES ('$userid',0,'h')");
					mysql_query("INSERT INTO uwar_tships (userid,fleetnum,action) VALUES 	('$userid',1,'h')");
					mysql_query("INSERT INTO uwar_tships (userid,fleetnum,action) VALUES ('$userid',2,'h')");
					mysql_query("INSERT INTO uwar_tships (userid,fleetnum,action) VALUES ('$userid',3,'h')");

					sendMail($email, $realname, $uname, $pword, "N/A");

					if(empty($msgred))
					$msggreen = "Signup was successfully completed!";
				}
			}
		}
		else if($gal == "create")
		{
			if(empty($msgred)) //If no errors so far, continue signup
			{
				$syspass = "sys".substr(md5(time()),0,9);	//Generate unique syspassword
				$x = generateX();
				$y = generateY($x);
				$z = generateZ($x, $y);

				mysql_query("INSERT INTO uwar_sysfund (sysid,sysmercury,syscobalt,syshelium) VALUES ('$sysid',0,0,0)");

				$request = mysql_query("SELECT id FROM uwar_users ORDER BY id desc LIMIT 0,1");
				$userid_array = mysql_fetch_array($request);
				$userid = $userid_array["id"];
				$userid++;

				$signupdate = time();
				$sql = mysql_query("INSERT INTO uwar_users (id, username, password, nick, planet, name, email, city, ip, country, gender, sysid, z, mercury, cobalt, helium, score, asteroid_mercury, asteroid_cobalt, asteroid_helium, ui_roids, rank, timer, sleep, lastsleep, closed, protection, commander, vote, vacation, activity, online, tag, signupdate) VALUES
				('$userid', '$uname', '$pword', '$cname', '$pname', '$realname', '$email', '$city', '$REMOTE_ADDR', '$country', '$gender', '$sysid', '1', 10000, 10000, 5000, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 100, 0, 0, 0, 0, 0, '', '$signupdate')") or die("Could not insert to database!!");

				$request = mysql_query("SELECT * FROM uwar_users WHERE username='$uname' and password='$pword'");
				$result = mysql_fetch_array($request);
				$userid = $result["id"];
				for ($i=0;$i<=4;$i++)
				mysql_query("INSERT INTO uwar_constructions (userid,constructionid) VALUES ($userid,$i)");

				mysql_query("INSERT INTO uwar_tships (userid,fleetnum,action) VALUES ('$userid',0,'h')");
				mysql_query("INSERT INTO uwar_tships (userid,fleetnum,action) VALUES 	('$userid',1,'h')");
				mysql_query("INSERT INTO uwar_tships (userid,fleetnum,action) VALUES ('$userid',2,'h')");
				mysql_query("INSERT INTO uwar_tships (userid,fleetnum,action) VALUES ('$userid',3,'h')");

				//Logging("register",$sql);
				
				sendMail($email, $realname, $uname, $pword, $syspass);

				if(empty($msgred))
					$msggreen = "Signup was successfully completed!";
			}
		}
	}
	
/*** Draw signup interface ***/
	include_once("signupform.php");
	
?>

  