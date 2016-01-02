<?php

namespace Soluble\DbWrapper\Adapter;

use Soluble\DbWrapper\Exception;
use mysqli;
use Soluble\DbWrapper\Result\Resultset;
use Soluble\DbWrapper\Connection\MysqliConnection;

class MysqliAdapter implements AdapterInterface
{

    /**
     *
     * @var \mysqli
     */
    protected $resource;

    /**
     *
     * @var MysqliConnection
     */
    protected $connection;


    /**
     * Constructor
     *
     * @param mysqli $resource
     */
    public function __construct(mysqli $resource)
    {
        $this->resource = $resource;
        $this->connection = new MysqliConnection($this, $resource);
    }


    /**
     * {@inheritdoc}
     */
    public function quoteValue($value)
    {
        return "'" . $this->resource->real_escape_string($value) . "'";
    }


    /**
     * {@inheritdoc}
     */
    public function query($query)
    {
        try {
            $r = $this->resource->query($query);

            $results = new Resultset();

            if ($r === false) {
                throw new Exception\InvalidArgumentException("Query cannot be executed [$query].");
            } elseif ($r !== true && !$r instanceof \mysqli_result) {
                throw new Exception\InvalidArgumentException("Query didn't return any result [$query].");
            } elseif ($r instanceof \mysqli_result) {
                while ($row = $r->fetch_assoc()) {
                    $results->append($row);
                }
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
     * @return MysqlConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
