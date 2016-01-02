<?php

namespace Soluble\DbWrapper\Connection;

interface ConnectionInterface
{
    
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

