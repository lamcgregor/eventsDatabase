<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/header.html.php';
?>

<div id="main">
	<h1>Event Display</h1>
	<div id="content">
		<div id="eventsDisplay">
					<form action="" method="post">
						<div>
							<h2><?php htmlprint($event['title']); ?> - <?php htmlprint($event['day'] . "/" . $event['month'] . "/" . $event['year'])?>
							<input type="submit" name="action" value="Edit">
							<input type="submit" name="action" value="Delete">
							</h3>
							<?php if(isset($event['location']) && $event['location']['lat'] != NULL) {
							echo '<img class="locationImg" src="http://maps.googleapis.com/maps/api/staticmap?center=' . $event['location']['lat'] . ',' . $event['location']['lon'] . '&zoom=15&size=350x200&markers=' . $event['location']['lat'] . ',' . $event['location']['lon'] . '&sensor=false">';
							} ?>
							<div id="eventData">
								<p>Location: <?php htmlprint($event['location']['address']);?></p>
								<p>Time: <em><?php htmlprint($event['starttime']);?>-<?php htmlprint($event['endtime']);?></em></p>
								<p>Description: <?php htmlprint($event['description']); ?></p>
								<input type="hidden" name="id" value="<?php	echo $event['id']; ?>">
							</div>
						</div>
					</form>
			<a class="button" href="?add">Add a new event</a>
		</div>
		<?php $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');  ?>
		<div id="calendarSearch">
			<h2>Back to calendar</h2>
			<form action="?calendar" method="post">
				<div>
					<label for="name">Month to view:</label>
					<select name="month">
						<?php for($i = 0; $i < count($months); $i++): ?>
							<option value="<?php echo $i + 1; ?>"<?php
							if(isset($_SESSION['month'])) {
								if($i + 1 == $_SESSION['month']) echo ' selected="selected"'; 
							}
							else if ($i + 1 == date('m')) {
								echo ' selected="selected"'; 
							}
							?>><?php echo $months[$i]; ?></option>
						<?php endfor; ?>
					</select>
					<?php 
					if(isset($_SESSION['year'])) {
						$year = $_SESSION['year'];
					}
					else {
						$year = date('Y');
					}
					optionslist(date('Y'), date('Y') + 10, 'year', $year, 'NO-PAD') ?>
					<input class="button" type="submit" value="View Calendar">
				</div>
			</form>
		</div>
	</div>
</div>

<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/footer.html.php';
?>