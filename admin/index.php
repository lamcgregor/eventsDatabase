<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/db.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/functions.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/access.inc.php';

//Check the user has logged in else show login page
if (!userIsLoggedIn()) {
	$title = 'Log in';
	include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/login.html.php';
	exit();
}

//Check user has one of the admin roles else access denied
if (!userHasRole('Super Admin') && !userHasRole('Admin')) {
	errorReport('Access denied', 'Only Admins may access this page.');
}

//This redirect is merely to stop the index page trying to "resubmit" the logging in form (if the user refreshes on the first time they reach the index page)
if(isset($_GET['login'])) {
	header('Location: .');
}

if (isset($_POST['action']) && $_POST['action'] == 'Reset') {
	$id = strip($link, $_POST['id']);
	$result = mysql_query("SELECT name, email, users.id FROM users WHERE users.id='$id'", $link);
	if (!$result)
	{
		errorReport('Functional error', 'Error getting user from database for editing.' . mysql_error($link));
	}
	while ($row = mysql_fetch_array($result))
	{
		$users = array(
		'name' => $row['name'],
		'email' => $row['email'],
		'id' => $row['id'],
		'password' => ''
		);
	}

	$pagetitle = 'Reset user password';
	$action = 'resetpass';
	$button = 'Reset Password';
	include 'form.html.php';
	exit();
}
if (isset($_GET['resetpass'])) {
	$name = strip($link, $_POST['name']);
	$email = strip($link, $_POST['email']);
	$id = strip($link, $_POST['id']);
	$result = mysql_query("SELECT roles.id FROM users INNER JOIN roleid ON id=userid INNER JOIN roles ON roleid.roleid = roles.id WHERE users.id ='$id'", $link);
	if (!$result)
	{
		errorReport('Functional error', 'Error fetching checking user before deletion');
	}
	while ($row = mysql_fetch_array($result))
	{
		$usersid = $row['id'];
	}
	if($usersid == 1 && !userHasRole('Super Admin')) {
		errorReport('Access denied', 'Only Super Admins can delete a Super Admin user.');
	}
	$password = strip($link, $_POST['password']);
	$password = md5($password . 'evdb');
	$sql = "UPDATE users SET
				password='$password'
				WHERE id='$id'";
	if (!mysql_query($sql, $link))
	{
		errorReport('Functional error', 'Error adding user to database.' . mysql_error($link));
	}
	header('Location: .');
	exit();
}


if (isset($_GET['add'])) {
	$pagetitle = $title = 'Add a user';
	$action = 'adduser';
	$users['name'] = $users['email'] = $users['id'] = $users['password'] = '';
	$users['role'] = 0;
	$button = 'Add user';
	$result = mysql_query("SELECT functions FROM roles ORDER BY id", $link);
	if (!$result)
	{
		errorReport('Functional error', 'Error getting available roles from database.' . mysql_error($link));
	}
	while ($row = mysql_fetch_array($result))
	{
		$functions[] = $row['functions'];
	}
	include 'form.html.php';
	exit();
}


if (isset($_GET['adduser'])) {

	$name = strip($link, $_POST['name']);
	$email = strip($link, $_POST['email']);
	$password = strip($link, $_POST['password']);
	$password = md5($password . 'evdb');
	$role = $_POST['roles'][0];
	if($role == 1 && !userHasRole('Super Admin')) {
		$title = 'Access denied';
		$error = 'Only Super Admins can create a Super Admin user.';
		include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/accessdenied.html.php';
		exit();
	}
	$sqlOne = "INSERT INTO users (name, email, password)
			VALUES ('$name','$email','$password')";
	if (!mysql_query($sqlOne, $link))
	{
		errorReport('Functional error', 'Error adding user to database.' . mysql_error($link));
	}
	$lastId = mysql_insert_id($link);
	$sqlTwo = "INSERT INTO roleid (userid, roleid) VALUES ('$lastId','$role')";
	if (!mysql_query($sqlTwo, $link))
	{
		errorReport('Functional error', 'Error adding user to database.' . mysql_error($link));
	}
	header('Location: .');
	exit();
}

