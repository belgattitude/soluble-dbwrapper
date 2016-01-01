<?php

namespace Soluble\DbWrapper\Adapter;

use Soluble\DbWrapper\Exception;
use ArrayObject;
use PDO;

class PdoMysqlAdapter implements AdapterInterface
{
    use Mysql\MysqlCommonTrait;

    /**
     *
     * @var PDO
     */
    protected $connection;


    /**
     * Constructor
     *
     * @throws Exception\InvalidArgumentException
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        if ($connection->getAttribute(\PDO::ATTR_DRIVER_NAME) != 'mysql') {
            $msg = __CLASS__ . " requires pdo connection to be 'mysql'";
        }
        $this->connection = $connection;
    }


    /**
     * {@inheritdoc}
     */
    public function quoteValue($value)
    {
        return $this->connection->quote($value);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($query)
    {
        try {
            $ret = $this->connection->exec($query);
            if ($ret === false) {
                throw new Exception\InvalidArgumentException("Cannot execute [$query].");
            }
        } catch (Exception\InvalidArgumentException $e) {
            throw $e;
        } catch (\Exception $e) {
            $msg = "PDOException : {$e->getMessage()} [$query]";
            throw new Exception\InvalidArgumentException($msg);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function query($query)
    {
        try {
            $stmt = $this->connection->query($query, \PDO::FETCH_ASSOC);
            if ($stmt === false) {
                throw new Exception\InvalidArgumentException("Query cannot be executed [$query].");
            }
            $results = new ArrayObject();
            foreach ($stmt as $row) {
                $results->append($row);
            }
        } catch (Exception\InvalidArgumentException $e) {
            throw $e;
        } catch (\Exception $e) {
            $msg = "PDOException : {$e->getMessage()} [$query]";
            throw new Exception\InvalidArgumentException($msg);
        }
        return $results;
    }
}
