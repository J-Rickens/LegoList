<?php 

declare(strict_types = 1);
namespace Src\Shared\Classes;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

class DbhClass {

	private $stmt;

	protected function connect() {
		try {
			$username = "root";
			$password = "";
			$dbh = new \PDO('mysql:host=localhost;dbname=db_ws_lego_php', $username, $password);
			return $dbh;
			
		} catch (\PDOException $e) {
			print("Error!: " . $e->getMessage() . "<br/>");
			die();
		}
	}

	public function prepStmt(string $stmt): void {
		$this->stmt = $this->connect()->prepare($stmt);
	}

	public function execStmt(array $stmtInputs): bool {
		return $this->stmt->execute($stmtInputs);
	}

	public function getStmt() {
		return $this->stmt;
	}

	public function setStmtNull(): void {
		$this->stmt = null;
	}
}