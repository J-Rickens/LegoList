<?php 

class Login extends Dbh {

	protected function getUser($usna, $pwd) {
		$stmt = $this->connect()->prepare('SELECT password FROM users WHERE username = ? OR email = ?;');

		if (!$stmt->execute(array($usna, $usna))) {
			$stmt = null;
			header("location: ../login/index.php?error=getstmtfailed");
			exit();
		}
		if ($stmt->rowCount() == 0) {
			$stmt = null;
			header("location: ../login/index.php?error=usernotfound");
			exit();
		}

		$hashedPwds = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$checkPwd = password_verify($pwd, $hashedPwds[0]["password"]);
		if ($checkPwd == false) {
			$stmt = null;
			header("location: ../login/index.php?error=wrongpassword");
			exit();
		}
		elseif ($checkPwd == true) {
			$stmt = $this->connect()->prepare('SELECT * FROM users WHERE username = ? OR email = ? AND password = ?;');

			if (!$stmt->execute(array($usna, $usna, $hashedPwds[0]["password"]))) {
				$stmt = null;
				header("location: ../login/index.php?error=getstmtfailed");
				exit();
			}
			if ($stmt->rowCount() == 0) {
				$stmt = null;
				header("location: ../login/index.php?error=usernotfound");
				exit();
			}

			$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

			session_start();
			$_SESSION["uid"] = $user[0]["user_id"];
			$_SESSION["username"] = $user[0]["username"];
			$_SESSION["name"] = $user[0]["name"];
			$_SESSION["userdate"] = $user[0]["date_created"];
		}

		$stmt = null;
	}

}