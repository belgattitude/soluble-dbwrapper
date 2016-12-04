<?php

namespace SolubleTest\DbWrapper\Connection;

use Soluble\DbWrapper\Connection;
use Soluble\DbWrapper\Adapter\PdoMysqlAdapter;

class PdoMysqlConnectionTest extends MysqliConnectionTest
{
    /**
     * @var Connection\MysqlConnection
     */
    protected $connection;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $adapter = new PdoMysqlAdapter(\SolubleTestFactories::getDbConnection('pdo:mysql'));
        $this->connection = $adapter->getConnection();
    }

    public function testGetResource()
    {
        $conn = $this->connection->getResource();
        $this->assertInstanceOf('PDO', $conn);
    }

    public function testGetHost()
    {
        $params = \SolubleTestFactories::getDbConfiguration('pdo:mysql');
        $host = strtolower($params['hostname']);
        $this->assertEquals($host, $this->connection->getHost());
    }
}
