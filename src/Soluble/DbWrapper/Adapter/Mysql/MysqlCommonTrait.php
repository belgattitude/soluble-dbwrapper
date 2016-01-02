<?php

namespace Soluble\DbWrapper\Adapter\Mysql;

use Soluble\DbWrapper\Exception;

trait MysqlCommonTrait
{
    
    /**
     * Execute query and return query as an ArrayObject
     *
     * @throws \Soluble\DbWrapper\Exception\InvalidArgumentException
     * @param string $query
     * @return \Soluble\DbWrapper\Result\Resultset
     */    
    abstract public function query($query);

    /**
     * Return current schema/database name
     * 
     * @throws \Soluble\DbWrapper\Exception\RuntimeException
     * @return string|false
     */
    public function getCurrentSchema()
    {
        $query = 'SELECT DATABASE() as current_schema';
        try {
            $results = $this->query($query);
            if (count($results) == 0 || $results[0]['current_schema'] === null) {
                return false;
            }
        } catch (\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage());
        }
        return $results[0]['current_schema'];
    }
}
