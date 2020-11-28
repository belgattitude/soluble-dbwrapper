<?php

declare(strict_types=1);

namespace Soluble\DbWrapper\Adapter\Doctrine;

use Soluble\DbWrapper\Adapter\AdapterInterface;
use Soluble\DbWrapper\Connection\ConnectionInterface;
use Soluble\DbWrapper\Connection\Doctrine\Dbal2Connection;
use Soluble\DbWrapper\Exception;
use Soluble\DbWrapper\Result\Resultset;

class Dbal2Adapter implements AdapterInterface
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $dbal;

    /**
     * @var Dbal2Connection
     */
    protected $connection;

    public function __construct(\Doctrine\DBAL\Connection $dbal)
    {
        $this->dbal       = $dbal;
        $this->connection = new Dbal2Connection($this, $dbal);
    }

    /**
     * {@inheritdoc}
     */
    public function quoteValue($value): string
    {
        return $this->dbal->quote($value);
    }

    /**
     * {@inheritdoc}
     */
    public function query(string $query, string $resultsetType = Resultset::TYPE_ARRAY): Resultset
    {
        try {
            $r = $this->dbal->executeQuery($query);
            $results = new Resultset($resultsetType);
            if ($r->columnCount() > 0) {
                while ($row = $r->fetchAssociative()) {
                    $results->append($row);
                }
            }
        } catch (\Exception $e) {
            $msg = "Doctrine\Dbal2 adapter query error: {$e->getMessage()} [$query]";
            throw new Exception\InvalidArgumentException($msg);
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     *
     * @return Dbal2Connection
     */
    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }
}
