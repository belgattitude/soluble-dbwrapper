<?php

namespace Soluble\DbWrapper\Connection;

use Soluble\DbWrapper\Adapter\AdapterInterface;

class MysqlConnection implements ConnectionInterface
{

    /**
     *
     * @var AdapterInterface
     */
    protected $adapter;
    
    
    /**
     *
     * @var mixed
     */
    protected $resource;
    
    /**
     * 
     * @param AdapterInterface $adapter
     * @param mixed $resource
     */
    public function __construct(AdapterInterface $adapter, $resource)
    {
        $this->adapter = $adapter;
        $this->resource = $resource;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getCurrentSchema()
    {
        $query = 'SELECT DATABASE() as current_schema';
        try {
            $results = $this->adapter->query($query);
            if (count($results) == 0 || $results[0]['current_schema'] === null) {
                return false;
            }
        } catch (\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage());
        }
        return $results[0]['current_schema'];
    }
    
    /**
     * {@inheritdoc}
     * @return \mysqli|\PDO
     */
    public function getResource() {
        return $this->resource;    
    }
    
}

