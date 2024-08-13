<?php 

declare(strict_types = 1);
namespace Src\User\Add\Lego;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Classes\DbhClass;
use Src\Shared\Exceptions\StmtFailedException;

class LegoClass {

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

	public function setLego(array $legoVal) {
		//global $openerTp;
		
		// check if any of the values are not null and add to statment
		$opColNames = array('name'=>$legoVal['legoName'], 'collection'=>$legoVal['collection'], 'cost'=>$legoVal['cost']);
		$stmtP1 = 'INSERT INTO legos (lego_id, piece_count';
		$stmtP2 = ') VALUES (?, ?';
		$stmtInputs = array($legoVal['legoID'], $legoVal['pieceCount']);
		foreach ($opColNames as $key => $value) {
			if (!empty($value)) {
				$stmtP1 = $stmtP1 . ', ' . $key;
				$stmtP2 = $stmtP2 . ', ?';
				$stmtInputs[] = $value;
			}
		}
		$stmt = $stmtP1 . $stmtP2 . ');';

		// prepare the statment
		$this->dbh->prepStmt($stmt);

		if (!$this->dbh->execStmt($stmtInputs)) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('setstmtfailed'); 
			//header('location: ' . $openerTp->getUrlReturn() . 'User/Add/Lego/index.php?error=setstmtfailed');
			//exit();
		}
	}

	public function checkLegoExist($legoID): bool {
		//global $openerTp;
		
		$this->dbh->prepStmt('SELECT lego_id FROM legos WHERE lego_id = ?;');

		if (!$this->dbh->execStmt(array($legoID))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('checkstmtfailed');
			//header('location: ' . $openerTp->getUrlReturn() . 'User/Add/Lego/index.php?error=checkstmtfailed');
			//exit();
		}

		if ($this->dbh->getStmt()->rowCount() > 0) {
			return true;
		}
		return false;
	}

}