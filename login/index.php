<?php 

	$urlLvl = 1;
	$urlTitle = "login/Register";
	include('../.templates/opener.tp.php');
	include($urlReturn . ".regex/login.regex.php");

 ?>


 <!DOCTYPE html>
 <html>

 	<?php include($urlReturn . '.templates/header.tp.php'); ?>

	<section>
		<div>
			<h4>Login</h4>
			<p>Existing Builders Login Here!</p>
			<form action="../.includes/login.inc.php" method="post">
				<input type="text" name="usna" placeholder="Username" required>
				<input type="password" name="pwd" placeholder="Password" required>
				<br>
				<button type="submit" name="submit">LOGIN</button>
			</form>
		</div>

		<div>
			<h4>Register</h4>
			<p>Don't have an account yet? Sign up here!</p>
			<form action="../.includes/register.inc.php" method="post">
				<input type="text" name="name" placeholder="Name" pattern="<?php echo LoginRegex::NAME; ?>" required title="<?php echo LoginRegex::NAMEDESCR; ?>">
				<input type="email" name="email" placeholder="Email Address" required>
				<input type="text" name="usna" placeholder="Username" pattern="<?php echo LoginRegex::USNA; ?>" required title="<?php echo LoginRegex::USNADESCR; ?>">
				<input type="password" name="pwd" placeholder="Password" pattern="<?php echo LoginRegex::PWD; ?>" required title="<?php echo LoginRegex::PWDDESCR; ?>">
				<input type="password" name="pwd2" placeholder="Repeat Password" required>
				<br>
				<button type="submit" name="submit">SIGN UP</button>
			</form>
		</div>
	</section>

	<?php include($urlReturn . '.templates/footer.tp.php'); ?>

 </html>