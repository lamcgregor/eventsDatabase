<?php
/*
* This page serves as the main landing page.
* All requests are handled here apart from those relating to the admin tab (which are handled with the admin/index.php file).
* The other files are all included as they are needed.
*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/functions.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/db.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/access.inc.php';

//Check the user has logged in, else show login page
if (!userIsLoggedIn()) {
	$title = 'Log in';
	include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/login.html.php';
	exit();
}

//Check user has one of the available roles, else access denied
if (!userHasRole('User') && !userHasRole('Admin') && !userHasRole('Super Admin')) {
	errorReport('Functional error', 'Your must be a User or Admin to access this page.');
}

//This redirect is merely to stop the index page trying to "resubmit" the logging in form (if the user refreshes on the first time they reach the index page)
if(isset($_GET['login'])) {
	header('Location: .');
}

//If calendar page is asked for display it
if (isset($_GET['calendar'])) {
	//If they've asked for a specific month display it
	if (isset($_POST['month']) && $_POST['month'] != '') {
		$month = $_POST['month'];
		$year = $_POST['year'];
	}
	//Else display the last month shown
	elseif(isset($_SESSION['month']) && $_SESSION['month'] != '') {
		$month = $_SESSION['month'];
		$year = $_SESSION['year'];
	}
	//Else show the current month
	else {
		$month = date('m');
		$year = date('Y');
	}
	$title = 'Calendar - ' . date('F',mktime(0,0,0,$month,1,$year)) . ' ' . $year; //Sets title i.e. Calendar - January 2013
	include 'calendar.html.php';
	exit();
}

// ------- ADDING EVENTS -------
//If adding an event include the testevent include. The testevent include deals with both adding and editing events.
if (isset($_GET['addev']) || isset($_GET['add'])) {
	include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/testevent.inc.php';
}

// ------- EDITING EVENTS -------
//If editing an event include the testevent include.
if ((isset($_POST['action']) && $_POST['action'] == 'Edit') || isset($_GET['editev'])) {
	include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/testevent.inc.php';
}


// ------- DELETING EVENTS -------
if (isset($_POST['action']) && $_POST['action'] == 'Delete') {
	$id = strip($link, $_POST['id']);
	$sql = "DELETE FROM events WHERE id='$id'";
	if (!mysql_query($sql, $link))
	{
		errorReport('Functional error', 'Error deleting event.');
	}
	header('Location: .');
	exit();
} 
// ------- VIEWING EVENTS -------
if (isset($_GET['view'])) {
	$id = $_GET['view'];
	$result = mysql_query("SELECT * FROM events WHERE id='$id'", $link);
	if (!$result)
	{
		errorReport('Functional error', 'Error fetching events from database');
	}
	while ($row = mysql_fetch_array($result))
	{
		$event = array(
				'title' => $row['title'],
				'day' => $row['day'],
				'month' => $row['month'],
				'year' => $row['year'],
				'starttime' => $row['startTime'],
				'endtime' => $row['endTime'],
				'description' => $row['description'],
				'location' => array (
							'lat' => $row['latitude'],
							'lon' => $row['longitude'],
							'address' => $row['formatted_address']
						),
				'id' => $row['id']
			);
	}
	$title = 'Event Display';
	include 'eventdisplay.html.php';
	exit();
}

// ------- DEFAULT TO MAIN PAGE -------
else {
	$id = $_SESSION['id'];
	$name = strip($link, $_SESSION['name']);
	//Select all events related to logged in user
	$result = mysql_query("SELECT * FROM events WHERE userid='$id' ORDER BY year ASC, month ASC, day ASC", $link);
	if (!$result)
	{
		errorReport('Functional error', 'Error fetching events from database');
	}

	while ($row = mysql_fetch_array($result))
	{
		if(futureTime($row['year'],$row['month'],$row['day'],24,0)) {
			$events[] = array(
				'title' => $row['title'],
				'day' => $row['day'],
				'month' => $row['month'],
				'year' => $row['year'],
				'starttime' => $row['startTime'],
				'endtime' => $row['endTime'],
				'description' => $row['description'],
				'location' => $row['formatted_address'],
				'id' => $row['id']
			);
		}
		else {
			$pastEvents[] = array(
				'title' => $row['title'],
				'day' => $row['day'],
				'month' => $row['month'],
				'year' => $row['year'],
				'starttime' => $row['startTime'],
				'endtime' => $row['endTime'],
				'description' => $row['description'],
				'location' => $row['formatted_address'],
				'id' => $row['id']
			);
		}
	}
	//Have past events shown in reverse order so the most recent one is first
	if(isset($pastEvents)) $pastEvents = array_reverse($pastEvents, TRUE);
	if(isset($events) || isset($pastEvents)) {
		$preset['month'] = date('m');
		$preset['year'] = date('Y');
		$title = 'Dashboard';
		include 'display.html.php';
	}
	else {
		$title = 'Dashboard';
		include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/header.html.php'; ?>
			<div id="main">
				<div id="content">
					<p class="noEvents">There are no events, apologies. Perhaps <a href="?add">add some</a>.</p>
				</div>
			</div>
		</body>
		</html>
		<?php
	}
}
?>