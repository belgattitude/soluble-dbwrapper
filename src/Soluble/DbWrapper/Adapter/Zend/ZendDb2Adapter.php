<?php

declare(strict_types=1);

namespace Soluble\DbWrapper\Adapter\Zend;

use Soluble\DbWrapper\Adapter\AdapterInterface;
use Soluble\DbWrapper\Connection\ConnectionInterface;
use Soluble\DbWrapper\Connection\Zend\ZendDb2Connection;
use Soluble\DbWrapper\Exception;
use Soluble\DbWrapper\Result\Resultset;

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
        $this->connection  = new ZendDb2Connection($this, $zendAdapter);
    }

    /**
     * {@inheritdoc}
     */
    public function quoteValue($value): string
    {
        return $this->zendAdapter->getPlatform()->quoteValue($value);
    }

    /**
     * {@inheritdoc}
     */
    public function query(string $query, string $resultsetType = Resultset::TYPE_ARRAY): Resultset
    {
        try {
            $stmt    = $this->zendAdapter->createStatement($query);
            $r       = $stmt->execute();
            $results = new Resultset($resultsetType);
            if ($r->getFieldCount() > 0) {
                foreach ($r as $row) {
                    $results->append($row);
                }
            }
            unset($r);
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
    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }
}
