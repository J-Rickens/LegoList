<?php 

declare(strict_types = 1);
namespace Test\Unit\Shared\Classes;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

use PHPUnit\Framework\TestCase;
use Src\Shared\Classes\SessionValidatorClass;

class SessionValidatorClassTest extends TestCase
{
	public function testValidatorValidSession(): void
	{
		if (session_status() === PHP_SESSION_ACTIVE)
		{
			session_unset();
			session_destroy();
		}
		session_start();
		$_SESSION['uid'] = '1';
		$_SESSION['validator'] = 'exists';

		$sessionValidator = new SessionValidatorClass();
		$this->assertTrue($sessionValidator->validate());
	}

	public function testValidatorInvalidSession(): void
	{
		if (session_status() === PHP_SESSION_ACTIVE)
		{
			session_unset();
			session_destroy();
		}
		session_start();
		//$_SESSION['uid'] = '1';
		$_SESSION['validator'] = 'exists';

		$sessionValidator = new SessionValidatorClass();
		$this->assertFalse($sessionValidator->validate());
	}
}