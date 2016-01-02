<?php

namespace SolubleTest\DbWrapper\Adapter;

use Soluble\DbWrapper\Adapter\PdoMysqlAdapter;

class PdoMysqlAdapterTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var PdoMysqlAdapter
     */
    protected $adapter;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->adapter = new PdoMysqlAdapter(\SolubleTestFactories::getDbConnection('pdo:mysql'));
    }

    public function testConstructorThrowsException() {
        
        $this->setExpectedException('\Soluble\DbWrapper\Exception\InvalidArgumentException');
        $adapter = new PdoMysqlAdapter(new \PDO('sqlite::memory:'));
    }
    
    
    public function testGetCurrentSchema()
    {
        $current = $this->adapter->getCurrentSchema();
        $this->assertEquals(\SolubleTestFactories::getDatabaseName('pdo:mysql'), $current);

        $config = \SolubleTestFactories::getDbConfiguration('pdo:mysql');
        unset($config['database']);

        $adapter = new PdoMysqlAdapter(\SolubleTestFactories::getDbConnection('pdo:mysql', $config));
        $current = $adapter->getCurrentSchema();

        $this->assertFalse($current);
    }

    public function testGetResource()
    {
        $conn = $this->adapter->getResource();
        $this->assertInstanceOf('PDO', $conn);
    }


    public function testQueryWithSet()
    {
        $this->adapter->query('set @psbtest=1');

        try {
            $this->adapter->query('set qsd=');
            $this->assertTrue(false, "wrong execute command didn't throw an exception");
        } catch (\Soluble\DbWrapper\Exception\InvalidArgumentException $e) {
            $this->assertTrue(true);
        }

    }

    public function testQuery()
    {
        $results = $result = $this->adapter->query('select * from product');
        $this->assertInstanceOf('ArrayObject', $results);
        $this->assertInternalType('array', $results[0]);

        try {
            $this->adapter->query('selectwhere');
            $this->assertTrue(false, "wrong query didn't throw an exception");
        } catch (\Soluble\DbWrapper\Exception\InvalidArgumentException $e) {
            $this->assertTrue(true, "wrong query throwed successfully an exception");
        }

    }

    public function testQuoteValue()
    {
        $string = "aa';aa";
        $quoted = $this->adapter->quoteValue($string);
        $this->assertEquals("'aa\';aa'", $quoted);
    }
}
