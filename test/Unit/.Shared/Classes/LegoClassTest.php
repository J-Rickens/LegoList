<?php 

declare(strict_types = 1);
namespace Test\Unit\Shared\Classes;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

// test classes
use PHPUnit\Framework\TestCase;
// target classes
use Src\Shared\Classes\LegoClass;
use Src\Shared\Exceptions\StmtFailedException;
// mock classes
use Src\Shared\Classes\DbhClass;

class LegoClassTest extends TestCase
{
	private \PDOStatement $stmtMock;
	private DbhClass $dbhMock;
	private LegoClass $legoClass;

	protected function setUp(): void
	{
		parent::setUp();

		$this->stmtMock = $this->createMock(\PDOStatement::class);
		$this->dbhMock = $this->createMock(DbhClass::class);
		$this->dbhMock->method('getStmt')
			->willReturn($this->stmtMock);

		$this->legoClass = new LegoClass($this->dbhMock);
	}

	public function testGetLegosStmtFail(): void
	{
		$this->dbhMock->method('execStmt')
			->willReturn(false);

		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('getlegosstmtfailed');
		$this->legoClass->getLegos();
	}

	public function testGetLegosNoData(): void
	{
		$this->dbhMock->method('execStmt')
			->willReturn(True);
		$this->stmtMock->method('rowCount')
			->willReturn(0);

		$this->assertEmpty($this->legoClass->getLegos());
	}

	public function testGetLegosValid(): void
	{
		$this->dbhMock->method('execStmt')
			->willReturn(True);
		$this->stmtMock->method('rowCount')
			->willReturn(2);
		$mockData = [
			['lego_id'=>1,'lego_name'=>'LegoSet1'],
			['lego_id'=>2,'lego_name'=>'LegoSet2']
		];
		$this->stmtMock->method('fetchAll')
			->willReturn($mockData);

		$this->assertEquals($mockData, $this->legoClass->getLegos());
	}
}