<?php
	if (!session_id())@session_start();

	ini_set('display_errors', 'On');
	require_once("header.php");
	require_once("php_classes/DBManager.php");

?>

<div class="container">
	<div class="col-md-6 well" style="margin:20px auto; float:none;">
		<form action="process_login.php" method="post">
			<?php if (isset($_SESSION['errors'])) : ?>
				<p><?php echo $_SESSION['errors']; $_SESSION['errors'] = NULL; ?></p>
			<?php endif ?>
			<div class="form-group">
				<label class="control-label" for="email">Email: </label>
				<input class="form-control" id="email" type="text" name="email">
			</div>
			<div class="form-group">
				<label class="control-label" for="password">Password: </label>
				<input class="form-control" id="password" type="password" name="password">
			</div>

			<button class="btn btn-success" id = "loginbutton" type="submit">Login</button>

		</form>
		<br>
		<a href="forgot_password.php" id="forgot">Fogrot your password? Click here</a>
	</div>
</div>


</body>
</html>
