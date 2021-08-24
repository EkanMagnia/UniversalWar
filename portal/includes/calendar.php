<?php

/********************************************************************
*         calendar.php
*       -------------
*	Desc		: class file for the calendar
*   Changed		: Wednesday Jan 14 2004
*   Copyright	: (C) 2003 Peter Vestberg
*   Email		: peter.vestberg@telia.com
*   
*********************************************************************/

/*** construct array of month names ***/
	$months = array('', 
		'January', 
		'February', 
		'March', 
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December'
	);
	
/*** Initiate and declare calendar class ***/
	class calendar {
			
		var $year, $month, $months;
		
/*** class constructor ***/
		function calendar() {
			global  $months;
			
			if( is_numeric( $_GET["y"] ) && is_numeric( $_GET["m"] ) ) {
				
				if( ( is_numeric( $_GET["y"] ) && is_numeric( $_GET["m"] ) ) && ( strlen( $_GET["y"] ) == 4 && $_GET["m"] > 0 && $_GET["m"] < 13  ) ) {
			
					$this->year = $_GET["y"];
					$this->month = $_GET["m"];
					
				} else {
					
					$this->year = date('Y');
					$this->month = date('n');								
					
				}
				
			} else {
				$this->year = date('Y');
				$this->month = date('n');			
			}				
			
			$this->months = array('', 
				'January', 
				'February', 
				'March', 
				'April',
				'May',
				'June',
				'July',
				'August',
				'September',
				'October',
				'November',
				'December'
			);
		}
		
		function getEvents($date) {
			
			global $_CFG;
						
			$query = mysql_query( 'SELECT * FROM '.$_CFG["db"]["events"].' WHERE date="'.$date.'" ORDER BY postdate DESC LIMIT 2' );

			if ( mysql_num_rows($query) ) {
				echo '<p align="left">';
				while( $events = mysql_fetch_array( $query )  ) {
					echo '&nbsp;<a href="'.$PHP_SELF.'?page=calendar&action=viewevent&eid='.$events["id"].'&y='.$_GET["y"].'&m='.$_GET["m"].'">'.$events["name"].'</a><br />';
				}
				echo "</p>";
							
			} else {
				echo '<p><br />&nbsp;</p>';
			}

		}
		
		function currentMonth() {
			
			return $this->months[$this->month];
				
		}
		
		function currentYear() {
			
			return $this->year;
				
		}		
		
		function nextMonth() {
			
			global $months;
		
			if($this->month == 12) {
				$next_year = $this->year+1;
				$next_month = 1;
			} elseif($this->month == 1) {
				$next_year = $this->year;
				$next_month = $this->month+1;
			} else {
				$next_year = $this->year;
				$next_month = $this->month+1;
			}
			print '<a class="calendarLinks" href="'.$PHP_SELF.'?page=calendar&y='.$next_year.'&m='.$next_month.'">'.$this->months[$next_month].' >></a>';
		}

		
		function prevMonth() {
			
			global $months;
		
			if($this->month == 12) {
				$prev_year = $this->year;
				$prev_month = $this->month-1;
			} elseif($this->month == 1) {
				$prev_year = $this->year-1;
				$prev_month = 12;
			} else {
				$prev_year = $this->year;
				$prev_month = $this->month-1;
			}
			print '<a class="calendarLinks" href="'.$PHP_SELF.'?page=calendar&y='.$prev_year.'&m='.$prev_month.'"><< '.$this->months[$prev_month].'</a>';
			
		}
		
		function drawCalendar() {
			
			?>
			<table class="calendarHead" cellspacing="1" cellpadding="3"> 
				<tr> 
					<td class="calendarDayTopic">Monday</td> 
					<td class="calendarDayTopic">Tuesday</td> 
					<td class="calendarDayTopic">Wednesday</td> 
					<td class="calendarDayTopic">Thursday</td> 
					<td class="calendarDayTopic">Friday</td> 
					<td class="calendarDayTopic">Saturday</td> 
					<td class="calendarDayTopic">Sunday</td> 
				</tr> 
			
				<?
				$year = $this->year;
				$month = $this->month;
				
				$first_day = @date( 'w', mktime( 0, 0, 0, $month, 1, $year ) );
				$days_in_month = @date( 't', mktime( 0, 0, 0, $month, 1, $year ) );
			
							
				if($month == date('n') && $year == date('Y')) { 
					$today = date('j'); 
				} else {
					$today = -1;	
				}
			
				$current_date = 1;
				$emptyBefore = 0;
				
				// draw 35 boxes	
				for( $x = 1; $x <= 35; $x++ ) {
					
					
					if ( $first_day == 0 ) {
						for($y = 0; $y < 6; $y++) {
							print '<td class="calendarDayEmpty">&nbsp;</td>';	
							$emptyBefore++;
							$x = 7;
							$first_day = -1;
						}
					}
					
					if( $x < $first_day) {
						
						print '<td class="calendarDayEmpty">&nbsp;</td>';
						$emptyBefore++;
					} elseif( $x-$emptyBefore > $days_in_month ) {
						print '<td class="calendarDayEmpty">&nbsp;</td>';
						
					} elseif ( $x-$emptyBefore == $today ) {
						$date = mktime (0, 0, 0, $month, $current_date, $year );
						print '<td class="calendarDayToday">';
						print '<a href="'.$PHP_SELF.'?page=calendar&action=viewevent&eid=all&date='.$date.'">'.$current_date.'</a><br />';
						$this->getEvents($date);
						print '</td>';
						$current_date++;
					} else {
						$date = mktime (0, 0, 0, $month, $current_date, $year );
						print '<td class="calendarDayNormal">';
						print '<a href="'.$PHP_SELF.'?page=calendar&action=viewevent&eid=all&date='.$date.'">'.$current_date.'</a><br />';
						$this->getEvents($date);
						print '</td>';
						$current_date++;
					}
					
					if ( @date( 'w', mktime( 0, 0, 0, $month, $current_date, $year ) ) == 1) {
						print '</tr><tr>';
					}
					
					if ( $x == 35 ) {
						print "</td>";
					}
		
				}

				?> 
			</table>
			<?
		}
	}

?>
