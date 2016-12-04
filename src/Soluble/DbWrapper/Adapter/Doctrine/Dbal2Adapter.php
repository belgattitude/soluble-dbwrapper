<?php

namespace Soluble\DbWrapper\Adapter\Doctrine;

use Soluble\DbWrapper\Exception;
use Soluble\DbWrapper\Result\Resultset;
use Soluble\DbWrapper\Adapter\AdapterInterface;
use Soluble\DbWrapper\Connection\Doctrine\Dbal2Connection;

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

    /**
     * Constructor.
     *
     * @param \Doctrine\DBAL\Connection $dbal
     */
    public function __construct(\Doctrine\DBAL\Connection $dbal)
    {
        $this->dbal = $dbal;
        $this->connection = new Dbal2Connection($this, $dbal);
    }

    /**
     * {@inheritdoc}
     */
    public function quoteValue($value)
    {
        return $this->dbal->quote($value);
    }

    /**
     * {@inheritdoc}
     */
    public function query($query, $resultsetType = Resultset::TYPE_ARRAY)
    {
        // This error may happen with the libmysql instead of mysqlnd and using set statement (set @test=1)
        // : "Attempt to read a row while there is no result set associated with the statement"

        try {
            /**
             * @var \Doctrine\DBAL\Driver\Mysqli\MysqliStatement
             */
            $r = $this->dbal->query($query);

            $results = new Resultset($resultsetType);
            if ($r === false) {
                throw new Exception\InvalidArgumentException("Query cannot be executed [$query].");
            } else {
                if ($r->columnCount() > 0) {
                    while ($row = $r->fetch()) {
                        $results->append((array) $row);
                    }
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
    public function getConnection()
    {
        return $this->connection;
    }
}
