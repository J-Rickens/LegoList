<?php 

declare(strict_types = 1);
namespace Test\Unit\User\ListEditor;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

// test classes
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\Mock\Exceptions\SuccessException;
// target classes
use Src\Shared\Exceptions\InvalidInputException;
use Src\User\ListEditor\ListEditorContrClass;
// mock classes
use Src\User\ListEditor\ListEditorClass;
use Src\User\ListEditor\ListEditorViewClass;
use Src\User\Add\Lego\LegoClass;
use Src\Shared\Classes\LegoClass as LegoClassS;

class ListEditorContrClassTest extends TestCase
{
	private ListEditorClass $listEditorMock;
	private ListEditorViewClass $listEditorViewMock;
	private LegoClass $legoMock;
	private LegoClassS $legoSMock;
	private ListEditorContrClass $LEContr;

	protected function setUp(): void
	{
		parent::setUp();

		//setup mocks
		$this->listEditorMock = $this->createMock(ListEditorClass::class);
		$this->listEditorViewMock = $this->createMock(ListEditorViewClass::class);
		$this->legoMock = $this->createMock(LegoClass::class);
		$this->legoSMock = $this->createMock(LegoClassS::class);

		//create test contr
		$this->LEContr =  $this->getMockBuilder(ListEditorContrClass::class)
			->onlyMethods(['parentChecks'])
			->setConstructorArgs([$this->legoMock, $this->legoSMock, $this->listEditorMock, $this->listEditorViewMock])
			->getMock();

		//clear session values
		if (session_status() === PHP_SESSION_ACTIVE)
		{
			session_unset();
			session_destroy();
		}
	}

