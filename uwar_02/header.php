<?
error_reporting(1);
include("logindb.php");

//LOG IN
if ($_POST["username"] !="" || $_POST["password"] !="")
{
	$_POST["username"] = strip_tags($_POST["username"]);
	$_POST["password"] = strip_tags($_POST["password"]);

    $result = mysql_query("SELECT * FROM uwar_users WHERE username='$_POST[username]' AND password='$_POST[password]' AND closed=0",$db);
    if (mysql_num_rows($result) == 1)
    {
		$UserStuff = mysql_fetch_array($result);
		$Userid = $UserStuff['id'];
		$nick = $UserStuff["nick"]." of ".$UserStuff["planet"];
		if ($UserStuff["vacation"] <= 1)
		{
			Setcookie("username", $UserStuff["username"],time()+7200);
			Setcookie("password", $UserStuff["password"],time()+7200);
			Setcookie("Userid", $Userid,time()+7200);
			Setcookie("Access","", time()+7200);
	        Logging("login", $nick, $Userid, $Userid);
			if ($UserStuff["sleep"] > 0) mysql_query("UPDATE uwar_users SET sleep=0 WHERE id='$Userid'",$db);
			if ($UserStuff["deletemode"] > 0) mysql_query("UPDATE uwar_users SET deletemode=0 WHERE id='$Userid'",$db);
			if ($UserStuff["vacation"] > 0) mysql_query("UPDATE uwar_users SET vacation=0 WHERE id='$Userid'",$db);
			$justloggedin = "true";
		}
		else
		{
			$tickrequest = mysql_query("SELECT tickertime FROM uwar_modes");
			$tickresult = mysql_fetch_array($tickrequest);
			$tickertime = $tickresult["tickertime"];
//			$hoursleft = (($UserStuff["vacation"] * $tickertime) / 3600);
			$hourself = $UserStuff["vacation"];
			Header("Location: ../index.php?p=login.php&error=3&eta=$hoursleft");
			die();
		}
	}
    else
    {
	    $result = mysql_query("SELECT * FROM uwar_users WHERE username='$_POST[username]' AND password='$_POST[password]'",$db);
		$UserStuff = mysql_fetch_array($result);
		if ($UserStuff["closed"] <> 0)
	        header("Location: ../index.php?p=login&error=2");
		else
			header("Location: ../index.php?p=login&error=1");
        die();
    }
}

if( (!isset ($_POST["username"]) || !isset ($_POST["password"])) && !isset ($_COOKIE["username"]) && !isset ($_COOKIE["username"]))
{
	header("Location: ../index.php?p=login&action=notlogged");
    die();
}

