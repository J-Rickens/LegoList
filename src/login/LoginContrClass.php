<?php 

declare(strict_types = 1);
namespace Src\Login;
require __DIR__ . '\\..\\..\\vendor\\autoload.php';

use Src\Login\LoginClass;
use Src\Shared\Exceptions\InvalidInputException;

class LoginContrClass {

	private $loginClass;
	private $userVals = array(
		'usna'=>null,
		'pwd'=>null
	);

	public function __construct(array $userVals = array(), $loginClass = null) {
		if (is_null($loginClass)) {
			$this->loginClass = new LoginClass();
		}
		else {
			$this->loginClass = $loginClass;
		}

		foreach ($userVals as $key => $value) {
			$this->userVals[$key] = $value;
		}
	}

	public function setUserVals(array $userVals): void
	{
		foreach ($userVals as $key => $value) {
			$this->userVals[$key] = $value;
		}
	}

	public function getUserVals(bool $getFull = true, array $valNames = array()): array
	{
		if ($getFull) {
			return $this->userVals;
		}
		else {
			$returnArray = array();
			foreach ($valNames as $key) {
				$returnArray[$key] = $this->userVals[$key];
			}
			return $returnArray;
		}
	}

	// Run Error Checks and login user if possible
	public function loginUser(array $userVals = array()): void {
		$this->setUserVals($userVals);

		//global $openerTp;

		if ($this->ecEmptyInput()) {
			// echo 'Empty Value(s)';
			throw new InvalidInputException('emptyinput');
			//header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=emptyinput');
			//exit();
		}
		
		$this->loginClass->getUser($this->userVals);
	}


	// Error Checks: empty
	private function ecEmptyInput(): bool {
		if (empty($this->userVals['usna']) || empty($this->userVals['pwd'])){
			return true;
		}
		return false;
	}
}