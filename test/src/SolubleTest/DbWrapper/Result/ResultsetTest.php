<?php

namespace SolubleTest\DbWrapper\Result;

use Soluble\DbWrapper\Result\Resultset;
use ArrayObject;

class ResultsetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
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
}
