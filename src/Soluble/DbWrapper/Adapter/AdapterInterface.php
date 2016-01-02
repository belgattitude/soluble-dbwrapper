<?php

namespace Soluble\DbWrapper\Adapter;

interface AdapterInterface
{

    /**
     * Execute query and return query as an ArrayObject
     *
     * @throws \Soluble\DbWrapper\Exception\InvalidArgumentException
     * @param string $query
     * @return \Soluble\DbWrapper\Result\Resultset
     */
    public function query($query);

    /**
     * Execute special sql like set names...
     *
     * @throws \Soluble\DbWrapper\Exception\InvalidArgumentException
     * @param string $query
     * @return void
     */
    public function execute($query);

    /**
     * Quote value for safely build your queries
     *
     * @param string $value
     * @return string quoted string
     */
    public function quoteValue($value);


    /**
     * Return current schema/database name
     * @throws \Soluble\DbWrapper\Exception\RuntimeException
     * @return string|false
     */
    public function getCurrentSchema();


    /**
     * Return internal connection (pdo, mysqli...)
     *
     * @return mixed
     */
    public function getResource();
}
