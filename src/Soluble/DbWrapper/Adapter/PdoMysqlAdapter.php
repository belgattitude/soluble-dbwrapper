<?php

namespace Soluble\DbWrapper\Adapter;

use Soluble\DbWrapper\Exception;
use Soluble\DbWrapper\Adapter\Pdo\GenericPdo;
use Soluble\DbWrapper\Connection\PdoMysqlConnection;
use PDO;

class PdoMysqlAdapter extends GenericPdo implements AdapterInterface
{
    /**
     * @var \PDO
     */
    protected $resource;

    /**
     * @var PdoMysqlConnection
     */
    protected $connection;

    /**
     * Constructor.
     *
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     *
     * @param \PDO $resource
     */
    public function __construct(PDO $resource)
    {
        $this->checkEnvironment();
        if ($resource->getAttribute(\PDO::ATTR_DRIVER_NAME) != 'mysql') {
            $msg = __CLASS__ . " requires pdo connection to be 'mysql'";
            throw new Exception\InvalidArgumentException($msg);
        }
        $this->resource = $resource;
        $this->connection = new PdoMysqlConnection($this, $resource);
    }

    /**
     * {@inheritdoc}
     *
     * @return MysqlConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
