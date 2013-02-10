<?php
//Magicquotes
if (get_magic_quotes_gpc())
{
	function stripslashes_deep($value)
	{
		$value = is_array($value) ?
				array_map('stripslashes_deep', $value) :
				stripslashes($value);

		return $value;
	}

	$_POST = array_map('stripslashes_deep', $_POST);
	$_GET = array_map('stripslashes_deep', $_GET);
	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
	$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

//Function to return an error
function errorReport ($title, $errorMessage) {
		$title = $title;
		$error = $errorMessage;
		include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/error.html.php';
		exit();
}

// Quick function to print out POST / GET data
function htmlprint ($text) {
	echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

//
function strip ($link, $text) {
	return mysql_real_escape_string($text, $link);
}

//Function to pad numbers (used for database stored dates etc.)
function pad($number) {
	if($number < 10) return '0' . $number;
	else return $number;
}

//Creates an options list. Ill replace it with a date picker at some point, some nice jQuery thing.
function optionslist($start, $count, $name, $eventscheck , $pad) {
	echo '<select name="' . $name . '">';
	for ($i = $start; $i <= $count; $i++) {
		if($pad == 'PAD') {
			echo '<option value="' . pad($i) . '"';
			if (pad($i) == $eventscheck) {
				echo ' selected="selected"';
			}
			echo '>';
			echo pad($i);
		}
		else {
			echo '<option value="' . $i . '"';
			if ($i == $eventscheck) {
				echo ' selected="selected"';
			}
			echo '>';
			echo $i;
		}
		echo '</option>';
	}
	echo '</select>';
}

//Checks if a date is in the future
/*
function futureDate($year,$month,$day,$hour,$minute) {
	if($year >= date('Y')) {
		if($year > date('Y')) {
			return TRUE;
		}
		elseif ($month >= date('m')) {
			if($month > date('m')) {
				return TRUE;
			}
			elseif ($day >= date('j')) {
				if($day > date('j')) {
					return TRUE;
				}
				elseif($hour >= date('H')) {
					if($hour > date('H')) {
						return TRUE;
					}
					elseif($minute >= date('i')) {
						return TRUE;
					}
					else {
						return FALSE;
					}
				}
				else {
					return FALSE;
				}
			}
			else {
				return FALSE;
			}
		}
		else {
			return FALSE;
		}
	}
	else {
		return FALSE;
	}
}
*/
function futureTime($year,$month,$day,$hour,$minute) {
	date_default_timezone_set('Europe/London');
	$check = mktime($hour, $minute, 0, $month, $day, $year);
	$today = mktime(date('H'), date('i'), 0, date("n"), date("j"), date("Y"));

	if ($check > $today) {
	  return TRUE;
	}
	elseif ($check == $today) {
	  return TRUE;
	}
	else {
	  return FALSE;
	}
}

//Function to validate events before inputting to database
function validateEvent($event) {
	$errors = '';
	if($event['title'] == '') {
		$errors .= "This event has no title<br />";
	}
	if(!$event['date']) {
		$errors .= "Please enter a date for the event.<br />";		
	}
	if(($event['starttimehr'] == $event['endtimehr'] && $event['starttimemin'] >= $event['endtimemin']) || ($event['starttimehr'] > $event['endtimehr'])) {
		$errors .= "This events ends before/at the same time it begins!<br />";
	}

	return $errors != '' ? $errors : false;
}
function futureEvent($event) {
	$errors = '';
	if(!$event['date']) {
		return $errors;
	}
	$date = explode('/',$event['date']);
	$day = ltrim($date[0], "0");
	$month = ltrim($date[1], "0");
	$year = $date[2];
	if(!futureTime($year, $month, $day, $event['starttimehr'],$event['starttimemin'])) {
		$errors .= "This event is in the past!<br />";
	}
	return $errors != '' ? $errors : false;
}

?>