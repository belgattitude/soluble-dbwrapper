<?php

namespace SolubleTest\DbWrapper;

use Soluble\DbWrapper\AdapterFactory;

class AdapterFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateAdapterFromMysqliConnection()
    {
        $conn = \SolubleTestFactories::getDbConnection('mysqli');
        $adapter = AdapterFactory::createAdapterFromResource($conn);
        $this->assertInstanceOf('\Soluble\DbWrapper\Adapter\AdapterInterface', $adapter);
        $this->assertInstanceOf('\Soluble\DbWrapper\Adapter\MysqliAdapter', $adapter);
    }


    public function testCreateAdapterFromPDOMysqlConnection()
    {
        $conn = \SolubleTestFactories::getDbConnection('pdo:mysql');
        $adapter = AdapterFactory::createAdapterFromResource($conn);
        $this->assertInstanceOf('\Soluble\DbWrapper\Adapter\AdapterInterface', $adapter);
        $this->assertInstanceOf('\Soluble\DbWrapper\Adapter\PdoMysqlAdapter', $adapter);
    }

    public function testCreateAdapterThrowsException()
    {
        $this->setExpectedException('Soluble\DbWrapper\Exception\UnsupportedDriverException');
        $connection = new \PDO('sqlite::memory:');
        $adapter = AdapterFactory::createAdapterFromResource($connection);
    }
}
