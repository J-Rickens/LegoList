<?php 

declare(strict_types = 1);
namespace Test\Mock\Classes;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Test\Mock\Classes\MockStmtClass;
use Test\Mock\Exceptions\SuccessException;

class MockDbhClass
{
	private $stmt;
	private $stmtCount;
	private $stmtStatus;

	public function prepStmt(string $stmt): void
	{
		if(str_starts_with($stmt, 'SELECT')) {
			$this->stmtCount = substr_count($stmt, '?');
			$this->stmt = true;
			$this->stmtStatus = true;
		}
		elseif (str_starts_with($stmt, 'INSERT')) {
			$this->stmtCount = substr_count($stmt, '?');
			$this->stmt = false;
			$this->stmtStatus = true;
		}
		else {
			$this->stmtStatus = false;
			$this->stmt = false;
			$this->stmtCount = 0;
		}
	}

	public function execStmt(array $stmtInputs): bool
	{
		if ($this->stmtStatus & ($this->stmtCount == count($stmtInputs)) & !is_null($stmtInputs[0])) {
			if ($this->stmt) {
				$count = (int)$stmtInputs[0] % 2;
				$this->stmt = new MockStmtClass(array(), $count);
				return true;
			}
			else {
				throw new SuccessException();
				return true;
			}
		}
		else {
			return false;
		}
	}

	public function getStmt()
	{
		return $this->stmt;
	}

	public function setStmtNull(): void {}
}