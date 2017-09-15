<?php

namespace SolubleTest\DbWrapper;

use PHPUnit\Framework\TestCase;
use Soluble\DbWrapper\AdapterFactory;

class AdapterFactoryTest extends TestCase
{
    public function testCreateAdapterFromMysqliConnection()
    {
        $conn = \SolubleTestFactories::getDbConnection('mysqli');
        $adapter = AdapterFactory::createAdapterFromResource($conn);
        self::assertInstanceOf(\Soluble\DbWrapper\Adapter\AdapterInterface::class, $adapter);
        self::assertInstanceOf(\Soluble\DbWrapper\Adapter\MysqliAdapter::class, $adapter);
    }

    public function testCreateAdapterFromPDOMysqlConnection()
    {
        $conn = \SolubleTestFactories::getDbConnection('pdo:mysql');
        $adapter = AdapterFactory::createAdapterFromResource($conn);
        self::assertInstanceOf(\Soluble\DbWrapper\Adapter\AdapterInterface::class, $adapter);
        self::assertInstanceOf(\Soluble\DbWrapper\Adapter\PdoMysqlAdapter::class, $adapter);
    }

    public function testCreateAdapterFromPDOSqliteConnection()
    {
        $connection = new \PDO('sqlite::memory:');
        $adapter = AdapterFactory::createAdapterFromResource($connection);
        self::assertInstanceOf(\Soluble\DbWrapper\Adapter\AdapterInterface::class, $adapter);
        self::assertInstanceOf(\Soluble\DbWrapper\Adapter\PdoSqliteAdapter::class, $adapter);
    }

    public function testCreateAdapterFromZendDb2Adapter()
    {
        $zendAdapter = \SolubleTestFactories::getDbConnection('zend-db2-mysqli');
        $adapter = AdapterFactory::createAdapterFromZendDb2($zendAdapter);
        self::assertInstanceOf(\Soluble\DbWrapper\Adapter\AdapterInterface::class, $adapter);
        self::assertInstanceOf(\Soluble\DbWrapper\Adapter\Zend\ZendDb2Adapter::class, $adapter);
    }

    public function testCreateAdapterFromDoctrine2Test()
    {
        $doctrine = \SolubleTestFactories::getDbConnection('doctrine2-mysqli');

        $adapter = AdapterFactory::createAdapterFromDoctrine2($doctrine);
        self::assertInstanceOf(\Soluble\DbWrapper\Adapter\AdapterInterface::class, $adapter);
        self::assertInstanceOf(\Soluble\DbWrapper\Adapter\Doctrine\Dbal2Adapter::class, $adapter);
    }

    public function testCreateAdapterFromCapsule5Test()
    {
        $capsule = \SolubleTestFactories::getDbConnection('capsule5-mysqli');

        $adapter = AdapterFactory::createAdapterFromCapsule5($capsule);
        self::assertInstanceOf(\Soluble\DbWrapper\Adapter\AdapterInterface::class, $adapter);
        self::assertInstanceOf(\Soluble\DbWrapper\Adapter\Laravel\Capsule5Adapter::class, $adapter);
    }

    public function testCreateAdapterFromResourceThrowsException()
    {
        $this->expectException(\Soluble\DbWrapper\Exception\InvalidArgumentException::class);
        $fct = function () {
            return true;
        };
        $adapter = AdapterFactory::createAdapterFromResource($fct);
    }

    public function testCreateAdapterFromResourceThrowsExceptionInvalidType()
    {
        $this->expectException(\Soluble\DbWrapper\Exception\InvalidArgumentException::class);
        $adapter = AdapterFactory::createAdapterFromResource('a');
    }
}
