<?php 

declare(strict_types = 1);
namespace Test\Unit\Shared\Classes;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

/*

Created with ChatGPT
GPT-4o

(1) Gives Error: SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it
when db and Apache are off

(2) Gives Error: PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException: Trying to configure method "connect" which cannot be configured because it does not exist, has not been specified, is final, or is static
when server is on

Modifications are needed

(2) Corrected by switching connect method from private to protected

(1) testSetStmtNull failed as it used prepStmt without mocking it which uses connect. replaced with new version that mocks dbh

*/

use PHPUnit\Framework\TestCase;
use Src\Shared\Classes\DbhClass;

class DbhClassTest extends TestCase
{
    private $pdoMock;
    private $stmtMock;

    protected function setUp(): void
    {
        // Mock the PDOStatement
        $this->stmtMock = $this->createMock(\PDOStatement::class);
        
        // Mock the PDO object
        $this->pdoMock = $this->getMockBuilder(\PDO::class)
                              ->disableOriginalConstructor()
                              ->getMock();

        // Configure the PDO mock to return the PDOStatement mock when prepare is called
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
    }

    public function testPrepStmt(): void
    {
        // Create an instance of DbhClass
        $dbh = $this->getMockBuilder(DbhClass::class)
                    ->onlyMethods(['connect'])
                    ->getMock();
        
        // Make the connect method return the mocked PDO object
        $dbh->method('connect')->willReturn($this->pdoMock);

        // Test the prepStmt method
        $dbh->prepStmt('SELECT * FROM users');
        $stmt = $dbh->getStmt();

        // Assert that the statement was prepared and stored
        $this->assertSame($this->stmtMock, $stmt);
    }

    public function testExecStmt(): void
    {
        // Simulate a successful execution
        $this->stmtMock->method('execute')->willReturn(true);

        // Create an instance of DbhClass
        $dbh = $this->getMockBuilder(DbhClass::class)
                    ->onlyMethods(['connect'])
                    ->getMock();
        
        // Make the connect method return the mocked PDO object
        $dbh->method('connect')->willReturn($this->pdoMock);

        // Prepare the statement
        $dbh->prepStmt('SELECT * FROM users');

        // Execute the statement
        $result = $dbh->execStmt(['id' => 1]);

        // Assert that execute returns true
        $this->assertTrue($result);
    }

    public function testSetStmtNull(): void
    {
        // Create a mock of the DbhClass with the connect method mocked
        $dbh = $this->getMockBuilder(DbhClass::class)
                    ->onlyMethods(['connect'])
                    ->getMock();

        // Mock the connect method to return the mock PDO object
        $dbh->method('connect')->willReturn($this->pdoMock);

        // Now, use the real prepStmt method, which will use the mocked connect method
        $dbh->prepStmt('SELECT * FROM users');

        // Set the statement to null
        $dbh->setStmtNull();

        // Assert that the statement is now null
        $this->assertNull($dbh->getStmt());
    }
    /* failed testSetStmtNull function
    public function testSetStmtNull(): void
    {
        // Create an instance of DbhClass
        $dbh = new DbhClass();

        // Prepare the statement (we can use the real prepStmt here)
        $dbh->prepStmt('SELECT * FROM users');

        // Set the statement to null
        $dbh->setStmtNull();

        // Assert that the statement is now null
        $this->assertNull($dbh->getStmt());
    }*/
}
