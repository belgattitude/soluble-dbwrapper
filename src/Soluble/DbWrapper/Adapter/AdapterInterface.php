<?php

namespace Soluble\DbWrapper\Adapter;

interface AdapterInterface
{

    /**
     * Execute query and return query as an ArrayObject
     *
     * @throws \Soluble\DbWrapper\Exception\InvalidArgumentException
     * @param string $query
     * @param string $resultsetType default to Resultset::ARRAY
     * @return \Soluble\DbWrapper\Result\Resultset
     */
    public function query($query, $resultsetType = \Soluble\DbWrapper\Result\Resultset::TYPE_ARRAY);


    /**
     * Quote value for safely build your queries
     *
     * @param string $value
     * @return string quoted string
     */
    public function quoteValue($value);


    /**
     * Return connection object
     *
     * @return \Soluble\DbWrapper\Connection\ConnectionInterface
     */
    public function getConnection();
}
