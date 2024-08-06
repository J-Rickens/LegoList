<?php 

class LoginContr extends Login {

	private $usna;
	private $pwd;

	public function __construct($usna, $pwd) {
		$this->usna = $usna;
		$this->pwd = $pwd;
	}

	// Run Error Checks and login user if possible
	public function loginUser() {
		global $urlReturn;
		
		if ($this->ecEmptyInput()) {
			// echo "Empty Value(s)";
			header("location: " . $urlReturn . "login/index.php?error=emptyinput");
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