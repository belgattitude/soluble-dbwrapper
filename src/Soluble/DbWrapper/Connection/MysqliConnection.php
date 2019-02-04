<?php

declare(strict_types=1);

namespace Soluble\DbWrapper\Connection;

use mysqli;
use Soluble\DbWrapper\Adapter\MysqliAdapter;
use Soluble\DbWrapper\Exception;

class MysqliConnection implements ConnectionInterface
{
    /**
     * @var MysqliAdapter
     */
    protected $adapter;

    /**
     * @var mysqli
     */
    protected $resource;

    /**
     * @param MysqliAdapter $adapter
     * @param mysqli        $resource
     */
    public function __construct(MysqliAdapter $adapter, mysqli $resource)
    {
        $this->adapter  = $adapter;
        $this->resource = $resource;
    }

    /**
     * {@inheritdoc}
     *
     * @return mysqli
     */
    public function getResource(): mysqli
    {
        return $this->resource;
    }

    /**
     * {@inheritdoc}
     */
    public function getHost(): string
    {
        $infos = explode(' ', trim($this->resource->host_info));

        return mb_strtolower($infos[0]);
    }

    /**
     * Return current schema/database name.
     *
     * @throws Exception\RuntimeException
     *
     * @return string|false
     */
    public function getCurrentSchema()
    {
        $query = 'SELECT DATABASE() as current_schema';
        try {
            $results = $this->adapter->query($query);
            if (count($results) === 0 || $results[0]['current_schema'] === null) {
                return false;
            }
        } catch (\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage());
        }

        return $results[0]['current_schema'];
    }
}
