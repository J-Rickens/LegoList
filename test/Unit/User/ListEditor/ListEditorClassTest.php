<?php 

declare(strict_types = 1);
namespace Test\Unit\User\ListEditor;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

// test classes
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
// target classes
use Src\Shared\Exceptions\StmtFailedException;
use Src\User\ListEditor\ListEditorClass;
// mock classes
use Src\Shared\Classes\DbhClass;

class ListEditorClassTest extends TestCase
{
	private \PDOStatement $stmtMock;
	private DbhClass $dbhMock;
	private ListEditorClass $listEditor;

	protected function setUp(): void
	{
		parent::setUp();

		$this->stmtMock = $this->createMock(\PDOStatement::class);
		$this->dbhMock = $this->createMock(DbhClass::class);
		$this->dbhMock->method('getStmt')
			->willReturn($this->stmtMock);

		$this->listEditor = new ListEditorClass($this->dbhMock);
	}

	public function testCheckLegoListStmtFail(): void
	{
		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(false);
		$this->dbhMock
			->expects($this->once())
			->method('setStmtNull');

		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('checkstmtfailed');
		$this->listEditor->checkLegoList(null, null);
	}
	public function testCheckLegoListNoList(): void
	{
		$uid = 1;
		$data = [['owner_id'=>$uid]];

		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(true);
		$this->dbhMock
			->expects($this->exactly(1))
			->method('getStmt');
		$this->stmtMock
			->expects($this->once())
			->method('rowCount')
			->willReturn(0);
		$this->dbhMock
			->expects($this->once())
			->method('setStmtNull');

		$this->stmtMock
			->method('fetchAll')
			->willReturn($data);

		$this->assertEquals([false, 'listnotfound'], $this->listEditor->checkLegoList(null, null));
	}
	public function testCheckLegoListNotOwner(): void
	{
		$uid = 1;
		$data = [['owner_id'=>$uid]];
		$uid2 = 2;

		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(true);
		$this->dbhMock
			->expects($this->exactly(2))
			->method('getStmt');
		$this->stmtMock
			->expects($this->once())
			->method('rowCount')
			->willReturn(1);
		$this->stmtMock
			->expects($this->once())
			->method('fetchAll')
			->willReturn($data);
		$this->dbhMock
			->expects($this->once())
			->method('setStmtNull');

		$this->assertEquals([false, 'listpermissionfail'], $this->listEditor->checkLegoList(null, $uid2));
	}
	public function testCheckLegoListValid(): void
	{
		$uid = 1;
		$data = [['owner_id'=>$uid]];

		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(true);
		$this->dbhMock
			->expects($this->exactly(2))
			->method('getStmt');
		$this->stmtMock
			->expects($this->once())
			->method('rowCount')
			->willReturn(1);
		$this->stmtMock
			->expects($this->once())
			->method('fetchAll')
			->willReturn($data);
		$this->dbhMock
			->expects($this->once())
			->method('setStmtNull');

		$this->assertEquals([true, null], $this->listEditor->checkLegoList(null, $uid));
	}
	public function testCheckLegoInLegoListStmtFail(): void
	{
		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(false);
		$this->dbhMock
			->expects($this->once())
			->method('setStmtNull');

		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('checkstmtfailed');
		$this->listEditor->checkLegoInLegoList(null, null);
	}
	public function testCheckLegoInLegoListNoEntry(): void
	{
		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(true);
		$this->dbhMock
			->expects($this->once())
			->method('getStmt');
		$this->stmtMock
			->expects($this->once())
			->method('rowCount')
			->willReturn(0);
		$this->dbhMock
			->expects($this->once())
			->method('setStmtNull');

		$this->assertFalse($this->listEditor->checkLegoInLegoList(null, null));
	}
	public function testCheckLegoInLegoListValidList(): void
	{
		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(true);
		$this->dbhMock
			->expects($this->once())
			->method('getStmt');
		$this->stmtMock
			->expects($this->once())
			->method('rowCount')
			->willReturn(1);

		$this->assertTrue($this->listEditor->checkLegoInLegoList(null, null));
	}
	public function testGetLegoListDataStmtFail(): void
	{
		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(false);
		$this->dbhMock
			->expects($this->once())
			->method('setStmtNull');

		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('getdatastmtfailed');
		$this->listEditor->getLegoListData(null);
	}
	public function testGetLegoListDataNoData(): void
	{
		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(true);
		$this->dbhMock
			->expects($this->once())
			->method('getStmt');
		$this->stmtMock
			->expects($this->once())
			->method('rowCount')
			->willReturn(0);
		$this->dbhMock
			->expects($this->once())
			->method('setStmtNull');

		$this->stmtMock
			->method('fetchAll')
			->willReturn([1,2,3]);

		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('listnotfound');
		$this->listEditor->getLegoListData(null);
	}
	public function testGetLegoListDataValid(): void
	{
		$data = [1,2,3];

		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(true);
		$this->dbhMock
			->expects($this->exactly(2))
			->method('getStmt');
		$this->stmtMock
			->expects($this->once())
			->method('rowCount')
			->willReturn(1);
		$this->stmtMock
			->expects($this->once())
			->method('fetchAll')
			->willReturn($data);

		$this->assertEquals($data, $this->listEditor->getLegoListData(null));
	}
	public function testGetLegoListLegosStmtFail(): void
	{
		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(false);
		$this->dbhMock
			->expects($this->once())
			->method('setStmtNull');

		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('getlegosstmtfailed');
		$this->listEditor->getLegoListLegos(null);
	}
	public function testGetLegoListLegosNoLegos(): void
	{
		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(true);
		$this->dbhMock
			->expects($this->once())
			->method('getStmt');
		$this->stmtMock
			->expects($this->once())
			->method('rowCount')
			->willReturn(0);
		$this->dbhMock
			->expects($this->once())
			->method('setStmtNull');

		$this->stmtMock
			->method('fetchAll')
			->willReturn([1,2,3]);

		$this->assertEmpty($this->listEditor->getLegoListLegos(null));
	}
	public function testGetLegoListLegosValid(): void
	{
		$data = [1,2,3];

		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(true);
		$this->dbhMock
			->expects($this->exactly(2))
			->method('getStmt');
		$this->stmtMock
			->expects($this->once())
			->method('rowCount')
			->willReturn(1);
		$this->stmtMock
			->expects($this->once())
			->method('fetchAll')
			->willReturn($data);

		$this->assertEquals($data, $this->listEditor->getLegoListLegos(null));
	}
	public function testUpdateLegoListDataStmtFail(): void
	{
		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(false);
		$this->dbhMock
			->expects($this->once())
			->method('setStmtNull');

		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('updatestmtfailed');
		$this->listEditor->updateLegoListData(['listName'=>null, 'isPublic'=>null, 'uid'=>null,'listId'=>null]);
	}
	public function testUpdateLegoListDataValid(): void
	{
		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(true);

		$this->listEditor->updateLegoListData(['listName'=>null, 'isPublic'=>null, 'uid'=>null,'listId'=>null]);
	}
	public function testSetLegoToListStmtFail(): void
	{
		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(false);
		$this->dbhMock
			->expects($this->once())
			->method('setStmtNull');

		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('setstmtfailed');
		$this->listEditor->setLegoToList(['listId'=>null,'legoId'=>null]);
	}
	public function testSetLegoToListValid(): void
	{
		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(true);

		$this->listEditor->setLegoToList(['listId'=>null,'legoId'=>null]);
	}
	public function testDeleteLegoFromListStmtFail(): void
	{
		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(false);
		$this->dbhMock
			->expects($this->once())
			->method('setStmtNull');

		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('deletestmtfailed');
		$this->listEditor->deleteLegoFromList(['listId'=>null,'legoId'=>null]);
	}
	public function testDeleteLegoFromListValid(): void
	{
		$this->dbhMock
			->expects($this->once())
			->method('prepStmt');
		$this->dbhMock
			->expects($this->once())
			->method('execStmt')
			->willReturn(true);

		$this->listEditor->deleteLegoFromList(['listId'=>null,'legoId'=>null]);
	}
}