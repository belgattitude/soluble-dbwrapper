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

    public function testCreateAdapterFromPDOSqliteConnection()
    {
        $connection = new \PDO('sqlite::memory:');
        $adapter = AdapterFactory::createAdapterFromResource($connection);
        $this->assertInstanceOf('\Soluble\DbWrapper\Adapter\AdapterInterface', $adapter);
        $this->assertInstanceOf('\Soluble\DbWrapper\Adapter\PdoSqliteAdapter', $adapter);
    }

    public function testCreateAdapterThrowsException2()
    {
        $this->setExpectedException('Soluble\DbWrapper\Exception\InvalidArgumentException');
        $adapter = AdapterFactory::createAdapterFromResource("a");
    }

    public function testCreateAdapterThrowsException()
    {
        $this->setExpectedException('Soluble\DbWrapper\Exception\InvalidArgumentException');
        $fct = function () {
            return true;

        };
        $adapter = AdapterFactory::createAdapterFromResource($fct);
    }
}
