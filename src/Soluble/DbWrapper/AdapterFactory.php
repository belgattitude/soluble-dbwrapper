<?php

declare(strict_types=1);

namespace Soluble\DbWrapper;

class AdapterFactory
{
    /**
     * Create adapter from an existing doctrine/dbal connection.
     *
     * @param \Illuminate\Database\Capsule\Manager $capsule
     *
     * @return \Soluble\DbWrapper\Adapter\Laravel\Capsule5Adapter
     */
    public static function createAdapterFromCapsule5(\Illuminate\Database\Capsule\Manager $capsule)
    {
        return new \Soluble\DbWrapper\Adapter\Laravel\Capsule5Adapter($capsule);
    }

    /**
     * Create adapter from an existing doctrine/dbal connection.
     *
     * @param \Doctrine\DBAL\Connection $dbalConnection
     *
     * @return \Soluble\DbWrapper\Adapter\Doctrine\Dbal2Adapter
     */
    public static function createAdapterFromDoctrine2(\Doctrine\DBAL\Connection $dbalConnection)
    {
        return new \Soluble\DbWrapper\Adapter\Doctrine\Dbal2Adapter($dbalConnection);
    }

    /**
     * Create adapter from an existing zendframework/zend-db adapter.
     *
     * @param \Zend\Db\Adapter\Adapter $zendAdapter
     *
     * @return \Soluble\DbWrapper\Adapter\Zend\ZendDb2Adapter
     */
    public static function createAdapterFromZendDb2(\Zend\Db\Adapter\Adapter $zendAdapter)
    {
        return new \Soluble\DbWrapper\Adapter\Zend\ZendDb2Adapter($zendAdapter);
    }

    /**
     * Create adapter from an existing connection resource.
     *
     * @param mixed $resource database connection object (mysqli, pdo_mysql,...)
     *
     * @throws Exception\InvalidArgumentException
     * @throws Exception\UnsupportedDriverException
     *
     * @return Adapter\AdapterInterface
     */
    public static function createAdapterFromResource($resource)
    {
        if (is_scalar($resource) || is_array($resource)) {
            throw new Exception\InvalidArgumentException("Resource param must be a valid 'resource' link (mysqli, pdo)");
        }
        if ($resource instanceof \PDO) {
            $adapter = self::getAdapterFromPdo($resource);
        } elseif (extension_loaded('mysqli') && $resource instanceof \mysqli) {
            $adapter = new Adapter\MysqliAdapter($resource);
        } else {
            throw new Exception\InvalidArgumentException('Resource must be a valid connection link, like PDO or mysqli');
        }

        return $adapter;
    }

    /**
     * Get an adapter from an existing connection resource.
     *
     * @param \PDO $resource database connection object
     *
     * @throws Exception\UnsupportedDriverException
     *
     * @return Adapter\AdapterInterface
     */
    protected static function getAdapterFromPdo(\PDO $resource)
    {
        $driver = mb_strtolower($resource->getAttribute(\PDO::ATTR_DRIVER_NAME));
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
