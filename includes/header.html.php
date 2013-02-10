<?php
/*
* This is included in every page and starts the html document and includes the CSS and jQuery
*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/functions.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="/eventsdb/css/default.css" />
	<link type="text/css" href="css/smoothness/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.21.custom.min.js"></script>
    <title><?php echo $title; ?></title>
</head>
<body>
	<div id="header">
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/menu.html.php'; ?>
	</div>