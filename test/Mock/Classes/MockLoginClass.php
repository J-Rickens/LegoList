<?php 

declare(strict_types = 1);
namespace Test\Mock\Classes;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Test\Mock\Exceptions\SuccessException;

class MockLoginClass
{
	public function getUser(array $userVals): void
	{
		throw new SuccessException();
	}
}