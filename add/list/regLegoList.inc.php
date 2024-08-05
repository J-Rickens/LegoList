<?php 

// Check if data was submit through post
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	// Grabbing data
	$listName = htmlspecialchars($_POST["listName"], ENT_QUOTES, 'UTF-8');
	$pubPri = htmlspecialchars($_POST["pubPri"], ENT_QUOTES, 'UTF-8');
	$uid = htmlspecialchars($_POST["uid"], ENT_QUOTES, 'UTF-8');

	// Instantiate ListContr Class
	if(!class_exists('Dbh')) {
		include("../.classes/dbh.class.php");
	}
	include("../.classes/legoList.class.php");
	include("../.classes/legoList-contr.class.php");
	$legoList = new legoListContr($listName, $pubPri, $uid);

	// Running error handlers and create list
	$legoList->addLegoList();

	// Send user to dashboard page
	header("location: ../dashboard/");
}
else
{
	// Send user to home page
	header("location: ../");
}