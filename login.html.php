<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/header.html.php';
?>

<div id="main">
	<h1>Log In</h1>
	<div id="content">
		<div id="loginForm">	
			<p>Please log in to view the page that you requested.</p>
			<?php if (isset($loginError)): ?>
				<p><em><?php echo htmlprint($loginError); ?></em></p>
			<?php endif; ?>
			<form action="?login" method="post">
				<div>
					<label for="email">Email: <input type="email" name="email" id="email" ></label>
				</div>
				<div>
					<label for="password">Password: <input type="password" name="password" id="password" required="required" ></label>
				</div>
				<div>
					<input type="hidden" name="action" value="login">
					<input type="submit" value="Log in">
				</div>
			</form>
		</div>
		<div id="loginInfo">
			<p>If you're here to see the site's functionality please use the following login details:</p>
			<p>Email: <em>demo</em></p>
			<p>Password: <em>test</em></p>
		</div>
	</div>
</div>

<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/footer.html.php';
?>
