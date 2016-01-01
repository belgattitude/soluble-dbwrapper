<?php

class SolubleTestFactories {

    /**
     * @return string
     */
    public static function getCachePath() {
        $cache_dir = $_SERVER['PHPUNIT_CACHE_DIR'];
        if (!preg_match('/^\//', $cache_dir)) {
            $cache_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $cache_dir;
        }
        return $cache_dir;
    }
    
    /**
     * 
     * @param string $type
     * @param string|database
     * @return PDO|Mysqli
     */
    public static function getDbConnection($type, $config=null, $charset="UTF8")
    {
        if ($config === null) {
            $config = self::getDbConfiguration($type);
        }
        $hostname = $config['hostname'];
        $username = $config['username'];
        $password = $config['password'];
        $database = isset($config['database']) ? $config['database'] : null;
        switch ($type) {
            case 'pdo:mysql':
                $options = array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset",
                );                 
                if ($database === null) {
                    $conn = new PDO("mysql:host=$hostname", $username, $password, $options);
                } else {
                    $conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password, $options);
                }
                break;
            case 'mysqli' :
                $conn = new \mysqli($hostname,$username,$password,$database);
                $conn->set_charset($charset);
                break;
            default:
                throw new \Exception(__METHOD__ . " Unsupported driver type ($type)");
        }
        return $conn;
    }
    
    /**
     * 
     * @param string $type
     * @return array
     */
    public static function getDbConfiguration($type)
    {
        switch ($type) {
            case 'mysqli':
            case 'pdo:mysql' :    
                $config = array(
                    'hostname' => $_SERVER['MYSQL_HOSTNAME'],
                    'username' => $_SERVER['MYSQL_USERNAME'],
                    'password' => $_SERVER['MYSQL_PASSWORD'],
                    'database' => $_SERVER['MYSQL_DATABASE']
                );
                break;
            default:
                throw new \Exception("Database type unsupported in test configuration ($type)");
                
        }
        return $config;
        
    }
    
    public static function getDatabaseName($type) {
        $name = false;
        switch ($type) {
            case 'pdo:mysql':
            case 'mysqli' :
                $name = $_SERVER['MYSQL_DATABASE'];
                break;
            default:
                throw new \Exception(__METHOD__ . " Unsupported driver type ($type)");
        }
        return $name;
    }

}
