<?php 

class LegoList extends Dbh {

	protected function setLegoList($listName, $pubPri, $uid) {
		global $urlReturn;
		
		$stmt = $this->connect()->prepare('INSERT INTO user_lists (list_name, public, owner_id) VALUES (?, ?, ?);');
		
		if (!$stmt->execute(array($listName, $pubPri, $uid))) {
			$stmt = null;
			header("location: " . $urlReturn . "user/add/list/index.php?error=setstmtfailed");
			exit();
		}
	}

}