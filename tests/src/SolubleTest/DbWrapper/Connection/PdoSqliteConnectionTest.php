<?php

declare(strict_types=1);

namespace SolubleTest\DbWrapper\Connection;

use PHPUnit\Framework\TestCase;
use Soluble\DbWrapper\Adapter\PdoSqliteAdapter;
use Soluble\DbWrapper\Connection;

class PdoSqliteConnectionTest extends TestCase
{
    /**
     * @var Connection\PdoSqliteConnection
     */
    protected $connection;

    protected function setUp(): void
    {
        $connection       = new \PDO('sqlite::memory:');
        $adapter          = new PdoSqliteAdapter($connection);
        $this->connection = $adapter->getConnection();
    }

    public function testGetCurrentSchema()
    {
        $current = $this->connection->getCurrentSchema();
        self::assertEquals('main', $current);
    }

    public function testGetResource()
    {
        $conn = $this->connection->getResource();
        self::assertInstanceOf('PDO', $conn);
    }

    public function testGetHost()
    {
        self::assertEquals('localhost', $this->connection->getHost());
    }
}
