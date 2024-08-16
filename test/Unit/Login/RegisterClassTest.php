<?php 

declare(strict_types = 1);
namespace Test\Unit\Login;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\Mock\Classes\MockDbhClass;
use Test\Mock\Exceptions\SuccessException;

use Src\Shared\Exceptions\StmtFailedException;
use Src\Login\RegisterClass;


class RegisterClassTest extends TestCase
{

	private $dbh;
	private RegisterClass $register;

	protected function setUp(): void
	{
		parent::setUp();

		$this->dbh = new MockDbhClass();
		$this->register = new RegisterClass($this->dbh);
	}

	public function testCheckUserExistMockFailedStmt(): void
	{
		$this->dbh->setTestingConditions([['stmtFail'=>true]]);
		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('checkstmtfailed');
		$this->register->checkUserExist(null,null);
	}

	public function testCheckUserExistIfTrue(): void
	{
		$this->dbh->setTestingConditions([['fetch'=>true, 'exists'=>true]]);
		$this->assertTrue($this->register->checkUserExist('1','e'));
	}

	public function testCheckUserExistIfFalse(): void
	{
		$this->dbh->setTestingConditions([['fetch'=>true, 'exists'=>false]]);
		$this->assertFalse($this->register->checkUserExist('1','e'));
	}

	public function testSetUserInvalidInputs(): void
	{
		$this->dbh->setTestingConditions([['stmtFail'=>true]]);
		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('setstmtfailed');
		$this->register->setUser(array('usna'=>null, 'email'=>null, 'name'=>null, 'pwd'=>'1'));
	}

	public function testSetUserValidInputs(): void
	{
		$this->dbh->setTestingConditions([[]]);
		$this->expectException(SuccessException::class);
		$this->register->setUser(array('usna'=>'1', 'email'=>'1', 'name'=>'1', 'pwd'=>'1'));
	}
}