<?php 

declare(strict_types = 1);
namespace Src\User\Dashboard;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Classes\DbhClass;
use Src\Shared\Exceptions\StmtFailedException;

class DashboardClass {

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

	public function getLegoLists($uid): array {
		$this->dbh->prepStmt('SELECT * FROM legolists WHERE owner_id = ?;');

		if (!$this->dbh->execStmt(array($uid))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('getstmtfailed');
		}

		return $this->dbh->getStmt()->fetchAll(\PDO::FETCH_ASSOC);
	}
}