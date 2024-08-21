<?php 

declare(strict_types = 1);
namespace Src\Shared\Classes;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Classes\DbhLoginClass;

class DbhClass extends DbhLoginClass {

	private $stmt;

	protected function connect() {
		try {
			
			$dbh = new \PDO(self::DBPATH, self::USERNAME, self::PASSWORD);
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