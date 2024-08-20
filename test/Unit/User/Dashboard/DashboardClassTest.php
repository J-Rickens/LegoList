<?php 

declare(strict_types = 1);
namespace Test\Unit\User\Dashboard;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\Mock\Classes\MockDbhClass;
use Test\Mock\Exceptions\SuccessException;

use Src\Shared\Exceptions\StmtFailedException;
use Src\User\Dashboard\DashboardClass;


class DashboardClassTest extends TestCase
{
	private $dbh;
	private DashboardClass $dash;

	protected function setUp(): void
	{
		parent::setUp();

		$this->dbh = new MockDbhClass();
		$this->dash = new DashboardClass($this->dbh);
	}

	public function testgetLegoListsMockFailedStmt(): void {
		$this->dbh->setTestingConditions([['stmtFail'=>true]]);
		$this->expectException(StmtFailedException::class);
		$this->expectExceptionMessage('getstmtfailed');
		$this->dash->getLegoLists(null);
	}

	public function testgetLegoListsValidInputs(): void {
		$expected = [['list_id'=>1]];
		$this->dbh->setTestingConditions([['fetch'=>true]],[$expected]);
		$this->assertEquals($expected, $this->dash->getLegoLists(1));
	}
}