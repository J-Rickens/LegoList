<?php 

declare(strict_types = 1);
namespace Src\Login;
require __DIR__ . '\\..\\..\\vendor\\autoload.php';

use Src\Shared\Classes\DbhClass;
use Src\Shared\Exceptions\StmtFailedException;

class RegisterClass {

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

	public function setUser(array $userVals): void {
		//global $openerTp;

		$this->dbh->prepStmt('CALL insert_into_user_tables (?, ?, ?, ?);');

		$hashedPwd = password_hash($userVals['pwd'], PASSWORD_DEFAULT);

		if (!$this->dbh->execStmt(array($userVals['usna'], $userVals['email'], $hashedPwd, $userVals['name']))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('setstmtfailed');
			//header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=setstmtfailed');
			//exit();
		}
	}

	public function checkUserExist($usna, $email): bool {
		//global $openerTp;

		$this->dbh->prepStmt('SELECT user_id FROM users WHERE username = ? OR email = ?;');

		if (!$this->dbh->execStmt(array($usna, $email))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('checkstmtfailed');
			//header('location: ' . $openerTp->getUrlReturn() . 'Login/index.php?error=checkstmtfailed');
			//exit();
		}

		if ($this->dbh->getStmt()->rowCount() > 0) {
			return true;
		}
		return false;
	}

}