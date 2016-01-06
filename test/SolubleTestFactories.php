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
     * @param array|null $config
     * @param string $charset
     * @return PDO|Mysqli|mixed
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
            case 'capsule5-mysqli' :
                
                $params = \SolubleTestFactories::getDbConfiguration('mysqli');
                $capsule = new \Illuminate\Database\Capsule\Manager();        
                $capsule->addConnection([
                    'driver'    => 'mysql',
                    'host'      => $params['hostname'],
                    'database'  => $params['database'],
                    'username'  => $params['username'],
                    'password'  => $params['password'],
                    'charset'   => $charset,
                    'collation' => 'utf8_unicode_ci'
                ]);

                //$capsule->setEventDispatcher(new \Illuminate\Events\Dispatcher(new Illuminate\Container\Container()));        
                $conn = $capsule;
                
                break;
           
            case 'doctrine2-mysqli' :
                $params = \SolubleTestFactories::getDbConfiguration('mysqli');
                $connectionParams = array(
                    'dbname' => $params['database'],
                    'user' => $params['username'],
                    'password' => $params['password'],
                    'host' =>  $params['hostname'],
                    'driver' => 'mysqli',
                    'charset' => $charset
                );
                $c = new \Doctrine\DBAL\Configuration();
                $e = new \Doctrine\Common\EventManager();
                //$r = new \ReflectionClass('Doctrine\Common\EventManager');
                //var_dump($r->getFilename());
                //die();
                $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $c, $e);        

                break;
            case 'zend-db2-mysqli' :
                $params = \SolubleTestFactories::getDbConfiguration('mysqli');
                $params = array_merge($params, array('driver' => 'Mysqli', 'charset' => $charset));
                $conn = new \Zend\Db\Adapter\Adapter($params);
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
            case 'zend-db2-mysqli':
            case 'doctrine2-mysqli':
            case 'capsule5-mysqli':
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
