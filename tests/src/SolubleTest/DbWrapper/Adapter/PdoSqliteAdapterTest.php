<?php

declare(strict_types=1);

namespace SolubleTest\DbWrapper\Adapter;

use PHPUnit\Framework\TestCase;
use Soluble\DbWrapper\Adapter\PdoSqliteAdapter;

class PdoSqliteAdapterTest extends TestCase
{
    /**
     * @var \PDO
     */
    protected $connection;

    /**
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
        $this->adapter    = new PdoSqliteAdapter($this->connection);
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
        $this->expectException(\Soluble\DbWrapper\Exception\InvalidArgumentException::class);
        $adapter = new PdoSqliteAdapter(\SolubleTestFactories::getDbConnection('pdo:mysql'));
    }

    public function testQuery()
    {
        $results = $result = $this->adapter->query('select * from test');
        self::assertInstanceOf('Soluble\DbWrapper\Result\ResultInterface', $results);
        self::assertInternalType('array', $results[0]);
        self::assertCount(1, $results);

        try {
            $this->adapter->query('selectwhere');
            self::assertTrue(false, "wrong query didn't throw an exception");
        } catch (\Soluble\DbWrapper\Exception\InvalidArgumentException $e) {
            self::assertTrue(true, 'wrong query throwed successfully an exception');
        }
    }

    public function testQuoteValue()
    {
        $string = "aa';aa";
        $quoted = $this->adapter->quoteValue($string);
        self::assertEquals("'aa'';aa'", $quoted);
    }
}
