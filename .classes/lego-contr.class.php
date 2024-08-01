<?php 

include("../.regex/lego.regex.php");

class LegoContr extends Lego {

	private $legoID;
	private $pieceCount;
	private $legoName;
	private $collection;
	private $cost;

	public function __construct($legoID, $pieceCount, $legoName = null, $collection = null, $cost = null) {
		$this->legoID = $legoID;
		$this->pieceCount = $pieceCount;
		$this->legoName = $legoName;
		$this->collection = $collection;
		$this->cost = $cost;
	}

	// Run Error Checks and register lego if possible
	public function addLego() {
		// Running Error Checks
		if ($this->ecEmptyInput()) {
			// echo "Empty Value(s)";
			header("location: ../add/lego/index.php?error=emptyinput");
			exit();
		}

		if (!$this->ecValidLegoID()) {
			// echo "Invalid Lego Set ID";
			header("location: ../add/lego/index.php?error=legoid");
			exit();
		}

		if ($this->checkLegoExist($this->legoID)) {
			// echo "Lego alread Exists";
			header("location: ../add/lego/index.php?error=legoexists");
			exit();
		}

		if (!$this->ecValidPeiceCount()) {
			// echo "Invalid Peice Count";
			header("location: ../add/lego/index.php?error=piececount");
			exit();
		}

		if (!$this->ecValidName()) {
			// echo "Invalid Name";
			header("location: ../add/lego/index.php?error=name");
			exit();
		}

		if (!$this->ecValidCollection()) {
			// echo "Invalid Collection";
			header("location: ../add/lego/index.php?error=collection");
			exit();
		}

		if (!$this->ecValidCost()) {
			// echo "Invalid Cost";
			header("location: ../add/lego/index.php?error=cost");
			exit();
		}

		
		// Register Lego Set
		$this->setLego($this->legoID, $this->pieceCount, $this->legoName, $this->collection, $this->cost);
	}


	// Error Checks: empty, valid, legoID exists (extended)
	private function ecEmptyInput() {
		if (empty($this->legoID) || empty($this->pieceCount)){
			return true;
		}
		return false;
	}

	private function ecValidLegoID() {
		if (preg_match("/". LegoRegex::LEGOID ."/", $this->legoID)) {
			return true;
		}
		return false;
	}

	private function ecValidPeiceCount() {
		if (preg_match("/". LegoRegex::PEICES ."/", $this->pieceCount)) {
			return true;
		}
		return false;
	}

	private function ecValidName() {
		if (empty($this->legoName) || preg_match("/". LegoRegex::NAME ."/", $this->legoName)) {
			return true;
		}
		return false;
	}

	private function ecValidCollection() {
		if (empty($this->collection) || preg_match("/". LegoRegex::COLLECTION ."/", $this->collection)) {
			return true;
		}
		return false;
	}

	private function ecValidCost() {
		if (empty($this->cost) || preg_match("/". LegoRegex::COST ."/", $this->cost)) {
			return true;
		}
		return false;
	}

}