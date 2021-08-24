<?PHP

function validateSignupInput() {

	global $msgred;
	
	if (!isset($_POST["email"]) || !strlen($_POST["email"]) > 5 || !eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+([a-z]{2,3}$)", $_POST["email"])) { 
		$msgred[] = "Email invalid!"; 
	}
	if ($_POST["uname"] == "") { 
		$msgred[] = "Username not specified!"; 
	}
	if ($_POST["cname"] == "") { 
		$msgred[] = "Commander name not specified!"; 
	}
	if ( strlen($_POST["cname"]) > 25 ) {
		$msgred[] = "Commander name too long, max 25 chars!"; 
	}
	if ($_POST["pname"] == "") { 
		$msgred[] = "Planet name not specified!"; 
	}
	if ( strlen($_POST["pname"]) > 25 ) {
		$msgred[] = "Planet name too long, max 25 chars!"; 
	}				
    if ($_POST["gal"] == "") { 
    	$msgred[] = "System option not specified!"; 
    }
    if ($_POST["gal"] == "private" && $_POST["galpass"] == "") { 
    	$msgred[] = "System password not specified!"; 
    }
    
    if ( empty($msgred) ) {
    	return TRUE;	
    } else {
    	return FALSE;	
    }
}

function checkSignupInput() {
	
	global $msgred;

	$resultUname = mysql_query("SELECT * FROM uwar_users WHERE username='".$_POST["uname"]."'");
	$resultCname = mysql_query("SELECT * FROM uwar_users WHERE nick='".$_POST["cname"]."'");
	$resultPname = mysql_query("SELECT * FROM uwar_users WHERE planet='".$_POST["pname"]."'");
	$resultEmail = mysql_query("SELECT * FROM uwar_users WHERE email='".$_POST["email"]."'");
	if (mysql_num_rows($resultUname) > 1 ) { 
			$msgred[] = "Username is already taken!"; 
	}	
	if (mysql_num_rows($resultCname)) { 
		$msgred[] = "Commander name is already taken!"; 
	}	
	if (mysql_num_rows($resultPname)) { 
		$msgred[] = "Planet name is already taken!"; 
	}	
	if (mysql_num_rows($resultEmail)) {
		$msgred[] = "E-mail address is already registered!";
	}
    
	if ( empty($msgred) ) {
    	return TRUE;	
    } else {
    	return FALSE;	
    }	
	
}

function stripSignupInput () {

	$_POST["realname"] = strip_tags($_POST["realname"]);
	$_POST["email"] = strip_tags($_POST["email"]);
	$_POST["uname"] =  strip_tags($_POST["uname"]);
	$_POST["pword"] = strip_tags($_POST["pword"]);
	$_POST["cname"] = strip_tags($_POST["cname"]);
	$_POST["pname"] = strip_tags($_POST["pname"]);
	$_POST["galpass"] = strip_tags($_POST["galpass"]);
}

function generateX() {

	global $cn;
	
	/*** Grab highest value of X ***/
	$resultMaxSector = mysql_query("SELECT MAX(x) FROM uwar_systems", $cn);
	$maxSector = mysql_fetch_array($resultMaxSector);
	$x = $maxSector[0];
	
	if ($x < 1 ) {
		$x = 1;	
		$hasFreeSpot = TRUE;
	} else {
		
		/*** Controls if the sector has an empty spot ***/
		$hasFreeSpot = FALSE;
		$resultSystems = mysql_query("SELECT id FROM uwar_systems WHERE x='$x'", $cn);
		
		if ($_POST["gal"] == "random" ) {
			
			while( $system = mysql_fetch_array($resultSystems) ) {
				
				if ($system["systype"] != 2) {
					
					$resultPlayersInSystem = mysql_query("SELECT id FROM uwar_users WHERE sysid=".$system["id"]." ");
					if ( mysql_num_rows($resultPlayersInSystem) < 10 ) {
						$hasFreeSpot = TRUE;
						break;	
					}
				}
			}
		
		} elseif ($_POST["gal"] == "create" ) {
	
			if ( mysql_num_rows($resultSystems) < 5 ) {
				$hasFreeSpot = TRUE;	
			}
	
		}
	}
	
	
	/*** Return X if sector has an empty spot, otherwise return a new sector ***/
	if ($hasFreeSpot) {
		return $x;			
	} else {
		return ++$x;
	}

}

