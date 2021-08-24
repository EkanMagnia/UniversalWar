<?php
drawHeader("administrate calendar");

if ($_SESSION["logged"] != 1) {
	header("Location: ".$PHP_SELF."?page=login");
}

if ($_GET["action"] == "add") {
	if (isset($_POST["submit"])) {
		if ( $_POST["name"] && $_POST["year"] && $_POST["month"] && $_POST["date"] && $_POST["description"]) {
			
			$name = $_POST["name"];
			$description = nl2br($_POST["description"]);
			$author = $_SESSION["username"];
			$postdate = time();
			$date = mktime(0, 0, 0, $_POST["month"], $_POST["date"], $_POST["year"]);
						
			mysql_query("INSERT INTO ".$_CFG["db"]["events"]." (date, name, description, author, postdate ) VALUES ('$date', '$name', '$description', '$author', '$postdate')");
			echo "<font class=\"font\">Event was added! You will now be re-directed to the calendar page.</font>";
			header("Location: ".$PHP_SELF."?page=calendar&y=".$_POST["year"]."&m=".$_POST["month"]."");
		} else {
			echo "<font class=\"error\">&nbsp;&nbsp;&nbsp;&nbsp;You have not filled in all required fields.</font>";	
		}
	}
	
	?>
	<table border="0" cellspacing="10" cellpadding="0">
	<form action="<?=$PHP_SELF?>?page=admin_calendar&action=add" method="post">
	<tr>
		<td width="150"><font class="font">Event name (very short):</font></td>
		<td><input type="text" name="name" value="<?=$_POST["name"]?>" style="width: 200px;" /></td>
	</tr>
	<tr>
		<td width="150" style="vertical-align: top;"><font class="font">Event date:</font></td>
		<td>
			<select name="year" style="color: #FFFFFF; background-color: #000000; width: 100px; height: 20px;">
				<option value="">Year:</option>
				<option value="2004">2004</option>
				<option value="2005">2005</option>
				<option value="2006">2006</option>
			</select> <br /><br />
			<select name="month" style="color: #FFFFFF; background-color: #000000; width: 100px; height: 20px;">
				<option value="">Month:</option>
				<?
					include_once( $_CFG["paths"]["includes"].'names.php');
					foreach( $months as $id => $monthname ) {
						echo '<option value='.$id.'>'.$monthname.'</option>';	
					}
				?>
			</select> <br /><br />
			<select name="date" style="color: #FFFFFF; background-color: #000000; width: 100px; height: 20px;">
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
		<td width="50" style="vertical-align: top;"><font class="font">Description:</font></td>
		<td><textarea name="description" style="width: 200px; height: 100px;"><?=$_POST["description"]?></textarea></td>
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
			<a href="<?=$PHP_SELF?>?page=admin_calendar&action=add">Add event</a><br />
			<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">&nbsp;&nbsp;&nbsp;
			<a href="<?=$PHP_SELF?>?page=admin_calendar&action=edit">Edit event</a><br />
			<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">&nbsp;&nbsp;&nbsp;
			<a href="<?=$PHP_SELF?>?page=admin_calendar&action=remove">Remove remove</a><br /><br /><br /><br />
			<img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="6" alt="">&nbsp;&nbsp;&nbsp;
			<a href="<?=$PHP_SELF?>?page=login">Return to admin index</a><br />

		</td>
	</tr>
	</table>
	<?
}
drawFooter();
?>