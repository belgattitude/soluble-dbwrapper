<?php

namespace Soluble\DbWrapper\Connection\Zend;

use Soluble\DbWrapper\Adapter\AdapterInterface;
use Soluble\DbWrapper\Exception;
use Soluble\DbWrapper\Connection\ConnectionInterface;

class ZendDb2Connection implements ConnectionInterface
{

    /**
     *
     * @var AdapterInterface;
     */
    protected $adapter;

    /**
     *
     * @var \Zend\Db\Adapter\Adapter;
     */
    protected $zendAdapter;

    /**
     * @param AdapterInterface $adapter
     * @param \Zend\Db\Adapter\Adapter $zendAdapter
     */
    public function __construct(AdapterInterface $adapter, \Zend\Db\Adapter\Adapter $zendAdapter)
    {
        $this->adapter = $adapter;
        $this->zendAdapter = $zendAdapter;
    }


    /**
     * {@inheritdoc}
     * @return mixed
     */
    public function getResource()
    {
        return $this->zendAdapter->getDriver()->getConnection()->getResource();
    }

    /**
     * {@inheritdoc}
     * @throws Exception\UnsupportedFeatureException
     */
    public function getHost()
    {
        throw new Exception\UnsupportedFeatureException(__METHOD__ . " is not (yet) supported for zend-db bridge");
    }

    /**
     * {@inheritdoc}
     * @throws Exception\RuntimeException
     * @return string
     */
    public function getCurrentSchema()
    {
        try {
            $schema = $this->zendAdapter->getDriver()->getConnection()->getCurrentSchema();
        } catch (\Exception $e) {
            throw new Exception\RuntimeException("Cannot retrieve current schema:" . $e->getMessage());
        }
        return $schema;
    }
}
