<?php
error_reporting(1);
$section = "Leave Alliance";
include("functions.php");
include("header.php");

/* Algoritm
1. verifica daca jucatorul este intr-o alintza.
	- daca nu,atunci afiseaza un mesaj eroare
	- daca da, continua
2. verifica daca jucatorul este liderul aliantei:
	- daca nu atunci scrie tagid=0 in tabelul cu usere shi decrementeaza members in tabelul tags
	- daca da atunci:
		- update uwar_users set tagid=0 where id='$Userid'
		- $request = select * from tags where id='$tagid'; 
		- update uwar_users set tagid='0' where tagid='$tagid'
		- add_news("The leader of your alliance has deleted it. As a fact of this all the members were removed.");
		-sterge campul corespunzator aliantei din uwar_tags
3. Afisarea sectiunii
*/
$tag = $myrow["tagid"];
$sql = mysql_query("SELECT * FROM uwar_tags WHERE id='$tag'",$db);
$ally = mysql_fetch_array($sql);
$name = $ally["name"];
if($myrow["tagid"] == 0) 
{
	header("Location: allyindex.php"); 
	die(); 
}
if ($tag!=0)
{
	if ($leaveallie)
	{
		if ($myrow["leader"]!=1)
		{
			mysql_query("UPDATE uwar_users SET tagid='0' WHERE id='$Userid'",$db);
			mysql_query("UPDATE uwar_users SET leader='0' WHERE id='$Userid'",$db);
			$subject = "Retired from alliance $name";
			$news = "Commander, we hope that the decision you made, to leave your alliance, was correct. We also hope we will get stronger alliances to join and that this change is posivite for us. We know now that it's reasonable that our enemy number has increased, but we still have hope. We have the hope of survival in this wild universe and we once again put our faith in you.";
			add_news($subject, $news, $Userid);
			$request = Mysql_Query("SELECT * FROM uwar_users WHERE tagid='$tag' and leader='1'",$db);
			if ($leader = Mysql_Fetch_Array($request))
			{
				$news = "Commander $myrow[nick] of $myrow[planet] has left our alliance";
				add_news("Alliance Left", $news, $leader["id"]);
			}
			$newmem = $ally["members"] - 1;    
			$sql2 = mysql_query("UPDATE uwar_tags SET members='$newmem' WHERE id='$tag'",$db);
			Header("Location: allyindex.php?action=leave");
			die();
		}
		elseif($myrow["leader"] == 1)
		{
			headerDsp( "Retiring from alliance" );
				 echo "<br/><center>You cannot leave the alliance before you have abdicated from the leader position. This can be done in the Management section.</center><br/>";
				 echo "<center><a href=allyindex.php><< Alliance index</a></center><br />";
			footerDsp();
		}
	}
}
if (!$leaveallie && $myrow["leader" != 1])
{
	headerDsp( "Retiring from alliance" );
	?>
	<BR><center>
	<TABLE align="center" borderColor="<?$tdbg1;?>" width="" style="border-collapse: collapse" cellpadding="0" cellspacing="0">
		<TR>
			<TD><center>This is your last chance to think if you want to be a member of alliance <?=$ally["name"];?> or not. Take a moment of silence, think of your future as a commander without your alliance, and make your choice.<br><br></TD>
		</TR>
		<TR>
			<TD><center><form action="<?=$PHP_SELF;?>" method="post"><input type="submit" name="leaveallie" value="Leave alliance"></form></center></TD>
		</TR>
		<tr>
			<td><center><a href="allyindex.php"><< Alliance index</a></center><br /></td>
		</tr>
	</TABLE></center>
	<?
	footerDsp();
}

include("footer.php");
?>