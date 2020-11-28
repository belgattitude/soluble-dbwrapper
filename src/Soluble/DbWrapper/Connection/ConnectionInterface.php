<?php

declare(strict_types=1);

namespace Soluble\DbWrapper\Connection;

interface ConnectionInterface
{
    /**
     * Return current schema/database name.
     *
     * @throws \Soluble\DbWrapper\Exception\RuntimeException
     * @return string|null The name of the database or NULL if a database is not selected.
     *                     The platforms which don't support the concept of a database (e.g. embedded databases)
     *                     must always return a string as an indicator of an implicitly selected database.
     * @return string|null
     */
    public function getCurrentSchema();

    /**
     * Return internal connection (pdo, mysqli...).
     *
     * @return mixed
     */
    public function getResource();

    /**
     * Return connection host name or IP.
     *
     * @return string
     */
    public function getHost(): string;
}
