<?php 

declare(strict_types = 1);
namespace Src\Shared\Classes;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Classes\DbhClass;
use Src\Shared\Exceptions\StmtFailedException;

class LegoClass {

	public function __construct(private ?DbhClass $dbh = null) {
		if (is_null($dbh)) {
			$this->dbh = new DbhClass();
		}

	}

	public function getLegos(): array {
		$this->dbh->prepStmt('SELECT * FROM legos;');

		if (!$this->dbh->execStmt(array())) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('getlegosstmtfailed');
		}

		if ($this->dbh->getStmt()->rowCount() == 0) {
			$this->dbh->setStmtNull();
			return array();
		}

		return $this->dbh->getStmt()->fetchAll(\PDO::FETCH_ASSOC);
	}
}