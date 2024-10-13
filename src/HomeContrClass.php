<?php 

declare(strict_types = 1);
namespace Src;
require __DIR__ . '\\..\\vendor\\autoload.php';

use Src\HomeViewClass;
use Src\Shared\Classes\LegoClass as LegoClassS;

class HomeContrClass {

	public function __construct(private ?legoClassS $legoClassS = null, private ?HomeViewClass $homeViewClass = null) {
		if (is_null($legoClassS)) {
			$this->legoClassS = new LegoClassS();
		}

		if (is_null($homeViewClass)) {
			$this->homeViewClass = new HomeViewClass();
		}
	}

	// view methods
	public function viewLegoDB(): void {
		$legos = $this->legoClassS->getLegos();
		$this->homeViewClass->echoLegoDB($legos);
	}
}