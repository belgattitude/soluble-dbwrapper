<?php

namespace Soluble\DbWrapper\Adapter;

class MysqlAdapterPDOTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var MysqlAdapter
     */
    protected $adapter;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->adapter = new MysqlAdapter(\SolubleTestFactories::getDbConnection('pdo:mysql'));
    }

    public function testGetCurrentSchema()
    {
        $current = $this->adapter->getCurrentSchema();
        $this->assertEquals(\SolubleTestFactories::getDatabaseName('pdo:mysql'), $current);

        $config = \SolubleTestFactories::getDbConfiguration('pdo:mysql');
        unset($config['database']);

        $adapter = new MysqlAdapter(\SolubleTestFactories::getDbConnection('pdo:mysql', $config));
        $current = $adapter->getCurrentSchema();

        $this->assertFalse($current);

    }
    public function testExecute()
    {
        $this->adapter->execute('set @psbtest=1');

        try {
            $this->adapter->execute('set qsd=');
            $this->assertTrue(false, "wrong execute command didn't throw an exception");
        } catch (\Soluble\DbWrapper\Exception\InvalidArgumentException $e) {
            $this->assertTrue(true);
        }

    }

    public function testQuery()
    {

        $this->adapter->execute('set @psbtest=1');
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
