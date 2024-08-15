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
	private LegoListClass $legoList;

	protected function setUp(): void
	{
		parent::setUp();

		$this->legoList = new LegoListClass(new MockDbhClass());
	}

	public function testSetLegoMockFailedStmt(): void
	{
		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('setstmtfailed');
		$this->legoList->setLegoList(['listName'=>null,'pubPri'=>null,'uid'=>null]);
	}

	#[DataProvider('setLegoListValidCases')]
	public function testSetLegoListValid(
		array $legoListVals
	): void
	{
		$this->expectException(SuccessException::class);
		$this->legoList->setLegoList($legoListVals);
	}
	public static function setLegoListValidCases(): array
	{
		return [
			[['listName'=>'1','pubPri'=>true,'uid'=>'1']],
			[['listName'=>'1','pubPri'=>False,'uid'=>'1']],
			[['listName'=>'Name 1','pubPri'=>true,'uid'=>'1']]
		];
	}
}