<?php 

declare(strict_types = 1);
namespace Src\User\Add\LegoList;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

use Src\User\Add\LegoList\LegoListClass;
use Src\Shared\Regex\LegoListRegex;
use Src\Shared\Exceptions\InvalidInputException;

class LegoListContrClass {

	private $legoListClass;
	private $legoListVals = array(
		'listName'=>null,
		'isPublic'=>null,
		'uid'=>null
	);

	public function __construct(array $legoListVals = array(), $legoListClass = null) {
		if (is_null($legoListClass)) {
			$this->legoListClass = new LegoListClass();
		}
		else {
			$this->legoListClass = $legoListClass;
		}

		foreach ($legoListVals as $key => $value) {
			$this->legoListVals[$key] = $value;
		}
	}

	public function setLegoListVals(array $legoListVals): void
	{
		foreach ($legoListVals as $key => $value) {
			$this->legoListVals[$key] = $value;
		}
	}

	public function getLegoListVals(bool $getFull = true, array $valNames = array()): array
	{
		if ($getFull) {
			return $this->legoListVals;
		}
		else {
			$returnArray = array();
			foreach ($valNames as $key) {
				$returnArray[$key] = $this->legoListVals[$key];
			}
			return $returnArray;
		}
	}

	// Run Error Checks and create list if possible
	public function addLegoList(array $legoListVals = array()): void {
		$this->setLegoListVals($legoListVals);

		// run error checks
		$this->legoListErrorChecks();

		// Create new List
		$this->legoListClass->setLegoList($this->legoListVals);
	}

	// Run Error Checks and create list if possible
	protected function legoListErrorChecks(): void {
		//global $openerTp;
		
		// Running Error Checks
		if ($this->ecEmptyInput()) {
			// echo 'Empty Value(s)';
			throw new InvalidInputException('emptyinput');
			//header('location: ' . $openerTp->getUrlReturn() . 'User/Add/LegoList/index.php?error=emptyinput');
			//exit();
		}

		if (!$this->ecValidName()) {
			// echo 'Invalid Name';
			throw new InvalidInputException('name');
			//header('location: ' . $openerTp->getUrlReturn() . 'User/Add/LegoList/index.php?error=name');
			//exit();
		}

		if (!$this->ecValidPubPri()) {
			// echo 'Invalid Public Private';
			throw new InvalidInputException('pubpri');
			//header('location: ' . $openerTp->getUrlReturn() . 'User/Add/LegoList/index.php?error=pubpri');
			//exit();
		}
		$this->formatPubPri();

		if (!$this->ecValidUID()) {
			// echo 'Invalid UID';
			throw new InvalidInputException('uid');
			//header('location: ' . $openerTp->getUrlReturn() . 'User/Add/LegoList/index.php?error=uid');
			//exit();
		}
	}

	// Reformats isPublic as true/false
	private function formatPubPri(): void {
		if ($this->legoListVals['isPublic'] == 'public') {
			$this->legoListVals['isPublic'] = true;
		}
		else {
			$this->legoListVals['isPublic'] = false;
		}
	}

	// Error Checks: empty, valid
	private function ecEmptyInput(): bool {
		if (empty($this->legoListVals['listName']) || empty($this->legoListVals['isPublic']) || empty($this->legoListVals['uid'])){
			return true;
		}
		return false;
	}

	private function ecValidName(): bool {
		if (preg_match('/'. LegoListRegex::NAME .'/', $this->legoListVals['listName'])) {
			return true;
		}
		return false;
	}

	private function ecValidPubPri(): bool {
		if ($this->legoListVals['isPublic'] == 'public' || $this->legoListVals['isPublic'] == 'private') {
			return true;
		}
		return false;
	}

	private function ecValidUID(): bool {
		if (preg_match('/^\d+$/', $this->legoListVals['uid'])) {
			return true;
		}
		return false;
	}

}