<?php 

declare(strict_types = 1);
namespace Test\Unit\User\Add\Lego;
require __DIR__ . '\\..\\..\\..\\..\\..\\vendor\\autoload.php';

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\Mock\Classes\MockLegoClass;
use Test\Mock\Exceptions\SuccessException;

use Src\Shared\Exceptions\InvalidInputException;
use Src\User\Add\Lego\LegoContrClass;


class LegoContrClassTest extends TestCase
{

	private LegoContrClass $legoContr;

	protected function setUp(): void
	{
		parent::setUp();

		$this->legoContr = new LegoContrClass(array(), new MockLegoClass());
	}

	public function testEmptyInitialLegoValIsNull(): void
	{
		$lego = new LegoContrClass(array());

		foreach ($lego->getLegoVal() as $key => $value) {
			$this->assertNull($value, $key .' failed null');
		}
	}

	public function testInitialLegoValWhenNotNull(): void
	{
		$lego = new LegoContrClass(array(
			'legoID'=>'not null',
			'pieceCount'=>'not null',
			'legoName'=>'not null',
			'collection'=>'not null',
			'cost'=>'not null'
		));

		foreach ($lego->getLegoVal() as $key => $value) {
			$this->assertEquals('not null', $value, $key .' failed not null');
		}
	}

	public function testSetLegoVal(): void
	{
		$this->legoContr->setLegoVal(array(
			'legoID'=>'something',
			'pieceCount'=>'something',
			'legoName'=>'something',
			'collection'=>'something',
			'cost'=>'something'
		));

		foreach ($this->legoContr->getLegoVal() as $key => $value) {
			$this->assertEquals('something', $value, $key .' failed set');
		}
	}

	#[DataProvider('addLegoValidCases')]
	public function testAddLegoValidInputs(
		array $legoVal
	): void
	{
		$this->expectException(SuccessException::class);
		$this->legoContr->addLego($legoVal);
	}
	public static function addLegoValidCases(): array
	{
		return [
			[['legoID'=>'1234','pieceCount'=>'1234']],
			[['legoID'=>'123','pieceCount'=>'1','legoName'=>'123','collection'=>'123','cost'=>'1']],
			[['legoID'=>'12345678','pieceCount'=>'1234567890','legoName'=>'123456789012345678901234567890abzABZ -12',
				'collection'=>'1234567890abzABZ 123','cost'=>'$123,456.78']],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>'123456.78']],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>'123456']],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>'$1,234']],
			[['legoID'=>'123','pieceCount'=>'1','legoName'=>'123','collection'=>'123','cost'=>null]],
			[['legoID'=>'123','pieceCount'=>'1','legoName'=>'','collection'=>'','cost'=>'']]
		];
	}

	#[DataProvider('addLegoInvalidCases')]
	public function testAddLegoInvalidInputs(
		array $legoVal,
		string $errorMessage
	): void
	{
		$this->expectException(InvalidInputException::class);
		$this->expectExceptionMessage($errorMessage);
		$this->legoContr->addLego($legoVal);
		

	}
	public static function addLegoInvalidCases(): array
	{
		return [
			[['legoID'=>'1234'],'emptyinput'],
			[['pieceCount'=>'1234'],'emptyinput'],
			[['cost'=>'5'],'emptyinput'],

			[['legoID'=>'0','pieceCount'=>'1234'],'emptyinput'],
			[['legoID'=>'1','pieceCount'=>'1234'],'legoid'],
			[['legoID'=>'asdf','pieceCount'=>'1234'],'legoid'],
			[['legoID'=>'1asdf1','pieceCount'=>'1234'],'legoid'],
			[['legoID'=>'123456789','pieceCount'=>'1234'],'legoid'],

			[['legoID'=>'111','pieceCount'=>'1234'],'legoexists'],

			[['legoID'=>'1234','pieceCount'=>'0'],'emptyinput'],
			[['legoID'=>'1234','pieceCount'=>'-11'],'piececount'],
			[['legoID'=>'1234','pieceCount'=>'12345678909'],'piececount'],
			[['legoID'=>'1234','pieceCount'=>'one'],'piececount'],
			[['legoID'=>'1234','pieceCount'=>'#12'],'piececount'],

			//[['legoID'=>'1234','pieceCount'=>'1234','legoName'=>''],'name'],
			[['legoID'=>'1234','pieceCount'=>'1234','legoName'=>'12'],'name'],
			[['legoID'=>'1234','pieceCount'=>'1234','legoName'=>'12345678901234567890123456789012345678901'],'name'],
			[['legoID'=>'1234','pieceCount'=>'1234','legoName'=>'1234#'],'name'],

			//[['legoID'=>'1234','pieceCount'=>'1234','collection'=>''],'collection'],
			[['legoID'=>'1234','pieceCount'=>'1234','collection'=>'12'],'collection'],
			[['legoID'=>'1234','pieceCount'=>'1234','collection'=>'123456789012345678901'],'collection'],
			[['legoID'=>'1234','pieceCount'=>'1234','collection'=>'1234#'],'collection'],

			//[['legoID'=>'1234','pieceCount'=>'1234','cost'=>''],'cost'],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>'05'],'cost'],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>'$05'],'cost'],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>'$$123'],'cost'],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>'$.99'],'cost'],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>'1234567'],'cost'],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>'1,23'],'cost'],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>',123'],'cost'],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>'1,1234'],'cost'],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>'1,234,567'],'cost'],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>'1234,567'],'cost'],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>'1.001'],'cost'],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>'1.1'],'cost'],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>'#234'],'cost'],
			[['legoID'=>'1234','pieceCount'=>'1234','cost'=>'a234'],'cost']
		];
	}
}