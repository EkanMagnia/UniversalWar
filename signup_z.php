<?
//Z coord
$system_request = mysql_query("SELECT id FROM uwar_systems WHERE x='$x' AND y='$y'",$cn);
$system = mysql_fetch_array($system_request);
$sysid = $system["id"];
$system_members_query = mysql_query("SELECT z FROM uwar_users WHERE sysid='$sysid' ORDER by z",$cn);

$alreadyused = array();

while($system_members = mysql_fetch_array($system_members_query))
	$alreadyused[] = $system_members["z"];

for($j=1; $j <= 10; $j++)
{
	if(!in_array($j, $alreadyused))
	{ 
		$z = $j; 
		break;
	}
}

?>