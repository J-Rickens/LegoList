<?php 

declare(strict_types = 1);
namespace Src\User\ListEditor;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Classes\DbhClass;
use Src\Shared\Exceptions\StmtFailedException;

class ListEditorClass {

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

	public function checkLegoList($list_id, $uid): bool {
		$this->dbh->prepStmt('SELECT owner_id FROM user_lists WHERE list_id = ?;');

		if (!$this->dbh->execStmt(array($list_id))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('getstmtfailed');
		}

		if ($this->dbh->getStmt()->rowCount() == 0) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('listnotfound');
		}

		$owner_id = $this->dbh->getStmt()->fetchAll(\PDO::FETCH_ASSOC);
		$this->dbh->setStmtNull();
		if ($owner_id[0]['owner_id'] != $uid) {
			throw new StmtFailedException('listpermissionfail');
		}

		return true;
	}

	public function getLegoListData($list_id): array {
		$this->dbh->prepStmt('SELECT * FROM user_lists WHERE list_id = ?;');

		if (!$this->dbh->execStmt(array($list_id))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('getstmtfailed');
		}

		if ($this->dbh->getStmt()->rowCount() == 0) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('listnotfound');
		}

		return $this->dbh->getStmt()->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getLegoListLegos($list_id): array {
		$this->dbh->prepStmt('SELECT lego_id FROM lego_list_c WHERE list_id = ?;');

		if (!$this->dbh->execStmt(array($list_id))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('getstmtfailed');
		}

		if ($this->dbh->getStmt()->rowCount() == 0) {
			$this->dbh->setStmtNull();
			return array();
		}

		return $this->dbh->getStmt()->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function setLegoToList(array $addLegoVals): void {
		$stmt = $this->dbh->prepStmt('INSERT INTO lego_list_c (list_id, lego_id) VALUES (?, ?);');
		
		if (!$this->dbh->execStmt(array($addLegoVals['list_id'], $addLegoVals['legoId']))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('setstmtfailed');
		}
	}

	public function deleteLegoFromList(array $removeLegoVals): void {
		$stmt = $this->dbh->prepStmt('DELETE FROM lego_list_c WHERE list_id = ? AND lego_id = ?;');
		
		if (!$this->dbh->execStmt(array($removeLegoVals['list_id'], $removeLegoVals['legoId']))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('setstmtfailed');
		}
	}
}