<?php

namespace Soluble\DbWrapper;

class AdapterFactory
{

    /**
     * Get an adapter from an existing connection
     *
     * @param \PDO|\mysqli $connection database connection object
     * @throws Exception\InvalidArgumentException
     * @return \Soluble\DbWrapper\Adapter\MysqlAdapter
     */
    public static function createAdapterFromConnection($connection)
    {
        if ($connection instanceof \PDO) {
            switch ($connection->getAttribute(\PDO::ATTR_DRIVER_NAME)) {
                case 'mysql':
                    $adapter = new Adapter\MysqlAdapter($connection);
                    break;
                default:
                    $msg = "Currently only support 'pdo_mysql' connection";
                    throw new Exception\InvalidArgumentException($msg);
            }
        } elseif ($connection instanceof \mysqli) {
            $adapter = new Adapter\MysqlAdapter($connection);
        } else {
            $msg = "Currently only support 'pdo' or 'mysqli' connections";
            throw new Exception\InvalidArgumentException($msg);
        }
        return $adapter;
    }
}
