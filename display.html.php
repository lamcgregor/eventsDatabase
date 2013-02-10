<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/header.html.php';
?>
<div id="main">

	<span class="welcome">Welcome<?php if(isset($name)) htmlprint(', ' . $name); ?></span>
	<h1><?php echo $title; ?></h1>
	<div id="content">
		<a class="button" href="?add">Add a new event</a>
		<div id="eventsDisplayFuture">
			<h2>Future Events</h2>
			<ul class="eventsList">
				<?php if(isset($events)) : foreach ($events as $event): ?>
					<li class="events">
						<form action="" method="post">
							<div>
								<span class="eventDate"><?php htmlprint($event['day'] . "." . $event['month'] . "." . $event['year'])?></span>
								<h3><a class="event" href="?view=<?php echo $event['id']; ?>"><?php htmlprint($event['title']); ?></a></h3>
								<p><?php htmlprint($event['starttime']);?>-<?php htmlprint($event['endtime']);?></p>
								<p><b><?php htmlprint($event['location']); ?></b></p>
								<p><?php htmlprint($event['description']); ?></p>


							</div>
						</form>
					</li>
				<?php endforeach;
				endif;
				if(!isset($events)) : ?><p class="noEvents">There are no future events, apologies. Perhaps <a href="?add">add some</a>?</p><?php endif;?>
			</ul>
		</div>
		
		<?php	
		/*
		<input type="hidden" name="id" value="echo $event['id']; ">
		<input type="submit" name="action" value="Edit">
		<input type="submit" name="action" value="Delete">
		*/
		?>
		
		<div id="eventsDisplayPast">
			<h2>Past Events</h2>
			<ul class="eventsList">
				<?php if(isset($pastEvents)) : foreach ($pastEvents as $event): ?>
					<li class="events">
						<form action="" method="post">
							<div>
								<span class="eventDate"><?php htmlprint($event['day'] . "." . $event['month'] . "." . $event['year'])?></span>
								<h3><a class="event" href="?view=<?php echo $event['id']; ?>"><?php htmlprint($event['title']); ?></a></h3>
								<p><?php htmlprint($event['starttime']);?>-<?php htmlprint($event['endtime']);?></p>
								<p><b><?php htmlprint($event['location']); ?></b></p>
								<p><?php htmlprint($event['description']); ?></p>
							</div>
						</form>
					</li>
				<?php endforeach; endif; ?>
			</ul>
		</div>
		<?php $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');  ?>
		<div id="calendarSearch">
			<h2>Calendar</h2>
			<form action="?calendar" method="post">
				<div>
					<label for="name">Month to view:</label>
					<select name="month">
						<?php for($i = 0; $i < count($months); $i++): ?>
							<option value="<?php echo $i + 1; ?>"<?php if($i + 1 == $preset['month']) echo ' selected="selected"'; ?>><?php echo $months[$i]; ?></option>
						<?php endfor; ?>
					</select>
					<?php optionslist(date('Y'), date('Y') + 10, 'year', $preset['year'], 'NO-PAD') ?>
					<input type="submit" class="button" value="View Calendar">
				</div>
			</form>
		</div>
	</div>
	<div class="clear">
	</div>
</div>


<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/footer.html.php';
?>