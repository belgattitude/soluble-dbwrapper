<?php

namespace Soluble\DbWrapper\Adapter\Mysql;

trait MysqlCommonTrait
{

    /**
     * Return current schema/database name
     *
     * @return string|false
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
    
    
}

