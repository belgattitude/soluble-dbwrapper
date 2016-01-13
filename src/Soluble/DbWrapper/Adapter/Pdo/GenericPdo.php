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
    public function query($query)
    {
        try {
            //$query = "select * from product";
            $stmt = $this->resource->prepare($query);

            if ($stmt === false) {
                $error = $this->resource->errorInfo();
                throw new Exception\InvalidArgumentException($error[2]);
            }

            if (!$stmt->execute()) {
                throw new Exception\InvalidArgumentException(
                    'Statement could not be executed (' . implode(' - ', $this->resource->errorInfo()) . ')'
                );
            };

            $results = new Resultset();
            if ($stmt->columnCount() > 0) {
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $results->append($row);
                }
            }
            $stmt->closeCursor();

        } catch (Exception\InvalidArgumentException $e) {
            throw $e;
        } catch (\Exception $e) {
            $eclass = get_class($e);
            $msg = "GenericPdo '$eclass' : {$e->getMessage()} [$query]";
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
}
