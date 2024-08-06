<?php 
	
session_start();

global $urlReturn;
$urlReturn = "";
for ($i = 0; $i < $urlLvl; $i++) {
	$urlReturn = $urlReturn . "../";
}