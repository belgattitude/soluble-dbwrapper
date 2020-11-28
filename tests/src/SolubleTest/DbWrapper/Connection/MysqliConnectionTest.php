<?php

declare(strict_types=1);

namespace SolubleTest\DbWrapper\Connection;

use PHPUnit\Framework\TestCase;
use Soluble\DbWrapper\Adapter\MysqliAdapter;
use Soluble\DbWrapper\Connection;

class MysqliConnectionTest extends TestCase
{
    /**
     * @var Connection\MysqlConnection
     */
    protected $connection;

    protected function setUp(): void
    {
        $adapter          = new MysqliAdapter(\SolubleTestFactories::getDbConnection('mysqli'));
        $this->connection = $adapter->getConnection();
    }

    public function testGetCurrentSchema()
    {
        $current = $this->connection->getCurrentSchema();
        self::assertEquals(\SolubleTestFactories::getDatabaseName('mysqli'), $current);

        $config = \SolubleTestFactories::getDbConfiguration('mysqli');

        $config['database'] = '';

        $adapter = new MysqliAdapter(\SolubleTestFactories::getDbConnection('mysqli', $config));
        $current = $adapter->getConnection()->getCurrentSchema();

        self::assertFalse($current);
    }

    public function testGetResource()
    {
        $conn = $this->connection->getResource();
        self::assertInstanceOf('mysqli', $conn);
    }

    public function testGetHost()
    {
        $params = \SolubleTestFactories::getDbConfiguration('mysqli');
        $host   = mb_strtolower($params['hostname']);
        self::assertEquals($host, $this->connection->getHost());
    }
}
