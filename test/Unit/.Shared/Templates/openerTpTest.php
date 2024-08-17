<?php 

declare(strict_types = 1);
namespace Test\Unit\Shared\Tp;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use Src\Shared\Exceptions\UndefinedVariableException;
use Src\Shared\Tp\OpenerTp;


class OpenerTpTest extends TestCase
{
	private $openerTpMock;

	protected function setUp(): void
	{
		parent::setUp();

		$this->openerTpMock = $this->getMockBuilder(OpenerTp::class)
			->onlyMethods(['validate'])
			->getMock();
	}

	public function testStartSessionWhenNoneExists(): void
	{
		//create validate mock always false
		$this->openerTpMock->method('validate')->willReturn(false);

		//clear session values
		if (session_status() === PHP_SESSION_ACTIVE)
		{
			session_unset();
			session_destroy();
		}

		$this->assertTrue($this->openerTpMock->startSession());
		$this->assertEquals(session_status(), PHP_SESSION_ACTIVE);
		$this->assertFalse(isset($_SESSION['validator']));
	}

	public function testStartSessionWhenEmptySessionExists(): void
	{
		//create validate mock always false
		$this->openerTpMock->method('validate')->willReturn(false);

		//clear session values
		if (session_status() === PHP_SESSION_ACTIVE)
		{
			session_unset();
			session_destroy();
		}
		session_start();

		$this->assertTrue($this->openerTpMock->startSession());
		$this->assertEquals(session_status(), PHP_SESSION_ACTIVE);
		$this->assertFalse(isset($_SESSION['validator']));
	}

	public function testStartSessionWhenInvalidSessionExists(): void
	{
		//create validate mock always false
		$this->openerTpMock->method('validate')->willReturn(false);

		//clear session values
		if (session_status() === PHP_SESSION_ACTIVE)
		{
			session_unset();
			session_destroy();
		}
		session_start();
		$_SESSION['uid'] = '1';
		$_SESSION['validator'] = 'fake exists';

		$this->assertTrue($this->openerTpMock->startSession());
		$this->assertEquals(session_status(), PHP_SESSION_ACTIVE);
		$this->assertFalse(isset($_SESSION['validator']));
	}

	public function testStartSessionWhenValidSessionExists(): void
	{
		//create validate mock always true
		$this->openerTpMock->method('validate')->willReturn(true);

		//clear session values
		if (session_status() === PHP_SESSION_ACTIVE)
		{
			session_unset();
			session_destroy();
		}
		session_start();
		$_SESSION['uid'] = '1';
		$_SESSION['validator'] = 'exists';

		$this->assertFalse($this->openerTpMock->startSession());
		$this->assertEquals(session_status(), PHP_SESSION_ACTIVE);
		$this->assertTrue(isset($_SESSION['validator']));
	}

	#[DataProvider('urlReturnValidCases')]
	public function testUrlReturnValidInputs(
		int $urlLvl,
		string $expectedReturn): void
	{
		$this->openerTpMock->setUrlReturn($urlLvl);
		$this->assertEquals($this->openerTpMock->getUrlReturn(),$expectedReturn);
	}
	public static function urlReturnValidCases(): array
	{
		return [
			[-2,""],
			[0,""],
			[1, "../"],
			[3, "../../../"],
		];
	}

	public function testUrlReturnThrowErrorIfNotSet(): void
	{
		$openerTpMock = new OpenerTp();
		$this->expectException(UndefinedVariableException::class);
		$openerTpMock->getUrlReturn();
	}

}