if (isset($_POST['action']) && $_POST['action'] == 'Edit') {
	$id = strip($link, $_POST['id']);
	$result = mysql_query("SELECT name, email, users.id, roleid.roleid FROM users INNER JOIN roleid ON id=userid INNER JOIN roles ON roleid=roles.id WHERE users.id='$id' GROUP BY users.id", $link);
	if (!$result)
	{
		errorReport('Functional error', 'Error getting user from database for editing.' . mysql_error($link));
	}
	while ($row = mysql_fetch_array($result))
	{
		$users = array(
		'name' => $row['name'],
		'email' => $row['email'],
		'id' => $row['id'],
		'role' => $row['roleid']
		);
	}
	$result = mysql_query("SELECT functions FROM roles ORDER BY id", $link);
	if (!$result)
	{
		errorReport('Functional error', 'Error getting available roles from database.' . mysql_error($link));
	}
	while ($row = mysql_fetch_array($result))
	{
		$functions[] = $row['functions'];
	}
	$pagetitle = $title = 'Edit a user';
	$action = 'edituser';
	$button = 'Submit changes';
	include 'form.html.php';
	exit();
}

if (isset($_GET['edituser'])) {

	$name = strip($link, $_POST['name']);
	$email = strip($link, $_POST['email']);
	$id = strip($link, $_POST['id']);
	$role = $_POST['roles'][0];
	if($role == 1 && !userHasRole('Super Admin')) {
		errorReport('Access denied', 'Only Super Admins can give Super Admin user permissions.');
	}
	$prevRole = $_POST['prevRole'];
	$sql = "UPDATE users SET
				name='$name',
				email='$email'
				WHERE id='$id'";
	if($role != $prevRole) {
		$sqlOne = "DELETE FROM roleid WHERE userid='$id'";
		$sqlTwo = "INSERT INTO roleid (userid, roleid) VALUES ('$id','$role')";
		if (!mysql_query($sqlOne, $link) || !mysql_query($sqlTwo, $link))
		{
			errorReport('Functional error', 'Error changing roles on the database.' . mysql_error($link));
		}
	}
	if (!mysql_query($sql, $link))
	{
		errorReport('Functional error', 'Error adding user to database.' . mysql_error($link));
	}
	header('Location: .');
	exit();
}

if (isset($_POST['action']) && $_POST['action'] == 'Delete') {
	$id = strip($link, $_POST['id']);
	$result = mysql_query("SELECT roles.id FROM users INNER JOIN roleid ON id=userid INNER JOIN roles ON roleid.roleid = roles.id WHERE users.id ='$id'", $link);
	if (!$result)
	{
		errorReport('Functional error', 'Error fetching checking user before deletion' . mysql_error($link));
	}
	while ($row = mysql_fetch_array($result))
	{
		$usersid = $row['id'];
	}
	if($usersid == 1 && !userHasRole('Super Admin')) {
		errorReport('Access denied', 'Only Super Admins can delete a Super Admin user.');
	}
	$sql = "DELETE users, roleid FROM users INNER JOIN roleid ON id=userid WHERE id='$id'";
	if (!mysql_query($sql, $link))
	{
		errorReport('Functional error', 'Error deleting user.' . mysql_error($link));
	}
	header('Location: .');
	exit();
} 

$result = mysql_query("SELECT name, email, users.id, functions FROM users INNER JOIN roleid ON id=userid INNER JOIN roles ON roleid.roleid = roles.id ORDER BY id ASC", $link);
if (!$result)
{
	errorReport('Functional error', 'Error fetching users from database');
}

while ($row = mysql_fetch_array($result))
{
	$users[] = array(
		'name' => $row['name'],
		'email' => $row['email'],
		'id' => $row['id'],
		'role' => $row['functions']
	);
}

if(isset($users)) {
	$title = 'Admin panel';
	include 'display.html.php';
}
else {
	echo 'There are no users, apologies. Perhaps <a href="?add">add some</a>.';
}


?>