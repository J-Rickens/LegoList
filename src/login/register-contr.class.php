<?php 

include($urlReturn . ".shared/.regex/login.regex.php");

class RegisterContr extends Register {

	private $name;
	private $email;
	private $usna;
	private $pwd;
	private $pwd2;

	public function __construct($name, $email, $usna, $pwd, $pwd2) {
		$this->name = $name;
		$this->email = $email;
		$this->usna = $usna;
		$this->pwd = $pwd;
		$this->pwd2 = $pwd2;
	}

	// Run Error Checks and register user if possible
	public function registerUser() {
		if ($this->ecEmptyInput()) {
			// echo "Empty Value(s)";
			header("location: " . $urlReturn . "login/index.php?error=emptyinput");
			exit();
		}

		if (!$this->ecValidName()) {
			// echo "Invalid Name";
			header("location: " . $urlReturn . "login/index.php?error=name");
			exit();
		}

		if (!$this->ecValidUsna()) {
			// echo "Invalid Username";
			header("location: " . $urlReturn . "login/index.php?error=username");
			exit();
		}

		if (!$this->ecValidEmail()) {
			// echo "Invalid Email";
			header("location: " . $urlReturn . "login/index.php?error=email");
			exit();
		}

		if ($this->checkUserExist($this->usna, $this->email)) {
			// echo "Username or Email Taken";
			header("location: " . $urlReturn . "login/index.php?error=useroremailtaken");
			exit();
		}

		if (!$this->ecValidPwd()) {
			// echo "Invalid Password";
			header("location: " . $urlReturn . "login/index.php?error=password");
			exit();
		}

		if (!$this->ecPwdMatch()) {
			// echo "Passwords Didn't Match";
			header("location: " . $urlReturn . "login/index.php?error=passworddmatch");
			exit();
		}
		
		$this->setUser($this->usna, $this->email, $this->name, $this->pwd);
	}


	// Error Checks: empty, valid, pwd match, usna/email taken (extended)
	private function ecEmptyInput() {
		if (empty($this->name) || empty($this->email) || empty($this->usna) || empty($this->pwd) || empty($this->pwd2)){
			return true;
		}
		return false;
	}

	private function ecValidName() {
		if (preg_match("/". LoginRegex::NAME ."/", $this->name)) {
			return true;
		}
		return false;
	}

	private function ecValidEmail() {
		if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		return false;
	}

	private function ecValidUsna() {
		if (preg_match("/". LoginRegex::USNA ."/", $this->usna)) {
			return true;
		}
		return false;
	}

	private function ecValidPwd() {
		if (preg_match("/". LoginRegex::PWD ."/", $this->pwd)) {
			return true;
		}
		return false;
	}

	private function ecPwdMatch() {
		if ($this->pwd != $this->pwd2) {
			return false;
		}
		return true;
	}
}