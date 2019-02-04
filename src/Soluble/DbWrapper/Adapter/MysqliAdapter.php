<?php

declare(strict_types=1);

namespace Soluble\DbWrapper\Adapter;

use mysqli;
use Soluble\DbWrapper\Connection\ConnectionInterface;
use Soluble\DbWrapper\Connection\MysqliConnection;
use Soluble\DbWrapper\Exception;
use Soluble\DbWrapper\Result\Resultset;

class MysqliAdapter implements AdapterInterface
{
    /**
     * @var mysqli
     */
    protected $resource;

    /**
     * @var MysqliConnection
     */
    protected $connection;

    /**
     * Constructor.
     *
     * @param mysqli $resource
     */
    public function __construct(mysqli $resource)
    {
        $this->resource   = $resource;
        $this->connection = new MysqliConnection($this, $resource);
    }

    /**
     * {@inheritdoc}
     */
    public function quoteValue($value): string
    {
        return "'" . $this->resource->real_escape_string($value) . "'";
    }

    /**
     * {@inheritdoc}
     */
    public function query(string $query, string $resultsetType = Resultset::TYPE_ARRAY): Resultset
    {
        try {
            $r = $this->resource->query($query, MYSQLI_STORE_RESULT);

            $results = new Resultset($resultsetType);

            if ($r === false) {
                throw new Exception\InvalidArgumentException("Query cannot be executed [$query].");
            } elseif ($r instanceof \mysqli_result) {
                while ($row = $r->fetch_assoc()) {
                    $results->append($row);
                }
                $r->close();
            }
        } catch (Exception\InvalidArgumentException $e) {
            throw $e;
        } catch (\Exception $e) {
            $msg = "MysqliException: {$e->getMessage()} [$query]";
            throw new Exception\InvalidArgumentException($msg);
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     *
     * @return MysqliConnection
     */
    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }
}
