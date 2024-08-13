<?php 

declare(strict_types = 1);
namespace Src\User\Add\Lego;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

use Src\User\Add\Lego\LegoClass;
use Src\Shared\Regex\LegoRegex;
use Src\Shared\Exceptions\InvalidInputException;

class LegoContrClass {

	private $legoClass;
	private $legoID;
	private $pieceCount;
	private $legoName;
	private $collection;
	private $cost;

	public function __construct($legoID, $pieceCount, $legoName = null, $collection = null, $cost = null, $legoClass = null) {
		if (is_null($legoClass))
		{
			$this->legoClass = new LegoClass();
		}
		else
		{
			$this->legoClass = $legoClass;
		}
		$this->legoID = $legoID;
		$this->pieceCount = $pieceCount;
		$this->legoName = $legoName;
		$this->collection = $collection;
		$this->cost = $cost;
	}

	// Run Error Checks and register lego if possible
	public function addLego() {
		//global $openerTp;
		
		// Running Error Checks
		if ($this->ecEmptyInput()) {
			// echo 'Empty Value(s)';
			throw new InvalidInputException('emptyinput');
			//header('location: ' . $openerTp->getUrlReturn() . 'User/Add/Lego/index.php?error=emptyinput');
			//exit();
		}

		if (!$this->ecValidLegoID()) {
			// echo 'Invalid Lego Set ID';
			throw new InvalidInputException('legoid');
			//header('location: ' . $openerTp->getUrlReturn() . 'User/Add/Lego/index.php?error=legoid');
			//exit();
		}

		if ($this->legoClass->checkLegoExist($this->legoID)) {
			// echo 'Lego alread Exists';
			throw new InvalidInputException('legoexists');
			//header('location: ' . $openerTp->getUrlReturn() . 'User/Add/Lego/index.php?error=legoexists');
			//exit();
		}

		if (!$this->ecValidPeiceCount()) {
			// echo 'Invalid Peice Count';
			throw new InvalidInputException('piececount');
			//header('location: ' . $openerTp->getUrlReturn() . 'User/Add/Lego/index.php?error=piececount');
			//exit();
		}

		if (!$this->ecValidName()) {
			// echo 'Invalid Name';
			throw new InvalidInputException('name');
			//header('location: ' . $openerTp->getUrlReturn() . 'User/Add/Lego/index.php?error=name');
			//exit();
		}

		if (!$this->ecValidCollection()) {
			// echo 'Invalid Collection';
			throw new InvalidInputException('collection');
			//header('location: ' . $openerTp->getUrlReturn() . 'User/Add/Lego/index.php?error=collection');
			//exit();
		}

		if (!$this->ecValidCost()) {
			// echo 'Invalid Cost';
			throw new InvalidInputException('cost');
			//header('location: ' . $openerTp->getUrlReturn() . 'User/Add/Lego/index.php?error=cost');
			//exit();
		}
		$this->formatCost();

		
		// Register Lego Set
		$this->legoClass->setLego($this->legoID, $this->pieceCount, $this->legoName, $this->collection, $this->cost);
	}


	// Reformats cost to remove ',' and '$'
	private function formatCost() {
		$this->cost = str_replace(array(',','$'), '', $this->cost);
	}

	// Error Checks: empty, valid, legoID exists (extended)
	private function ecEmptyInput() {
		if (empty($this->legoID) || empty($this->pieceCount)){
			return true;
		}
		return false;
	}

	private function ecValidLegoID() {
		if (preg_match('/'. LegoRegex::LEGOID .'/', $this->legoID)) {
			return true;
		}
		return false;
	}

	private function ecValidPeiceCount() {
		if (preg_match('/'. LegoRegex::PEICES .'/', $this->pieceCount)) {
			return true;
		}
		return false;
	}

	private function ecValidName() {
		if (empty($this->legoName) || preg_match('/'. LegoRegex::NAME .'/', $this->legoName)) {
			return true;
		}
		return false;
	}

	private function ecValidCollection() {
		if (empty($this->collection) || preg_match('/'. LegoRegex::COLLECTION .'/', $this->collection)) {
			return true;
		}
		return false;
	}

	private function ecValidCost() {
		if (empty($this->cost) || preg_match('/'. LegoRegex::COST .'/', $this->cost)) {
			return true;
		}
		return false;
	}

}