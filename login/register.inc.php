<?php 

// Check if data was submit through post
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	// Grabbing data
	$name = htmlspecialchars($_POST["name"], ENT_QUOTES, 'UTF-8');
	$email = htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8');
	$usna = htmlspecialchars($_POST["usna"], ENT_QUOTES, 'UTF-8');
	$pwd = htmlspecialchars($_POST["pwd"], ENT_QUOTES, 'UTF-8');
	$pwd2 = htmlspecialchars($_POST["pwd2"], ENT_QUOTES, 'UTF-8');

	// Instantiate RegisterContr Class
	include("../.classes/dbh.class.php");
	include("../.classes/register.class.php");
	include("../.classes/register-contr.class.php");
	$register = new RegisterContr($name, $email, $usna, $pwd, $pwd2);

	// Running error handlers and user signup
	$register->registerUser();

	// Login User
	include("login.inc.php");
}
else
{
	// Send user back to home page
	header("location: ../");
}
