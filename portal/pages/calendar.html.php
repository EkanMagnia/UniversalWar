<?php
drawHeader($_GET["page"]);

	/*$cal = array();
	
	$cal["name"]		= "y-calendar";
	$cal["version"]		= "v.1.0";
	$cal["titlebar"]	= "y-calendar ";

	$cal["dbhost"]		= "localhost";
	$cal["dbusername"]	= "root";
	$cal["dbpassword"]	= "";
	$cal["dbdatabase"]	= "y-calendar";
	
	$cal["includes"]	= "includes/";
	$cal["template"]	= "template/";*/

/*** include configuration settings, functions library, calendar class ***/
	include_once( $_CFG["paths"]["includes"].'names.php' );
	include_once( $_CFG["paths"]["includes"].'calendar.php' );

	
/*** create calendar object ***/
	$calendar = new calendar();
	

	if( $_GET["action"] == "viewevent" && ( is_numeric( $_GET["eid"] ) || ($_GET["eid"] == 'all' && is_numeric($_GET["date"])) ) ) {
		
		if ($_GET["eid"] == 'all' ) {

			$query = mysql_query( "SELECT * FROM ".$_CFG["db"]["events"]." WHERE date=".$_GET["date"]." ORDER BY postdate DESC " );
			?>
				<table border="0" cellspacing="10" cellpadding="0" style="width: 100%;">
					<tr>
						<td style="text-align: left;"><font class="subject">Events <?=date("Y.m.d", $_GET["date"])?></font></td>
					</tr>				
					<tr>
						<td>
						<?
						
							if ( $result = mysql_fetch_array( $query ) ) {
								
								while($result = mysql_fetch_array( $query )) {
									
									echo '<a href="'.$PHP_SELF.'?page=calendar&action=viewevent&eid='.$result["id"].'">'.$result["name"].'</a><br />';
								}
								
							} else {
							
								echo '<font class="font">There are currently no events this date.</font>';
									
							}
						?>
						</td>
					</tr>
					<tr>
						<td>
							<br /><img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="5" alt="">&nbsp;&nbsp;&nbsp;
							<a href="<?=$PHP_SELF?>?page=calendar&y=<?=$_GET["y"]?>&m=<?=$_GET["m"]?>">Return to calendar</a><br />
						</td>
					</tr>					
				</table>
			
			<?
			
		} else {

			$query = mysql_query( "SELECT * FROM ".$_CFG["db"]["events"]." WHERE id=".$_GET["eid"]." " );
			$result = mysql_fetch_array( $query );
		
		?>
		
		<table border="0" cellspacing="10" cellpadding="0" style="width: 100%;">
			<tr>
				<td colspan="2" style="text-align: left;"><font class="subject"><?=$result["name"]?></font></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: left;"><font class="writtenby">Posted by <b><?=$result["author"]?></b> <?=date("Y.m.d h:m" ,$result["postdate"])?></font></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: left;"><hr noshade></td>
			</tr>			
			<tr>
				<td style="text-align: left; width: 100px;">
					
					<table border="0" cellspacing="4" cellpadding="0" style="width: 100%;">
						<tr>
							<td><font class="calendarLeft">Event name:</font></td>
							<td><font class="calendarRight"><?=$result["name"]?></font></td>
						</tr>
						<tr>
							<td><font class="calendarLeft">Event date:</font></td>
							<td><font class="calendarRight"><?=date("Y.m.d" ,$result["date"])?></font></td>
						</tr>					
						<tr>
							<td><font class="calendarLeft">Event author:</font></td>
							<td><font class="calendarRight"><?=$result["author"]?></font></td>
						</tr>						
						<tr>
							<td style="width: 150px; vertical-align: top;"><font class="calendarLeft">Event description:</font></td>
							<td><font class="calendarRight"><?=$result["description"]?></font></td>
						</tr>
						<tr>
							<td colspan="2">
								<br /><img src="<?=$_CFG["paths"]["img"]?>arrow.gif" width="7" height="5" alt="">&nbsp;&nbsp;&nbsp;
								<a href="<?=$PHP_SELF?>?page=calendar&y=<?=$_GET["y"]?>&m=<?=$_GET["m"]?>">Return to calendar</a><br />
							</td>
						</tr>
					</table>
				</td>
 			</tr>			
		</table>
		
		<?
		}
	}
	else {
	
?>

<table border="0" cellspacing="20" cellpadding="0" style="width: 100%;">
	<tr>
		<td>
			<font class="font">All Universal War-related events/happenings will be shown in this calendar.
			The two first events on each day is visible, to view the full event list of a day, click on the day number.</font><br /><br /><br />
			<table class="calendarTable" align="center">
				<tr>
					<td style="text-align: left; vertical-align: bottom;" width="100"><?=$calendar->prevMonth()?></td>
					<td style="text-align: center;"><font class="calendarMonth"><?=$calendar->currentMonth().", ".$calendar->currentYear()?></font>
					<td style="text-align: right; vertical-align: bottom;" width="100" align="right"><?=$calendar->nextMonth()?></td>
				</tr>
				<tr>
					<td colspan="3">
						<br /><br /><?=$calendar->drawCalendar()?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>


<?

	}
	
drawFooter();
?>