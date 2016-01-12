<?php

namespace Soluble\DbWrapper\Connection;

use Soluble\DbWrapper\Adapter\MysqliAdapter;
use mysqli;

class MysqliConnection implements ConnectionInterface
{

    /**
     *
     * @var MysqliAdapter
     */
    protected $adapter;


    /**
     *
     * @var \mysqli
     */
    protected $resource;

    /**
     *
     * @param MysqliAdapter $adapter
     * @param \mysqli $resource
     */
    public function __construct(MysqliAdapter $adapter, mysqli $resource)
    {
        $this->adapter = $adapter;
        $this->resource = $resource;
    }


    /**
     * {@inheritdoc}
     * @return \mysqli
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * {@inheritdoc}
     */
    public function getHost()
    {
        $infos = explode(' ', trim($this->resource->host_info));
        return strtolower($infos[0]);
    }

    /**
     * Return current schema/database name
     *
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
