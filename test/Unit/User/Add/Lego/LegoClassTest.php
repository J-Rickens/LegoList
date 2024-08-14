<?php 

declare(strict_types = 1);
namespace Test\Unit\User\Add\Lego;
require __DIR__ . '\\..\\..\\..\\..\\..\\vendor\\autoload.php';

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\Mock\Classes\MockDbhClass;
use Test\Mock\Exceptions\SuccessException;

use Src\Shared\Exceptions\StmtFailedException;
use Src\User\Add\Lego\LegoClass;


class LegoClassTest extends TestCase
{
	private LegoClass $lego;

	protected function setUp(): void
	{
		parent::setUp();

		$this->lego = new LegoClass(new MockDbhClass());
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
		$this->expectException(SuccessException::class);
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