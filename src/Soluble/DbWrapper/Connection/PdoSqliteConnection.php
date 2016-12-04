<?php

namespace Soluble\DbWrapper\Connection;

use Soluble\DbWrapper\Adapter\AdapterInterface;
use PDO;

class PdoSqliteConnection implements ConnectionInterface
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var \PDO
     */
    protected $resource;

    /**
     * @param AdapterInterface $adapter
     * @param \PDO             $resource
     */
    public function __construct(AdapterInterface $adapter, PDO $resource)
    {
        $this->adapter = $adapter;
        $this->resource = $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentSchema()
    {
        return 'main';
    }

    /**
     * {@inheritdoc}
     *
     * @return \PDO
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
        return 'localhost';
    }
}
