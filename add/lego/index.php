<?php 

	$urlLvl = 2;
	$urlTitle = "AddLego";
	include('../../.templates/opener.tp.php');

	if (!isset($_SESSION['uid'])) {
		header("location: ". $urlReturn ."login");
	}

 ?>


 <!DOCTYPE html>
 <html>

 	<?php include($urlReturn . '.templates/header.tp.php'); ?>

	<!--start--><p>hi</p>

	<?php include($urlReturn . '.templates/footer.tp.php'); ?>

 </html>