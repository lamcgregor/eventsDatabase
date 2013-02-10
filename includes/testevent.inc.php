<?php
/*

This file deals with both adding and editing events.

The first section deals with the initial request to add an event or edit an event and gets the event information (in the event of editing) or creates blank information (for adding an event). It then pulls in the form HTML which is populated with the gathered (or created) event information.

The second section deals with validating the submitted event form and then placing this information in to the database.

A note about the variable $id; in the case of adding an event this refers to the user's id which is constant. If the action is editing an event this refers to the events unique id which is used to update the single event.

*/

//Create form pages for adding/editing events
if (isset($_GET['add']) || (isset($_POST['action']) && $_POST['action'] == 'Edit')) {
	//Blank info for adding an event
	if (isset($_GET['add'])) {
		$pagetitle = $title = 'Add an event';
		$action = 'addev';
		$events['title'] = $events['description'] = $events['date'] = '';
		$events['endtimehr'] = $events['starttimehr'] = date('H') + 1;
		$events['endtimemin'] = $events['starttimemin'] = round(date('i'),-1);
		$events['day'] = date('j');
		$events['month'] = date('m');
		$events['year'] = date('Y');
		$id = $_SESSION['id'];
		$button = 'Add event';
	}
	//Get event info from database for editing
	else {
		$id = strip($link, $_POST['id']);
		$result = mysql_query("SELECT * FROM events WHERE id='$id'", $link);
		if (!$result)
		{
			errorReport('Functional error', 'Error getting event from database for editing.');
		}
		while ($row = mysql_fetch_array($result))
		{
			$start = explode(':', $row['startTime']);
			$end = explode(':', $row['endTime']);
			$events = array(
				'title' => $row['title'],
				'date' => $row['day'] . '/' . $row['month'] . '/' .  $row['year'],
				'starttimehr' => $start[0],
				'starttimemin' => $start[1],
				'endtimehr' => $end[0],
				'endtimemin' => $end[1],
				'description' => $row['description'],
				'location' => array (
						'lat' => $row['latitude'],
						'lon' => $row['longitude'],
						'address' => $row['formatted_address']
					),
				'id' => $row['id']
			);
		}
		$pagetitle = $title = 'Edit event';
		$action = 'editev';
		$button = 'Submit changes';
	}
	//Pull in form HTML
	include 'form.html.php';
	exit();
}
//Validate and add the newly created/edited event to the database
if (isset($_GET['addev']) || isset($_GET['editev'])) {
	//Validating function see /includes/functions.inc.php
	$formError = validateEvent($_POST);
	//Adding an event also checks that the date is in the future
	if (isset($_GET['addev'])) {
		$formError .= futureEvent($_POST);
	}
	//If the event data fails the validation pass it back to the form for editing
	if($formError != false) {
		$error = $formError;
		$events = array(
			'title' => $_POST['title'],
			'date' => $_POST['date'],
			'starttimehr' => $_POST['starttimehr'],
			'starttimemin' => $_POST['starttimemin'],
			'endtimehr' => $_POST['endtimehr'],
			'endtimemin' => $_POST['endtimemin'],
			'description' => $_POST['desc']
		);
		if($_POST['latitude'] != 'NULL') {
		$events += array(
				'location' => array (
					'lat' => $_POST['latitude'],
					'lon' => $_POST['longitude'],
					'address' => $_POST['address']
				)
			);
		}
		else {
		$events += array(
				'location' => array (
					'lat' => NULL,
					'lon' => NULL,
					'address' => NULL
				)
			);
		}
		//Title and form action for adding an event
		if (isset($_GET['addev'])) {
			$pagetitle = $title = 'Add an event';
			$action = 'addev';
			$button = 'Add event';
		}
		//Title and form action for editing an event
		if (isset($_GET['editev'])) {
			$pagetitle = $title = 'Edit an event';
			$action = 'editev';
			$button = 'Submit changes';
		}
		$id = $_POST['id'];
		include 'form.html.php';
		exit();
	}
	else {
		$title = strip($link, $_POST['title']);
		$date = explode('/',$_POST['date']);
		$day = ltrim($date[0], "0");
		$month = ltrim($date[1], "0");
		$year = $date[2];
		$description = strip($link, $_POST['desc']);
		$id = $_POST['id'];
		$starttime = $_POST['starttimehr'] . ':' . $_POST['starttimemin'];
		$endtime = $_POST['endtimehr'] . ':' . $_POST['endtimemin'];
		if(isset($_GET['editev'])) {
			$location['lat'] = round($_POST['latitude'], 11);
			$location['lon'] = round($_POST['longitude'], 11);
			$location['address'] = $_POST['address'];
			$result = mysql_query("SELECT latitude, longitude, formatted_address FROM events WHERE id='$id'", $link);
			if (!$result)
			{
				errorReport('Functional error', 'Error checking event location on database.' . mysql_error($link));
			}
			while ($row = mysql_fetch_array($result))
			{
				$old = array(
					'lat' => $row['latitude'],
					'lon' => $row['longitude'],
					'address' => $row['formatted_address']
				);
			}
			//Work out the difference between the old and new addresses
			$difference = array_diff($location,$old);
			$new = array_merge($old,$difference);
			$lat = $new['lat'];
			$lon = $new['lon'];
			$address = $new['address'];
		}
		
		
		if($_POST['latitude'] != 'NULL') {
			$lat = strip($link, $_POST['latitude']);
			$lon = strip($link, $_POST['longitude']);
			$address = strip($link, $_POST['address']);
			if(isset($_GET['editev'])) {
				$sql = "UPDATE events SET ";
			}
			else {
				$sql = "INSERT INTO events SET
						userid='$id', ";
			}
			$sql .= "title='$title',
				day='$day',
				month='$month',
				year='$year',
				startTime='$starttime',
				endTime='$endtime',
				description='$description',
				latitude='$lat',
				longitude='$lon',
				formatted_address='$address'";
			if(isset($_GET['editev'])) {
				$sql .= " WHERE id='$id'";
			}
		}
		else {
			if(isset($_GET['editev'])) {
				$sql = "UPDATE events SET ";
			}
			else {
				$sql = "INSERT INTO events SET
						userid='$id',";
			}
			$sql .= "title='$title',
					day='$day',
					month='$month',
					year='$year',
					startTime='$starttime',
					endTime='$endtime',
					description='$description'";
			if(isset($_GET['editev'])) {
				$sql .= " WHERE id='$id'";
			}
		}
		if (!mysql_query($sql, $link))
		{
			errorReport('Functional error', 'Error adding event to database.');
		}
		header('Location: .');
		exit();
	}
}
?>