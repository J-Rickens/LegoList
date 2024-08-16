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

	private $dbh;
	private LoginClass $login;

	protected function setUp(): void
	{
		parent::setUp();

		$this->dbh = new MockDbhClass();
		$this->login = new LoginClass($this->dbh);
	}

	/*#[DataProvider('getUserValidCases')]
	public function testGetUserValidInputs(
		array $userVals,
		array $testCode,
		array $rows
	): void
	{
		$this->dbh->setTestingConditions($testCode, $rows);
		$this->expectException(SuccessException::class);
		$this->login->getUser($userVals);
	}
	public static function getUserValidCases(): array
	{
		return [
			[['usna'=>'username','pwd'=>'password'],[['stmtFail'=>false, 'fetch'=>false, 'exists'=>false]],[[]]],
			[['usna'=>'1','pwd'=>'1'],[['stmtFail'=>false, 'fetch'=>false, 'exists'=>false]],[[]]]
		];
	}*/

	#[DataProvider('getUserInvalidCases')]
	public function testGetUserInvalidInputs(
		array $userVals,
		array $testCode,
		string $errorMessage,
		array $rows
	): void
	{
		$this->dbh->setTestingConditions($testCode,$rows);
		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage($errorMessage);
		$this->login->getUser($userVals);
	}
	public static function getUserInvalidCases(): array
	{
		return [
			[['usna'=>'','pwd'=>''],[['stmtFail'=>true]],'getstmtfailed',[[]]],
			[['usna'=>'1','pwd'=>''],[['fetch'=>true, 'exists'=>false]],'usernotfound',[[]]],
			//[['usna'=>'','pwd'=>'1'],[['fetch'=>true, 'exists'=>true]],'wrongpassword',[[]]],
			[['usna'=>null,'pwd'=>null],[['stmtFail'=>true]],'getstmtfailed',[[]]],
			[['usna'=>'1','pwd'=>null],[['fetch'=>true, 'exists'=>false]],'usernotfound',[[]]],
		];
	}
}