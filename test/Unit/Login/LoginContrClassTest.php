<?php 

declare(strict_types = 1);
namespace Test\Unit\Login;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\Mock\Classes\MockLoginClass;
use Test\Mock\Exceptions\SuccessException;

use Src\Shared\Exceptions\InvalidInputException;
use Src\Login\LoginContrClass;


class LoginContrClassTest extends TestCase
{

	private LoginContrClass $loginContr;

	protected function setUp(): void
	{
		parent::setUp();

		$this->loginContr = new LoginContrClass(array(), new MockLoginClass());
	}

	public function testEmptyInitialUserValsIsNull(): void
	{
		$login = new LoginContrClass();

		foreach ($login->getUserVals() as $key => $value) {
			$this->assertNull($value, $key .' failed null');
		}
	}

	public function testInitialUserValsWhenNotNull(): void
	{
		$login = new LoginContrClass(array(
			'usna'=>'not null',
			'pwd'=>'not null'
		));

		foreach ($login->getUserVals() as $key => $value) {
			$this->assertEquals('not null', $value, $key .' failed not null');
		}
	}

	public function testSetUserVals(): void
	{
		$this->loginContr->setUserVals(array(
			'usna'=>'something',
			'pwd'=>'something'
		));

		foreach ($this->loginContr->getUserVals() as $key => $value) {
			$this->assertEquals('something', $value, $key .' failed set');
		}
	}

	#[DataProvider('loginUserValidCases')]
	public function testLoginUserValidInputs(
		array $userVals
	): void
	{
		$this->expectException(SuccessException::class);
		$this->loginContr->loginUser($userVals);
	}
	public static function loginUserValidCases(): array
	{
		return [
			[['usna'=>'username','pwd'=>'password']],
			[['usna'=>'1','pwd'=>'1']]
		];
	}

	#[DataProvider('loginUserInvalidCases')]
	public function testLoginUserInvalidInputs(
		array $userVals,
		string $errorMessage
	): void
	{
		$this->expectException(InvalidInputException::class);
		$this->expectExceptionMessage($errorMessage);
		$this->loginContr->loginUser($userVals);
	}
	public static function loginUserInvalidCases(): array
	{
		return [
			[['usna'=>'','pwd'=>''],'emptyinput'],
			[['usna'=>'1','pwd'=>''],'emptyinput'],
			[['usna'=>'','pwd'=>'1'],'emptyinput'],

			[['usna'=>null,'pwd'=>null],'emptyinput'],
			[['usna'=>'1','pwd'=>null],'emptyinput'],
			[['usna'=>null,'pwd'=>'1'],'emptyinput'],

			[['usna'=>null,'pwd'=>''],'emptyinput'],
			[['usna'=>'','pwd'=>null],'emptyinput']
		];
	}
}