<?php

namespace Soluble\DbWrapper\Adapter\Zend;

use Soluble\DbWrapper\Exception;
use Soluble\DbWrapper\Result\Resultset;
use Soluble\DbWrapper\Adapter\AdapterInterface;
use Soluble\DbWrapper\Connection\Zend\ZendDb2Connection;

class ZendDb2Adapter implements AdapterInterface
{
    /**
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $zendAdapter;

    /**
     * @var ZendDb2Connection
     */
    protected $connection;

    /**
     * Constructor.
     *
     * @param \Zend\Db\Adapter\Adapter $zendAdapter
     */
    public function __construct(\Zend\Db\Adapter\Adapter $zendAdapter)
    {
        $this->zendAdapter = $zendAdapter;
        $this->connection = new ZendDb2Connection($this, $zendAdapter);
    }

    /**
     * {@inheritdoc}
     */
    public function quoteValue($value)
    {
        return $this->zendAdapter->getPlatform()->quoteValue($value);
    }

    /**
     * {@inheritdoc}
     */
    public function query($query, $resultsetType = Resultset::TYPE_ARRAY)
    {
        try {
            //$r = $this->zendAdapter->query($query, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
            $r = $this->zendAdapter->query($query)->execute();
            $results = new Resultset($resultsetType);
            if ($r === false) {
                throw new Exception\InvalidArgumentException(
                    'Query could not be executed ([$query])'
                );
            } elseif ($r instanceof \Zend\Db\ResultSet\ResultSet) {
                foreach ($r as $row) {
                    $results->append((array) $row);
                }
            } elseif ($r instanceof \Zend\Db\Adapter\Driver\ResultInterface && $r->getFieldCount() > 0) {
                foreach ($r as $row) {
                    $results->append($row);
                }
            }
        } catch (\Exception $e) {
            $msg = "ZendDb2 adapter query error: {$e->getMessage()} [$query]";
            throw new Exception\InvalidArgumentException($msg);
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     *
     * @return ZendDb2Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
