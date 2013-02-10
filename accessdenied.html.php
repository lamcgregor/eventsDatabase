<!--This page is shown when a user attempts to access a page they do not have the role for (such as a User accessing the admin page)-->

<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/header.html.php';
?>

<div id="main">
	<h1>Access Denied</h1>
	<div id="content">
		<p><?php echo htmlprint($error); ?></p>
		<a href="/eventsdb/">Return to home</a>
	</div>
</div>

<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/footer.html.php';
?>
