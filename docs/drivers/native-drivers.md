# Introduction

Native adapters does not require additional libraries and should provide the
most efficient interface to database access.

## Current implementations

| PHP extension      | `DbWrapper\Adapter\AdapterInterface` implementations   |
|--------------------|--------------------------------------------------------|
| mysqli             | `Soluble\DbWrapper\Adapter\MysqliAdapter`              |
| pdo_mysql          | `Soluble\DbWrapper\Adapter\PdoMysqlAdapter`            |
| pdo_sqlite         | `Soluble\DbWrapper\Adapter\PdoSqliteAdapter`           |


## Connection examples

### Mysqli example

Create an adapter from an existing Mysqli connection

```php
<?php

use Soluble\DbWrapper;

$conn = new \mysqli($hostname,$username,$password,$database);
$conn->set_charset($charset);

$adapter = DbWrapper\AdapterFactory::createAdapterFromResource($conn);

echo get_class($adapter); // -> `Soluble\DbWrapper\Adapter\MysqliAdapter`

```

### PDO_mysqli example

Create and adapter from an existing PDO_mysql connection

```php
<?php

use Soluble\DbWrapper;

$conn = new \PDO("mysql:host=$hostname", $username, $password, [
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
]);

try {
    $adapter = DbWrapperAdapterFactory::createAdapterFromResource($conn);
} catch (DbWrapper\Exception\UnsupportedDriverException $e) {
    // ...
} catch (DbWrapper\Exception\InvalidArgumentException $e) {
    // ...
}
1
echo get_class($adapter); // -> `Soluble\DbWrapper\Adapter\PdoMysqlAdapter`

```


### PDO_sqlite example

Create and adapter from an existing PDO_sqlite connection

```php
<?php

use Soluble\DbWrapper;

$conn = $this->connection = new \PDO('sqlite::memory:');

try {
    $adapter = DbWrapperAdapterFactory::createAdapterFromResource($conn);
} catch (DbWrapper\Exception\UnsupportedDriverException $e) {
    // ...
} catch (DbWrapper\Exception\InvalidArgumentException $e) {
    // ...
}

echo get_class($adapter); // -> `Soluble\DbWrapper\Adapter\PdoSqliteAdapter`


```





