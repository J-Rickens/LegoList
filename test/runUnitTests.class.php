<?php 


class RunUnitTests {

	private $testers = array(
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

	public function getTesters() {
		return $this->testers;
	}

	public function runTesters($testers, $path = array()) {

		$results = array();

		foreach ($testers as $key => $value):
			if (is_array($value)):
				$results[$key] = $this->runTesters($value, array_merge($path, array($key)));
			else:
				include(join("/",$path) . "/" . $key);
				$results[$key] = $tester->runTests();
			endif;
		endforeach;
		
		return $results;
	}

	public function printResults($results, $path = array()) {

		foreach ($results as $key => $value):
			if (preg_match("/^.+Test$/", $key)):
				echo $key . ": ";
				print_r($value);
				echo "<br>";
			else:
				echo $key . "<br>";
				$this->printResults($value, array_merge($path, array($key)));
			endif;
		endforeach;
	}

}

// radio type, id, checked, disabled, (label for(id))