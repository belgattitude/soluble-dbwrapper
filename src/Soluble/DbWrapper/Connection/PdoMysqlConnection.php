<?php

declare(strict_types=1);

namespace Soluble\DbWrapper\Connection;

use PDO;
use Soluble\DbWrapper\Adapter\PdoMysqlAdapter;
use Soluble\DbWrapper\Exception;

class PdoMysqlConnection implements ConnectionInterface
{
    /**
     * @var PdoMysqlAdapter
     */
    protected $adapter;

    /**
     * @var PDO
     */
    protected $resource;

    /**
     * @param PdoMysqlAdapter $adapter
     * @param PDO             $resource
     */
    public function __construct(PdoMysqlAdapter $adapter, PDO $resource)
    {
        $this->adapter  = $adapter;
        $this->resource = $resource;
    }

    /**
     * {@inheritdoc}
     *
     * @return PDO
     */
    public function getResource(): PDO
    {
        return $this->resource;
    }

    /**
     * {@inheritdoc}
     */
    public function getHost(): string
    {
        $infos = explode(' ', trim($this->resource->getAttribute(PDO::ATTR_CONNECTION_STATUS)));

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
