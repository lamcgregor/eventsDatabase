<?php
function calendarDisplay($month, $year, $link) {

	$id = $_SESSION['id'];
	$result = mysql_query("SELECT * FROM events WHERE userid='$id' AND month='$month' AND year='$year' ORDER BY day", $link);
	if (!$result)
	{
		errorReport('Functional error', 'Error fetching events from database' . mysql_error($link));
	}

	while ($row = mysql_fetch_array($result))
	{
		$events[] = array(
		'title' => $row['title'],
		'day' => $row['day'],
		'month' => $row['month'],
		'year' => $row['year'],
		'starttime' => $row['startTime'],
		'endtime' => $row['endTime'],
		'description' => $row['description'],
		'id' => $row['id']
		);
	}
	
	$weekdays = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
	//Print the calendar table
	print('
	<table class="calendar">
	<thead>
	<tr>');
		for($i = 0; $i < 7; $i ++) { //For each day of the week create a table heading
			print("<th>" . $weekdays[$i] . "</th>");
		}
	print("
	</tr>
	</thead>
	<tbody>");
	$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year); //Find out how many days are in the month
	$firstDay = date('w', mktime(0,0,0,$month,0,$year)); //Find out what day of the week the month starts on
	$daysLeftInMonth = $daysInMonth - (7-$firstDay);
	$day = 0;
	$b = 0;
	for($i = 0;$i <= ceil($daysLeftInMonth / 7);$i++) {
		print("
		<tr>");
		for($a = 1; $a<= 7; $a++) {
			$day++;
			if($i == 0 && $firstDay >= $a) {
				$day = '';
				$class = 'empty';
			}
			elseif ($day > $daysInMonth || $day == 'Y') {
				$day = 'X';
				$class = 'empty';
			}
			else {
				$class = 'date';
			}
			print('
			<td class="' . $class . '">');
			if($day <= $daysInMonth) {
				echo '<h3>';
				if($day != 'X') { echo $day; }
				if (isset($events[$b]['day']) && $events[$b]['day'] == $day) {
					echo ' </h3><h4>'. $events[$b]['title'] . '</h4>
							<p><em>' . $events[$b]['starttime'] . ' - ' . $events[$b]['endtime'] . '</em></p>
							<a class="button" href="?view=' . $events[$b]['id'] . '">View Event</a>';
					$b++;
				}
				else {
					echo '</h3>';
				}
			}
			print("
			</td>");
		}
		print("
		</tr>");
	}
	print("
	</tbody>
	</table>");
}
?>