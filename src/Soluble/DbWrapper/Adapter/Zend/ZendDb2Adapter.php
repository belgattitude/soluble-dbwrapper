<?php declare(strict_types=1);

namespace Soluble\DbWrapper\Adapter\Zend;

use Soluble\DbWrapper\Exception;
use Soluble\DbWrapper\Result\Resultset;
use Soluble\DbWrapper\Adapter\AdapterInterface;
use Soluble\DbWrapper\Connection\Zend\ZendDb2Connection;

class ZendDb2Adapter implements AdapterInterface
{
    /**
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $zendAdapter;

    /**
     * @var ZendDb2Connection
     */
    protected $connection;

    /**
     * Constructor.
     *
     * @param \Zend\Db\Adapter\Adapter $zendAdapter
     */
    public function __construct(\Zend\Db\Adapter\Adapter $zendAdapter)
    {
        $this->zendAdapter = $zendAdapter;
        $this->connection = new ZendDb2Connection($this, $zendAdapter);
    }

    /**
     * {@inheritdoc}
     */
    public function quoteValue($value)
    {
        return $this->zendAdapter->getPlatform()->quoteValue($value);
    }

    /**
     * {@inheritdoc}
     */
    public function query($query, $resultsetType = Resultset::TYPE_ARRAY)
    {
        try {
            $stmt = $this->zendAdapter->createStatement($query);
            $r = $stmt->execute();
            $results = new Resultset($resultsetType);
            if ($r->getFieldCount() > 0) {
                foreach ($r as $row) {
                    $results->append($row);
                }
            }
            unset($r);
        } catch (\Exception $e) {
            $msg = "ZendDb2 adapter query error: {$e->getMessage()} [$query]";
            throw new Exception\InvalidArgumentException($msg);
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     *
     * @return ZendDb2Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
