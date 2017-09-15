<?php

namespace SolubleTest\DbWrapper\Result;

use PHPUnit\Framework\TestCase;
use Soluble\DbWrapper\Result\Resultset;
use ArrayObject;

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
        $this->assertFalse($rs->valid());
        $this->assertEquals(0, $rs->count());
        $rs->append($row1);
        $rs->append($row2);

        $this->assertEquals(2, count($rs));
        $this->assertEquals(2, $rs->count());

        $this->assertEquals($row1, $rs->current());
        $this->assertEquals($row1, $rs[0]);
        $this->assertEquals($row2, $rs[1]);

        $rs->next();
        $this->assertEquals($row2, $rs->current());

        $rs->rewind();
        $this->assertEquals($row1, $rs->current());

        $this->assertInternalType('array', $rs->getArray());
        $arr = (array) $rs->getArray();
        $this->assertEquals($arr, $rs->getArray());
    }

    public function testBehaviourWithArrayObject()
    {
        $row1 = new ArrayObject(['name' => 'contact_1', 'address' => 'address_1']);
        $row2 = new ArrayObject(['name' => 'contact_2', 'address' => 'address_2']);

        $rs = new Resultset(Resultset::TYPE_ARRAYOBJECT);
        $this->assertFalse($rs->valid());
        $this->assertEquals(0, $rs->count());
        $rs->append((array) $row1);
        $rs->append((array) $row2);

        $this->assertEquals(2, count($rs));
        $this->assertEquals(2, $rs->count());

        $this->assertEquals($row1, $rs->current());
        $this->assertEquals($row1, $rs[0]);
        $this->assertEquals($row2, $rs[1]);

        $rs->next();
        $this->assertEquals($row2, $rs->current());

        $rs->rewind();
        $this->assertEquals($row1, $rs->current());

        $this->assertInternalType('array', $rs->getArray());
        $arr = (array) $rs->getArray();
        $this->assertEquals($arr, $rs->getArray());
    }

    public function testOffsetExists()
    {
        $row1 = new ArrayObject(['name' => 'contact_1', 'address' => 'address_1']);

        $rs = new Resultset(Resultset::TYPE_ARRAYOBJECT);
        $rs->append((array) $row1);
        $this->assertTrue($rs->offsetExists(0));
        $this->assertFalse($rs->offsetExists(1));
    }

    public function testOffsetSetImmutable()
    {
        $this->expectException('Exception');
        $row1 = new ArrayObject(['name' => 'contact_1', 'address' => 'address_1']);
        $rs = new Resultset(Resultset::TYPE_ARRAYOBJECT);
        $rs->append((array) $row1);

        $rs->offsetSet(0, 'cool');
    }

    public function testOffsetUnsetImmutable()
    {
        $this->expectException('Exception');
        $row1 = new ArrayObject(['name' => 'contact_1', 'address' => 'address_1']);
        $rs = new Resultset(Resultset::TYPE_ARRAYOBJECT);
        $rs->append((array) $row1);

        $rs->offsetUnset(0);
    }

    public function testGetArrayObject()
    {
        $row1 = new ArrayObject(['name' => 'contact_1', 'address' => 'address_1']);
        $rs = new Resultset(Resultset::TYPE_ARRAY);
        $rs->append((array) $row1);
        $returned = $rs->getArrayObject();
        $this->assertInstanceOf('ArrayObject', $returned);
        $firstLine = $returned[0];
        $this->assertEquals((array) $firstLine, (array) $row1);
    }

    public function testGetArrayObjectWithArrayObjectType()
    {
        $row1 = new ArrayObject(['name' => 'contact_1', 'address' => 'address_1']);
        $rs = new Resultset(Resultset::TYPE_ARRAYOBJECT);
        $rs->append((array) $row1);
        $returned = $rs->getArrayObject();
        $this->assertInstanceOf('ArrayObject', $returned);
        $firstLine = $returned[0];
        $this->assertEquals((array) $firstLine, (array) $row1);
    }

    public function testKey()
    {
        $row1 = new ArrayObject(['name' => 'contact_1', 'address' => 'address_1']);
        $rs = new Resultset(Resultset::TYPE_ARRAY);
        $rs->append((array) $row1);
        foreach ($rs as $idx => $row) {
            $this->assertEquals(0, $idx);
            $this->assertEquals(0, $rs->key());
        }
    }

    public function testGetReturnType()
    {
        $rs1 = new Resultset(Resultset::TYPE_ARRAYOBJECT);
        $this->assertEquals(Resultset::TYPE_ARRAYOBJECT, $rs1->getReturnType());

        $rs2 = new Resultset(Resultset::TYPE_ARRAY);
        $this->assertEquals(Resultset::TYPE_ARRAY, $rs2->getReturnType());

        $rs2 = new Resultset();
        $this->assertEquals(Resultset::TYPE_ARRAY, $rs2->getReturnType());
    }
}
