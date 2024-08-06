<?php 

// set urlReturn
$urlLvl = 1;
include('../.shared/.templates/opener.tp.php');

// Check if data was submit through post
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	// Grabbing data
	$usna = htmlspecialchars($_POST["usna"], ENT_QUOTES, 'UTF-8');
	$pwd = htmlspecialchars($_POST["pwd"], ENT_QUOTES, 'UTF-8');

	// Instantiate LoginContr Class
	if(!class_exists('Dbh')) {
		include($urlReturn . ".shared/.classes/dbh.classes/dbh.class.php");
	}
	include("login.class.php");
	include("login-contr.class.php");
	$login = new LoginContr($usna, $pwd);

	// Running error handlers and user login
	$login->loginUser();

	// Send user to dashboard page
	header("location: " . $urlReturn . "user/dashboard/");
}
else
{
	// Send user to home page
	header("location: " . $urlReturn);
}
