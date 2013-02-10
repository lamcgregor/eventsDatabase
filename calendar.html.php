<?php
//Save month and year variables to session
if(isset($_POST['month'])) {
$_SESSION['month'] = $_POST['month'];
$_SESSION['year'] = $_POST['year'];
}

//Include the calendar creating function
include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/calendarDisplay.inc.php'; 

//Begin HTML page
include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/header.html.php';
?>

<div id="main">
<?php 

//Print the month and year being shown
print("<h1>" . date("F", mktime(0,0,0,$month,1,$year)) . " " . $year . "</h1>");

?>
	<div id="content">
		<div id="calendar">
			<?php
			calendarDisplay($month, $year, $link);

			echo '<a href="/eventsdb">Return to home</a>';
			?>

		</div>
	</div>
	<div class="clear"></div>
</div>

<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/footer.html.php';
?>
