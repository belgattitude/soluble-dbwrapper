<?php

namespace Soluble\DbWrapper\Result;

use ArrayObject;
use Soluble\DbWrapper\Exception;

class Resultset implements ResultInterface
{
    const TYPE_ARRAYOBJECT = 'arrayobject';
    const TYPE_ARRAY = 'array';

    /**
     * Allowed return types.
     *
     * @var array
     */
    protected $allowedReturnTypes = [
        self::TYPE_ARRAYOBJECT,
        self::TYPE_ARRAY,
    ];

    /**
     * Return type to use when returning an object from the set.
     *
     * @var string ResultSet::TYPE_ARRAYOBJECT|ResultSet::TYPE_ARRAY
     */
    protected $returnType = self::TYPE_ARRAY;

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @var array|ArrayObject
     */
    protected $storage;

    /**
     * Constructor.
     *
     * @throws Exception\InvalidArgumentException
     *
     * @param string $returnType
     */
    public function __construct($returnType = self::TYPE_ARRAY)
    {
        if (!in_array($returnType, $this->allowedReturnTypes)) {
            throw new Exception\InvalidArgumentException("Unsupported returnType argument ($returnType)");
        }
        $this->returnType = $returnType;
        if ($this->returnType === self::TYPE_ARRAYOBJECT) {
            $this->storage = new ArrayObject([]);
        } else {
            $this->storage = [];
        }
        $this->position = 0;
        $this->count = count($this->storage);
    }

    /**
     * {@inheritdoc}
     *
     * @return array|ArrayObject
     */
    public function current()
    {
        return $this->storage[$this->position];
    }

    /**
     * {@inheritdoc}
     *
     * @return int position
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->storage[$this->position]);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->count;
    }

    /**
     * Append a row to the end of resultset.
     *
     * @param array $row an associative array
     */
    public function append(array $row)
    {
        if ($this->returnType == self::TYPE_ARRAYOBJECT) {
            $this->storage[] = new ArrayObject($row);
        } else {
            $this->storage[] = $row;
        }
        ++$this->count;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($position)
    {
        return isset($this->storage[$position]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($position)
    {
        return isset($this->storage[$position]) ? $this->storage[$position] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($position, $row)
    {
        throw new \Exception('Resultsets are immutable');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($position)
    {
        throw new \Exception('Resultsets are immutable');
    }

    /**
     * Return underlying stored resultset as array.
     *
     * @return array
     */
    public function getArray()
    {
        return (array) $this->storage;
    }

    /**
     * Return underlying stored resultset as ArrayObject.
     *
     * Depending on the $returnType Resultset::TYPE_ARRAY|Resultset::TYPE_ARRAYOBJECT you can modify
     * the internal storage
     *
     * @return ArrayObject
     */
    public function getArrayObject()
    {
        if ($this->returnType == self::TYPE_ARRAY) {
            return new ArrayObject($this->storage);
        } else {
            return $this->storage;
        }
    }

    /**
     * Return the currently set return type.
     *
     * @see Resultset::TYPE_ARRAY|Resultset::TYPE_ARRAYOBJECT
     *
     * @return string
     */
    public function getReturnType()
    {
        return $this->returnType;
    }
}
