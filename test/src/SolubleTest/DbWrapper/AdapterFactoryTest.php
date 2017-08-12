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

    public function testCreateAdapterFromZendDb2Adapter()
    {
        $zendAdapter = \SolubleTestFactories::getDbConnection('zend-db2-mysqli');
        $adapter = AdapterFactory::createAdapterFromZendDb2($zendAdapter);
        $this->assertInstanceOf('\Soluble\DbWrapper\Adapter\AdapterInterface', $adapter);
        $this->assertInstanceOf('\Soluble\DbWrapper\Adapter\Zend\ZendDb2Adapter', $adapter);
    }

    public function testCreateAdapterFromDoctrine2Test()
    {
        $doctrine = \SolubleTestFactories::getDbConnection('doctrine2-mysqli');

        $adapter = AdapterFactory::createAdapterFromDoctrine2($doctrine);
        $this->assertInstanceOf('\Soluble\DbWrapper\Adapter\AdapterInterface', $adapter);
        $this->assertInstanceOf('\Soluble\DbWrapper\Adapter\Doctrine\Dbal2Adapter', $adapter);
    }

    public function testCreateAdapterFromCapsule5Test()
    {
        $capsule = \SolubleTestFactories::getDbConnection('capsule5-mysqli');

        $adapter = AdapterFactory::createAdapterFromCapsule5($capsule);
        $this->assertInstanceOf('\Soluble\DbWrapper\Adapter\AdapterInterface', $adapter);
        $this->assertInstanceOf('\Soluble\DbWrapper\Adapter\Laravel\Capsule5Adapter', $adapter);
    }

    public function testCreateAdapterThrowsException()
    {
        $this->setExpectedException('Soluble\DbWrapper\Exception\InvalidArgumentException');
        $fct = function () {
            return true;
        };
        $adapter = AdapterFactory::createAdapterFromResource($fct);
    }

    public function testCreateAdapterThrowsException2()
    {
        $this->setExpectedException('Soluble\DbWrapper\Exception\InvalidArgumentException');
        $adapter = AdapterFactory::createAdapterFromResource('a');
    }
}
