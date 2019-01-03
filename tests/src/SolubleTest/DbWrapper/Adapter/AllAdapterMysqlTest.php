<?php declare(strict_types=1);

namespace SolubleTest\DbWrapper\Adapter;

use PHPUnit\Framework\TestCase;
use Soluble\DbWrapper\Adapter;
use Soluble\DbWrapper\Result\Resultset;

class AllAdapterMysqlTest extends TestCase
{
    /**
     * @var array
     */
    protected $elements;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $elements = [];

        // 1. PDO_mysql
        $pdo_mysql = new Adapter\PdoMysqlAdapter(\SolubleTestFactories::getDbConnection('pdo:mysql'));
        $elements['pdo_mysql']['adapter'] = $pdo_mysql;

        // 2. Mysqli
        $mysqli = new Adapter\MysqliAdapter(\SolubleTestFactories::getDbConnection('mysqli'));
        $elements['mysqli']['adapter'] = $mysqli;

        // 3. ZendDb 2.*
        $zendAdapter = \SolubleTestFactories::getDbConnection('zend-db2-mysqli');
        $zend2adapter = new Adapter\Zend\ZendDb2Adapter($zendAdapter);

        $elements['zend-db-2']['adapter'] = $zend2adapter;

        // 4. Laravel illuminate database
        $capsule = \SolubleTestFactories::getDbConnection('capsule5-mysqli');
        $capsuleAdapter = new Adapter\Laravel\Capsule5Adapter($capsule);
        $elements['capsule-5']['adapter'] = $capsuleAdapter;

        // 5. Doctrine 2

        $dbal = \SolubleTestFactories::getDbConnection('doctrine2-mysqli');
        $dbalAdapter = new Adapter\Doctrine\Dbal2Adapter($dbal);
        $elements['doctrine-2']['adapter'] = $dbalAdapter;

        $this->elements = $elements;
    }

    public function testQueryWithSet()
    {
        foreach ($this->elements as $key => $element) {
            $adapter = $element['adapter'];
            $adapter->query('set @psbtest=1');
            try {
                $adapter->query('set qsd=');
                self::assertTrue(false, "wrong execute command didn't throw an exception");
            } catch (\Soluble\DbWrapper\Exception\InvalidArgumentException $e) {
                self::assertTrue(true);
            }
        }
    }

    public function testResultset()
    {
        $testedTypes = [
            Resultset::TYPE_ARRAY => 'array',
            Resultset::TYPE_ARRAYOBJECT => 'ArrayObject'
        ];

        foreach ($this->elements as $key => $element) {
            foreach ($testedTypes as $resultsetType => $mustbe) {
                $adapter = $element['adapter'];
                $results = $adapter->query('select * from product', $resultsetType);
                self::assertInstanceOf('Soluble\DbWrapper\Result\ResultInterface', $results);
                self::assertInstanceOf('Soluble\DbWrapper\Result\Resultset', $results);
                if ($mustbe == 'array') {
                    self::assertInternalType('array', $results[0]);
                } else {
                    self::assertInstanceOf($mustbe, $results[0]);
                }
            }
        }
    }

    public function testQuery()
    {
        foreach ($this->elements as $key => $element) {
            $adapter = $element['adapter'];
            $results = $adapter->query('select * from product');
            self::assertInstanceOf('Soluble\DbWrapper\Result\ResultInterface', $results);
            self::assertInternalType('array', $results[0]);

            try {
                $adapter->query('selectwhere');
                self::assertTrue(false, "wrong query didn't throw an exception");
            } catch (\Soluble\DbWrapper\Exception\InvalidArgumentException $e) {
                self::assertTrue(true, 'wrong query throwed successfully an exception');
            }
        }
    }

    public function testQuoteValue()
    {
        foreach ($this->elements as $key => $element) {
            $adapter = $element['adapter'];
            $string = "aa';aa";
            $quoted = $adapter->quoteValue($string);
            self::assertEquals("'aa\';aa'", $quoted);
        }
    }

    public function testGetConnection()
    {
        foreach ($this->elements as $key => $element) {
            $adapter = $element['adapter'];
            $conn = $adapter->getConnection();
            self::assertInstanceOf("\Soluble\DbWrapper\Connection\ConnectionInterface", $conn);

            $params = \SolubleTestFactories::getDbConfiguration('mysqli');
            self::assertEquals($params['database'], $conn->getCurrentSchema());
            self::assertInternalType('object', $conn->getResource());
        }
    }
}
