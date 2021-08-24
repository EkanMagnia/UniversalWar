<?php

function sendMail($to, $name, $username, $password, $syspassword)
{
	/* subject */
	$subject = "Welcome to Universal War!";
	$url = "http://dragons.pixelrage.ro/universalwar/";
	/* message */
	$message = '
				<html>
				<head>
					<title>Universal War: Genesis</title>
					
					<meta name=resource-type content=text/html; charset=iso-8859-1>
					<meta name=content-type content=text/html; charset=iso-8859-1>
				
					<link rel=stylesheet type=text/css href=$url/email.css>
				</head>
				
				
				<body bgcolor=#000000>
				
				<BR>
				<TABLE align=center bgcolor=White width=100% height=100% cellpadding=0 cellspacing=0 border=0 style=border: 2px solid #757575>
				<TR>
					<TD valign=top align=left>
						<TABLE cellpadding=0 cellspacing=0 width=100% border=0>
						<TR>
							<TD background=$url/img/maillogo_bg.gif><IMG src=$url/img/maillogo.gif border=0 width=379 height=108></TD>
						</TR>
						</TABLE>
					<BR>
						<TABLE cellpadding=10 cellspacing=0 width=100% border=0>
				    	<TR> 
				   		  <TD>
					       	<TABLE width=100% border=0 cellpadding=0 cellspacing=0>             
					        <TR> 
					          	<TD valign=bottom width=9><IMG src=$url/img/mail_dot.gif border=0 vspace=0></TD>
					            <TD background=$url/img/mail_line.gif class=title style=font-size: 12px; font-weight: bold; color: #000000>&nbsp; Signup was successful - Welcome to the Universal War</TD>
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
								<p>To login and start playing Universal War, go to the <a href=$url/login.php>login page</a>. </p>
								<TABLE width=100% border=0 cellpadding=0 cellspacing=0>             
				              	<TR> 
				                	<TD valign=bottom width=9><IMG src=$url/img/mail_dot.gif border=0 vspace=0></TD>
				                	<TD background=$url/img/mail_line.gif class=title style=font-size: 12px; font-weight: bold; color: #000000>&nbsp; Beginner\'s guide</TD>
				              	</TR>
				            	</TABLE>
								<p>Players new to Universal War need to learn and understand how to play the game. Therefor the Universal War team has developed a guide for new players called <strong>Beginner\'s Guide</strong>. In the guide the reader
								  will learn the basics of the game and how to play it. We strongly recommend new users to read this guide, to be prepared for the game.<br/>
								  To read <strong>Beginner\'s Guide</strong>, <a href=http://universalwar.pixelrage.ro/game-manual/index.php?section=Begineer%20Guide&id=6>click here</a>.
								  
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

	/* HTML mail*/
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	
	/* FROM Header */
	$headers .= "From: Universal War <signup@universalwar.net>\r\n";
	
	/* and now mail it */
	mail($to, $subject, $message, $headers);
}


?>