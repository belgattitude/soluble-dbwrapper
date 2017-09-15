<?php

namespace SolubleTest\DbWrapper\Connection;

use PHPUnit\Framework\TestCase;
use Soluble\DbWrapper\Connection;
use Soluble\DbWrapper\Adapter\PdoSqliteAdapter;

class PdoSqliteConnectionTest extends TestCase
{
    /**
     * @var Connection\PdoSqliteConnection
     */
    protected $connection;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $connection = new \PDO('sqlite::memory:');
        $adapter = new PdoSqliteAdapter($connection);
        $this->connection = $adapter->getConnection();
    }

    public function testGetCurrentSchema()
    {
        $current = $this->connection->getCurrentSchema();
        $this->assertEquals('main', $current);
    }

    public function testGetResource()
    {
        $conn = $this->connection->getResource();
        $this->assertInstanceOf('PDO', $conn);
    }

    public function testGetHost()
    {
        $this->assertEquals('localhost', $this->connection->getHost());
    }
}