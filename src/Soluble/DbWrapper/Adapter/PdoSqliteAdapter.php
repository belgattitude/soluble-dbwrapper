<?php

namespace Soluble\DbWrapper\Adapter;

use Soluble\DbWrapper\Exception;
use Soluble\DbWrapper\Adapter\Pdo\GenericPdo;
use PDO;

class PdoSqliteAdapter extends GenericPdo implements AdapterInterface
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
     * @param \PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->checkEnvironment();
        if ($connection->getAttribute(\PDO::ATTR_DRIVER_NAME) != 'sqlite') {
            $msg = __CLASS__ . " requires pdo connection to be 'sqlite'";
            throw new Exception\InvalidArgumentException($msg);
        }
        $this->resource = $connection;
    }
    
    
    /**
     * {@inheritdoc}
     */
    public function getCurrentSchema() 
    {
        return 'main';
    }
}
