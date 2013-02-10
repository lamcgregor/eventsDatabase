<?php include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/header.html.php'; ?>
<div id="main">
	<span class="welcome">Welcome<?php if(isset($_SESSION['name'])) htmlprint(', ' . $_SESSION['name']); ?></span>
	<h1><?php echo $title; ?></h1>
	<div id="content">
		<a class="button" href="?add">Add user</a>
		<h2>List of users:</h2>
		<ul>
			<?php foreach ($users as $user): ?>
				<li>
					<form action="" method="post">
						<div>
							<p><?php htmlprint($user['name']); ?> - <?php  htmlprint($user['role']) ?> - <?php htmlprint($user['email'])?>
							<?php if($user['role'] == 'Super Admin' && !userHasRole('Super Admin')) {
								echo '<em>Onlys Super Admins can edit other Super Admins</em>';
							}
							else {
							echo'
							<input type="submit" name="action" value="Edit">
							<input type="submit" name="action" value="Delete">';
							}
							 ?>
							<?php if(userHasRole('Super Admin')) : ?>
							<input type="submit" name="action" value="Reset">								
							<?php endif; ?>
							<input type="hidden" name="id" value="<?php	echo $user['id']; ?>">
							</p>
						</div>
					</form>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>

</div>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/footer.html.php'; ?>	