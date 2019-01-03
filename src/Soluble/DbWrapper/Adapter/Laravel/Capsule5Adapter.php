<?php declare(strict_types=1);

namespace Soluble\DbWrapper\Adapter\Laravel;

use Soluble\DbWrapper\Adapter\AdapterInterface;
use Soluble\DbWrapper\Connection\Laravel\Capsule5Connection;
use Soluble\DbWrapper\Adapter\Pdo\GenericPdo;

class Capsule5Adapter extends GenericPdo implements AdapterInterface
{
    /**
     * @var \Illuminate\Database\Capsule\Manager
     */
    protected $capsule;

    /**
     * @var Capsule5Connection
     */
    protected $connection;

    /**
     * @var \PDO
     */
    protected $resource;

    /**
     * Constructor.
     *
     * @param \Illuminate\Database\Capsule\Manager $capsule
     */
    public function __construct(\Illuminate\Database\Capsule\Manager $capsule)
    {
        $this->capsule = $capsule;
        $this->connection = new Capsule5Connection($this, $capsule);
        $this->resource = $capsule->getConnection()->getPdo();
    }

    /**
     * {@inheritdoc}
     *
     * @return Capsule5Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
