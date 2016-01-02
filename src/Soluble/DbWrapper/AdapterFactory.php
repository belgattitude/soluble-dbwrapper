<?php

namespace Soluble\DbWrapper;

class AdapterFactory
{

    /**
     * Create adapter from an existing connection resource
     *
     * @param mixed $resource database connection object (mysqli, pdo_mysql,...)
     * @throws Exception\InvalidArgumentException
     * @throws Exception\UnsupportedDriverException
     * @return Adapter\AdapterInterface
     */
    public static function createAdapterFromResource($resource)
    {
        if (is_scalar($resource) || is_array($resource)) {
            throw new Exception\InvalidArgumentException("Resource param must be a valid 'resource' link (mysqli, pdo)");
        } if ($resource instanceof \PDO) {
            $adapter = self::getAdapterFromPdo($resource);
        } elseif (extension_loaded('mysqli') && $resource instanceof \mysqli) {
            $adapter = new Adapter\MysqliAdapter($resource);
        } else {
            throw new Exception\InvalidArgumentException("Resource must be a valid connection link, like PDO or mysqli");
        }
        return $adapter;
    }


    /**
     * Get an adapter from an existing connection resource
     *
     * @param \PDO $resource database connection object
     * @throws Exception\UnsupportedDriverException
     * @return Adapter\AdapterInterface
     */
    protected static function getAdapterFromPdo(\PDO $resource)
    {

        $driver = strtolower($resource->getAttribute(\PDO::ATTR_DRIVER_NAME));
        switch ($driver) {
            case 'mysql':
                $adapter = new Adapter\PdoMysqlAdapter($resource);
                break;
            case 'sqlite':
                $adapter = new Adapter\PdoSqliteAdapter($resource);
                break;
            default:
                $msg = "Driver 'PDO_$driver' is not currently supported.";
                throw new Exception\UnsupportedDriverException($msg);
        }
        return $adapter;
    }
}
