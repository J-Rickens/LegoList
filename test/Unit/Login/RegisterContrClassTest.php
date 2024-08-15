<?php 

declare(strict_types = 1);
namespace Test\Unit\Login;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\Mock\Classes\MockRegisterClass;
use Test\Mock\Exceptions\SuccessException;

use Src\Shared\Exceptions\InvalidInputException;
use Src\Login\RegisterContrClass;


class RegisterContrClassTest extends TestCase
{

	private RegisterContrClass $registerContr;

	protected function setUp(): void
	{
		parent::setUp();

		$this->registerContr = new RegisterContrClass(array(), new MockRegisterClass());
	}

	public function testEmptyInitialUserValsIsNull(): void
	{
		$registerContr = new RegisterContrClass();

		foreach ($registerContr->getUserVals() as $key => $value) {
			$this->assertNull($value, $key .' failed null');
		}
	}

	public function testInitialUserValsWhenNotNull(): void
	{
		$registerContr = new RegisterContrClass(array(
			'name'=>'not null',
			'email'=>'not null',
			'usna'=>'not null',
			'pwd'=>'not null',
			'pwd2'=>'not null'
		));

		foreach ($registerContr->getUserVals() as $key => $value) {
			$this->assertEquals('not null', $value, $key .' failed not null');
		}
	}

	public function testSetUserVals(): void
	{
		$this->registerContr->setUserVals(array(
			'name'=>'something',
			'email'=>'something',
			'usna'=>'something',
			'pwd'=>'something',
			'pwd2'=>'something'
		));

		foreach ($this->registerContr->getUserVals() as $key => $value) {
			$this->assertEquals('something', $value, $key .' failed set');
		}
	}

	#[DataProvider('registerUserValidCases')]
	public function testRegisterUserValidInputs(
		array $userVals
	): void
	{
		$this->expectException(SuccessException::class);
		$this->registerContr->registerUser($userVals);
	}
	public static function registerUserValidCases(): array
	{
		return [
			[['name'=>'abz','email'=>'a@b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678']],
			[['name'=>'abzABZaaasaaaaaaaaas','email'=>'a@b.cz','usna'=>'abzABZ190_1234567890','pwd'=>'12345678901234567890abzABZ123456','pwd2'=>'12345678901234567890abzABZ123456']],
			//[['name'=>'abz','email'=>'aAbBzZ12390.%+-ab@aAbBzZ12390.-ab.abzABZ1290','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678']], //regex would validate
			[['name'=>'abz','email'=>'asdf+asdf1@b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678']],
			[['name'=>'abz','email'=>'asdf-as.df1@b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678']],
			[['name'=>'abz','email'=>'a.a@b.b.b.b.b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678']],
			[['name'=>'abz','email'=>'a.a@b-b-b-b-b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678']],
			//[['name'=>'abz','email'=>'a.a@b...cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678']], //regex would validate
			//[['name'=>'abz','email'=>'a.a@b--.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678']], //regex would validate
			//[['name'=>'abz','email'=>'a@b.12','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678']], //regex would validate
			[['name'=>'abz','email'=>'a@b.a12','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678']]
		];
	}

	#[DataProvider('registerUserInvalidCases')]
	public function testRegisterUserInvalidInputs(
		array $userVals,
		string $errorMessage
	): void
	{
		$this->expectException(InvalidInputException::class);
		$this->expectExceptionMessage($errorMessage);
		$this->registerContr->registerUser($userVals);
	}
	public static function registerUserInvalidCases(): array
	{
		return [
			[['name'=>'','email'=>'','usna'=>'','pwd'=>'','pwd2'=>''],'emptyinput'],
			[['name'=>null,'email'=>null,'usna'=>null,'pwd'=>null,'pwd2'=>null],'emptyinput'],
			[['name'=>null,'email'=>'a@b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678'],'emptyinput'],
			[['name'=>'abz','email'=>null,'usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678'],'emptyinput'],
			[['name'=>'abz','email'=>'a@b.cz','usna'=>null,'pwd'=>'12345678','pwd2'=>'12345678'],'emptyinput'],
			[['name'=>'abz','email'=>'a@b.cz','usna'=>'abz','pwd'=>null,'pwd2'=>'12345678'],'emptyinput'],
			[['name'=>'abz','email'=>'a@b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>null],'emptyinput'],
			[['name'=>'','email'=>'a@b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678'],'emptyinput'],
			[['name'=>'abz','email'=>'','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678'],'emptyinput'],

			[['name'=>'ab','email'=>'a@b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678'],'name'],
			[['name'=>'abzABZaaasaaaaaaaaasa','email'=>'a@b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678'],'name'],
			[['name'=>'ab12z','email'=>'a@b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678'],'name'],
			[['name'=>'ab-.z','email'=>'a@b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678'],'name'],
			[['name'=>'ab@!#$z','email'=>'a@b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678'],'name'],

			[['name'=>'abz','email'=>'a@b.cz','usna'=>'ab','pwd'=>'12345678','pwd2'=>'12345678'],'username'],
			[['name'=>'abz','email'=>'a@b.cz','usna'=>'abzABZ190_12345678901','pwd'=>'12345678','pwd2'=>'12345678'],'username'],
			[['name'=>'abz','email'=>'a@b.cz','usna'=>'ab-.z','pwd'=>'12345678','pwd2'=>'12345678'],'username'],
			[['name'=>'abz','email'=>'a@b.cz','usna'=>'a!#$bz','pwd'=>'12345678','pwd2'=>'12345678'],'username'],
			[['name'=>'abz','email'=>'a@b.cz','usna'=>'@abz','pwd'=>'12345678','pwd2'=>'12345678'],'username'],

			[['name'=>'abz','email'=>'a@b.','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678'],'email'],
			[['name'=>'abz','email'=>'a@.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678'],'email'],
			[['name'=>'abz','email'=>'@b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678'],'email'],
			[['name'=>'abz','email'=>'a@b@b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678'],'email'],
			[['name'=>'abz','email'=>'a"@b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678'],'email'],

			[['name'=>'abz','email'=>'a@a.ao','usna'=>'111','pwd'=>'12345678','pwd2'=>'12345678'],'useroremailtaken'],
			[['name'=>'abz','email'=>'a@b.cz','usna'=>'222','pwd'=>'12345678','pwd2'=>'12345678'],'useroremailtaken'],
			[['name'=>'abz','email'=>'b@b.bo','usna'=>'abz','pwd'=>'12345678','pwd2'=>'12345678'],'useroremailtaken'],

			[['name'=>'abz','email'=>'a@b.cz','usna'=>'abz','pwd'=>'1234567','pwd2'=>'12345678'],'password'],
			[['name'=>'abz','email'=>'a@b.cz','usna'=>'abz','pwd'=>'12345678901234567890abzABZ1234567','pwd2'=>'12345678'],'password'],
			[['name'=>'abz','email'=>'a@b.cz','usna'=>'abz','pwd'=>'1234 _-.5678','pwd2'=>'12345678'],'password'],
			[['name'=>'abz','email'=>'a@b.cz','usna'=>'abz','pwd'=>'1234!@#$^&*()[]{}5678','pwd2'=>'12345678'],'password'],

			[['name'=>'abz','email'=>'a@b.cz','usna'=>'abz','pwd'=>'1234567890','pwd2'=>'12345678'],'passworddmatch'],
			[['name'=>'abz','email'=>'a@b.cz','usna'=>'abz','pwd'=>'12345678','pwd2'=>'1234567890'],'passworddmatch']
		];
	}
}