<?php

namespace Soluble\DbWrapper\Result;


class Resultset  implements ResultInterface
{
    /**
     *
     * @var integer 
     */
    protected $position = 0;

    /**
     *
     * @var integer
     */
    protected $count = 0;
    
    /**
     *
     * @var array
     */
    protected $storage=[];
    
    /**
     * 
     
     */
    public function __construct() {
        
        $this->storage = [];
        $this->position = 0;
        $this->count = count($this->storage);
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function current() {
        return $this->storage[$this->position];
    }

    /**
     * {@inheritdoc}
     * @return int position
     */    
    public function key() {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */    
    public function next() {
        ++$this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind() {
        $this->position=0;
    }

    /**
     * {@inheritdoc}
     */    
    public function valid() {
        return isset($this->storage[$this->position]);
    }

    /**
     * {@inheritdoc}
     */
    public function count() {
        return $this->count;
    }

    /**
     * 
     * {@inheritdoc}
     */
    public function append(array $row) {
        $this->storage[] = $row;
        ++$this->count;
    }
    

    /**
     * 
     * {@inheritdoc}
     */
    public function offsetExists($position) {
        return isset($this->storage[$position]);
    }

    /**
     * 
     * {@inheritdoc}
     */
    public function offsetGet($position) {
        return isset($this->storage[$position]) ? $this->storage[$position] : null;        
        
    }

    /**
     * 
     * {@inheritdoc}
     */
    public function offsetSet($position, $row) {
        throw new \Exception('Resultsets are immutable');
    }

    /**
     * 
     * {@inheritdoc}
     */    
    public function offsetUnset($position) {
        throw new \Exception("Resultsets are immutable");
    }
    
    /**
     * 
     * @return array
     */
    public function getArray()
    {
        return $this->storage;
    }

}
