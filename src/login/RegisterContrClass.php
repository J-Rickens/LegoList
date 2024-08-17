<?php 

declare(strict_types = 1);
namespace Src\Login;
require __DIR__ . '\\..\\..\\vendor\\autoload.php';

use Src\Login\RegisterClass;
use Src\Shared\Regex\LoginRegex;
use Src\Shared\Exceptions\InvalidInputException;

class RegisterContrClass {

	private $registerClass;
	private $userVals = array(
		'name'=>null,
		'email'=>null,
		'usna'=>null,
		'pwd'=>null,
		'pwd2'=>null
	);

	public function __construct(array $userVals = array(), $registerClass = null) {
		if (is_null($registerClass)) {
			$this->registerClass = new RegisterClass();
		}
		else {
			$this->registerClass = $registerClass;
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

	// Run Error Checks and register user if possible
	public function registerUser(array $userVals = array()): void {
		$this->setUserVals($userVals);

		//global $openerTp;

		if ($this->ecEmptyInput()) {
			// echo 'Empty Value(s)';
			throw new InvalidInputException('emptyinput');
			//header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=emptyinput');
			//exit();
		}

		if (!$this->ecValidName()) {
			// echo 'Invalid Name';
			throw new InvalidInputException('name');
			//header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=name');
			//exit();
		}

		if (!$this->ecValidUsna()) {
			// echo 'Invalid Username';
			throw new InvalidInputException('username');
			//header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=username');
			//exit();
		}

		if (!$this->ecValidEmail()) {
			// echo 'Invalid Email';
			throw new InvalidInputException('email');
			//header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=email');
			//exit();
		}

		if ($this->registerClass->checkUserExist($this->userVals['usna'], $this->userVals['email'])) {
			// echo 'Username or Email Taken';
			throw new InvalidInputException('useroremailtaken');
			//header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=useroremailtaken');
			//exit();
		}

		if (!$this->ecValidPwd()) {
			// echo 'Invalid Password';
			throw new InvalidInputException('password');
			//header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=password');
			//exit();
		}

		if (!$this->ecPwdMatch()) {
			// echo 'Passwords Didn't Match';
			throw new InvalidInputException('passworddmatch');
			//header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=passworddmatch');
			//exit();
		}
		
		$this->registerClass->setUser($this->userVals);
	}


	// Error Checks: empty, valid, pwd match, usna/email taken (extended)
	private function ecEmptyInput(): bool {
		if (empty($this->userVals['name']) || empty($this->userVals['email']) || empty($this->userVals['usna']) || empty($this->userVals['pwd']) || empty($this->userVals['pwd2'])){
			return true;
		}
		return false;
	}

	private function ecValidName(): bool {
		if (preg_match('/'. LoginRegex::NAME .'/', $this->userVals['name'])) {
			return true;
		}
		return false;
	}

	private function ecValidEmail(): bool {
		if (filter_var($this->userVals['email'], FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		return false;
	}

	private function ecValidUsna(): bool {
		if (preg_match('/'. LoginRegex::USNA .'/', $this->userVals['usna'])) {
			return true;
		}
		return false;
	}

	private function ecValidPwd(): bool {
		if (preg_match('/'. LoginRegex::PWD .'/', $this->userVals['pwd'])) {
			return true;
		}
		return false;
	}

	private function ecPwdMatch(): bool {
		if ($this->userVals['pwd'] != $this->userVals['pwd2']) {
			return false;
		}
		return true;
	}
}