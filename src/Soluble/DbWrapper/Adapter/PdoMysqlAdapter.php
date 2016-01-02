<?php

namespace Soluble\DbWrapper\Adapter;

use Soluble\DbWrapper\Exception;
use Soluble\DbWrapper\Adapter\Pdo\GenericPdo;
use Soluble\DbWrapper\Connection\MysqlConnection;
use PDO;

class PdoMysqlAdapter extends GenericPdo implements AdapterInterface
{

    /**
     *
     * @var \PDO
     */
    protected $resource;


    /**
     * Constructor
     *
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     * @param \PDO $resource
     * @param \PDO $connection
     */
    public function __construct(PDO $resource)
    {
        $this->checkEnvironment();
        if ($resource->getAttribute(\PDO::ATTR_DRIVER_NAME) != 'mysql') {
            $msg = __CLASS__ . " requires pdo connection to be 'mysql'";
            throw new Exception\InvalidArgumentException($msg);
        }
        $this->resource = $resource;
        $this->connection = new MysqlConnection($this, $resource);
    }
    
    /**
     * {@inheritdoc}
     * @return MysqlConnection
     */
    public function getConnection() 
    {
        return $this->connection;
    }
    
}
