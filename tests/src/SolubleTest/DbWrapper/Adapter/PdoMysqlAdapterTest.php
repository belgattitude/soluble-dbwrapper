<?php

declare(strict_types=1);

namespace SolubleTest\DbWrapper\Adapter;

use PHPUnit\Framework\TestCase;
use Soluble\DbWrapper\Adapter\PdoMysqlAdapter;

class PdoMysqlAdapterTest extends TestCase
{
    /**
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

    public function testConstructorThrowsException()
    {
        $this->expectException(\Soluble\DbWrapper\Exception\InvalidArgumentException::class);
        $adapter = new PdoMysqlAdapter(new \PDO('sqlite::memory:'));
    }

    public function testQueryWithSet()
    {
        $this->adapter->query('set @psbtest=1');

        try {
            $this->adapter->query('set qsd=');
            self::assertTrue(false, "wrong execute command didn't throw an exception");
        } catch (\Soluble\DbWrapper\Exception\InvalidArgumentException $e) {
            self::assertTrue(true);
        }
    }

    public function testQuery()
    {
        $results = $result = $this->adapter->query('select * from product');
        self::assertInstanceOf('Soluble\DbWrapper\Result\ResultInterface', $results);
        self::assertInternalType('array', $results[0]);

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
        self::assertEquals("'aa\';aa'", $quoted);
    }
}
