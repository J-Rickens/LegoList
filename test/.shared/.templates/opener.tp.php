<?php 

$tester = new OpenerTpTest();

class OpenerTpTest {

	public function runTests() {
		$results = array("urlReturnTest"=>$this->urlReturnTest());

		return $results;
	}

	// test url return
	private function urlReturnTest() {
		$testVals = array(0 => "", 1 => "../", 3 => "../../../");
		$testResults = array();
		foreach ($testVals as $key => $value) {
			$urlLvl = $key;
			include("../src/.shared/.templates/opener.tp.php");
			$testResults[$key] = ($urlReturn == $value);
		}
		return $testResults;
	}
}