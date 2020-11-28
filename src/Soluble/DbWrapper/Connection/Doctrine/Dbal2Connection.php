<?php

declare(strict_types=1);

namespace Soluble\DbWrapper\Connection\Doctrine;

use Soluble\DbWrapper\Adapter\AdapterInterface;
use Soluble\DbWrapper\Connection\ConnectionInterface;
use Soluble\DbWrapper\Exception;

class Dbal2Connection implements ConnectionInterface
{
    /**
     * @var AdapterInterface;
     */
    protected $adapter;

    /**
     * @var \Doctrine\DBAL\Connection;
     */
    protected $dbal;

    public function __construct(AdapterInterface $adapter, \Doctrine\DBAL\Connection $dbal)
    {
        $this->adapter = $adapter;
        $this->dbal    = $dbal;
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function getResource()
    {
        return $this->dbal->getWrappedConnection();
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception\UnsupportedFeatureException
     */
    public function getHost(): string
    {
        throw new Exception\UnsupportedFeatureException(__METHOD__ . ' is not (yet) supported for doctrine dbal bridge');
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception\RuntimeException
     */
    public function getCurrentSchema()
    {
        try {
            $schema = $this->dbal->getDatabase();
        } catch (\Exception $e) {
            throw new Exception\RuntimeException(sprintf('Cannot retrieve current schema: %s', $e->getMessage()));
        }

        return $schema;
    }
}
