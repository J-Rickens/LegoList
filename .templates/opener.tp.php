<?php 
	
	session_start();

	$urlReturn = "";
	for ($i = 0; $i < $urlLvl; $i++) {
		$urlReturn = $urlReturn . "../";
	}