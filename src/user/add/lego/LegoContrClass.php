<?php 

declare(strict_types = 1);
namespace Src\User\Add\Lego;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

use Src\User\Add\Lego\LegoClass;
use Src\Shared\Regex\LegoRegex;
use Src\Shared\Exceptions\InvalidInputException;

class LegoContrClass {

	private $legoClass;
	private $legoVal = array('legoID'=>null,
		'pieceCount'=>null,
		'legoName'=>null,
		'collection'=>null,
		'cost'=>null);

	public function __construct(array $legoVal, $legoClass = null) {
		if (is_null($legoClass)) {
			$this->legoClass = new LegoClass();
		}
		else {
			$this->legoClass = $legoClass;
		}

		foreach ($legoVal as $key => $value) {
			$this->legoVal[$key] = $value;
		}
	}

	public function setLegoVal(array $legoVal): void
	{
		foreach ($legoVal as $key => $value) {
			$this->legoVal[$key] = $value;
		}
	}

	public function getLegoVal(bool $getFull = true, array $valNames = array()): array
	{
		if ($getFull) {
			return $this->legoVal;
		}
		else {
			$returnArray = array();
			foreach ($valNames as $key) {
				$returnArray[$key] = $this->legoVal[$key];
			}
			return $returnArray;
		}
	}

	// Run Error Checks and register lego if possible
	public function addLego(array $legoVal = array()): void {
		$this->setLegoVal($legoVal);

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

		if ($this->legoClass->checkLegoExist($this->legoVal['legoID'])) {
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
		if (isset($this->legoVal['cost'])) {
			$this->formatCost();
		}

		
		// Register Lego Set
		$this->legoClass->setLego($this->legoVal);
		//legoID, $this->pieceCount, $this->legoName, $this->collection, $this->cost);
	}


	// Reformats cost to remove ',' and '$'
	private function formatCost(): void {
		$this->legoVal['cost'] = str_replace(array(',','$'), '', $this->legoVal['cost']);
	}

	// Error Checks: empty, valid, legoID exists (extended)
	private function ecEmptyInput(): bool {
		if (empty($this->legoVal['legoID']) || empty($this->legoVal['pieceCount'])){
			return true;
		}
		return false;
	}

	private function ecValidLegoID(): bool {
		if (preg_match('/'. LegoRegex::LEGOID .'/', $this->legoVal['legoID'])) {
			return true;
		}
		return false;
	}

	private function ecValidPeiceCount(): bool {
		if (preg_match('/'. LegoRegex::PEICES .'/', $this->legoVal['pieceCount'])) {
			return true;
		}
		return false;
	}

	private function ecValidName(): bool {
		if (is_null($this->legoVal['legoName']) || preg_match('/'. LegoRegex::NAME .'/', $this->legoVal['legoName'])) {
			return true;
		}
		return false;
	}

	private function ecValidCollection(): bool {
		if (is_null($this->legoVal['collection']) || preg_match('/'. LegoRegex::COLLECTION .'/', $this->legoVal['collection'])) {
			return true;
		}
		return false;
	}

	private function ecValidCost(): bool {
		if (is_null($this->legoVal['cost']) || preg_match('/'. LegoRegex::COST .'/', $this->legoVal['cost'])) {
			return true;
		}
		return false;
	}

}