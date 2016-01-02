<?php

namespace Soluble\DbWrapper\Connection;

use Soluble\DbWrapper\Adapter\MysqliAdapter;
use Soluble\DbWrapper\Connection\Mysql\AbstractMysqlConnection;
use mysqli;

class MysqliConnection extends AbstractMysqlConnection implements ConnectionInterface
{

    /**
     *
     * @var MysqliAdapter
     */
    protected $adapter;


    /**
     *
     * @var mysqli
     */
    protected $resource;

    /**
     *
     * @param MysqliAdapter $adapter
     * @param mysqli $resource
     */
    public function __construct(MysqliAdapter $adapter, $resource)
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
}