if ( (isset ($_COOKIE["username"]) && isset ($_COOKIE["password"])) || $justloggedin == "true" )
{
	if ($justloggedin != "true")
	{
		$result = mysql_query("SELECT * FROM uwar_users WHERE username='$_COOKIE[username]' AND password='$_COOKIE[password]'",$db);

		if(mysql_num_rows($result) == 0)
		{
			header("Location: ../index.php?p=login&action=notlogged");
			die();
		}
	}
	else
	{
		$result = mysql_query("SELECT * FROM uwar_users WHERE username='$UserStuff[username]' AND password='$UserStuff[password]'",$db);
		if(mysql_num_rows($result) == 0)
		{
			header("Location: ../login.php?action=notlogged");
			die();
		}
	}
	$myrow = mysql_fetch_array($result);

	$result = mysql_query("SELECT * FROM uwar_systems WHERE id='$myrow[sysid]'",$db);
	$gal = mysql_fetch_array($result);

	//select users language
/*	switch ($myrow["lang"])
	{
		case "EN" : $langfile = "languages/english.php";
		case "RO" : $langfile = "languages/romanian.php";
		case "NO" : $langfile = "languages/norwegian.php";
		case "SW" : $langfile = "languages/swedish.php";
		case "GE" : $langfile = "languages/german.php";
		case "SP" : $langfile = "languages/spanish.php";
		case "FR" : $langfile = "languages/french.php";
	}
	*/
	if ($myrow["lang"] == "EN") $langfile = "languages/english.php";
	elseif ($myrow["lang"] == "RO") $langfile = "languages/romanian.php";
	elseif ($myrow["lang"] == "NO") $langfile = "languages/norwegian.php";
	elseif ($myrow["lang"] == "SW") $langfile = "languages/swedish.php";
	elseif ($myrow["lang"] == "GE") $langfile = "languages/german.php";
	elseif ($myrow["lang"] == "SP") $langfile = "languages/spanish.php";
	elseif ($myrow["lang"] == "FR") $langfile = "languages/french.php";

//	include($langfile);
	//SET THE USER TO ONLINE
	mysql_query("UPDATE uwar_users SET online=1 WHERE id='$Userid'");

	if($myrow["design"] == 2)
	{
		Function Swap_Logo()
		{
			$logos = 5;
			$logoNumber = rand(1, $logos);
			$logopath = "images/uniwar".$logoNumber.".jpg";
			return $logopath;
		}

		$logopath = Swap_Logo();

		?>
		<HTML>
		<HEAD><TITLE>Universal War - <? print $section; ?></TITLE>
		<STYLE>
		BODY { 
			SCROLLBAR-FACE-COLOR: #333333; FONT-SIZE: 8pt; BACKGROUND: #000000; SCROLLBAR-HIGHLIGHT-COLOR: #cacaca; SCROLLBAR-SHADOW-COLOR: #000000; COLOR: #c0c0c0; SCROLLBAR-3DLIGHT-COLOR: #445555; SCROLLBAR-ARROW-COLOR: #ffffff; SCROLLBAR-TRACK-COLOR: #6f6f6f; FONT-FAMILY: verdana; SCROLLBAR-DARKSHADOW-COLOR: #aaaaaa
		}
		TD {
			FONT-SIZE: 8pt; COLOR: #FFFFFF; FONT-FAMILY: verdana
		}
		A:link {
			COLOR: #FFFFFF; TEXT-DECORATION: none
		}
		A:visited {
			COLOR: #FFFFFF; TEXT-DECORATION: none
		}
		A:active {
			COLOR: #FFFFFF; TEXT-DECORATION: none
		}
		A:hover {
			COLOR: #FFFFFF; TEXT-DECORATION: underline
		}
		INPUT {
			FONT-SIZE: 8pt; COLOR: #FFFFFF; FONT-FAMILY: verdana; BACKGROUND-COLOR: rgb(55,55,55)
		}
		TEXTAREA {
			FONT-SIZE: 8pt; COLOR: #FFFFFF; FONT-FAMILY: verdana; BACKGROUND-COLOR: rgb(44,44,44)
		}
		SELECT {
			FONT-SIZE: 8pt; COLOR: #FFFFFF; FONT-FAMILY: verdana; BACKGROUND-COLOR: rgb(44,44,44)
		}
		.content {
			border-style: solid 1px;
		}
		</STYLE>
		<?
		if($myrow["blendingoff"] == 0)
		{
			?>
			<meta http-equiv="Page-Enter" content="blendTrans Duration(1.0)">
			<?
		}
		?>
		</HEAD>

		<BODY>
		<? 
			$mercury = number_format($myrow["mercury"],0,".",".");
			$cobalt = number_format($myrow["cobalt"],0,".",".");
			$caesium = number_format($myrow["helium"],0,".",".");
			$score = number_format($myrow["score"],0,".",".");
			$rank = $myrow["rank"];
		?>

		<TABLE align="center" borderColor=#c0c0c0 height=515 cellSpacing="1" width="792" bgColor="393939" border="0">
			<TR>
				<TD align="middle" width="792" height="512">

				<TABLE style="BORDER-COLLAPSE: collapse" borderColor=#111111 cellSpacing=0 cellPadding=0 width="100%" border="0">
					<TR>
						<TD bgColor=#444444 align="center" height="" valign="top" colspan="6">
							<TABLE style="BORDER-COLLAPSE: collapse" borderColor="#FFFFFF" cellSpacing="0" cellPadding="0" width="100%" style="vertical-align: top;" border="0">
								<TR>
									<TD colspan="6" style="background-image: url(images/logo_top.gif)">&nbsp;<font size="1"><?print $myrow["nick"];?> of <?print $myrow["planet"]; ?> (<?print $gal["x"];?>:<?print $gal["y"];?>:<?print $myrow["z"];?>) - Score: <?=$score?> Rank: <?=$rank?></td>
								</TR>
								<TR>
									<TD rowspan="6" width="648" height="108" style="background-image: url(images/logo_left.gif);">&nbsp;</TD>
									<TD width="145" height="19"><img src="images/logo_mercury.gif" width="145" height="19"></TD>
								</TR>
								<TR>	
									<TD width="145" height="15" valign="top" style="background-image: url(images/logo_mercury_bg.gif)"><?=$mercury?></TD>
								</TR>
								<TR>
									<TD><img src="images/logo_cobalt.gif" width="145" height="18"></TD>
								</TR>
								<TR>
									<TD width="145" height="15" valign="top" style="background-image: url(images/logo_cobalt_bg.gif)"><?=$cobalt?></TD>
								</TR>
								<TR>
									<TD><img src="images/logo_caesium.gif" width="145" height="19"></TD>
								</TR>
								<TR>
									<TD width="145" height="22" valign="top" style="background-image: url(images/logo_caesium_bg.gif)"><?=$caesium?></TD>
								</TR>
							</table>
						</TD>
					<TR>
					<?
						$news = mysql_query("SELECT newsid FROM uwar_news WHERE seen=0 AND userid='$Userid'");
						$mail = mysql_query("SELECT mailid FROM uwar_mail WHERE seen=0 AND userid='$Userid'");
						$enemies = mysql_query("SELECT * FROM uwar_tships WHERE targetid='$Userid' and action='a'",$db);
						$allies = mysql_query("SELECT * FROM uwar_tships WHERE targetid='$Userid' and action='d'",$db);
					?>
						<td width="64" height="25"><img src="images/logo_down_left.gif" width="64" height="25"></td>
						<td width="127" height="25"><a href="news.php"><img border="0" src="images/logo_news<? if(mysql_num_rows($news) > 0) print "_on";?>.gif" width="127" height="25"></a></td>
						<td width="124" height="25"><a href="communication.php"><img border="0" src="images/logo_mail<? if(mysql_num_rows($mail) > 0) print "_on";?>.gif" width="124" height="25"></a></td>
						<td width="135" height="25"><a href="warstatus.php"><img border="0" src="images/logo_allies<? if(mysql_num_rows($allies) > 0) print "_on";?>.gif" width="135" height="25"></a></td>
						<td width="129" height="25"><a href="warstatus.php"><img border="0" src="images/logo_enemies<? if(mysql_num_rows($enemies) > 0) print "_on";?>.gif" width="129" height="25"></a></td>
						<td width="214" height="25"><img src="images/logo_down_right.gif" width="214" height="25"></td>
					</TR>
					<TR>
						<TD width="100%" colspan="6" style="background-image: url(images/logo_down.gif)" height="14" align="left">
						  <SPAN lang=en-us>&nbsp;
							<? 

							?>
							</SPAN>
						</td>
					</TR>

					<TR>
						<TD width="100%" colspan="6" background="images/all_div.gif" bgColor=#111111></TD>
					</TR>

					<TR>
						<TD width="100%" colspan="6"><SPAN lang=en-us>

					<TABLE style="BORDER-COLLAPSE: collapse" borderColor=#111111 cellSpacing=0 cellPadding=0 width=792>
						<TR>
							<TD vAlign=top width=101 bgColor=#444444 background="images/menubg.jpg" style="background-repeat: no-repeat">
								<FONT face=Verdana color=#c0c0c0 size=1>&nbsp;<CENTER><B>Main Menu</B></CENTER></FONT><BR>

								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=news>
								<A href="index.php">Base</A><BR>

<?
$request33 = mysql_query("SELECT * FROM uwar_users WHERE sysid='$myrow[sysid]'") or die(mysql_error());
while ($user = mysql_fetch_array($request33))
{
    $target_request = mysql_query("SELECT * FROM uwar_tships WHERE targetid='$user[id]' AND (action='a' OR action='d')") or die(mysql_error());
    if (mysql_num_rows($target_request) > 0)
    {
		$incomming = "a";
	}
	continue;
}

	if ($incomming == "a")
		{
			?>
			<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
			<IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=news>
			<A href="warstatus.php"><font color="#FF0000"><b>War Status</b></font></A><BR>
			<?
		}
		else
		{
			?>
			<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
			<IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=news><font color="#FF0000">
			<A href="warstatus.php">War Status</A><BR>
			<?
		}
			?>
								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=forum>
								<A href="government.php">Government</A><BR>

								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=history>
								<A href="senate.php">Senate</A><BR>

								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=history>
								<A href="finance.php">Finance</A><BR>

								<IMG src="images/all_div.gif" border=0 width="100" height="2"><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=match>
								<A href="news.php">News</A><BR>

								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=history>
								<A href="communication.php">Messages</A><BR>

								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=downloads>
								<A href="labs.php">Laboratories</A><BR>

								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=downloads>
								<A href="tech.php">Technologies</A><BR>

								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=sns>
								<A href="resources.php">Resources</A><BR>

								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=history>
								<A href="ships.php">Ships</A><BR>

								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 name=intern>
								<A href="def.php">OPS</A><BR>

								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 >
								<A href="scans.php">Intel</A><BR>

								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 >
								<A href="military.php">Military</A><BR>

								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" border=0 name="contact">
								<A href="allyindex.php">Alliance</A><BR>

								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 >
								<A href="system.php">System</A><BR>

								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 >
								<A href="universe.php">Universe</A><BR>

								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 >
								<A href="pref.php">Preferences</A><BR>

								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 >
								<A href="bugs.php">Bugs Report</A><BR>

								<? if ($myrow["access"] == 1) 
								{
								?>
								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 >
								<A href="administrator.php">Admin Panel</A><BR>
								<?
								}
								?>
								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 >
								<A href="logout.php">Log Out</A><BR>
								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR><BR>
								
								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 >
								<A href="under_construction.php" target="_blank">Portal</A><BR>
								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 >
								<A href="http://universalwar.pixelrage.ro/forum/index.php" target="_blank">Forums</A><BR>
								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 >
								<A href="http://universalwar.pixelrage.ro/game-manual/" target="_blank">Manual</A><BR>
								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 >
								<A href="techtree.php">Tech Tree</A><BR>
								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 >
								<A href="shipstats.php" target="_blank">Shipstats</A><BR>
								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR>&nbsp;
								<IMG height=7 src="images/arrow_off.gif" width=7 border=0 >
								<A href="irc.php">IRC</A><BR>
								<IMG height=2 src="images/all_div.gif" width=100 border=0><BR><BR><BR>
								</SPAN>
								<BR><BR><BR>
							</TD>

							<TD vAlign=top width=1 bgColor=#000000 rowspan="4">
								<IMG height=1 src="images/dot_clear.gif" width=1 border=0>
							</TD>

							<TD vAlign=top width=1 bgColor=#000000 rowspan="4">
								<IMG height=1 src="images/dot_clear.gif" width=1 border=0>
							</TD>


		<TD bgcolor=#000000>&nbsp;&nbsp;&nbsp;&nbsp;</TD>
		<TD bgcolor=#000000 vAlign=top width=511 rowspan="4">

			<TABLE style="BORDER-COLLAPSE: collapse" borderColor=#808080 cellSpacing=0 cellPadding=0 width=647 border=0>
				<TR>
					<TD>
						<?
						$news = mysql_query("SELECT newsid FROM uwar_news WHERE seen=0 AND userid='$Userid'");
						$mail = mysql_query("SELECT mailid FROM uwar_mail WHERE seen=0 AND userid='$Userid'");
						$enemies = mysql_query("SELECT * FROM uwar_tships WHERE targetid='$Userid' and action='a'",$db);
						$allies = mysql_query("SELECT * FROM uwar_tships WHERE targetid='$Userid' and action='d'",$db);
						?>

					<TD>
				</TR>
			</TABLE><BR><?
								$request = mysql_query("SELECT * FROM uwar_tick",$db);
								$tick = mysql_fetch_array($request);
								$ticktime = $tick["number"];
								$tickdate = $tick["date"]+$ticktime;
								print "<font size=1>&nbsp;Current tick: ".$ticktime." - ".date("l, F jS, Y - H:i:s", time()-7200);  ?> GMT<br>&nbsp; Universal Date: <?print UST($tickdate);?><br>
											<? if ($ticktime == 0) print "&nbsp;&nbsp;Ticker is not started";
			else
			{
				?>
&nbsp;
								Last tick: <?=seconds_for_tick($tickdif)?>&nbsp;&nbsp;&nbsp;  
								Next tick: <?=seconds_for_tick($tickertime - $tickdif);?><br>
								<? } ?>
		<BR>	<BR><FONT size="4"><? print $section; ?>:</FONT><BR><BR>
			<TABLE style="BORDER-COLLAPSE: collapse" borderColor=#111111 cellSpacing=0 cellPadding=0 width=444 border=0>
				<TR>
					<TD width="100%">
					<?
					function headerDsp( $title ) {
		?>
		<TABLE border="1" bordercolor="000000" style="BORDER-COLLAPSE: collapse" cellSpacing="0" cellPadding="0" width="647" >
				<TR>
					<TD width="647" background="images/bg_news.gif" bgColor="#555555">
						<SPAN lang=en-us style="LETTER-SPACING: 2px">
						<FONT face=Verdana size=1>&nbsp; <? print $title; ?></FONT></SPAN>
					</TD>
				</TR>

				<TR>
					<TD width=647 bgColor=#444444>
		<?
		}

		function footerDsp() {
		?>
					</TD>
				</TR>
			</TABLE><BR>
		<?
		}

		// template colors
		$tdbg1 = "#333333"; // <td bgcolor =""> title
		$tdbg2 = "#222222"; // <td bgcolor ="">  
	}
	elseif ($myrow["design"] == 1)
	{
		?>
		<html>
		 <head>
		 <?
			if($myrow["blendingoff"] == 0)
			{
				?><meta http-equiv="Page-Enter" content="blendTrans Duration(1.0)"><?
			}
		?>
		<TITLE>Universal War - <? print $section; ?></TITLE>
		  <style type="text/css">
		   body,td {
			font-family: arial;
			font-size: 12px;
			color: #FFFFFF;
			margin: 0px;
		   }
			a:active, a:link, a:visited {
			text-decoration: none;
			color: #FFFFFF;
		   }
		   a:hover {
			text-decoration: underline;
			color: #FFFFFF;
		   }
		   .name {
			font-family: arial;
			font-size: 15px;
			font-weight: bold;
			color: #FFFFFF;
		   }
		   .resources {
			font-family: arial;
			font-size: 11px;
			font-weight: bold;
			color: #FFFFFF;
		   }
		   .tick {
			font-family: arial;
			font-size: 12px;
			font-weight: bold;
			color: #FFFFFF;
		   }
		   .menu {
			font-family: arial;
			font-size: 11px;
			color: #FFFFFF;
		   }
		   .title {
			font-family: arial;
			color: #FFFFFF;
			font-weight: bold;
		   }
		INPUT {
			FONT-SIZE: 8pt; COLOR: #FFFFFF; FONT-FAMILY: verdana; BACKGROUND-COLOR: #00354F
		}
		TEXTAREA {
			FONT-SIZE: 8pt; COLOR: #FFFFFF; FONT-FAMILY: verdana; BACKGROUND-COLOR: #00354F
		}
		SELECT {
			FONT-SIZE: 8pt; COLOR: #FFFFFF; FONT-FAMILY: verdana; BACKGROUND-COLOR: #004D71
		}
		 </style>

		 </head>
		 <body bgcolor="#000000">
		  <table cellspacing="0" cellpadding="0" height="100%" width="100%"><tr><td>

		   <table cellspacing="0" cellpadding="0" width="100%"><tr>
			<td width="100%" background="images2/title-bg.gif"><img alt="" src="images2/title.gif"></td>
			<td><img alt="" src="images2/title-right.gif"></td>
		   </tr></table>

		   <table cellspacing="0" cellpadding="0" width="100%"><tr>
			<td width="100%" background="images2/sub-title-bg.gif"><table cellspacing="0" cellpadding="0" width="100%"><tr><td height="24" style="background-image: url(images/sub-title-left.gif); background-position: left; background-repeat: no-repeat; padding-left: 5px" class="name"><?print $myrow["nick"];?> of <?print $myrow["planet"]; ?> (<?print $gal["x"];?>:<?print $gal["y"];?>:<?print $myrow["z"];?>)</td></tr></table></td>
			<?
						$news = mysql_query("SELECT newsid FROM uwar_news WHERE seen=0 AND userid='$Userid'");
						$mail = mysql_query("SELECT mailid FROM uwar_mail WHERE seen=0 AND userid='$Userid'");
						$enemies = mysql_query("SELECT * FROM uwar_tships WHERE targetid='$Userid' and action='a'",$db);
						$allies = mysql_query("SELECT * FROM uwar_tships WHERE targetid='$Userid' and action='d'",$db);
			?>
			<td><a href="communication.php"><img alt="" src="images2/mail-<? if(mysql_num_rows($mail) > 0) print "on"; else print "off"?>.gif" border="0"></a></td>
			<td><a href="news.php"><img alt="" src="images2/news-<? if(mysql_num_rows($news) > 0) print "on"; else print "off"?>.gif" border="0"></a></td>
			<td><a href="warstatus.php"><img alt="" src="images2/enemies-<? if(mysql_num_rows($enemies) > 0) print "on"; else print "off"?>.gif" border="0"></a></td>
			<td><a href="warstatus.php"><img alt="" src="images2/allies-<? if(mysql_num_rows($allies) > 0) print "on"; else print "off"?>.gif" border="0"></a></td>
		   </tr></table>

		   <table cellspacing="0" cellpadding="0" width="100%"><tr>
			<td width="100%" bgcolor="#1C3C60"><img alt="" src="images2/resources-titles.gif"></td>
			<td><img alt="" src="images2/resources-right-planet.gif"></td>
		   </tr></table>

		   <table cellspacing="0" cellpadding="0" width="100%"><tr>
		   <? $mercury = number_format($myrow["mercury"],0,".",".");
				$cobalt = number_format($myrow["cobalt"],0,".",".");
				$helium = number_format($myrow["helium"],0,".",".");
				$score = number_format($myrow["score"],0,".",".");
				?>
			<td width="128" background="images2/mercury-bg.gif" align="center" class="resources"><?print $mercury;?></td>
			<td width="125" background="images2/cobalt-bg.gif" align="center" class="resources"><?print $cobalt;?></td>
			<td width="126" background="images2/caesium-bg.gif" align="center" class="resources"><?print $helium;?></td>
			<td width="126" background="images2/score-bg.gif" align="center" class="resources"><?print $score;?></td>
			<td width="120" background="images2/rank-bg.gif" align="center" class="resources"><?print $myrow["rank"];?></td>
			<td background="images2/resources-bg.gif" align="right"><img alt="" src="images2/planet-middle-right.gif"></td>
		   </tr></table>

		   <table cellspacing="0" cellpadding="0" width="100%"><tr>
			<td width="100%" background="images2/sub-2-bg.gif"><table cellspacing="0" cellpadding="0" width="100%"><tr><td height="18" style="background-image: url(images2/sub-2-left.gif); background-position: left; background-repeat: no-repeat; padding-left: 5px" class="resources">
			<?			$request = mysql_query("SELECT * FROM uwar_tick WHERE id='1'",$db);
						$tick = mysql_fetch_array($request);
						$ticktime = $tick["number"];
						$tickdate = $tick["date"]+$ticktime;
			?>
			<td align="left"> Universal Date: <?print UST($tickdate);?></td>
			<td align="center">
			<? if ($ticktime == 0) print "Ticker is not started";
			else
			{
				?>
			Last tick: <?=seconds_for_tick($tickdif)?>&nbsp;&nbsp;&nbsp;  
			Next tick: <?=seconds_for_tick($tickertime - $tickdif);?></td>
			<? } ?>
			<td align="right">	
			<?
print "&nbsp;Current tick: ".$ticktime." - ".date("l, F jS, Y - H:i:s", time()-7200);  ?> GMT

		</td></tr></table></td>
			<td><img alt="" src="images2/sub-2-right.gif"></td>
		   </tr></table>

		  </td></tr><tr><td height="100%" valign="top">

		   <table cellspacing="0" cellpadding="0" height="100%" width="100%"><tr><td width="118" valign="top">

			<table cellspacing="0" cellpadding="0" height="100%"><tr>
			 <td background="images2/left-bg.gif" valign="top" height="100%">
			  <table cellspacing="0" cellpadding="0" height="100%"><tr>
			   <td height="100%" width="118" style="background-image: url(images2/left.gif); background-position: top left; background-repeat: no-repeat;" valign="top" class="menu">

		<!-- MENU START -->
				<br><CENTER><B>Main Menu</B></CENTER></FONT><BR>
				<? /*
				File Status:
				! completed
				? progress
				. unstarted
				*/ ?>

				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="index.php">&nbsp;<b>Base </a></div><img alt="" src="images2/menu-hr.gif"><br />
<?
$request33 = mysql_query("SELECT * FROM uwar_users WHERE sysid='$myrow[sysid]'") or die(mysql_error());
while ($user = mysql_fetch_array($request33))
{
    $target_request = mysql_query("SELECT * FROM uwar_tships WHERE targetid='$user[id]' AND (action='a' OR action='d')") or die(mysql_error());
    if (mysql_num_rows($target_request) > 0)
    {
		$incomming = "a";
	}
	continue;
}

	if ($incomming == "a")
		{
		?>
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="warstatus.php">&nbsp;<font color="#FF0000">War Status</font></a></div><img alt="" src="images2/menu-hr.gif"><br />
		<?
		}
		else
		{
		?>		<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="warstatus.php">&nbsp;War Status</a></div><img alt="" src="images2/menu-hr.gif"><br />
		<?
		}
		?>
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="government.php">&nbsp;Government</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="senate.php">&nbsp;Senate</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="finance.php">&nbsp;Finance</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="news.php">&nbsp;News</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="communication.php">&nbsp;Messages</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px">&nbsp;</div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="labs.php">&nbsp;Laboratories</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="tech.php">&nbsp;Technologies</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="resources.php">&nbsp;Resources</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px">&nbsp;</div><img alt="" src="images2/menu-hr.gif"><br/>
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="ships.php">&nbsp;Ships</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="def.php">&nbsp;OPS</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="scans.php">&nbsp;Intel Center</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="military.php">&nbsp;Military</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px">&nbsp;</div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="allyindex.php">&nbsp;Alliance</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="system.php">&nbsp;System</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="universe.php">&nbsp;Universe</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px">&nbsp;</div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="pref.php">&nbsp;Preferences</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="bugs.php">&nbsp;Bugs Report</a></div><img alt="" src="images2/menu-hr.gif"><br/>
				<? if ($myrow["access"] >= 1)
				{?>
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="administrator.php">&nbsp;Admin Panel</a></div><img alt="" src="images2/menu-hr.gif"><br />
				<?
				}
				?>
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="logout.php">&nbsp;Log Out</a></div><br>
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="under_construction.php" target="_blank">&nbsp;Portal</a></div><img alt="" src="images2/menu-hr.gif"><br/>
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="http://universalwar.pixelrage.ro/forum/index.php" target="_blank">&nbsp;Forums</a></div><img alt="" src="images2/menu-hr.gif"><br/>
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="http://universalwar.pixelrage.ro/game-manual/" target="_blank">&nbsp;Manual</a></div><img alt="" src="images2/menu-hr.gif"><br/>
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="techtree.php" >&nbsp;Tech Tree</a></div><img alt="" src="images2/menu-hr.gif"><br/>
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="shipstats.php" target="_blank">&nbsp;Shipstats</a></div><img alt="" src="images2/menu-hr.gif"><br/>
				<div style="padding-left: 10px"><img alt="" src="images2/menu-arrow.gif"> <a href="irc.php">&nbsp;IRC</a></div><img alt="" src="images2/menu-hr.gif"><br/>

		<!-- MENU END -->

			   </td>
			  </tr></table>
			 </td>
			</tr></table>

		   </td><td valign="top">
		<?
		function headerDsp( $title ) {
		?>
		<TABLE border="0" bordercolor="808080" style="BORDER-COLLAPSE: collapse" cellSpacing="0" cellPadding="0" width="647" >
				<TR>
					<TD width="647" background="images2/bg_news.gif" bgColor="#224165"">
						<SPAN lang=en-us style="LETTER-SPACING: 2px">
						<FONT face=Verdana size=1>&nbsp; <? print $title; ?></FONT></SPAN>
					</TD>
				</TR>

				<TR>
					<TD width=647 bgColor=#162F4C>
		<?
		}

		function footerDsp() {
		?>
					</TD>
				</TR>
			</TABLE><BR>
		<?
		}

		// template colord
		$tdbg1 = "#224165"; // <td bgcolor =""> title
		$tdbg2 = "#0E1F31"; // <td bgcolor ="">  
		?>

			<table cellspacing="0" cellpadding="0" width="100%"><tr>
			 <td background="images2/content-top-bg.gif" width="100%"><img alt="" src="images2/content-top-left.gif"></td>
			 <td valign="top"><img alt="" src="images2/content-top-right.gif"></td>
			</tr></table>

			<table cellspacing="0" cellpadding="0" height="100%" width="100%"><tr>
			 <td width="100%" background="images2/main-bg.gif"><table cellspacing="0" cellpadding="0" height="100%" width="100%"><tr><td width="100%" style="background-image: url(images/main-top-left.gif); background-position: top left; background-repeat: no-repeat" valign="top">


		<BR><FONT size="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? print $section; ?>:</FONT><BR><BR>
			   <table cellspacing="1" cellpadding="3" width="97%" align="center">
				<td><td>
		<?

	}

	elseif ($myrow["design"] == 0)
	{
		?>
		<html>
		 <head>
		 <?
			if($myrow["blendingoff"] == 0)
			{
				?><meta http-equiv="Page-Enter" content="blendTrans Duration(1.0)"><?
			}
		?>
		<title>Universal War - <? print $section; ?></title>
		  <style type="text/css">
			HTML, Body { 
			font-family: Verdana, Arial, helvetica, sans-serif;
			font-size: 11px;
			text-align: center;
			color: #FFFFFF;
			background-image: url('images3/starsbg.gif');
			background-attachment: fixed;
			background-color: #000000;
			}
			
			td {
			font-size: 11px;
			}
			H4 {
			font-weight: bold;
			font-size: 17px;
			}

			.site {
			border: 0px solid #FFFFFF;
			padding: 0px;
			text-align: center;
			width: 796px;

			}

			.banner {
			height: 164px;
			background-color: #111111;
			}

			.banner2 {
			padding: 0px;
			width: 100%;
			height: 164px;
			}

			.menu {
			width: 99px;
			background-color: #262626;
			vertical-align: top;
			padding-top: 50px;
			padding-bottom: 50px;
			text-align: left;
			}

			.content {
			color: #FFFFFF;
			width: 685px;
			background-color: #1A1A1A;
			vertical-align: top;
			padding-top: 15px;
			padding-bottom: 15px;
			font-size: 10px;
			text-align: left;
			}


			.content_table {
			color: #FFFFFF;
			vertical-align: top;
			padding: 0px;
			padding-left: 22px;
			font-size: 10px;
			text-align: left;
			}

			.bottom {
			width: 796px;
			height: 25px;
			}

			.bar {
			width: 4px;
			background-image: url("images3/bar.jpg");
			}

			.menu_table {
			border: 0px;
			width: 100%;
			}

			.menu_top {
			height: 16px;
			background-image: url("images3/menu_top.gif");
			}

			.menu_content {
			background-image: url('images3/menu_bgr.jpg');
			padding-top: 10px;
			padding-bottom: 10px;
			padding-left: 8px;
			font-size: 10px;
			color: #c0c0c0;
			}

			.menu_bottom {
			height: 20px;
			background-image: url("images3/menu_bottom.gif");
			}

			.menu_items {

			border: 0px;
			width: 100%;
			}

			.arrow {
			padding-left: 55px;
			padding-right: 15px;
			}

			.menu_item {
			font-size: 10px;
			color: #c0c0c0;
			}

			.playerinfo {
			background-image: url('images3/banner_04.jpg');
			vertical-align: top;
			padding-left: 5px;
			padding-top: 11px;
			}

			.playerinfo2 {
			font-size: 9px;
			color: #FFFFFF;
			height: 100px;
			width: 155px;
			text-align: left;
			}

			.playerscore {
			background-image: url("images3/banner_01.jpg");
			width: 600px;
			height: 17px;
			color: #FFFFFF;
			font-size: 10px;
			text-align: left;
			padding-left: 30px;
			}

			.box {
			width: 646px;
			padding: 0px;
			border: 0px solid #FFFFFF;
			}

			.box_header {
			background-image: url("images3/box_01.gif");
			height: 14px;
			font-size: 9px;
			padding-left: 28px;
			}

			.box_border {
			background-image: url("images3/box_02.gif");
			width: 1px;
			}

			.box_content {
			background-color: #444444;
			width: 100%;
			padding-top: 10px;
			padding-bottom: 10px;
			padding-left: 40px;
			padding-right: 40px;
			text-align: center;
			}

			.box_footer {
			background-image: url("images3/box_05.gif");
			height: 10px;
			}

			.box_text {
			padding: 0px;
			text-align: left;
			font-size: 11px;
			}

			input {
				font-size: 8pt; color: #FFFFFF; font-family: Verdana, arial, helvetica, sans-serif; background-color: rgb(55,55,55)
			}
			textarea {
				font-size: 8pt; color: #FFFFFF; font-family: Verdana, arial, helvetica, sans-serif; background-color: rgb(55,55,55)
			}
			select {
				font-size: 8pt; color: #FFFFFF; font-family: Verdana, arial, helvetica, sans-serif; background-color: rgb(55,55,55)
			}
			
			A:link {
			color: #FFFFFF; text-decoration: none;
			}
			A:visited {
			color: #FFFFFF; text-decoration: none;
			}
			A:active {
			color: #FFFFFF; text-decoration: none;
			}
			A:hover {
			color: #FFFFFF; text-decoration: underline;
			}

			</style>

			</head>
			<body bgcolor="#000000">

			<? 
			$mercury = number_format($myrow["mercury"],0,".",".");
			$cobalt = number_format($myrow["cobalt"],0,".",".");
			$caesium = number_format($myrow["helium"],0,".",".");
			$score = number_format($myrow["score"],0,".",".");
			$rank = $myrow["rank"];
			?>

			<table width="796" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td class="banner" colspan="5">
					
			<table class="banner2" cellspacing="0" cellpadding="0">
							<tr>
								<td colspan="6" class="playerscore">
								<div style="width:100%; filter: Glow(color=#000000, strength=1);">
								<b><?=$myrow[""]?><?=$myrow["nick"];?> of <?=$myrow["planet"]; ?> (<?=$gal["x"];?>:<?=$gal["y"];?>:<?=$myrow["z"];?>) - Score: <?=$score?> Rank: <?=$rank?></b>
								</div>
								</td>
								<td><img src="images3/banner_02.jpg" alt="" /></td>
							</tr>
							<tr>
								<td colspan="6"><img src="images3/banner_03.jpg" alt="" /></td>
								<td class="playerinfo">
								
									<table class="playerinfo2" align="center" border="0">
										<tr>
											<td style="font-size: 10px;">
											Mercury <br />
											<?=$mercury?>
											<br /><br />
											Cobalt <br />
											<?=$cobalt?>
											<br /><br />
											Caesium	<br />
											<?=$caesium?>
											</td>
										</tr>
									</table>
								
								</td>
							</tr>
							<tr>
								<td><img src="images3/banner_05.jpg" alt="" /></td>
								

								<?		
								$news = mysql_query("SELECT newsid FROM uwar_news WHERE seen=0 AND userid='$Userid'");
								$mail = mysql_query("SELECT mailid FROM uwar_mail WHERE seen=0 AND userid='$Userid'");
								$enemies = mysql_query("SELECT * FROM uwar_tships WHERE targetid='$Userid' and action='a'",$db);
								$allies = mysql_query("SELECT * FROM uwar_tships WHERE targetid='$Userid' and action='d'",$db);
								?>
								
								
								<td><a href="news.php"><img src="images3/banner_06<?if(mysql_num_rows($news) > 0) print "-on";?>.jpg" alt="" border="0" /></a></td>
								<td><a href="communication.php"><img src="images3/banner_07<?if(mysql_num_rows($mail) > 0) print "-on";?>.jpg" alt="" border="0" /></a></td>
								<td><a href="warstatus.php"><img src="images3/banner_08<?if(mysql_num_rows($enemies) > 0) print "-on";?>.jpg" alt="" border="0" /></a></td>
								<td><a href="warstatus.php"><img src="images3/banner_09<?if(mysql_num_rows($allies) > 0) print "-on";?>.jpg" alt="" border="0" /></a></td>
								<td><img src="images3/banner_10.jpg" alt="" /></td>
								<td><img src="images3/banner_11.jpg" alt="" /></td>
							</tr>
						</table>

					</td>
				</tr>
				<tr style="height: 300px">
					<td class="bar"></td>
					<td class="menu">
					
						<table cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td><img src="images3/menu_top.jpg" alt="" /></td>
							</tr>
							<tr>
								<td class="menu_content">
								
									<img src="images3/arrow.gif" alt="" /> <a href="index.php">Base</a><br />
									<?
									$request33 = mysql_query("SELECT * FROM uwar_users WHERE sysid='$myrow[sysid]'") or die(mysql_error());
									while ($user = mysql_fetch_array($request33))
									{
										$target_request = mysql_query("SELECT * FROM uwar_tships WHERE targetid='$user[id]' AND (action='a' OR action='d')") or die(mysql_error());
										if (mysql_num_rows($target_request) > 0)
										{
											$incomming = "a";
										}
										continue;
									}

										if ($incomming == "a")
											{
												?>
												<img src="images3/arrow.gif" alt="" /> <a href="warstatus.php"><font color="#FF0000">War Status</font></a><br />
												<?
											}
											else
											{
												?>
												<img src="images3/arrow.gif" alt="" /> <a href="warstatus.php">War Status</a><br />
												<?
											}
									?>
									<img src="images3/arrow.gif" alt="" /> <a href="government.php">Government</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="senate.php">Senate</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="finance.php">Finance</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="news.php">News</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="communication.php">Messages</a><br />
									<br />
									<img src="images3/arrow.gif" alt="" /> <a href="labs.php">Laboratories</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="tech.php">Technologies</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="resources.php">Resources</a><br />
									<br />
									<img src="images3/arrow.gif" alt="" /> <a href="ships.php">Ships</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="def.php">OPS</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="scans.php">Intel</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="military.php">Military</a><br />
									<br />
									<img src="images3/arrow.gif" alt="" /> <a href="allyindex.php">Alliance</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="system.php">System</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="universe.php">Universe</a><br />
									<br />
									<img src="images3/arrow.gif" alt="" /> <a href="pref.php">Preferences</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="bugs.php">Bugs Report</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="logout.php">Log Out</a><br />
									<br />
									<? if ($myrow["access"] == 1) { ?>
									<img src="images3/arrow.gif" alt="" /> <a href="administrator.php">Admin Panel</a><br />
									<? } ?>
									<img src="images3/arrow.gif" alt="" /> <a href="#">Portal</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="http://universalwar.pixelrage.ro/forum/index.php" target="_blank">Forums</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="http://universalwar.pixelrage.ro/game-manual/" target="_blank">Manual</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="techtree.php">Tech Tree</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="shipstats.php" target="_blank">Shipstats</a><br />
									<img src="images3/arrow.gif" alt="" /> <a href="irc.php">IRC</a>
								
								</td>
							</tr>
							<tr>
								<td><img src="images3/menu_bottom.jpg" alt="" /></td>
							</tr>
						</table>
					
					</td>
					<td class="bar"></td>
					<td class="content">
					

						<table class="content_table" align="left" cellspacing="0" cellpadding="0">
							<tr>
								<td>
								
								<?
								$request = mysql_query("SELECT * FROM uwar_tick",$db);
								$tick = mysql_fetch_array($request);
								$ticktime = $tick["number"];
								$tickdate = $tick["date"]+$ticktime;
								print "<font size=1>&nbsp;Current tick: ".$ticktime." - ".date("l, F jS, Y - H:i:s", time()-7200);  ?> GMT<br>&nbsp; Universal Date: <?print UST($tickdate);?><br />
											<? if ($ticktime == 0) print "&nbsp;&nbsp;Ticker is not started";
								else
								{
									?>
								&nbsp;
								Last tick: <?=seconds_for_tick($tickdif)?>&nbsp;&nbsp;&nbsp;  
								Next tick: <?=seconds_for_tick($tickertime - $tickdif);?><br />
								<? } ?>
									
									<h4><?=$section; ?></h4>

									<?
									function headerDsp( $title ) {
									?>
									<table border="0" cellspacing="0" cellpadding="0" width="647" >
											<tr style="height: 14px">
												<td width="647" class="box_header" colspan="3">
													<div style="width:100%; filter: Glow(color=#000000, strength=1);">
													<b>
													<font face="Verdana" style="color: #FFFFFF" size="1">&nbsp; <? print $title; ?>
													</b>
													</div>
												</td>
											</tr>

											<tr>
												<td class="box_border"></td>
												<td width="647" bgColor="#444444">
									<?
									}							
									function footerDsp() {
									?>
												</td>
												<td class="box_border"></td>
											</tr>
											<tr>
												<td colspan="3" class="box_footer">
											</tr>
										</table><br />
									<?
									}

									// template colors
									$tdbg1 = "#333333"; // <td bgcolor =""> title
									$tdbg2 = "#222222"; // <td bgcolor ="">  
									?>
						
									<!--
									<table class="box" cellspacing="0"> 
										<tr>
											<td class="box_header" colspan="3">
											<div style="width:100%; filter: Glow(color=#000000, strength=1);">
											<b>Planet information</b>
											</div>
											</td>
										</tr>
										<tr>
											<td class="box_border"></td>
											<td class="box_content">
											
											<table class="box_text" cellspacing="0" align="center">
												<tr>
													<td>
														Commander: Ancalagon<br />
														Planet: Eagleshieldsbay<br />
														Coordinates: 1:1:2<br />
														Rank: 0<br />
														You are in the Universal Lords Protection for 72 days.
													</td>
												</tr>
											</table>
											
											</td>
											<td class="box_border"></td>
										</tr>
										<tr>
											<td class="box_footer" colspan="3"></td>
										</tr>
									</table>
									-->
								<!--footer
								</td>
							</tr>
						</table>
					
					</td>
					<td class="bar"></td>
				</tr>
				<tr>
					<td class="bottom" colspan="5"><img src="images3/bottom.jpg" alt="" /></td>
				</tr>
			</table>

			</body>
			</html>
			-->


		<?

	}
}
else 
{
	header("Location: ../login.php?action=notlogged");
    die();
}
?>
