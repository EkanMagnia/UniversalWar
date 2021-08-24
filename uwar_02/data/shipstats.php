<?
function headerDsp( $title ) {
?>
<TABLE border="1" bordercolor="#808080" style="BORDER-COLLAPSE: collapse" cellSpacing="0" cellPadding="0" width="647" align="center">
		<TR>
			<TD width="647" background="bg_news.gif" bgColor="#555555">
				<SPAN lang=en-us style="LETTER-SPACING: 2px">
				<FONT face=Verdana size=1>&nbsp; <? print $title; ?></FONT></SPAN>
			</TD>
		</TR>

		<TR align="center">
			<TD width=647 bgColor=#444444 align="center">
<?
}

function footerDsp() {
?>
			</TD>
		</TR>
	</TABLE><BR>
<?
}
?>
<HTML>
<HEAD>
<TITLE>Universal War - Unit Statistics</TITLE>
<STYLE>
TD {
	FONT-SIZE: 8pt; COLOR: #FFFFFF; FONT-FAMILY: verdana
}
A:link {
	COLOR: #c0c0c0; TEXT-DECORATION: none
}
A:visited {
	COLOR: #c0c0c0; TEXT-DECORATION: none
}
A:active {
	COLOR: #c0c0c0; TEXT-DECORATION: none
}
A:hover {
	COLOR: #FFFFFF; TEXT-DECORATION: none
}
INPUT {
	FONT-SIZE: 8pt; COLOR: #c0c0c0; FONT-FAMILY: verdana; BACKGROUND-COLOR:#222222
}
TEXTAREA {
	FONT-SIZE: 8pt; COLOR: #c0c0c0; FONT-FAMILY: verdana; BACKGROUND-COLOR: rgb(44,44,44)
}
SELECT {
	FONT-SIZE: 8pt; COLOR: #c0c0c0; FONT-FAMILY: verdana; BACKGROUND-COLOR: rgb(44,44,44)
}
A {
	TEXT-DECORATION: none
}
A:hover {
	TEXT-DECORATION: underline
}
</STYLE>
</HEAD>

<BODY bgcolor="#000000" link="#FFFFFF" vlink="#FFFFFF" text="#FFFFFF">
<center><img src="logo.gif"></center>
<? include("ShipTypes.php");
headerDsp("Ships statistics") ?>
			<center>
			<table border="0" cellpadding="4" cellspacing="1" width="100%" align="center">
			<tr align="center">
				<td bgcolor="#333333">Name</td>
				<td bgcolor="#333333">Special</td>
				<td bgcolor="#333333">ShipClass</td>
				<td bgcolor="#333333">T1</td>
				<td bgcolor="#333333">T2</td>
				<td bgcolor="#333333">T3</td>
				<td bgcolor="#333333">Init</td>
				<td bgcolor="#333333">Agility</td>
				<td bgcolor="#333333">WP_SP</td>
				<td bgcolor="#333333">Guns</td>
				<td bgcolor="#333333">GunPWR</td>
				<td bgcolor="#333333">Armour</td>
				<td bgcolor="#333333">EMP_res</td>
				<td bgcolor="#333333">Fuel</td>
				<td bgcolor="#333333">Travel</td>
				<td bgcolor="#333333">Build Time</td>
				<td bgcolor="#333333">Mercury</td>
				<td bgcolor="#333333">Cobalt</td>
				<td bgcolor="#333333">Caesium</td>
			</tr>
			<tr>
			<? 

			foreach ($ShipTypes as $idx => $ship)
			{
				?>
			<tr>
				<td bgcolor="#222222" align="center"><?=$ship["Name"];?></td>
				<td bgcolor="#222222" align="center"><?=$ship["Special"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["ShipClass"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["Target1"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["Target2"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["Target3"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["Init"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["Agility"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["Weap_speed"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["Guns"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["Gunpower"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["Armour"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["Emp_res"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["Fuel"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["Travel"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["BuildTime"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["Mercury"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["Cobalt"];?></td>
				<td bgcolor="#222222" align="center"><center><?=$ship["Helium"];?></td>
			</tr>
				<? 
			}
footerDsp();
 headerDsp("Notes"); ?>
<center>
<table border="0" cellpadding="4" cellspacing="1" width="100%" align="center">
	<tr align="center">
		<td bgcolor="#333333" width="1%">Shortening</td>
		<td bgcolor="#333333" width="1%">Meaning</td>
	</tr>
	<tr align="center">
		<td bgcolor="#222222">FI</td>
		<td bgcolor="#222222">Fighter</td>
	</tr>
	<tr align="center">
		<td bgcolor="#222222">FR</td>
		<td bgcolor="#222222">Frigate	</td>
	</tr>
	<tr align="center">
		<td bgcolor="#222222">CR</td>
		<td bgcolor="#222222">Cruiser</td>
	</tr>
	<tr align="center">
		<td bgcolor="#222222">ST</td>
		<td bgcolor="#222222">Stealer</td>
	</tr>
	<tr align="center">
		<td bgcolor="#222222">CL</td>
		<td bgcolor="#222222">Cloacked</td>
	</tr>
	<tr align="center">
		<td bgcolor="#222222">RO</td>
		<td bgcolor="#222222">Probes</td>
	</tr>
	<tr align="center">
		<td bgcolor="#222222">-</td>
		<td bgcolor="#222222">None</td>
	</tr>
	<tr align="center">
		<td bgcolor="#222222">*</td>
		<td bgcolor="#222222">Any class</td>
	</tr>
	<tr align="center">
		<td bgcolor="#222222">**</td>
		<td bgcolor="#222222">Resources</td>
	</TR>
</TABLE>
</center>
<? footerDsp(); ?>
</HTML>
