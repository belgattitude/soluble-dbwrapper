<?php

namespace Soluble\DbWrapper\Connection\Doctrine;

use Soluble\DbWrapper\Adapter\AdapterInterface;
use Soluble\DbWrapper\Exception;
use Soluble\DbWrapper\Connection\ConnectionInterface;

class Dbal2Connection implements ConnectionInterface
{

    /**
     *
     * @var AdapterInterface;
     */
    protected $adapter;

    /**
     *
     * @var \Doctrine\DBAL\Connection;
     */
    protected $dbal;

    /**
     * @param AdapterInterface $adapter
     * @param \Doctrine\DBAL\Connection $dbal
     */
    public function __construct(AdapterInterface $adapter, \Doctrine\DBAL\Connection $dbal)
    {
        $this->adapter = $adapter;
        $this->dbal = $dbal;
    }


    /**
     * {@inheritdoc}
     * @return mixed
     */
    public function getResource()
    {
        return $this->dbal->getWrappedConnection();
    }

    /**
     * {@inheritdoc}
     * @throws Exception\UnsupportedFeatureException
     */
    public function getHost()
    {
        throw new Exception\UnsupportedFeatureException(__METHOD__ . " is not (yet) supported for doctrine dbal bridge");
    }

    /**
     * {@inheritdoc}
     * @throws Exception\RuntimeException
     * @return string
     */
    public function getCurrentSchema()
    {
        try {
            $schema = $this->dbal->getDatabase();
        } catch (\Exception $e) {
            throw new Exception\RuntimeException("Cannot retrieve current schema:" . $e->getMessage());
        }
        return $schema;
    }
}
