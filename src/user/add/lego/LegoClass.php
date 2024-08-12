<?php 

declare(strict_types = 1);
namespace Src\User\Add\Lego;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Classes\DbhClass;

class LegoClass extends DbhClass {

	protected function setLego($legoID, $pieceCount, $legoName = null, $collection = null, $cost = null) {
		global $openerTp;
		
		// check if any of the values are not null and add to statment
		$opColNames = array('name'=>$legoName, 'collection'=>$collection, 'cost'=>$cost);
		$stmtP1 = 'INSERT INTO legos (lego_id, piece_count';
		$stmtP2 = ') VALUES (?, ?';
		$stmtInputs = array($legoID, $pieceCount);
		foreach ($opColNames as $key => $value) {
			if (!empty($value)) {
				$stmtP1 = $stmtP1 . ', ' . $key;
				$stmtP2 = $stmtP2 . ', ?';
				$stmtInputs[] = $value;
			}
		}
		$stmtP1 = $stmtP1 . $stmtP2 . ');';

		// prepare the statment
		$stmt = $this->connect()->prepare($stmtP1);

		if (!$stmt->execute($stmtInputs)) {
			$stmt = null;
			header('location: ' . $openerTp->getUrlReturn() . 'User/Add/Lego/index.php?error=setstmtfailed');
			exit();
		}
	}

	protected function checkLegoExist($legoID) {
		global $openerTp;
		
		$stmt = $this->connect()->prepare('SELECT lego_id FROM legos WHERE lego_id = ?;');

		if (!$stmt->execute(array($legoID))) {
			$stmt = null;
			header('location: ' . $openerTp->getUrlReturn() . 'User/Add/Lego/index.php?error=checkstmtfailed');
			exit();
		}

		if ($stmt->rowCount() > 0) {
			return true;
		}
		return false;
	}

}