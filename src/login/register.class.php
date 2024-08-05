<?php 

class Register extends Dbh {

	protected function setUser($usna, $email, $name, $pwd) {
		$stmt = $this->connect()->prepare('INSERT INTO users (username, email, name, password) VALUES (?, ?, ?, ?);');

		$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

		if (!$stmt->execute(array($usna, $email, $name, $hashedPwd))) {
			$stmt = null;
			header("location: ../login/index.php?error=setstmtfailed");
			exit();
		}
	}

	protected function checkUserExist($usna, $email) {
		$stmt = $this->connect()->prepare('SELECT username FROM users WHERE username = ? OR email = ?;');

		if (!$stmt->execute(array($usna, $email))) {
			$stmt = null;
			header("location: ../login/index.php?error=checkstmtfailed");
			exit();
		}

		if ($stmt->rowCount() > 0) {
			return true;
		}
		return false;
	}

}