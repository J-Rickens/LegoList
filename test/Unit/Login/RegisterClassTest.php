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

	private RegisterClass $register;

	protected function setUp(): void
	{
		parent::setUp();

		$this->register = new RegisterClass(new MockDbhClass());
	}

	public function testCheckUserExistMockFailedStmt(): void
	{
		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('checkstmtfailed');
		$this->register->checkUserExist(null,null);
	}

	public function testCheckUserExistIfTrue(): void
	{
		$this->assertTrue($this->register->checkUserExist('123','e'));
	}

	public function testCheckUserExistIfFalse(): void
	{
		$this->assertFalse($this->register->checkUserExist('1234','e'));
	}

	public function testSetUserInvalidInputs(): void
	{
		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('setstmtfailed');
		$this->register->setUser(array('usna'=>null, 'email'=>'1', 'name'=>'1', 'pwd'=>'1'));
	}

	public function testSetUserValidInputs(): void
	{
		$this->expectException(SuccessException::class);
		$this->register->setUser(array('usna'=>'1234', 'email'=>'1', 'name'=>'1', 'pwd'=>'1'));
	}
}