function generateY($x) {

	global $cn, $syspass;
	
	/*** Define Y array ***/
	$possibleY = array();
	
	/*** Pick out all y-alternatives in the chosen sector ***/
	$resultSystems = mysql_query("SELECT * FROM uwar_systems WHERE x='$x'", $cn);
			
	for($i = 1; $systems = mysql_fetch_array($resultSystems); $i++ ) {
		
		if ($_POST["gal"] == "create" ) {
		
			$resultSystemExists = mysql_query("SELECT id FROM uwar_systems WHERE x='$x' AND y='$i'");
			if ( mysql_num_rows($resultSystemExists) == 0 ) {
				$possibleY[] = $systems["y"];	
			}
			
		} else {
			
			if ($systems["systype"] == 1) {
			
				$resultPlayersInSystem = mysql_query("SELECT id FROM uwar_users WHERE sysid=".$systems["id"]." ", $cn);
				if ( mysql_num_rows($resultPlayersInSystem) < 10 ) {
					$possibleY[] = $systems["y"];
				}
			}
		}			
	}
	
	$emptySystems = 5 - mysql_num_rows($resultSystems);
	for ($j = 0; $j < $emptySystems; $j++) {
		for ($k = 1; $k <= 5; $k++) {
			if( !in_array($k, $possibleY) ) {
				$possibleY[] = $k;
				$k = 6;
			}
		}
	}
	
	$y = $possibleY[array_rand($possibleY)];
		
	/*** Controls wheter the system needs to be created or not ***/
	$resultSystem = mysql_query("SELECT id FROM uwar_systems WHERE x='$x' AND y='$y'", $cn);
	if ( mysql_num_rows($resultSystem) == 0 )
	{
		if($gal == "create") {
			
			mysql_query("INSERT INTO uwar_systems (x,y, systype, syspword, sysname, sysmotd, syspic, syssize, sysscore, sysrank) VALUES ('$x', '$y', 2, '$syspass', 'A new sunshine', 'Welcome to ".$x.":".$y."', 'images/system.jpg', '0', '0', '0')", $cn);
			$resultSysid = mysql_query("SELECT id FROM uwar_systems WHERE x='".$x."' AND y='".$y."'", $cn);
			$sysid = mysql_fetch_array($resultSysid);
			mysql_query("INSERT INTO uwar_sysfund (sysid, sysmercury, syscobalt, syshelium) VALUES ('".$sysid["id"]."', 0, 0, 0)", $cn);			
		} else {
			mysql_query("INSERT INTO uwar_systems (x,y, systype, sysname, sysmotd, syspic, syssize, sysscore, sysrank) VALUES ('$x', '$y', 1, 'The Dark System', 'Welcome to ".$x.":".$y."', 'images/system.jpg', '0', '0', '0')", $cn) or die("ERROR!");;
			$resultSysid = mysql_query("SELECT id FROM uwar_systems WHERE x='".$x."' AND y='".$y."'", $cn);
			$sysid = mysql_fetch_array($resultSysid);
			mysql_query("INSERT INTO uwar_sysfund (sysid, sysmercury, syscobalt, syshelium) VALUES ('".$sysid["id"]."', 0, 0, 0)", $cn);
		}
	}		
	
	return $y;
}

