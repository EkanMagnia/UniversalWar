<?
error_reporting(1);
$section = "Admin - Multi Tools";
include("functions.php");
include("header.php");

headerDsp("Posible multi");
?><br><center>
<table>
<tr>
	<td bgcolor="<?=$tdbg1;?>">ID</td>
	<td bgcolor="<?=$tdbg1;?>">Coordinate</td>
	<td bgcolor="<?=$tdbg1;?>">Commander</td>
	<td bgcolor="<?=$tdbg1;?>">Username</td>
	<td bgcolor="<?=$tdbg1;?>">Password</td>
	<td bgcolor="<?=$tdbg1;?>">IP</td>
	<td bgcolor="<?=$tdbg1;?>">Close</td>
	<td bgcolor="<?=$tdbg1;?>">Edit</td>
	<td bgcolor="<?=$tdbg1;?>">Logs</td>
</tr>

<?
$request = mysql_query("SELECT * FROM uwar_users ORDER by id");
while ($user = mysql_fetch_array($request))
{
	$request2 = mysql_query("SELECT * FROM uwar_users WHERE id>'$user[id]' ORDER by id");
	while ($user2 = mysql_fetch_array($request2))
	{
		if ($user["ip"] == $user2["ip"])
		{
			$sysSQL = mysql_query("SELECT x,y FROM uwar_systems WHERE id='$user[sysid]'");
			$sys = mysql_fetch_array($sysSQL);
			?><tr>	<td bgcolor="<?=$tdbg2;?>"><?=$user["id"];?></td>
			<td bgcolor="<?=$tdbg2;?>"><?=$sys["x"]?>:<?=$sys["y"];?>:<?=$user["z"];?></td>
			<td bgcolor="<?=$tdbg2;?>"><?=$user["nick"]?> of <?=$user["planet"];?></td>
			<td bgcolor="<?=$tdbg2;?>"><?=$user["username"];?></td>
			<td bgcolor="<?=$tdbg2;?>"><?=$user["password"];?></td>
			<td bgcolor="<?=$tdbg2;?>"><?=$user["ip"];?></td>
			<td bgcolor="<?=$tdbg2;?>"><a href="<?=$PHP_SELF;?>?action=close">Close</a></td>
			<td bgcolor="<?=$tdbg2;?>"><a href="<?=$PHP_SELF;?>?action=edit">Edit</a></td>
			<td bgcolor="<?=$tdbg2;?>"><a href="admin_logs.php">Logs</a></td></tr>

			
			<?
		}
	}
}
?></table><br><br><?
footerDsp();
include("footer.php");
