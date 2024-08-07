<?php 

include("runUnitTests.class.php");

$RUT = new RunUnitTests();

print_r($RUT->getTesters());

echo "<br><br>";

$results = $RUT->runTesters($RUT->getTesters());
print_r($results);

echo "<br><br>";

echo "Unit Tests:" . "<br>";
$RUT->printResults($results);
