<?php
function userIsLoggedIn() {
	session_start();
	if (isset($_POST['action']) && $_POST['action'] == 'login') {
		if(!isset($_POST['email']) || $_POST['email'] == ''	|| !isset($_POST['password']) || $_POST['password'] == '') {
			$GLOBALS['loginError'] = 'Please fill in both fields';
			return FALSE;	
		}
		$password = md5($_POST['password'] . 'evdb');
		$userDeets = getUserDetails($_POST['email'], $password);
		if(isset($userDeets['id'])) {
			$_SESSION['loggedIn'] = TRUE;
			$_SESSION['email'] = $_POST['email'];
			$_SESSION['password'] = $password;
			$_SESSION['id'] = $userDeets['id'];
			$_SESSION['name'] = $userDeets['name'];
			return TRUE;
		}
		else {
			unset($_SESSION['loggedIn']);
			unset($_SESSION['email']);
			unset($_SESSION['password']);
			unset($_SESSION['id']);
			unset($_SESSION['name']);
			$GLOBALS['loginError'] = 'The specified email address or password was incorrect.';
			return FALSE;
		}
	}
	if (isset($_GET['logout'])) {
		unset($_SESSION['loggedIn']);
		unset($_SESSION['email']);
		unset($_SESSION['password']);
		unset($_SESSION['id']);
		unset($_SESSION['name']);
		header('Location: .' );
		exit();
	}
	if (isset($_SESSION['loggedIn'])) {
		getUserDetails($_SESSION['email'], $_SESSION['password']);
		if(isset($_SESSION)) {
			return TRUE;
		}
	}
	else {
		return FALSE;
	}
}

function getUserDetails($email, $password) {
	include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/db.inc.php';
	$email = strip($link, $email);
	$password = strip($link, $password);
	$sql = "SELECT id, name FROM users WHERE email='$email' AND password='$password'";
	$result = mysql_query($sql, $link);
	if(!$result) {
		errorReport('Functional error', 'Error searching for user.');
	}
	$row = mysql_fetch_array($result);
	if($row) {
		$userDeets['id'] = $row['id'];
		$userDeets['name'] = $row['name'];
		return $userDeets;
	}
	else {
		if(isset($_SESSION)) {
		unset($_SESSION['loggedIn']);
		unset($_SESSION['email']);
		unset($_SESSION['password']);
		unset($_SESSION['id']);
		unset($_SESSION['name']);
		}
		return FALSE;
	}
}

function userHasRole($role) {
	include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/db.inc.php';
	$email = strip($link, $_SESSION['email']);
	$role = strip($link, $role);
	$sql = "SELECT COUNT(*) FROM users
			INNER JOIN roleid ON users.id = roleid.userid
			INNER JOIN roles ON roleid.roleid = roles.id
			WHERE email ='$email' AND roles.functions ='$role'";
	$result = mysql_query($sql, $link);
	if (!$result) {
		errorReport('Functional error', 'Error searching for user roles.' . mysql_error($link));
	}
	$row = mysql_fetch_array($result);
	if ($row[0] > 0) {
		return TRUE;
	}
	else {
		return FALSE;
	}
}


?>