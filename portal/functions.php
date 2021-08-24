<?php

function getIP() 
{
	$ip = 0;
	if (getenv("HTTP_CLIENT_IP")) {
		$ip = getenv("HTTP_CLIENT_IP");
	} else if (getenv("HTTP_X_FORWARDED_FOR")) {
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	} else if (getenv("REMOTE_ADDR")) {
		$ip = getenv("REMOTE_ADDR");
	}
	else { 
		$ip = "UNKNOWN";
	}
	return $ip;
}

function fatalError($error)
{
	global $_CFG;
	$prefix = "<font color=\"#FF0000\">";
	$suffix = "</font><br />";
	$msg = "Please control your settings in the configuration file.";
	echo "<html><head><title>Universal War Portal</title>";
	echo "<link href=\"".$_CFG["paths"]["styles"]."error.css\" rel=\"stylesheet\" type=\"text/css\"></link>";
	echo "</head><body>";
	echo "$prefix $error $suffix $msg";
	echo "</body></html>";
	die();
}

function drawContents($page)
{
	global $_CFG;
	if (!file_exists($_CFG["paths"]["pages"]."header.html.php")) {
		$errormsg = "Unable to include page <b>".$_CFG["paths"]["pages"]."header.html.php</b>!";
		fatalError($errormsg);		
	}
	if (!file_exists($_CFG["paths"]["pages"].$page.".html.php")) {
		$errormsg = "Unable to include page <b>".$_CFG["paths"]["pages"].$page.".html.php</b>!";
		fatalError($errormsg);		
	}
	if (!file_exists($_CFG["paths"]["pages"]."footer.html.php")) {
		$errormsg = "Unable to include page <b>".$_CFG["paths"]["pages"]."footer.html.php</b>!";
		fatalError($errormsg);		
	}

	include_once($_CFG["paths"]["pages"]."header.html.php");
	include_once($_CFG["paths"]["pages"].$page.".html.php");
	include_once($_CFG["paths"]["pages"]."footer.html.php");
}

function subMenu($section)
{
	switch($section) {
		case 0:
		return TRUE;
		break;
		
		default: return FALSE;
		break;
	}
	return FALSE;
}

function drawHeader($section)
{
	global $_CFG;
	?>
	<table border="0" cellspacing="0" cellpadding="0" style="width: 100%">
		<tr>
			<td width="19" height="17"><img src="<?=$_CFG["paths"]["img"]?>box_topleft.gif" width="19" height="17" alt="" /></td>
			<td width="30%" height="17" style="background-image: url(<?=$_CFG["paths"]["img"]?>box_topdisplay.gif); background-repeat: repeat-x;">
				<font class="display">&nbsp;&nbsp;<?=$section?></font>
			</td>
			<td width="10" height="17"><img src="<?=$_CFG["paths"]["img"]?>box_topdisplayright.gif" width="10" height="17" alt="" /></td>
			<td width="100%" height="17" style="background-image: url(<?=$_CFG["paths"]["img"]?>box_topright.gif); background-repeat: repeat-x;">
				<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
			</td>
		</tr>
		<tr>
			<td colspan="4" style="background-image: url(<?=$_CFG["paths"]["img"]?>box_bg.gif);">
				<br />
	<?
}

function drawFooter()
{
	global $_CFG;
	?>			<br />
			</td>
		</tr>
		<tr>
			<td height="13" colspan="4" style="background-image: url(<?=$_CFG["paths"]["img"]?>box_bottom.gif); background-repeat: repeat-x;">
				<img src="<?=$_CFG["paths"]["img"]?>pix.gif" width="1" height="1" alt="" />
			</td>
		</tr>
	</table>	
	<?
}
?>