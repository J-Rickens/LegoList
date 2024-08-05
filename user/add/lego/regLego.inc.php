<?php 

// Check if data was submit through post
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	// Grabbing data
	$legoID = htmlspecialchars($_POST["legoID"], ENT_QUOTES, 'UTF-8');
	$pieceCount = htmlspecialchars($_POST["pieceCount"], ENT_QUOTES, 'UTF-8');
	$legoName = htmlspecialchars($_POST["legoName"], ENT_QUOTES, 'UTF-8');
	$collection = htmlspecialchars($_POST["collection"], ENT_QUOTES, 'UTF-8');
	$cost = htmlspecialchars($_POST["cost"], ENT_QUOTES, 'UTF-8');
	$uid = htmlspecialchars($_POST["uid"], ENT_QUOTES, 'UTF-8');

	// Instantiate RegisterContr Class
	if(!class_exists('Dbh')) {
		include("../.classes/dbh.class.php");
	}
	include("../.classes/lego.class.php");
	include("../.classes/lego-contr.class.php");
	$lego = new LegoContr($legoID, $pieceCount, $legoName, $collection, $cost);

	// Running error handlers and user signup
	$lego->addLego();

	// Send user to dashboard page
	header("location: ../dashboard/");
}
else
{
	// Send user to home page
	header("location: ../");
}
