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

	public function checkLegoList($listId, $uid): array {
		$this->dbh->prepStmt('SELECT owner_id FROM legolists WHERE list_id = ?;');

		if (!$this->dbh->execStmt(array($listId))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('checkstmtfailed');
		}

		if ($this->dbh->getStmt()->rowCount() == 0) {
			$this->dbh->setStmtNull();
			return [false, 'listnotfound'];
		}

		$ownerIds = $this->dbh->getStmt()->fetchAll(\PDO::FETCH_ASSOC);
		$this->dbh->setStmtNull();
		if ($ownerIds[0]['owner_id'] != $uid) {
			return [false, 'listpermissionfail'];
		}

		return [true, null];
	}

	public function checkLegoInLegoList($listId, $legoId): bool {
		$this->dbh->prepStmt('SELECT * FROM legolist_lego_c WHERE list_id = ? AND lego_id = ?;');

		if (!$this->dbh->execStmt(array($listId, $legoId))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('checkstmtfailed');
		}

		if ($this->dbh->getStmt()->rowCount() == 0) {
			$this->dbh->setStmtNull();
			return false;
		}
		return true;
	}

	public function getLegoListData($listId): array {
		$this->dbh->prepStmt('SELECT * FROM legolists WHERE list_id = ?;');

		if (!$this->dbh->execStmt(array($listId))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('getdatastmtfailed');
		}

		if ($this->dbh->getStmt()->rowCount() == 0) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('listnotfound');
		}

		return $this->dbh->getStmt()->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getLegoListLegos($listId): array {
		$this->dbh->prepStmt('SELECT lego_id FROM legolist_lego_c WHERE list_id = ?;');

		if (!$this->dbh->execStmt(array($listId))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('getlegosstmtfailed');
		}

		if ($this->dbh->getStmt()->rowCount() == 0) {
			$this->dbh->setStmtNull();
			return array();
		}

		return $this->dbh->getStmt()->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function updateLegoListData(array $legoListVals): void {
		$stmt = $this->dbh->prepStmt('UPDATE legolists SET list_name = ?, is_public = ?, owner_id = ? WHERE list_id = ?;');

		if (!$this->dbh->execStmt(array($legoListVals['listName'], $legoListVals['isPublic'], $legoListVals['uid'], $legoListVals['listId']))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('updatestmtfailed');
		}
	}

	public function setLegoToList(array $addLegoVals): void {
		$stmt = $this->dbh->prepStmt('INSERT INTO legolist_lego_c (list_id, lego_id) VALUES (?, ?);');
		
		if (!$this->dbh->execStmt(array($addLegoVals['listId'], $addLegoVals['legoId']))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('setstmtfailed');
		}
	}

	public function deleteLegoFromList(array $removeLegoVals): void {
		$stmt = $this->dbh->prepStmt('DELETE FROM legolist_lego_c WHERE list_id = ? AND lego_id = ?;');
		
		if (!$this->dbh->execStmt(array($removeLegoVals['listId'], $removeLegoVals['legoId']))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('setstmtfailed');
		}
	}
}