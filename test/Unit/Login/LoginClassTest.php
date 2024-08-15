<?php 

declare(strict_types = 1);
namespace Test\Unit\Login;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\Mock\Classes\MockDbhClass;
use Test\Mock\Exceptions\SuccessException;

use Src\Shared\Exceptions\StmtFailedException;
use Src\Login\LoginClass;


class LoginClassTest extends TestCase
{

	private LoginClass $login;

	protected function setUp(): void
	{
		parent::setUp();

		$this->login = new LoginClass(new MockDbhClass());
	}

	/*#[DataProvider('getUserValidCases')]
	public function testGetUserValidInputs(
		array $userVals
	): void
	{
		$this->expectException(SuccessException::class);
		$this->login->getUser($userVals);
	}
	public static function getUserValidCases(): array
	{
		return [
			[['usna'=>'username','pwd'=>'password']],
			[['usna'=>'1','pwd'=>'1']]
		];
	}*/

	#[DataProvider('getUserInvalidCases')]
	public function testGetUserInvalidInputs(
		array $userVals,
		string $errorMessage
	): void
	{
		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage($errorMessage);
		$this->login->getUser($userVals);
	}
	public static function getUserInvalidCases(): array
	{
		return [
			//[['usna'=>'','pwd'=>''],'getstmtfailed'],
			//[['usna'=>'1','pwd'=>''],'usernotfound'],
			//[['usna'=>'','pwd'=>'1'],'wrongpassword'],
			[['usna'=>null,'pwd'=>null],'getstmtfailed'],
			//[['usna'=>'1','pwd'=>null],'usernotfound'],
		];
	}
}