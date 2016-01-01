<?php

namespace Soluble\DbWrapper\Adapter;

use Soluble\DbWrapper\Exception;
use ArrayObject;

class MysqlAdapter implements AdapterInterface
{

    const DRIVER_TYPE_PDO = 'pdo';
    const DRIVER_TYPE_MYSQLI = 'mysqli';

    /**
     * @var string
     */
    protected $driver;

    /**
     *
     * @var \mysqli|null
     */
    protected $mysqli;

    /**
     *
     * @var \PDO|null
     */
    protected $pdo;

    /**
     *
     * @var string
     */
    protected $type;

    /**
     * Constructor
     *
     * @throws Exception\InvalidArgumentException
     * @param \mysqli|\PDO $conn
     */
    public function __construct($conn)
    {
        if ($conn instanceof \mysqli) {
            $this->mysqli = $conn;
            $this->type = self::DRIVER_TYPE_MYSQLI;
        } elseif ($conn instanceof \PDO && $conn->getAttribute(\PDO::ATTR_DRIVER_NAME) == 'mysql') {
            $this->pdo = $conn;
            $this->type = self::DRIVER_TYPE_PDO;
        } else {
            $msg = "MysqlAdapter requires connection to be either 'pdo:mysql' or 'mysqli'";
            throw new Exception\InvalidArgumentException($msg);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentSchema()
    {
        $query = 'SELECT DATABASE() as current_schema';
        $results = $this->query($query);
        if (count($results) == 0 || $results[0]['current_schema'] === null) {
            return false;
        }
        return $results[0]['current_schema'];
    }

    /**
     * {@inheritdoc}
     */
    public function quoteValue($value)
    {
        if ($this->type == self::DRIVER_TYPE_MYSQLI) {
            $quoted = "'" . $this->mysqli->real_escape_string($value) . "'";
        } else {
            $quoted = $this->pdo->quote($value);
        }
        return $quoted;
    }

    /**
     * {@inheritdoc}
     */
    public function query($query)
    {
        if ($this->type == self::DRIVER_TYPE_MYSQLI) {
            $results = $this->queryMysqli($query);
        } else {
            $results = $this->queryPDO($query);
        }
        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($query)
    {
        if ($this->type == self::DRIVER_TYPE_MYSQLI) {
            $this->queryMysqli($query);
        } else {
            $this->executePDO($query);
        }
    }


    /**
     * Execute on PDO
     *
     * @param string $query
     * @throws Exception\InvalidArgumentException
     */
    protected function executePDO($query)
    {
        try {
            $ret = $this->pdo->exec($query);
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
     * Query on PDO
     * @param string $query
     * @throws Exception\InvalidArgumentException
     * @return ArrayObject
     */
    protected function queryPDO($query)
    {
        try {
            $stmt = $this->pdo->query($query, \PDO::FETCH_ASSOC);
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

    /**
     * Query on Mysqli
     *
     * @throws Exception\InvalidArgumentException
     * @param string $query
     * @return ArrayObject
     */
    protected function queryMysqli($query)
    {
        try {
            $r = $this->mysqli->query($query);

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
}