function generateZ($x, $y) {
	
	global $cn;

	$resultSystem = mysql_query("SELECT id FROM uwar_systems WHERE x='".$x."' AND y='".$y."'", $cn);
	$system = mysql_fetch_array($resultSystem);
	
	for( $i = 1; $i <= 10; $i++ ) {
		
		$resultSystemMember = mysql_query("SELECT id FROM uwar_users WHERE sysid='".$system["id"]."' AND z='$i'", $cn);
		if ( mysql_num_rows($resultSystemMember) < 1) {
			$z = $i;
			break;
		}	
	}
	
	return $z;
}

function sendMail($to, $name, $username, $password, $syspassword)
{
	/*** subject ***/
	$subject = "Welcome to Universal War!";
	$url = "http://universalwar.pixelrage.ro/uwar/";
	/*** message ***/
	$message = '
				<html>
				<head>
					<title>Universal War: Genesis</title>
					
					<meta name=resource-type content=text/html; charset=iso-8859-1>
					<meta name=content-type content=text/html; charset=iso-8859-1>
				
					<link rel=stylesheet type=text/css href='.$url.'/email.css>
				</head>
				
				
				<body bgcolor=#000000>
				
				<BR>
				<TABLE align=center bgcolor=White width=100% height=100% cellpadding=0 cellspacing=0 border=0 style=border: 2px solid #757575>
				<TR>
					<TD valign=top align=left>
						<TABLE cellpadding=0 cellspacing=0 width=100% border=0>
						<TR>
							<TD background='.$url.'/img/maillogo_bg.gif><IMG src='.$url.'/img/maillogo.gif border=0 width=379 height=108></TD>
						</TR>
						</TABLE>
					<BR>
						<TABLE cellpadding=10 cellspacing=0 width=100% border=0>
				    	<TR> 
				   		  <TD>
					       	<TABLE width=100% border=0 cellpadding=0 cellspacing=0>             
					        <TR> 
					          	<TD valign=bottom width=9><IMG src='.$url.'/img/mail_dot.gif border=0 vspace=0></TD>
					            <TD background='.$url.'/img/mail_line.gif class=title style=font-size: 12px; font-weight: bold; color: #000000>&nbsp; Signup was successful - Welcome to the Universal War</TD>
					        </TR>
					        </TABLE>
				       		  <p>Dear '.$name.',
							  <p>Thank you for signing up for an Universal War game account. You are now in control of an own planet.
				       		  <p> Account / Login details:
				       		<UL type=square>
									<LI>Username: '.$username.'
						      		<LI>Password: '.$password.'
									<LI>System password: '.$syspassword.'
				       		</UL> 
								<p>To login and start playing Universal War, go to the <a href='.$url.'/login.php>login page</a>. </p>
								<TABLE width=100% border=0 cellpadding=0 cellspacing=0>             
				              	<TR> 
				                	<TD valign=bottom width=9><IMG src='.$url.'/img/mail_dot.gif border=0 vspace=0></TD>
				                	<TD background='.$url.'/img/mail_line.gif class=title style=font-size: 12px; font-weight: bold; color: #000000>&nbsp; Beginner\'s guide</TD>
				              	</TR>
				            	</TABLE>
								<p>Players new to Universal War need to learn and understand how to play the game. Therefor the Universal War team has developed a guide for new players called <strong>Beginner\'s Guide</strong>. In the guide the reader
								  will learn the basics of the game and how to play it. We strongly recommend new users to read this guide, to be prepared for the game.<br/>
								  To read <strong>Beginner\'s Guide</strong>, <a href=#>click here</a>.
								  
								<p>Best regards, <br>
						 		The Universal Lords </p></TD>
				    	</TR>
				      	</TABLE>
					</TD>
					</TR>
				</TABLE>
				<center><font size=1 face=arial color=#666666>Design originally created as a phpBB forum skin developed by <a href=www.volize.com>Volize</a>.</font></center>
				</body>
				</html>
				';

	/*** HTML-mail ***/
	$headers  = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\n";
	
	/*** FROM Header ***/
	$headers .= "From: Universal War <signup@universalwar.net>\n";
	
	/*** Mail ***/
	mail($to, $subject, $message, $headers);
}
?>