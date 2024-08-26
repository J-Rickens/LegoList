<?php 

declare(strict_types = 1);
namespace Src\Login;
require __DIR__ . '\\..\\..\\vendor\\autoload.php';

use Src\Shared\Classes\DbhClass;
use Src\Shared\Exceptions\StmtFailedException;

class LoginClass {

	private $dbh;

	public function __construct($dbh = null) {
		if (is_null($dbh))
		{
			$this->dbh = new DbhClass();
		}
		else
		{
			$this->dbh = $dbh;
		}
	}

	public function getUser(array $userVals): void {
		//global $openerTp;
		
		$this->dbh->prepStmt('SELECT pwd FROM users WHERE username = ? OR email = ?;');

		if (!$this->dbh->execStmt(array($userVals['usna'], $userVals['usna']))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('getstmtfailed1');
			//header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=getstmtfailed');
			//exit();
		}
		if ($this->dbh->getStmt()->rowCount() == 0) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('usernotfound1');
			//header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=usernotfound');
			//exit();
		}

		$hashedPwds = $this->dbh->getStmt()->fetchAll(\PDO::FETCH_ASSOC);
		$checkPwd = password_verify($userVals['pwd'], $hashedPwds[0]['pwd']);
		if ($checkPwd == false) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('wrongpassword');
			//header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=wrongpassword');
			//exit();
		}
		elseif ($checkPwd == true) {
			$stmt = 'SELECT users.*, user_data.name, user_data.date_created
				FROM users
				LEFT JOIN user_data ON user_data.user_id = users.user_id
				WHERE (users.username = ? OR users.email = ?) AND users.pwd = ?;';
			$this->dbh->prepStmt($stmt);

			if (!$this->dbh->execStmt(array($userVals['usna'], $userVals['usna'], $hashedPwds[0]['pwd']))) {
				$this->dbh->setStmtNull();
				throw new StmtFailedException('getstmtfailed2');
				//header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=getstmtfailed');
				//exit();
			}
			if ($this->dbh->getStmt()->rowCount() == 0) {
				$this->dbh->setStmtNull();
				throw new StmtFailedException('usernotfound2');
				//header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=usernotfound');
				//exit();
			}

			$user = $this->dbh->getStmt()->fetchAll(\PDO::FETCH_ASSOC);

			if (session_status() === PHP_SESSION_ACTIVE) {
				session_unset();
				session_destroy();
			}
			session_start();
			$_SESSION['uid'] = $user[0]['user_id'];
			$_SESSION['usna'] = $user[0]['username'];
			$_SESSION['name'] = $user[0]['name'];
			$_SESSION['dateCreated'] = $user[0]['date_created'];
		}

		$this->dbh->setStmtNull();
	}

}