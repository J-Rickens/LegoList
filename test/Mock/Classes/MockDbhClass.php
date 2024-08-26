<?php 

declare(strict_types = 1);
namespace Test\Mock\Classes;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Test\Mock\Classes\MockStmtClass;
use Test\Mock\Exceptions\FailedException;
use Test\Mock\Exceptions\SuccessException;

class MockDbhClass
{
	private $stmt = null;
	private $stmtCount = 0;
	private $stmtStatus = false;

	private $setCodes = ['stmtFail'=>false, 'fetch'=>false, 'exists'=>false];
	private $testingCode = [];
	private $tables = [[[]]];
	private $execCount = 0;

	public function setTestingConditions(array $testingCode = [[]], array $tables = array([[]])): void
	{
		// testingCode is an array of true/false 1/0
		foreach ($testingCode as $count => $codeSet) {
			$this->testingCode[$count] = $this->setCodes;
			foreach($codeSet as $key => $value) {
				$this->testingCode[$count][$key] = $value;
			}
		}
		$this->tables = $tables;
		$this->hashPassword();
		$this->execCount = 0;
	}

	private function hashPassword(): void
	{
		foreach ($this->tables as &$table) {
			foreach ($table as &$row) {
				foreach ($row as $key => $value) {
					if ($key == 'pwd') {
						$row[$key] = password_hash($value, PASSWORD_DEFAULT);
					}
				}
			}
		}
	}

	public function prepStmt(string $stmt): void
	{
		if(str_starts_with($stmt, 'SELECT') | str_starts_with($stmt, 'INSERT') | str_starts_with($stmt, 'CALL')) {
			$this->stmtCount = substr_count($stmt, '?');
			$this->stmtStatus = true;
		}
		else {
			$this->stmtStatus = false;
			$this->stmtCount = 0;
		}
	}

	public function execStmt(array $stmtInputs): bool
	{
		if ($this->stmtStatus & ($this->stmtCount == count($stmtInputs))) {
			if ($this->testingCode[$this->execCount]['stmtFail']) {
				$this->execCount += 1;
				return false;
			}
			if ($this->testingCode[$this->execCount]['fetch']) {
				$count = (int)$this->testingCode[$this->execCount]['exists'];
				$this->stmt = new MockStmtClass($this->tables[$this->execCount], $count);
				$this->execCount += 1;
				return true;
			}
			else {
				throw new SuccessException();
				$this->execCount += 1;
				return true;
			}
		}
		else {
			throw new FailedException();
		}
	}

	public function getStmt()
	{
		return $this->stmt;
	}

	public function setStmtNull(): void {}
}