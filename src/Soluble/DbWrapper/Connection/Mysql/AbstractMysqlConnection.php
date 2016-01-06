<?php

namespace Soluble\DbWrapper\Connection\Mysql;

use Soluble\DbWrapper\Exception;

abstract class AbstractMysqlConnection
{
    /**
     *
     * @var \Soluble\DbWrapper\Adapter\AdapterInterface
     */
    protected $adapter;

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
     * Return current schema/database name
     * @throws Exception\RuntimeException
     * @return string|false
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
}
