<?php

declare(strict_types=1);

namespace SolubleTest\DbWrapper\Result;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use Soluble\DbWrapper\Result\Resultset;

class ResultsetTest extends TestCase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    public function testConstructThrowsInvalidArgumentException()
    {
        $this->expectException('\Soluble\DbWrapper\Exception\InvalidArgumentException');
        $rs = new ResultSet('invalidreturntype');
    }

    public function testBehaviourWithArray()
    {
        $row1 = ['name' => 'contact_1', 'address' => 'address_1'];
        $row2 = ['name' => 'contact_2', 'address' => 'address_2'];

        $rs = new Resultset();
        self::assertFalse($rs->valid());
        self::assertEquals(0, $rs->count());
        $rs->append($row1);
        $rs->append($row2);

        self::assertCount(2, $rs);
        self::assertEquals(2, $rs->count());

        self::assertEquals($row1, $rs->current());
        self::assertEquals($row1, $rs[0]);
        self::assertEquals($row2, $rs[1]);

        $rs->next();
        self::assertEquals($row2, $rs->current());

        $rs->rewind();
        self::assertEquals($row1, $rs->current());

        self::assertInternalType('array', $rs->getArray());
        $arr = (array) $rs->getArray();
        self::assertEquals($arr, $rs->getArray());
    }

    public function testBehaviourWithArrayObject()
    {
        $row1 = new ArrayObject(['name' => 'contact_1', 'address' => 'address_1']);
        $row2 = new ArrayObject(['name' => 'contact_2', 'address' => 'address_2']);

        $rs = new Resultset(Resultset::TYPE_ARRAYOBJECT);
        self::assertFalse($rs->valid());
        self::assertEquals(0, $rs->count());
        $rs->append((array) $row1);
        $rs->append((array) $row2);

        self::assertCount(2, $rs);
        self::assertEquals(2, $rs->count());

        self::assertEquals($row1, $rs->current());
        self::assertEquals($row1, $rs[0]);
        self::assertEquals($row2, $rs[1]);

        $rs->next();
        self::assertEquals($row2, $rs->current());

        $rs->rewind();
        self::assertEquals($row1, $rs->current());

        self::assertInternalType('array', $rs->getArray());
        $arr = (array) $rs->getArray();
        self::assertEquals($arr, $rs->getArray());
    }

    public function testOffsetExists()
    {
        $row1 = new ArrayObject(['name' => 'contact_1', 'address' => 'address_1']);

        $rs = new Resultset(Resultset::TYPE_ARRAYOBJECT);
        $rs->append((array) $row1);
        self::assertTrue($rs->offsetExists(0));
        self::assertFalse($rs->offsetExists(1));
    }

    public function testOffsetSetImmutable()
    {
        $this->expectException('Exception');
        $row1 = new ArrayObject(['name' => 'contact_1', 'address' => 'address_1']);
        $rs   = new Resultset(Resultset::TYPE_ARRAYOBJECT);
        $rs->append((array) $row1);

        $rs->offsetSet(0, 'cool');
    }

    public function testOffsetUnsetImmutable()
    {
        $this->expectException('Exception');
        $row1 = new ArrayObject(['name' => 'contact_1', 'address' => 'address_1']);
        $rs   = new Resultset(Resultset::TYPE_ARRAYOBJECT);
        $rs->append((array) $row1);

        $rs->offsetUnset(0);
    }

    public function testGetArrayObject()
    {
        $row1 = new ArrayObject(['name' => 'contact_1', 'address' => 'address_1']);
        $rs   = new Resultset(Resultset::TYPE_ARRAY);
        $rs->append((array) $row1);
        $returned = $rs->getArrayObject();
        self::assertInstanceOf('ArrayObject', $returned);
        $firstLine = $returned[0];
        self::assertEquals((array) $firstLine, (array) $row1);
    }

    public function testGetArrayObjectWithArrayObjectType()
    {
        $row1 = new ArrayObject(['name' => 'contact_1', 'address' => 'address_1']);
        $rs   = new Resultset(Resultset::TYPE_ARRAYOBJECT);
        $rs->append((array) $row1);
        $returned = $rs->getArrayObject();
        self::assertInstanceOf('ArrayObject', $returned);
        $firstLine = $returned[0];
        self::assertEquals((array) $firstLine, (array) $row1);
    }

    public function testKey()
    {
        $row1 = new ArrayObject(['name' => 'contact_1', 'address' => 'address_1']);
        $rs   = new Resultset(Resultset::TYPE_ARRAY);
        $rs->append((array) $row1);
        foreach ($rs as $idx => $row) {
            self::assertEquals(0, $idx);
            self::assertEquals(0, $rs->key());
        }
    }

    public function testGetReturnType()
    {
        $rs1 = new Resultset(Resultset::TYPE_ARRAYOBJECT);
        self::assertEquals(Resultset::TYPE_ARRAYOBJECT, $rs1->getReturnType());

        $rs2 = new Resultset(Resultset::TYPE_ARRAY);
        self::assertEquals(Resultset::TYPE_ARRAY, $rs2->getReturnType());

        $rs2 = new Resultset();
        self::assertEquals(Resultset::TYPE_ARRAY, $rs2->getReturnType());
    }
}
