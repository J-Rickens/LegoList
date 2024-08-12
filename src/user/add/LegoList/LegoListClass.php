<?php 

declare(strict_types = 1);
namespace Src\User\Add\LegoList;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Classes\DbhClass;

class LegoListClass extends DbhClass {

	protected function setLegoList($listName, $pubPri, $uid) {
		global $openerTp;
		
		$stmt = $this->connect()->prepare('INSERT INTO user_lists (list_name, public, owner_id) VALUES (?, ?, ?);');
		
		if (!$stmt->execute(array($listName, $pubPri, $uid))) {
			$stmt = null;
			header('location: ' . $openerTp->getUrlReturn() . 'User/Add/LegoList/index.php?error=setstmtfailed');
			exit();
		}
	}

}