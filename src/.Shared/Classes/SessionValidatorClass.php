<?php 

declare(strict_types = 1);
namespace Src\Shared\Classes;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

class SessionValidatorClass {

	public function validate(): bool {
		if (isset($_SESSION['uid'])) {
			return true;
		}
		else {
			return false;
		}
	}
}