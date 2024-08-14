<?php 

declare(strict_types = 1);
namespace Test\Mock\Classes;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

class MockStmtClass
{
	private $rows;
	private $count;

	public function __construct(array $rows = array(), int $count = 0)
	{
		$this->rows = $rows;
		$this->count = $count;
	}

	public function rowCount(): int
	{
		return $this->count;
	}
}