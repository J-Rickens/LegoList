<?php 

class Lego extends Dbh {

	protected function setLego($legoID, $pieceCount, $legoName = null, $collection = null, $cost = null) {
		
		// check if any of the values are not null and add to statment
		$opColNames = array("name"=>$legoName, "collection"=>$collection, "cost"=>$cost);
		$stmtP1 = 'INSERT INTO legos (lego_id, piece_count';
		$stmtP2 = ') VALUES (?, ?';
		$stmtInputs = array($legoID, $pieceCount);
		foreach ($opColNames as $key => $value) {
			if (!empty($value)) {
				$stmtP1 = $stmtP1 . ", " . $key;
				$stmtP2 = $stmtP2 . ", ?";
				$stmtInputs[] = $value;
			}
		}
		$stmtP1 = $stmtP1 . $stmtP2 . ");";

		// prepare the statment
		$stmt = $this->connect()->prepare($stmtP1);

		if (!$stmt->execute($stmtInputs)) {
			$stmt = null;
			header("location: " . $urlReturn . "user/add/lego/index.php?error=setstmtfailed");
			exit();
		}
	}

	protected function checkLegoExist($legoID) {
		$stmt = $this->connect()->prepare('SELECT lego_id FROM legos WHERE lego_id = ?;');

		if (!$stmt->execute(array($legoID))) {
			$stmt = null;
			header("location: " . $urlReturn . "user/add/lego/index.php?error=checkstmtfailed");
			exit();
		}

		if ($stmt->rowCount() > 0) {
			return true;
		}
		return false;
	}

}