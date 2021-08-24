<?php

if ($_SESSION["logged"] != "true") {
	header("Location: ".$PHP_SELF."?page=login");
}

include_once($_CFG["paths"]["pages"]."navigation.html.php");
?>
								<td width="85%" style="vertical-align: top;">
									Welcome back, <b><?=$_SESSION["name"]?></b>!<br />
									Sessions are now set and you are logged in to the Universal War Project Manager. Due to security risks, please remember to <a href="<?=$PHP_SELF?>?page=logout"><u>logout</u></a> when you are have finished using the system. Also remember to continue to keep your login details absolutly safe.
								</td>
							</tr>