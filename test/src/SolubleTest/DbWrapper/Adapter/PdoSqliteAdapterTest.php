<?php

namespace SolubleTest\DbWrapper\Adapter;

use Soluble\DbWrapper\Adapter\PdoSqliteAdapter;

class PdoSqliteAdapterTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var \PDO
     */
    protected $connection;

    /**
     *
     * @var PdoSqliteAdapter
     */
    protected $adapter;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->connection = new \PDO('sqlite::memory:');
        $this->adapter = new PdoSqliteAdapter($this->connection);
        $this->createTestTable();
    }

    protected function createTestTable()
    {
        $this->adapter->query('drop table if exists test');
        $this->adapter->query('create table test (name, address)');
        $this->adapter->query('insert into test (name, address) values ("belgattitude", "Belgium")');
    }

    public function testConstructorThrowsException()
    {

        $this->setExpectedException('\Soluble\DbWrapper\Exception\InvalidArgumentException');
        $adapter = new PdoSqliteAdapter(\SolubleTestFactories::getDbConnection('pdo:mysql'));
    }


    public function testQuery()
    {
        $results = $result = $this->adapter->query('select * from test');
        $this->assertInstanceOf('Soluble\DbWrapper\Result\ResultInterface', $results);
        $this->assertInternalType('array', $results[0]);
        $this->assertEquals(1, count($results));

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
        $this->assertEquals("'aa'';aa'", $quoted);
    }
}
