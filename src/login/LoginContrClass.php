<?php 

declare(strict_types = 1);
namespace Src\Login;
require __DIR__ . '\\..\\..\\vendor\\autoload.php';

use Src\Login\LoginClass;

class LoginContrClass extends LoginClass {

	private $usna;
	private $pwd;

	public function __construct($usna, $pwd) {
		$this->usna = $usna;
		$this->pwd = $pwd;
	}

	// Run Error Checks and login user if possible
	public function loginUser() {
		global $openerTp;
		
		if ($this->ecEmptyInput()) {
			// echo 'Empty Value(s)';
			header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=emptyinput');
			exit();
		}
		
		$this->getUser($this->usna, $this->pwd);
	}


	// Error Checks: empty
	private function ecEmptyInput() {
		if (empty($this->usna) || empty($this->pwd)){
			return true;
		}
		return false;
	}
}