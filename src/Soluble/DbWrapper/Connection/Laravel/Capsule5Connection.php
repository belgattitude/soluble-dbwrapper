<?php declare(strict_types=1);

namespace Soluble\DbWrapper\Connection\Laravel;

use Soluble\DbWrapper\Adapter\AdapterInterface;
use Soluble\DbWrapper\Exception;
use Soluble\DbWrapper\Connection\ConnectionInterface;

class Capsule5Connection implements ConnectionInterface
{
    /**
     * @var AdapterInterface;
     */
    protected $adapter;

    /**
     * @var \Illuminate\Database\Capsule\Manager;
     */
    protected $capsule;

    /**
     * @param AdapterInterface                     $adapter
     * @param \Illuminate\Database\Capsule\Manager $capsule
     */
    public function __construct(AdapterInterface $adapter, \Illuminate\Database\Capsule\Manager $capsule)
    {
        $this->adapter = $adapter;
        $this->capsule = $capsule;
    }

    /**
     * {@inheritdoc}
     *
     * @return \PDO
     */
    public function getResource()
    {
        return $this->capsule->getConnection()->getPdo();
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception\UnsupportedFeatureException
     */
    public function getHost()
    {
        throw new Exception\UnsupportedFeatureException(__METHOD__ . ' is not (yet) supported for capsule bridge');
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception\RuntimeException
     *
     * @return string
     */
    public function getCurrentSchema()
    {
        try {
            $schema = $this->capsule->getConnection()->getDatabaseName();
        } catch (\Exception $e) {
            throw new Exception\RuntimeException('Cannot retrieve current schema:' . $e->getMessage());
        }

        return $schema;
    }
}
