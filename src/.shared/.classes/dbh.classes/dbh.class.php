<?php 

class Dbh {

	protected function connect() {
		try {
			$username = "root";
			$password = "";
			$dbh = new PDO('mysql:host=localhost;dbname=db_ws_lego_php', $username, $password);
			return $dbh;
			
		} catch (PDOException $e) {
			print("Error!: " . $e->getMessage() . "<br/>");
			die();
		}
	}

}