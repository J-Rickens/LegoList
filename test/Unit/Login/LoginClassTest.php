<?php 

declare(strict_types = 1);
namespace Test\Unit\Login;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\Mock\Classes\MockDbhClass;

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

	#[DataProvider('getUserValidCases')]
	public function testGetUserValidInputs(
		array $userVals,
		array $testCode,
		array $rows
	): void
	{
		$keyNames = ['user_id'=>'uid','username'=>'username','name'=>'name','date_created'=>'userdate'];
		$this->dbh->setTestingConditions($testCode, $rows);
		$this->login->getUser($userVals);
		foreach ($rows[1][0] as $key => $value) {
			$this->assertEquals($value, $_SESSION[$keyNames[$key]], $key .' failed equals');
		}
	}
	public static function getUserValidCases(): array
	{
		return [
			array(['usna'=>'user','pwd'=>'1'],[['fetch'=>true, 'exists'=>true],['fetch'=>true, 'exists'=>true]],[
				[['password'=>'1']],[['user_id'=>'1','username'=>'user','name'=>'use','date_created'=>'1/1/01']]
			]),
			array(['usna'=>'user','pwd'=>'1234567890asdf'],[['fetch'=>true, 'exists'=>true],['fetch'=>true, 'exists'=>true]],[
				[['password'=>'1234567890asdf']],[['user_id'=>'1','username'=>'user','name'=>'use','date_created'=>'1/1/01']]
			])
		];
	}

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
			array(['usna'=>'user','pwd'=>'1'],[['stmtFail'=>true, 'fetch'=>true, 'exists'=>true],['stmtFail'=>false, 'fetch'=>true, 'exists'=>true]],'getstmtfailed1',[
				[['password'=>'1']],[['user_id'=>'1','username'=>'user','name'=>'use','date_created'=>'1/1/01']]
			]),

			array(['usna'=>'user','pwd'=>'1'],[['stmtFail'=>false, 'fetch'=>true, 'exists'=>false],['stmtFail'=>false, 'fetch'=>true, 'exists'=>true]],'usernotfound1',[
				[['password'=>'1']],[['user_id'=>'1','username'=>'user','name'=>'use','date_created'=>'1/1/01']]
			]),

			array(['usna'=>'user','pwd'=>'12'],[['stmtFail'=>false, 'fetch'=>true, 'exists'=>true],['stmtFail'=>false, 'fetch'=>true, 'exists'=>true]],'wrongpassword',[
				[['password'=>'1234']],[['user_id'=>'1','username'=>'user','name'=>'use','date_created'=>'1/1/01']]
			]),

			array(['usna'=>'user','pwd'=>'1'],[['stmtFail'=>false, 'fetch'=>true, 'exists'=>true],['stmtFail'=>true, 'fetch'=>true, 'exists'=>true]],'getstmtfailed2',[
				[['password'=>'1']],[['user_id'=>'1','username'=>'user','name'=>'use','date_created'=>'1/1/01']]
			]),

			array(['usna'=>'user','pwd'=>'1'],[['stmtFail'=>false, 'fetch'=>true, 'exists'=>true],['stmtFail'=>false, 'fetch'=>true, 'exists'=>false]],'usernotfound2',[
				[['password'=>'1']],[['user_id'=>'1','username'=>'user','name'=>'use','date_created'=>'1/1/01']]
			])
		];
	}
}