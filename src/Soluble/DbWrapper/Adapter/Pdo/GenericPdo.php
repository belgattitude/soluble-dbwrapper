<?php

declare(strict_types=1);

namespace Soluble\DbWrapper\Adapter\Pdo;

use Soluble\DbWrapper\Adapter\AdapterInterface;
use Soluble\DbWrapper\Exception;
use Soluble\DbWrapper\Result\Resultset;

abstract class GenericPdo implements AdapterInterface
{
    /**
     * @var \PDO
     */
    protected $resource;

    /**
     * {@inheritdoc}
     */
    public function query(string $query, string $resultsetType = Resultset::TYPE_ARRAY): Resultset
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
                    sprintf('Statement could not be executed (%s)', implode(' - ', $this->resource->errorInfo()))
                );
            }

            $results = new Resultset($resultsetType);
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
            $msg    = sprintf("GenericPdo '%s' : %s [%s]", $eclass, $e->getMessage(), $query);
            throw new Exception\InvalidArgumentException($msg);
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function quoteValue($value): string
    {
        return $this->resource->quote($value);
    }

    /**
     * Check extension.
     *
     * @throws Exception\RuntimeException
     */
    protected function checkEnvironment(): void
    {
        if (!extension_loaded('PDO')) {
            throw new Exception\RuntimeException('The PDO extension is not loaded');
        }
    }
}
