<?php 

declare(strict_types = 1);
namespace Src\Login;
require __DIR__ . '\\..\\..\\vendor\\autoload.php';

use Src\Shared\Classes\DbhClass;

class RegisterClass extends DbhClass {

	protected function setUser($usna, $email, $name, $pwd) {
		global $openerTp;

		$stmt = $this->connect()->prepare('INSERT INTO users (username, email, name, password) VALUES (?, ?, ?, ?);');

		$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

		if (!$stmt->execute(array($usna, $email, $name, $hashedPwd))) {
			$stmt = null;
			header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=setstmtfailed');
			exit();
		}
	}

	protected function checkUserExist($usna, $email) {
		global $openerTp;

		$stmt = $this->connect()->prepare('SELECT username FROM users WHERE username = ? OR email = ?;');

		if (!$stmt->execute(array($usna, $email))) {
			$stmt = null;
			header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=checkstmtfailed');
			exit();
		}

		if ($stmt->rowCount() > 0) {
			return true;
		}
		return false;
	}

}