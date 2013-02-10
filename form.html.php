<?php
//Create an array of months
$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'); 

include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/header.html.php';
?>


<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDO7rCYc9zu8VHwP17FW0VMtExIEkDf70M&sensor=false">
</script>

</script>

<script type="text/javascript" src="/eventsdb/js/googleMap.js"></script>


<div id="main">
	<div id="content">
		<h1><?php htmlprint($pagetitle); ?></h1>
		<?php if(isset($formError)) echo '<p class="error">' . $formError . '</p>' ?>
		<form id="eventForm" action="?<?php htmlprint($action); ?>" method="post">
			<div class="dataInput">
				<label for="title">Event title: <input type="text" name="title" id="title" required="required" value="<?php htmlprint($events['title']); ?>"></label>
			</div>
			<div class="dataInput">
				<label for="date">Event date:</label>
				<input type="text" name="date" id="date" required="required" value="<?php htmlprint($events['date']); ?>">
			</div>
			<div class="dataInput">
				<label for="starttime">Start time:</label>
				<?php optionslist(0, 23, 'starttimehr', $events['starttimehr'], 'PAD') ?>
				<?php optionslist(0, 59, 'starttimemin', $events['starttimemin'], 'PAD') ?>
			</div>
			<div class="dataInput">
				<label for="endtime">End time:</label>
				<?php optionslist(0, 23, 'endtimehr', $events['endtimehr'], 'PAD') ?>
				<?php optionslist(0, 59, 'endtimemin', $events['endtimemin'], 'PAD') ?>
			</div>
			<?php if(isset($events['location']) && $events['location']['lat'] != NULL) echo '<div class="dataInput editLocation">' . $events['location']['address']; else echo '<label for="locationCheck" class="dataInput">Set Location'; ?>
			<input id="locationCheck" type="<?php if(isset($events['location']) && $events['location']['lat'] != NULL) echo "Button"; else echo "Checkbox"; ?>" name="<?php if(isset($events['location']) && $events['location']['lat'] != NULL) echo "locationChange"; else echo "locationCheck"; ?>" value="<?php if(isset($events['location']) && $events['location']['lat'] != NULL) echo "Change Location"; else echo "Set Location"; ?>" onclick="initialize()" >
			<?php if(isset($events['location']) && $events['location']['lat'] != NULL) echo '</div>'; else echo '</label>'; ?>
			<div id="locationFinder" class="dataInput">
				<div id="map">
					<div>
						<input id="address" type="textbox" value="<?php if(isset($events['location']) && $events['location']['lat'] != NULL) echo $events['location']['address']; else echo "Insert address here"; ?>" >
						<input type="button" value="Search" onclick="codeAddress(-1)"><br />
					</div>
					<div id="map_canvas" style="width: 500px; height: 300px;">
					</div>
					<div class="addressInput">
						Feel free to rename the address as you see fit: <input id="formatted_address" name="address" type="textbox" value="<?php if(isset($events['location']) && $events['location']['lat'] != NULL) echo $events['location']['address']; else echo "NULL"; ?>">
					</div>
				</div>
				<div id="addressOptions">
					<table>
						<tbody id="results">
						</tbody>
					</table>
				</div>
			</div>
			<div class="dataInput">
				<label for="desc">Brief Description:</label>
				<textarea id="desc" name="desc" rows="3" cols="40"><?php htmlprint($events['description']); ?></textarea>
			</div>
			<div class="dataInput">
				<input id="latitude" name="latitude" type="hidden" value="<?php if(isset($events['location']) && $events['location']['lat'] != NULL) echo $events['location']['lat']; else echo "NULL"; ?>">
				<input id="longitude" name="longitude" type="hidden" value="<?php if(isset($events['location']) && $events['location']['lat'] != NULL) echo $events['location']['lon']; else echo "NULL"; ?>">
				<input type="hidden" name="id" value="<?php	htmlprint($id); ?>">
				<input type="submit" value="<?php htmlprint($button); ?>">
			</div>
		</form>
	<script type="text/javascript">
		$('#date').datepicker({dateFormat: "dd/mm/yy"});
		var startLat = $('#latitude').val();
		var startLon = $('#longitude').val();
	</script>
	</div>
</div>

<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/footer.html.php';
?>