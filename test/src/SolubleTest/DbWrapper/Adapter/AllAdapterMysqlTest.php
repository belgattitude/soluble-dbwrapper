<?php

namespace SolubleTest\DbWrapper\Adapter;

use Soluble\DbWrapper\Adapter;

class AllAdapterMysqlTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var array
     */
    protected $elements;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $elements = array();

        // 1. PDO_mysql
        $pdo_mysql = new Adapter\PdoMysqlAdapter(\SolubleTestFactories::getDbConnection('pdo:mysql'));
        $elements['pdo_mysql']['adapter'] = $pdo_mysql;

        // 2. Mysqli
        $mysqli = new Adapter\MysqliAdapter(\SolubleTestFactories::getDbConnection('mysqli'));
        $elements['mysqli']['adapter'] = $mysqli;

        // 3. ZendDb 2.*
        $zendAdapter = \SolubleTestFactories::getDbConnection('zend-db2-mysqli');
        $zend2adapter     = new Adapter\Zend\ZendDb2Adapter($zendAdapter);

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
                $this->assertTrue(false, "wrong execute command didn't throw an exception");
            } catch (\Soluble\DbWrapper\Exception\InvalidArgumentException $e) {
                $this->assertTrue(true);
            }
        }
    }

    public function testQuery()
    {
        foreach ($this->elements as $key => $element) {
            $adapter = $element['adapter'];
            $results = $adapter->query('select * from product');
            $this->assertInstanceOf('ArrayObject', $results);
            $this->assertInternalType('array', $results[0]);

            try {
                $adapter->query('selectwhere');
                $this->assertTrue(false, "wrong query didn't throw an exception");
            } catch (\Soluble\DbWrapper\Exception\InvalidArgumentException $e) {
                $this->assertTrue(true, "wrong query throwed successfully an exception");
            }
        }
    }

    public function testQuoteValue()
    {
        foreach ($this->elements as $key => $element) {
            $adapter = $element['adapter'];
            $string = "aa';aa";
            $quoted = $adapter->quoteValue($string);
            $this->assertEquals("'aa\';aa'", $quoted);
        }
    }

    public function testGetConnection()
    {
        foreach ($this->elements as $key => $element) {
            $adapter = $element['adapter'];
            $conn = $adapter->getConnection();
            $this->assertInstanceOf("\Soluble\DbWrapper\Connection\ConnectionInterface", $conn);

            $params = \SolubleTestFactories::getDbConfiguration('mysqli');
            $this->assertEquals($params['database'], $conn->getCurrentSchema());

            $this->assertInternalType('object', $conn->getResource());
        }
    }
}
