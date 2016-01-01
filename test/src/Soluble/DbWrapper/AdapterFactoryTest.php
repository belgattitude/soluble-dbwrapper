<?php

namespace Soluble\DbWrapper;

class AdapterFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateAdapterFromMysqliConnection()
    {
        $conn = \SolubleTestFactories::getDbConnection('mysqli');
        $adapter = AdapterFactory::createAdapterFromConnection($conn);
        $this->assertInstanceOf('\Soluble\DbWrapper\Adapter\AdapterInterface', $adapter);
        $this->assertInstanceOf('\Soluble\DbWrapper\Adapter\MysqliAdapter', $adapter);
    }


    public function testCreateAdapterFromPDOMysqlConnection()
    {
        $conn = \SolubleTestFactories::getDbConnection('pdo:mysql');
        $adapter = AdapterFactory::createAdapterFromConnection($conn);
        $this->assertInstanceOf('\Soluble\DbWrapper\Adapter\AdapterInterface', $adapter);
        $this->assertInstanceOf('\Soluble\DbWrapper\Adapter\PdoMysqlAdapter', $adapter);
    }

    public function testCreateAdapterThrowsException()
    {
        $this->setExpectedException('Soluble\DbWrapper\Exception\InvalidArgumentException');
        $connection = new \PDO('sqlite::memory:');
        $adapter = AdapterFactory::createAdapterFromConnection($connection);
    }
}
