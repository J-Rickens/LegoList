<?php 

declare(strict_types = 1);
namespace Src\Shared\Tp;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Exceptions\UndefinedVariableException;

class OpenerTp
{
	private $urlReturn;
	private $isSet = false;

	public function startSession(): void
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}

	public function setUrlReturn(int $urlLvl): void
	{
		$this->urlReturn = "";
		for ($i = 0; $i < $urlLvl; $i++) {
			$this->urlReturn = $this->urlReturn . "../";
		}
		$this->isSet = true;
	}

	public function getUrlReturn(): string
	{
		if (!$this->isSet)
		{
			throw new UndefinedVariableException('$urlReturn is not set');
		}
		else
		{
			return $this->urlReturn;
		}
	}
}



