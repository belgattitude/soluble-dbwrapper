<?php

namespace Soluble\DbWrapper\Adapter;

use Soluble\DbWrapper\Exception;
use Soluble\DbWrapper\Adapter\Pdo\GenericPdo;
use PDO;

class PdoMysqlAdapter extends GenericPdo implements AdapterInterface
{
    use Mysql\MysqlCommonTrait;

    /**
     *
     * @var \PDO
     */
    protected $resource;


    /**
     * Constructor
     *
     * @throws Exception\InvalidArgumentException
     * @param \PDO $connection
     */
    public function __construct(PDO $connection)
    {
        if ($connection->getAttribute(\PDO::ATTR_DRIVER_NAME) != 'mysql') {
            $msg = __CLASS__ . " requires pdo connection to be 'mysql'";
        }
        $this->resource = $connection;
    }
}
