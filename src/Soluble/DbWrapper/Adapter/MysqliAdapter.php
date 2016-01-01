<?php

namespace Soluble\DbWrapper\Adapter;

use Soluble\DbWrapper\Exception;
use ArrayObject;
use mysqli;

class MysqliAdapter implements AdapterInterface
{

    use Mysql\MysqlCommonTrait;
    
    /**
     *
     * @var \mysqli
     */
    protected $connection;
    

    /**
     * Constructor
     *
     * @param mysqli $connection
     */
    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }


    /**
     * {@inheritdoc}
     */
    public function quoteValue($value)
    {
        return "'" . $this->connection->real_escape_string($value) . "'";
    }


    /**
     * {@inheritdoc}
     */
    public function query($query)
    {
        try {
            $r = $this->connection->query($query);

            $results = new ArrayObject();

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
     */
    public function execute($query)
    {
        $this->query($query);
    }    
}
