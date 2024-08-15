<?php 

declare(strict_types = 1);
namespace Test\Mock\Classes;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Test\Mock\Exceptions\SuccessException;

class MockLegoListClass
{
	public function setLegoList(array $legoListVals): void
	{
		throw new SuccessException();
	}
}