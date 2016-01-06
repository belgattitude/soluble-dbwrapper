# soluble/dbwrapper

[![PHP Version](http://img.shields.io/badge/php-5.4+-ff69b4.svg)](https://packagist.org/packages/soluble/dbwrapper)
[![HHVM Status](http://hhvm.h4cc.de/badge/soluble/dbwrapper.png?style=flat)](http://hhvm.h4cc.de/package/soluble/dbwrapper)
[![Build Status](https://travis-ci.org/belgattitude/soluble-dbwrapper.png?branch=master)](https://travis-ci.org/belgattitude/soluble-dbwrapper)
[![Code Coverage](https://scrutinizer-ci.com/g/belgattitude/soluble-dbwrapper/badges/coverage.png?s=aaa552f6313a3a50145f0e87b252c84677c22aa9)](https://scrutinizer-ci.com/g/belgattitude/soluble-dbwrapper)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/belgattitude/soluble-dbwrapper/badges/quality-score.png?s=6f3ab91f916bf642f248e82c29857f94cb50bb33)](https://scrutinizer-ci.com/g/belgattitude/soluble-dbwrapper)
[![Latest Stable Version](https://poser.pugx.org/soluble/dbwrapper/v/stable.svg)](https://packagist.org/packages/soluble/dbwrapper)
[![Total Downloads](https://poser.pugx.org/soluble/dbwrapper/downloads.png)](https://packagist.org/packages/soluble/dbwrapper)
[![License](https://poser.pugx.org/soluble/dbwrapper/license.png)](https://packagist.org/packages/soluble/dbwrapper)

## Introduction

Universal minimalist database wrapper to rule them all.

## Features

- Provide a generic API for handling database access across various implementations.
- Lightweight, framework adaptive and agnostic. 
- Natively supports `mysqli`, `pdo_mysql`, `pdo_sqlite` drivers.
- Bridged implementations of `zend-db`, `laravel` and `doctrine`.
  - Access to Oracle, SQL-Server, PostgreSql,...
  - Allow to develop database portable libraries.
- Adhere to soluble programming standards.

## Requirements

- PHP engine 5.4+, 7.0+ or HHVM >= 3.2.

## Documentation

 - [Manual](http://docs.soluble.io/soluble-dbwrapper/manual/) in progress and [API documentation](http://docs.soluble.io/soluble-dbwrapper/api/) available.

## Installation

Instant installation via [composer](http://getcomposer.org/).

```console
php composer require soluble/dbwrapper:1.*
```
Most modern frameworks will include Composer out of the box, but ensure the following file is included:

```php
<?php
// include the Composer autoloader
require 'vendor/autoload.php';
```

## Quick start

### Connection

Create an adapter from an existing PDO connection

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
```

Create an adapter from an existing Mysqli connection

```php
<?php

use Soluble\DbWrapper;

$conn = new \mysqli($hostname,$username,$password,$database);
$conn->set_charset($charset);

$adapter = DbWrapper\AdapterFactory::createAdapterFromResource($conn);

```

### Querying database

Execute SQL

```php
<?php
$results = $adapter->query("select * from my_table");
foreach($results as $result) {
    echo $result['my_column'];
}
```

### Get connection infos

Execute SQL

```php
<?php
$connection = $adapter->getConnection()
echo $connection->getCurrentSchema();
echo $connection->getHost();

$resource = $connection->getResource();
```

## API methods

### AdapterFactory

The `DbWrapper\AdapterFactory` allows to instanciate an Adapter from en existing connection link or resource.
 
| Methods                                       | Return             | Comment                             |
|-----------------------------------------------|--------------------|-------------------------------------|
| static `createAdapterFromResource($resource)` | `AdapterInterface` | Create an adapter from existing resource |


### AdapterInterface

The `DbWrapper\Adapter\AdapterInterface` provides common operation on your database.

| Methods                  | Return        | Description                                   |
|--------------------------|---------------|-----------------------------------------------|
| `query($query)`          | `Resultset`   | Iterable results `DbWrapper\Result\Resultset` |
| `execute($query)`        | `void`        | Execute command (set, ...)                    |
| `quoteValue($value)`     | `string`      | Quote value                                   |
| `getConnection()`        | `ConnectionInterface`  | ConnectionInterface                  |

### Resultset

The `DbWrapper\Result\Resultset` is can be easily iterated through a simple foreach loop. 
Additionnaly you can call the following methods :

| Methods                         | Return        | Description                                   |
|---------------------------------|---------------|-----------------------------------------------|
| `count()`                       | `int`         | Count the number of results                   |


### ConnectionInterface

The `DbWrapper\Connection\ConnectionInterface` provides information about your connection

| Methods                  | Return        | Description                                   |
|--------------------------|---------------|-----------------------------------------------|
| `getCurrentSchema()`     | `string|false`| Return current schema                         |
| `getResource()`          | `mixed`       | Return internal connection (pdo, mysqli...)   |
| `getHost()`              | `string`      | Return server hostname or IP                  |


## Supported drivers


### Native implementions

Native implementations does not require any external libraries in order to run.

| PHP extension      | DbWrapper\Adapter\AdapterInterface implementations   |
|--------------------|------------------------------------------------------|
| pdo_mysql          | `Soluble\DbWrapper\Adapter\PdoMysqlAdapter`          |
| pdo_sqlite         | `Soluble\DbWrapper\Adapter\PdoSqliteAdapter`         |
| mysqli             | `Soluble\DbWrapper\Adapter\MysqliAdapter`            |


### Bridged implementations

In addition to native support, `soluble/dbwrapper` allows to bridge over the following *userland* db abstraction layers :

| Bridge                   | Version | DbWrapper\Adapter\AdapterInterface implementations   |
|--------------------------|--------+|------------------------------------------------------|
| `zendframework/zend-db`  |     2.* | `Soluble\DbWrapper\Adapter\Zend\ZendDb2Adapter`      |
| `doctrine/dbal`          |     2.* | `Soluble\DbWrapper\Adapter\Doctrine\Dbal2Adapter`    |
| `illuminate/database`    |     5.* | `Soluble\DbWrapper\Adapter\Laravel\Capsule5Adapter`  |


The use of a bridged layer over a native implementation should be considered :

 - Your current project or library is already based on one of the supported bridged implementations
 - The native implementation does not exists for your database vendor (Oracle, PostgreSQL, Firebird, MS-SQL...)

In order to use a bridged implementation, you must add it to your dependencies in composer.json file if it's not already the case.

Additionnaly you can install dependencies from the command line :

For zendframework/zend-db :

```console
$ composer require zendframework/zend-db:~2
```

For doctrine/dbal :

```console
$ composer require doctrine/dbal:~2
```

For illuminate/database :

```console
$ composer require illuminate/database:~5
```

*You can easily add new drivers by implementing the `DbWrapper\Adapter\AdapterInterface`.*


## Motivations

Initially the reason behind the development of `soluble/dbwrapper` was to get
a reliable, modern and lightweight library to abstract the `PDO_mysql` and `mysqli` driver interfaces.

*If you are looking for a more complete library with extra drivers and a SQL abstraction, 
take a look at the excellent [`zendframework/zend-db`](https://github.com/zendframework/zend-db) package.*

## Contributing

Contribution and pull request are more than welcome, see the [contribution guide](./CONTRIBUTING.md)

## Coding standards

* [PSR 4 Autoloader](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md)
* [PSR 2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
* [PSR 1 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
* [PSR 0 Autoloading standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
