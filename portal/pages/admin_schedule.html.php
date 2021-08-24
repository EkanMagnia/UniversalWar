<?php
drawHeader("administrate schedule");

if ($_SESSION["logged"] != 1) {
	header("Location: ".$PHP_SELF."?page=login");
}

if ($_GET["action"] == "edit") {
	if (isset($_POST["submit"])) {
			
		$start = mktime(0, 0, 0, $_POST["startmonth"], $_POST["startdate"], $_POST["startyear"]);
		$end = mktime(0, 0, 0, $_POST["endmonth"], $_POST["enddate"], $_POST["endyear"]);
		$havoc = mktime(0, 0, 0, $_POST["havocmonth"], $_POST["havocdate"], $_POST["havocyear"]);
					
		mysql_query("UPDATE ".$_CFG["db"]["schedule"]." SET start=".$start.", end=".$end.", havoc=".$havoc."");
		echo "<font class=\"successful\">&nbsp;&nbsp;Schedule was successfully edited.</font>";
		
	}
	
	$query = mysql_query("SELECT * FROM ".$_CFG["db"]["schedule"]." LIMIT 1");
	$result = mysql_fetch_array( $query );
	?>
	<table border="0" cellspacing="10" cellpadding="0">
	<form action="<?=$PHP_SELF?>?page=admin_schedule&action=edit" method="post">
	<tr>
		<td width="150" style="vertical-align: top;"><font class="font">Round starts:</font></td>
		<td>
			<select name="startyear" style="color: #FFFFFF; background-color: #000000; width: 100px; height: 20px;">
				<option value="">Year:</option>
				<option value="2004">2004</option>
				<option value="2005">2005</option>
				<option value="2006">2006</option>
			</select> <br /><br />
			<select name="startmonth" style="color: #FFFFFF; background-color: #000000; width: 100px; height: 20px;">
				<option value="">Month:</option>
				<?
					include_once( $_CFG["paths"]["includes"].'names.php');
					foreach( $months as $id => $monthname ) {
						echo '<option value='.$id.'>'.$monthname.'</option>';	
					}
				?>
			</select> <br /><br />
			<select name="startdate" style="color: #FFFFFF; background-color: #000000; width: 100px; height: 20px;">
				<option value="">Date:</option>
				<?
					for( $id = 1; $id <= 31; $id++ ) {
						echo '<option value='.$id.'>'.$id.'</option>';	
					}
				?>
			</select><br /><br />		
		</td>
	</tr>
	<tr>
		<td width="150" style="vertical-align: top;"><font class="font">Round ends:</font></td>
		<td>
			<select name="endyear" style="color: #FFFFFF; background-color: #000000; width: 100px; height: 20px;">
				<option value="">Year:</option>
				<option value="2004">2004</option>
				<option value="2005">2005</option>
				<option value="2006">2006</option>
			</select> <br /><br />
			<select name="endmonth" style="color: #FFFFFF; background-color: #000000; width: 100px; height: 20px;">
				<option value="">Month:</option>
				<?
					include_once( $_CFG["paths"]["includes"].'names.php');
					foreach( $months as $id => $monthname ) {
						echo '<option value='.$id.'>'.$monthname.'</option>';	
					}
				?>
			</select> <br /><br />
			<select name="enddate" style="color: #FFFFFF; background-color: #000000; width: 100px; height: 20px;">
				<option value="">Date:</option>
				<?
					for( $id = 1; $id <= 31; $id++ ) {
						echo '<option value='.$id.'>'.$id.'</option>';	
					}
				?>
			</select><br /><br />	
		</td>
	</tr>
	<tr>
		<td width="150" style="vertical-align: top;"><font class="font">Havoc:</font></td>
		<td>
			<select name="havocyear" style="color: #FFFFFF; background-color: #000000; width: 100px; height: 20px;">
				<option value="">Year:</option>
				<option value="2004">2004</option>
				<option value="2005">2005</option>
				<option value="2006">2006</option>
			</select> <br /><br />
			<select name="havocmonth" style="color: #FFFFFF; background-color: #000000; width: 100px; height: 20px;">
				<option value="">Month:</option>
				<?
					include_once( $_CFG["paths"]["includes"].'names.php');
					foreach( $months as $id => $monthname ) {
						echo '<option value='.$id.'>'.$monthname.'</option>';	
					}
				?>
			</select> <br /><br />
			<select name="havocdate" style="color: #FFFFFF; background-color: #000000; width: 100px; height: 20px;">
				<option value="">Date:</option>
				<?
					for( $id = 1; $id <= 31; $id++ ) {
						echo '<option value='.$id.'>'.$id.'</option>';	
					}
				?>
			</select>		
		</td>
	</tr>		
	<tr>
		<td width="50" style="vertical-align: top;">&nbsp;</td>
		<td width="210" style="vertical-align: top;">
			<input type="submit" value="Submit" name="submit" style="width: 99px;" />&nbsp;
		</td>
	</tr>	
	</table>
	<?
}
else {
	?>
	<table border="0" cellspacing="10" cellpadding="0" style="width: 100%;">
	<tr>
		<td>
			<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">&nbsp;&nbsp;&nbsp;
			<a href="<?=$PHP_SELF?>?page=admin_schedule&action=edit">Edit dates</a><br />
			

		</td>
	</tr>
	</table>
	<?
}
drawFooter();
?>