<?
error_reporting(1);
$section = "Alliance Search";
include("functions.php");
include("header.php");


if($action == "search" && isset($tag))
{
   	$tag = strip_tags($tag);
	$request = mysql_query("SELECT * FROM uwar_tags WHERE tag='$tag'");
	if(mysql_num_rows($request) == 0) $msgred = "Alliance doesn't exist!";
    else
    {
		$search = mysql_fetch_array($request);
        $found = "yes";
	}
}

if(isset($msggreen))
	print "<CENTER><FONT face=Arial size=2 color=#00CC00><B>".$msggreen."</B></FONT></CENTER><BR>";
if(isset($msgred))
	print "<CENTER><FONT face=Arial size=2 color=#FF0000><B>".$msgred."</B></FONT></CENTER><BR>";


if($found == "yes" && $msgred == '')
{
	headerDsp("Alliance Search result");
	?>
    <br><br>
	<center>
	<table border="0" cellpadding="4" cellspacing="1" width="90%">
    <tr>
	    <td bgcolor="<?=$tdbg1;?>" colspan="2"><?=$search["allyname"]?></td>
    </tr>
    <tr>
		<td bgcolor="<?=$tdbg2;?>">Score:</td><td bgcolor="<?=$tdbg2;?>"><?=Number_format($search["score"],0,".",".")?></td>
    </tr>
    <tr>
		<td bgcolor="<?=$tdbg2;?>">Size:</td><td bgcolor="<?=$tdbg2;?>"><?=number_format($search["size"],0,".",".")?></td>
    </tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">Members</td><td bgcolor="<?=$tdbg2;?>"><?=$search["members"];?></td>
	</tr>
	<tr>
		<td bgcolor="<?=$tdbg2;?>">Description</td><td bgcolor="<?=$tdbg2;?>"><?=$search["description"];?></td>
	</tr>
    </table>
    </center>
    <br>
	<?
    footerDsp();
}

headerDsp("Alliance Search");
?>
<br><br>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="90%">
<form action="<?=$PHP_SELF?>?action=search" method="post">
<tr>
	<td bgcolor="<?=$tdbg2;?>">
	This option helps you to find information about any alliance that exists in Universal War.
    This information are: name, members, score, size, political positions, descriptions and members.
    However you won't get any secret or private information about the alliance.
    You could consider the alliance search feature as an alliance scan, but without having to pay for it.<br><br>
    In order to search an alliance you need to follow the next 2 steps:
    <ul>
    	<li>Type the correct TAG of the alliance in the space below
        <li>Click on the Search Button
    </ul>
    </td>
</tr>
<tr>
    <td bgcolor="<?=$tdbg2;?>" align="center">
    Alliance tag:&nbsp;
    <input type="text" name="tag">&nbsp;
	<input type="submit" value="Search">
    </td>
</tr>
</table>
</center>
<br>
<?
footerDsp();
include("footer.php");
?>