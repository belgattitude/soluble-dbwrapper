<?php

declare(strict_types=1);

namespace SolubleTest\DbWrapper\Connection;

use Soluble\DbWrapper\Adapter\PdoMysqlAdapter;
use Soluble\DbWrapper\Connection;

class PdoMysqlConnectionTest extends MysqliConnectionTest
{
    /**
     * @var Connection\MysqlConnection
     */
    protected $connection;

    protected function setUp(): void
    {
        $adapter          = new PdoMysqlAdapter(\SolubleTestFactories::getDbConnection('pdo:mysql'));
        $this->connection = $adapter->getConnection();
    }

    public function testGetResource()
    {
        $conn = $this->connection->getResource();
        self::assertInstanceOf('PDO', $conn);
    }

    public function testGetHost()
    {
        $params = \SolubleTestFactories::getDbConfiguration('pdo:mysql');
        $host   = mb_strtolower($params['hostname']);
        self::assertEquals($host, $this->connection->getHost());
    }
}
