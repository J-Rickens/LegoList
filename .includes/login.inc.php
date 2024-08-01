<?php 

// Check if data was submit through post
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	// Grabbing data
	$usna = htmlspecialchars($_POST["usna"], ENT_QUOTES, 'UTF-8');
	$pwd = htmlspecialchars($_POST["pwd"], ENT_QUOTES, 'UTF-8');

	// Instantiate RegisterContr Class
	if(!class_exists('Dbh')) {
		include("../.classes/dbh.class.php");
	}
	include("../.classes/login.class.php");
	include("../.classes/login-contr.class.php");
	$login = new LoginContr($usna, $pwd);

	// Running error handlers and user signup
	$login->loginUser();

	// Send user to dashboard page
	header("location: ../dashboard/");
}
else
{
	// Send user to home page
	header("location: ../");
}
