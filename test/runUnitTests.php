<?php 

$testers = array(
	".shared"=>array(
		".templates"=>array(
			"opener.tp.php"=>"(new OpenerTpTest())"
		)
	)/*
	,"login"=>array(
		"login-contr.class.php"=>"(new LoginContrTest())"
		,"login.class.php"=>"(new LoginTest())"
		,"login.inc.php"=>"(new LoginIncTest())"
		,"register-contr.class.php"=>"(new RegisterContrTest())"
		,"register.class.php"=>"(new RegisterTest())"
		,"register.inc.php"=>"(new RegisterIncTest())"
	)
	,"user"=>array(
		"add"=>array(
			"lego"=>array(
				"lego-contr.class.php"=>"(new LegoContrTest())"
				,"lego.class.php"=>"(new LegoTest())"
				,"lego.inc.php"=>"(new LegoIncTest())"
			)
			,"list"=>array(
				"list-contr.class.php"=>"(new ListContrTest())"
				,"list.class.php"=>"(new ListTest())"
				,"list.inc.php"=>"(new ListIncTest())"
			)
		)
	)*/
);

function runTesters($testers, $path = array()) {

	$results = array();

	foreach ($testers as $key => $value):
		if (is_array($value)):
			$results[$key] = runTesters($value, array_merge($path, array($key)));
		else:
			include(join("/",$path) . "/" . $key);
			$results[$key] = $tester->runTests();
		endif;
	endforeach;
	
	return $results;
}

function printResults($results, $path = array()) {

	foreach ($results as $key => $value):
		if (preg_match("/^.+Test$/", $key)):
			echo $key . ": ";
			print_r($value);
			echo "<br>";
		else:
			echo $key . "<br>";
			printResults($value, array_merge($path, array($key)));
		endif;
	endforeach;
}

print_r($testers);

echo "<br><br>";

$results = runTesters($testers);
print_r($results);

echo "<br><br>";

echo "Unit Tests:" . "<br>";
printResults($results);

// radio type, id, checked, disabled, (label for(id))