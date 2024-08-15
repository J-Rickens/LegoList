<?php 

declare(strict_types = 1);
namespace Test\Unit\User\Add\LegoList;
require __DIR__ . '\\..\\..\\..\\..\\..\\vendor\\autoload.php';

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\Mock\Classes\MockLegoListClass;
use Test\Mock\Exceptions\SuccessException;

use Src\Shared\Exceptions\InvalidInputException;
use Src\User\Add\LegoList\LegoListContrClass;


class LegoListContrClassTest extends TestCase
{

	private LegoListContrClass $legoListContr;

	protected function setUp(): void
	{
		parent::setUp();

		$this->legoListContr = new LegoListContrClass(array(), new MockLegoListClass());
	}

	public function testEmptyInitialLegoValIsNull(): void
	{
		$legoList = new LegoListContrClass(array());

		foreach ($legoList->getLegoListVals() as $key => $value) {
			$this->assertNull($value, $key .' failed null');
		}
	}

	public function testInitialLegoValWhenNotNull(): void
	{
		$legoList = new LegoListContrClass(array(
			'listName'=>'not null',
			'pubPri'=>'not null',
			'uid'=>'not null'
		));

		foreach ($legoList->getLegoListVals() as $key => $value) {
			$this->assertEquals('not null', $value, $key .' failed not null');
		}
	}

	public function testSetLegoListVals(): void
	{
		$this->legoListContr->setLegoListVals(array(
			'listName'=>'something',
			'pubPri'=>'something',
			'uid'=>'something'
		));

		foreach ($this->legoListContr->getLegoListVals() as $key => $value) {
			$this->assertEquals('something', $value, $key .' failed set');
		}
	}

	#[DataProvider('addLegoListValidCases')]
	public function testAddLegoListValidInputs(
		array $legoListVals
	): void
	{
		$this->expectException(SuccessException::class);
		$this->legoListContr->addLegoList($legoListVals);
	}
	public static function addLegoListValidCases(): array
	{
		return [
			[['listName'=>'123','pubPri'=>'public','uid'=>'1']],
			[['listName'=>'123456789012345678901234567890abzABZ -12','pubPri'=>'private','uid'=>'11111111111']]
		];
	}

	#[DataProvider('addLegoListInvalidCases')]
	public function testAddLegoListInvalidInputs(
		array $legoListVals,
		string $errorMessage
	): void
	{
		$this->expectException(InvalidInputException::class);
		$this->expectExceptionMessage($errorMessage);
		$this->legoListContr->addLegoList($legoListVals);
		

	}
	public static function addLegoListInvalidCases(): array
	{
		return [
			[['listName'=>null,'pubPri'=>null,'uid'=>null],'emptyinput'],
			[['listName'=>'','pubPri'=>'public','uid'=>'1'],'emptyinput'],
			[['listName'=>'123','pubPri'=>'public'],'emptyinput'],

			[['listName'=>'12','pubPri'=>'public','uid'=>'1'],'name'],
			[['listName'=>'12345678901234567890123456789012345678901','pubPri'=>'public','uid'=>'1'],'name'],
			[['listName'=>'123!@#$%^&*()_=+`~[{]}\\|;:<>,./?"\'','pubPri'=>'public','uid'=>'1'],'name'],

			[['listName'=>'123','pubPri'=>'Public','uid'=>'1'],'pubpri'],
			[['listName'=>'123','pubPri'=>'PUBLIC','uid'=>'1'],'pubpri'],
			[['listName'=>'123','pubPri'=>'pubpri','uid'=>'1'],'pubpri'],

			[['listName'=>'123','pubPri'=>'public','uid'=>'-1'],'uid'],
			//[['listName'=>'123','pubPri'=>'public','uid'=>'0'],'uid'],
			[['listName'=>'123','pubPri'=>'public','uid'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'],'uid'],
			[['listName'=>'123','pubPri'=>'public','uid'=>'!@#$%^&*()-_=+`~[{]}\\|;:",<.>/?\''],'uid'],
		];
	}
}