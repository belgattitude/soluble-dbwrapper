<?php

declare(strict_types=1);

namespace Soluble\DbWrapper\Adapter;

use Soluble\DbWrapper\Connection\ConnectionInterface;
use Soluble\DbWrapper\Result\Resultset;

interface AdapterInterface
{
    /**
     * Execute query and return query as an ArrayObject.
     *
     * @throws \Soluble\DbWrapper\Exception\InvalidArgumentException
     *
     * @param string $resultsetType default to Resultset::ARRAY
     */
    public function query(string $query, string $resultsetType = \Soluble\DbWrapper\Result\Resultset::TYPE_ARRAY): Resultset;

    /**
     * Quote value for safely build your queries.
     *
     * @param string $value
     */
    public function quoteValue($value): string;

    /**
     * Return connection object.
     */
    public function getConnection(): ConnectionInterface;
}
