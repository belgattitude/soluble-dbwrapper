<?php

declare(strict_types=1);

namespace Soluble\DbWrapper\Result;

use ArrayObject;
use Soluble\DbWrapper\Exception;

class Resultset implements ResultInterface
{
    public const TYPE_ARRAYOBJECT = 'arrayobject';
    public const TYPE_ARRAY       = 'array';

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
        if (!in_array($returnType, $this->allowedReturnTypes, true)) {
            throw new Exception\InvalidArgumentException("Unsupported returnType argument ($returnType)");
        }
        $this->returnType = $returnType;
        if ($this->returnType === self::TYPE_ARRAYOBJECT) {
            $this->storage = new ArrayObject([]);
        } else {
            $this->storage = [];
        }
        $this->position = 0;
        $this->count    = count($this->storage);
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
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return isset($this->storage[$this->position]);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return $this->count;
    }

    /**
     * Append a row to the end of resultset.
     *
     * @param array $row an associative array
     */
    public function append(array $row): void
    {
        if ($this->returnType === self::TYPE_ARRAYOBJECT) {
            $this->storage[] = new ArrayObject($row);
        } else {
            $this->storage[] = $row;
        }
        ++$this->count;
    }

    /**
     * @param mixed $position
     */
    public function offsetExists($position): bool
    {
        return isset($this->storage[$position]);
    }

    /**
     * @param mixed $position
     *
     * @return mixed
     */
    public function offsetGet($position)
    {
        return $this->storage[$position] ?? null;
    }

    /**
     * @param mixed $position
     * @param mixed $value
     */
    public function offsetSet($position, $value): void
    {
        throw new \Exception('Resultsets are immutable');
    }

    /**
     * @param mixed $position
     */
    public function offsetUnset($position): void
    {
        throw new \Exception('Resultsets are immutable');
    }

    /**
     * Return underlying stored resultset as array.
     *
     * @return array
     */
    public function getArray(): array
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
    public function getArrayObject(): ArrayObject
    {
        if ($this->returnType === self::TYPE_ARRAY) {
            return new ArrayObject($this->storage);
        } else {
            /** @var ArrayObject $storageAsArrayObject to silent static code analyzers */
            $storageAsArrayObject = $this->storage;

            return $storageAsArrayObject;
        }
    }

    /**
     * Return the currently set return type.
     *
     * @see Resultset::TYPE_ARRAY|Resultset::TYPE_ARRAYOBJECT
     *
     * @return string
     */
    public function getReturnType(): string
    {
        return $this->returnType;
    }
}
