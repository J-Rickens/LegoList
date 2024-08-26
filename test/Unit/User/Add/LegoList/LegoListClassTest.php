<?php 

declare(strict_types = 1);
namespace Test\Unit\User\Add\LegoList;
require __DIR__ . '\\..\\..\\..\\..\\..\\vendor\\autoload.php';

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\Mock\Classes\MockDbhClass;
use Test\Mock\Exceptions\SuccessException;

use Src\Shared\Exceptions\StmtFailedException;
use Src\User\Add\LegoList\LegoListClass;


class LegoListClassTest extends TestCase
{
	private $dbh;
	private LegoListClass $legoList;

	protected function setUp(): void
	{
		parent::setUp();

		$this->dbh = new MockDbhClass();
		$this->legoList = new LegoListClass($this->dbh);
	}

	public function testSetLegoMockFailedStmt(): void
	{
		$this->dbh->setTestingConditions([['stmtFail'=>true]]);
		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('setstmtfailed');
		$this->legoList->setLegoList(['listName'=>null,'isPublic'=>null,'uid'=>null]);
	}

	#[DataProvider('setLegoListValidCases')]
	public function testSetLegoListValid(
		array $legoListVals,
		array $testCode
	): void
	{
		$this->dbh->setTestingConditions($testCode);
		$this->expectException(SuccessException::class);
		$this->legoList->setLegoList($legoListVals);
	}
	public static function setLegoListValidCases(): array
	{
		return [
			[['listName'=>'1','isPublic'=>true,'uid'=>'1'],[[]]],
			[['listName'=>'1','isPublic'=>False,'uid'=>'1'],[[]]],
			[['listName'=>'Name 1','isPublic'=>true,'uid'=>'1'],[[]]]
		];
	}
}