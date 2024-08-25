<?php 

declare(strict_types = 1);
namespace Src\User\Dashboard;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Src\User\Dashboard\DashboardClass;
use Src\User\Dashboard\DashboardViewClass;

class DashboardContrClass {

	private DashboardClass $dashboardClass;
	private DashboardViewClass $dashboardViewClass;

	public function __construct($dashboardClass = null, $dashboardViewClass = null) {
		if (is_null($dashboardClass))
		{
			$this->dashboardClass = new DashboardClass();
		}
		else
		{
			$this->dashboardClass = $dashboardClass;
		}

		if (is_null($dashboardViewClass))
		{
			$this->dashboardViewClass = new DashboardViewClass();
		}
		else
		{
			$this->dashboardViewClass = $dashboardViewClass;
		}
	}

	public function createLegoListSec(): void {
		$this->dashboardViewClass->echoLegoLists($this->dashboardClass->getLegoLists($_SESSION['uid']));
	}
}