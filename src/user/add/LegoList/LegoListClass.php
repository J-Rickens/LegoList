<?php 

declare(strict_types = 1);
namespace Src\User\Add\LegoList;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Classes\DbhClass;
use Src\Shared\Exceptions\StmtFailedException;

class LegoListClass {

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

	public function setLegoList(array $legoListVals): void {//$listName, $pubPri, $uid) {
		//global $openerTp;
		
		$stmt = $this->dbh->prepStmt('INSERT INTO user_lists (list_name, public, owner_id) VALUES (?, ?, ?);');
		
		if (!$this->dbh->execStmt(array($legoListVals['listName'], $legoListVals['pubPri'], $legoListVals['uid']))) {
			$this->dbh->setStmtNull();
			throw new StmtFailedException('setstmtfailed');
			//header('location: ' . $openerTp->getUrlReturn() . 'User/Add/LegoList/index.php?error=setstmtfailed');
			//exit();
		}
	}

}