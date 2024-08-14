<?php 

declare(strict_types = 1);
namespace test\unit\User\Add\Lego;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use Src\Shared\Exceptions\StmtFailedException;
use Src\User\Add\Lego\LegoClass;

class SuccessException2 extends \Exception {}
class MockStmt
{
	private $rows;
	private $count;

	public function __construct(array $rows = array(), int $count = 0)
	{
		$this->rows = $rows;
		$this->count = $count;
	}

	public function rowCount(): int
	{
		return $this->count;
	}
}
class MockDbh
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
				$this->stmt = new MockStmt(array(), $count);
				return true;
			}
			else {
				throw new SuccessException2();
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

class LegoClassTest extends TestCase
{
	private LegoClass $lego;

	protected function setUp(): void
	{
		parent::setUp();

		$this->lego = new LegoClass(new MockDbh());
	}

	public function testCheckLegoExistMockFailedStmt(): void
	{
		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('checkstmtfailed');
		$this->lego->checkLegoExist(null);
	}

	public function testCheckLegoExistIfTrue(): void
	{
		$this->assertTrue($this->lego->checkLegoExist('123'));
	}

	public function testCheckLegoExistIfFalse(): void
	{
		$this->assertFalse($this->lego->checkLegoExist('1234'));
	}

	public function testSetLegoMockFailedStmt(): void
	{
		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('setstmtfailed');
		$this->lego->setLego(array('legoID'=>null,'pieceCount'=>null,'legoName'=>null,'collection'=>null,'cost'=>null));
	}

	#[DataProvider('setLegoValidCases')]
	public function testSetLegoValid(
		array $legoVal
	): void
	{
		$this->expectException(SuccessException2::class);
		$this->lego->setLego($legoVal);
	}
	public static function setLegoValidCases(): array
	{
		return [
			[['legoID'=>'1234','pieceCount'=>'1','legoName'=>'1', 'collection'=>'1', 'cost'=>'1' ]],
			[['legoID'=>'1234','pieceCount'=>'1','legoName'=>'1', 'collection'=>'1', 'cost'=>null]],
			[['legoID'=>'1234','pieceCount'=>'1','legoName'=>'1', 'collection'=>null,'cost'=>null]],
			[['legoID'=>'1234','pieceCount'=>'1','legoName'=>null,'collection'=>'1', 'cost'=>'1' ]],
			[['legoID'=>'1234','pieceCount'=>'1','legoName'=>null,'collection'=>null,'cost'=>'1' ]],
			[['legoID'=>'1234','pieceCount'=>'1','legoName'=>null,'collection'=>null,'cost'=>null]]
		];
	}
}