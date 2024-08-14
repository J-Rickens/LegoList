<?php 

declare(strict_types = 1);
namespace Test\Mock\Classes;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Test\Mock\Exceptions\SuccessException;

class MockLegoClass
{
	public function checkLegoExist($id): bool
	{
		$ids = array('111','222','333','444');
		return in_array($id, $ids);
	}

	public function setLego(array $legoVal): void
	{
		throw new SuccessException();
	}
}