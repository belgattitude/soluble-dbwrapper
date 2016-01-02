<?php

namespace Soluble\DbWrapper\Connection;

use Soluble\DbWrapper\Adapter\PdoMysqlAdapter;
use Soluble\DbWrapper\Connection\Mysql\AbstractMysqlConnection;
use PDO;

class PdoMysqlConnection extends AbstractMysqlConnection implements ConnectionInterface
{

    /**
     *
     * @var PdoMysqlAdapter
     */
    protected $adapter;


    /**
     *
     * @var PDO
     */
    protected $resource;

    /**
     *
     * @param PdoMysqlAdapter $adapter
     * @param PDO $resource
     */
    public function __construct(PdoMysqlAdapter $adapter, PDO $resource)
    {
        $this->adapter = $adapter;
        $this->resource = $resource;
    }


    /**
     * {@inheritdoc}
     * @return PDO
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
        $infos = explode(' ', trim($this->resource->getAttribute(PDO::ATTR_CONNECTION_STATUS)));
        return strtolower($infos[0]);
    }
}