	#[DataProvider('getLegoListValsCases')]
	public function testGetLegoListValsInitialNulls(
		bool $getFull,
		array $valNames,
		array $expReturn,
		string $message
	): void
	{
		$this->assertEquals($expReturn, $this->LEContr->getLegoListVals($getFull, $valNames), $message);
	}
	public static function getLegoListValsCases(): array
	{
		return [
			[true, array(), array(
				'listId'=>null,
				'listName'=>null,
				'isPublic'=>null,
				'uid'=>null,
				'dateCreated'=>null,
				'dateModified'=>null
			), ' fail: full, empty'],
			[true, array('listId', 'uid'), array(
				'listId'=>null,
				'listName'=>null,
				'isPublic'=>null,
				'uid'=>null,
				'dateCreated'=>null,
				'dateModified'=>null
			), ' fail: full, partial'],
			[false, array('listId', 'uid'), array(
				'listId'=>null,
				'uid'=>null
			), ' fail: partial, partial'],
			[false, array(), array(), ' fail: partial, empty']
		];
	}
	#[DataProvider('setLegoListValsCases')]
	public function testSetLegoListVals(
		array $legoListVals,
		array $expReturn,
		string $message
	): void
	{
		$this->LEContr->setLegoListVals($legoListVals);
		$this->assertEquals($expReturn, $this->LEContr->getLegoListVals(), $message);
	}
	public static function setLegoListValsCases(): array
	{
		return [
			[
				array('listId'=>1, 'listName'=>2, 'isPublic'=>3, 'uid'=>4, 'dateCreated'=>5, 'dateModified'=>6),
				array('listId'=>1, 'listName'=>2, 'isPublic'=>3, 'uid'=>4, 'dateCreated'=>5, 'dateModified'=>6),
				'fail: full set'
			],
			[
				array(),
				array('listId'=>null, 'listName'=>null, 'isPublic'=>null, 'uid'=>null, 'dateCreated'=>null, 'dateModified'=>null),
				'fail: no set'
			],
			[
				array('listId'=>1, 'listName'=>2, 'uid'=>4),
				array('listId'=>1, 'listName'=>2, 'isPublic'=>null, 'uid'=>4, 'dateCreated'=>null, 'dateModified'=>null),
				'fail: partial set'
			],
		];
	}
	#[DataProvider('checkListIdCases')]
	public function testCheckListId(
		array $mockReturn,
		string $message
	): void
	{
		session_start();
		$_SESSION['uid'] = 0;

		$this->listEditorMock
			->expects($this->once())
			->method('checkLegoList')
			->willReturn($mockReturn);

		if ($mockReturn[0]) {
			$this->assertTrue($this->LEContr->checkListId(null));
		}
		else {
			$this->assertFalse($this->LEContr->checkListId(null));
		}
		$this->assertEquals($mockReturn[1], $this->LEContr->getEMessage());
	}
	public static function checkListIdCases(): array
	{
		return [
			[[true, null], 'fail: true'],
			[[false, 'listnotfound'], 'fail: false, listnotfound'],
			[[false, 'listpermissionfail'], 'fail: false, listpermissionfail'],
		];
	}
	public function testGetListData(): void
	{
		session_start();
		$_SESSION['listId'] = 0;

		$mockData = [['list_id'=>1, 'list_name'=>2, 'is_public'=>3, 'owner_id'=>4, 'date_created'=>5, 'date_modified'=>6]];
		$expData = ['listId'=>1, 'listName'=>2, 'isPublic'=>3, 'uid'=>4, 'dateCreated'=>5, 'dateModified'=>6];
		$mockLegos = ['1'=>'2','3'=>'4'];

		$this->listEditorMock
			->expects($this->once())
			->method('getLegoListData')
			->willReturn($mockData);
		$this->listEditorMock
			->expects($this->once())
			->method('getLegoListLegos')
			->willReturn($mockLegos);

		$this->LEContr->getListData();
		$this->assertEquals($expData, $this->LEContr->getLegoListVals());
		$this->assertEquals($mockLegos, $this->LEContr->getLegoListLegos());
	}
	#[DataProvider('UpdateLegoListInvalidCases')]
	public function testUpdateLegoListInvalid(
		array $mockSet,
		string $eMessage
	): void
	{
		session_start();
		$_SESSION['uid'] = 0;

		$this->listEditorMock
			->expects($this->exactly($mockSet[0]))
			->method('checkLegoList')
			->willReturn($mockSet[1]);
		$this->LEContr
			->expects($this->exactly($mockSet[2]))
			->method('parentChecks')
			->willThrowException(new InvalidInputException($mockSet[3]));
		$this->listEditorMock
			->expects($this->exactly(0))
			->method('updateLegoListData');

		$this->expectException(InvalidInputException::class);
		$this->expectExceptionMessage($eMessage);
		$this->LEContr->updateLegoList(['listId'=>1, 'uid'=>6]);
	}
	public static function UpdateLegoListInvalidCases(): array
	{
		return [
			[[1, [false, 'listnotfound'], 0, 'null'], 'listid: listnotfound'],
			[[1, [false, 'listpermissionfail'], 0, 'null'], 'listid: listpermissionfail'],
			[[1, [true, null], 1, 'emptyinput'], 'emptyinput'],
			[[1, [true, null], 1, 'name'], 'name'],
			[[1, [true, null], 1, 'pubpri'], 'pubpri'],
			[[1, [true, null], 1, 'uid'], 'uid']
		];
	}
	public function testUpdateLegoListValid(): void
	{
		session_start();
		$_SESSION['uid'] = 0;
		$legoListVals = ['listId'=>1, 'dateModified'=>6];
		$mockReturn = ['listName'=>2, 'isPublic'=>3, 'uid'=>4];
		$expReturn = array(
			'listId'=>1,
			'listName'=>2,
			'isPublic'=>3,
			'uid'=>4
		);

		$this->listEditorMock
			->expects($this->once())
			->method('checkLegoList')
			->willReturn([true, null]);
		$this->LEContr
			->expects($this->once())
			->method('parentChecks')
			->willReturn($mockReturn);
		$this->listEditorMock
			->expects($this->once())
			->method('updateLegoListData')
			->with($expReturn)
			->willThrowException(new SuccessException());

		$this->expectException(SuccessException::class);
		$this->LEContr->updateLegoList($legoListVals);
	}
	#[DataProvider('addLegoToLegoListInvalidCases')]
	public function testAddLegoToLegoListInvalid(
		array $mockSet,
		string $eMessage
	): void
	{
		session_start();
		$_SESSION['uid'] = 0;

		$this->listEditorMock
			->expects($this->exactly($mockSet[0]))
			->method('checkLegoList')
			->willReturn($mockSet[1]);
		$this->legoMock
			->expects($this->exactly($mockSet[2]))
			->method('checkLegoExist')
			->willReturn($mockSet[3]);
		$this->listEditorMock
			->expects($this->exactly($mockSet[4]))
			->method('checkLegoInLegoList')
			->willReturn($mockSet[5]);
		$this->listEditorMock
			->expects($this->exactly(0))
			->method('setLegoToList');

		$this->expectException(InvalidInputException::class);
		$this->expectExceptionMessage($eMessage);
		$this->LEContr->addLegoToLegoList(['listId'=>0, 'legoId'=>0]);
	}
	public static function addLegoToLegoListInvalidCases(): array
	{
		return [
			[[1, [false, 'listnotfound'], 0, true, 0, false], 'listid: listnotfound'],
			[[1, [false, 'listpermissionfail'], 0, true, 0, false], 'listid: listpermissionfail'],
			[[1, [true, null], 1, false, 0, false], 'legonotexists'],
			[[1, [true, null], 1, true, 1, true], 'legoinlist']
		];
	}
	public function testAddLegoToLegoListValid(): void
	{
		session_start();
		$_SESSION['uid'] = 0;

		$this->listEditorMock
			->expects($this->once())
			->method('checkLegoList')
			->willReturn([true, null]);
		$this->legoMock
			->expects($this->once())
			->method('checkLegoExist')
			->willReturn(true);
		$this->listEditorMock
			->expects($this->once())
			->method('checkLegoInLegoList')
			->willReturn(false);
		$this->listEditorMock
			->expects($this->once())
			->method('setLegoToList');

		$this->LEContr->addLegoToLegoList(['listId'=>0, 'legoId'=>0]);
	}
	#[DataProvider('removeLegoFromLegoListInvalidCases')]
	public function testRemoveLegoFromLegoListInvalid(
		array $mockSet,
		string $eMessage
	): void
	{
		session_start();
		$_SESSION['uid'] = 0;

		$this->listEditorMock
			->expects($this->exactly($mockSet[0]))
			->method('checkLegoList')
			->willReturn($mockSet[1]);
		$this->legoMock
			->expects($this->exactly($mockSet[2]))
			->method('checkLegoExist')
			->willReturn($mockSet[3]);
		$this->listEditorMock
			->expects($this->exactly($mockSet[4]))
			->method('checkLegoInLegoList')
			->willReturn($mockSet[5]);
		$this->listEditorMock
			->expects($this->exactly(0))
			->method('deleteLegoFromList');

		$this->expectException(InvalidInputException::class);
		$this->expectExceptionMessage($eMessage);
		$this->LEContr->removeLegoFromLegoList(['listId'=>0, 'legoId'=>0]);
	}
	public static function removeLegoFromLegoListInvalidCases(): array
	{
		return [
			[[1, [false, 'listnotfound'], 0, true, 0, true], 'listid: listnotfound'],
			[[1, [false, 'listpermissionfail'], 0, true, 0, true], 'listid: listpermissionfail'],
			[[1, [true, null], 1, false, 0, true], 'legonotexists'],
			[[1, [true, null], 1, true, 1, false], 'legonotinlist']
		];
	}
	public function testRemoveLegoFromLegoListValid(): void
	{
		session_start();
		$_SESSION['uid'] = 0;

		$this->listEditorMock
			->expects($this->once())
			->method('checkLegoList')
			->willReturn([true, null]);
		$this->legoMock
			->expects($this->once())
			->method('checkLegoExist')
			->willReturn(true);
		$this->listEditorMock
			->expects($this->once())
			->method('checkLegoInLegoList')
			->willReturn(true);
		$this->listEditorMock
			->expects($this->once())
			->method('deleteLegoFromList');

		$this->LEContr->removeLegoFromLegoList(['listId'=>0, 'legoId'=>0]);
	}
}