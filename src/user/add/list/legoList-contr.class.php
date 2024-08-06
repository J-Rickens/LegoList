<?php 

include($urlReturn . ".shared/.regex/legoList.regex.php");

class LegoListContr extends LegoList {

	private $listName;
	private $pubPri;
	private $uid;

	public function __construct($listName, $pubPri, $uid) {
		$this->listName = $listName;
		$this->pubPri = $pubPri;
		$this->uid = $uid;
	}

	// Run Error Checks and create list if possible
	public function addLegoList() {
		global $urlReturn;
		
		// Running Error Checks
		if ($this->ecEmptyInput()) {
			// echo "Empty Value(s)";
			header("location: " . $urlReturn . "user/add/list/index.php?error=emptyinput");
			exit();
		}

		if (!$this->ecValidName()) {
			// echo "Invalid Name";
			header("location: " . $urlReturn . "user/add/list/index.php?error=name");
			exit();
		}

		if (!$this->ecValidPubPri()) {
			// echo "Invalid Public Private";
			header("location: " . $urlReturn . "user/add/list/index.php?error=pubpri");
			exit();
		}
		$this->formatPubPri();

		if (!$this->ecValidUID()) {
			// echo "Invalid UID";
			header("location: " . $urlReturn . "user/add/list/index.php?error=uid");
			exit();
		}

		
		// Create new List
		$this->setLegoList($this->listName, $this->pubPri, $this->uid);
	}


	// Reformats pubPri as true/false
	private function formatPubPri() {
		if ($this->pubPri == "public") {
			$this->pubPri = true;
		}
		else {
			$this->pubPri = false;
		}
	}

	// Error Checks: empty, valid
	private function ecEmptyInput() {
		if (empty($this->listName) || empty($this->pubPri) || empty($this->uid)){
			return true;
		}
		return false;
	}

	private function ecValidName() {
		if (preg_match("/". LegoListRegex::NAME ."/", $this->listName)) {
			return true;
		}
		return false;
	}

	private function ecValidPubPri() {
		if ($this->pubPri == "public" || $this->pubPri == "private") {
			return true;
		}
		return false;
	}

	private function ecValidUID() {
		if (preg_match("/^\d+$/", $this->uid)) {
			return true;
		}
		return false;
	}

}