<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/header.html.php';
?>

<div id="main">
	<div id="content">
		<h1><?php htmlprint($pagetitle); ?></h1>
		<form action="?<?php htmlprint($action); ?>" method="post">
			<div>
				<label for="name">User's name: 
				<input type="text" name="name" id="name" value="<?php htmlprint($users['name']); ?>">
				</label>
			</div>
			<div>
				<label for="email">Email: 
				<input type="text" name="email" id="email" value="<?php htmlprint($users['email']); ?>">
				</label>
			</div>
			<?php if(isset($users['password'])) : ?>
			<div>
				<label for="password">Password: 
				<input type="password" name="password" id="password" value="">
				</label>
			</div>
			<?php endif; ?>
			<?php if(isset($users['role'])) { 
					$i = 0;
					echo '<div><label for="role">Role: </label><ul>';
					foreach ($functions as $function) {
						if($i == 0 && !userHasRole('Super Admin')) {
						}
						else {
							echo'<li><input type="radio" name="roles[]" id="' . $function . '" value="' . ($i + 1) . '"';
							if($users['role'] == ($i + 1)) {
								echo ' checked="checked"';
							}
							echo '>' . $function . '</li>';
						}
							$i++;
					}
					echo '</ul></div><input type="hidden" name="prevRole" value="' . $users['role'] . '">';
				} ?>
				<div>
					<input type="hidden" name="id" value="<?php	htmlprint($users['id']); ?>">
					<input type="submit" value="<?php htmlprint($button); ?>">
				</div>
		</form>
	</div>
</div>

<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/eventsdb/includes/footer.html.php';
?>
