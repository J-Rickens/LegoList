<?php 

declare(strict_types = 1);
namespace test\unit\Shared\Tp;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use Src\Shared\Exceptions\UndefinedVariableException;
use Src\Shared\Tp\OpenerTp;


class OpenerTpTest extends TestCase
{
	private OpenerTp $openerTp;

	protected function setUp(): void
	{
		parent::setUp();

		$this->openerTp = new OpenerTp();
	}

	public function testStartSessionWhenNoneExists(): void
	{
		if (session_status() === PHP_SESSION_ACTIVE)
		{
			session_unset();
			session_destroy();
		}
		$this->openerTp->startSession();
		$this->assertEquals(session_status(), PHP_SESSION_ACTIVE);
	}

	public function testStartSessionWhenSessionExists(): void
	{
		if (session_status() === PHP_SESSION_NONE)
		{
			session_start();
		}
		$this->openerTp->startSession();
		$this->assertEquals(session_status(), PHP_SESSION_ACTIVE);
	}

	#[DataProvider('urlReturnValidCases')]
	public function testUrlReturnValidInputs(
		int $urlLvl,
		string $expectedReturn): void
	{
		$this->openerTp->setUrlReturn($urlLvl);
		$this->assertEquals($this->openerTp->getUrlReturn(),$expectedReturn);
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
		$openerTp = new OpenerTp();
		$this->expectException(UndefinedVariableException::class);
		$openerTp->getUrlReturn();
	}

}