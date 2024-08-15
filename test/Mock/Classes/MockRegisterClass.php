<?php 

declare(strict_types = 1);
namespace Test\Mock\Classes;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Test\Mock\Exceptions\SuccessException;

class MockRegisterClass
{
	public function checkUserExist($id, $email): bool
	{
		$ids = array('111','222','333','444');
		$emails = array('a@a.ao','b@b.bo','c@c.co');
		if (in_array($id, $ids)|in_array($email, $emails)) {
			return true;
		}
		else {
			return false;
		}
	}

	public function setUser(array $userVals): void
	{
		throw new SuccessException();
	}
}