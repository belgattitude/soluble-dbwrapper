<?php

namespace Soluble\DbWrapper\Adapter\Pdo;

use Soluble\DbWrapper\Exception;
use Soluble\DbWrapper\Adapter\AdapterInterface;
use Soluble\DbWrapper\Result\Resultset;

abstract class GenericPdo implements AdapterInterface
{
    /**
     *
     * @var \PDO
     */
    protected $resource;    

    /**
     * {@inheritdoc}
     */
    public function execute($query)
    {
        try {
            $ret = $this->resource->exec($query);
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
            $stmt = $this->resource->query($query, \PDO::FETCH_ASSOC);
            if ($stmt === false) {
                throw new Exception\InvalidArgumentException("Query cannot be executed [$query].");
            }
            $results = new Resultset();
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
     * {@inheritdoc}
     */
    public function quoteValue($value)
    {
        return $this->resource->quote($value);
    }

    /**
     * {@ineritdoc}
     * @return \PDO
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Check extension
     * @throws Exception\RuntimeException
     * @return void
     */
    protected function checkEnvironment()
    {
        if (!extension_loaded('PDO')) {
            throw new Exception\RuntimeException('The PDO extension is not loaded');
        }
    }
    
    /**
     * {@inheritdoc}
     */
    abstract public function getCurrentSchema();
    
    
}
