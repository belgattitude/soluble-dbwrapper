<?php declare(strict_types=1);

namespace Soluble\DbWrapper\Adapter;

use Soluble\DbWrapper\Exception;
use Soluble\DbWrapper\Adapter\Pdo\GenericPdo;
use Soluble\DbWrapper\Connection\PdoSqliteConnection;
use PDO;

class PdoSqliteAdapter extends GenericPdo implements AdapterInterface
{
    /**
     * @var \PDO
     */
    protected $resource;

    /**
     * @var PdoSqliteConnection
     */
    protected $connection;

    /**
     * Constructor.
     *
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     *
     * @param \PDO $resource
     */
    public function __construct(PDO $resource)
    {
        $this->checkEnvironment();
        if ($resource->getAttribute(\PDO::ATTR_DRIVER_NAME) != 'sqlite') {
            $msg = __CLASS__ . " requires pdo connection to be 'sqlite'";
            throw new Exception\InvalidArgumentException($msg);
        }
        $this->resource = $resource;
        $this->connection = new PdoSqliteConnection($this, $resource);
    }

    /**
     * {@inheritdoc}
     *
     * @return PdoSqliteConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
