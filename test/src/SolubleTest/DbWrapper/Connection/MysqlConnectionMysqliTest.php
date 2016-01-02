<?php

namespace SolubleTest\DbWrapper\Connection;

use Soluble\DbWrapper\Connection;
use Soluble\DbWrapper\Adapter\MysqliAdapter;

class MysqlConnectionMysqliTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Connection\MysqlConnection
     */
    protected $connection;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $adapter = new MysqliAdapter(\SolubleTestFactories::getDbConnection('mysqli'));
        $this->connection = $adapter->getConnection();
    }

    public function testGetCurrentSchema()
    {
        $current = $this->connection->getCurrentSchema();
        $this->assertEquals(\SolubleTestFactories::getDatabaseName('mysqli'), $current);

        $config = \SolubleTestFactories::getDbConfiguration('mysqli');
        unset($config['database']);

        $adapter = new MysqliAdapter(\SolubleTestFactories::getDbConnection('mysqli', $config));
        $current = $adapter->getConnection()->getCurrentSchema();

        $this->assertFalse($current);
    }

    public function testGetResource()
    {
        $conn = $this->connection->getResource();
        $this->assertInstanceOf('mysqli', $conn);
    }
}
