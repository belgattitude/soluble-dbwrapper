# soluble/dbwrapper

[![PHP 7.1+](https://img.shields.io/badge/php-7.1+-ff69b4.svg)](https://packagist.org/packages/soluble/dbwrapper)
[![Build Status](https://travis-ci.org/belgattitude/soluble-dbwrapper.png?branch=master)](https://travis-ci.org/belgattitude/soluble-dbwrapper)
[![codecov](https://codecov.io/gh/belgattitude/soluble-dbwrapper/branch/master/graph/badge.svg)](https://codecov.io/gh/belgattitude/soluble-dbwrapper)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/belgattitude/soluble-dbwrapper/badges/quality-score.png?s=6f3ab91f916bf642f248e82c29857f94cb50bb33)](https://scrutinizer-ci.com/g/belgattitude/soluble-dbwrapper)
[![Latest Stable Version](https://poser.pugx.org/soluble/dbwrapper/v/stable.svg)](https://packagist.org/packages/soluble/dbwrapper)
[![Total Downloads](https://poser.pugx.org/soluble/dbwrapper/downloads.png)](https://packagist.org/packages/soluble/dbwrapper)
[![License](https://poser.pugx.org/soluble/dbwrapper/license.png)](https://packagist.org/packages/soluble/dbwrapper)

## Introduction

Minimalist *(but universal)* database wrapper to rule them all *(and to not choose)*.

## Features

- Provide a generic API for handling database access across various implementations.
- Lightweight, framework adaptive and agnostic. 
- Natively supports `mysqli`, `pdo_mysql`, `pdo_sqlite` drivers.
- Bridged implementations of `zend-db`, `laravel` and `doctrine`.
  - Access to Oracle, SQL-Server, PostgreSql,...
  - Allow to develop database portable libraries.
- Adhere to soluble programming standards.

## Requirements

- PHP 7.1 optional ext-mysqli/PDO

> For PHP 5.6+, 7.0+ or HHVM >= 3.9 look at version 1.3.
> For PHP < 5.6 take the 1.2 version

## Documentation

 - [Manual](http://docs.soluble.io/soluble-dbwrapper/manual/) in progress and [API documentation](http://docs.soluble.io/soluble-dbwrapper/api/) available.

## Installation

Instant installation via [composer](http://getcomposer.org/).

```console
$ composer require soluble/dbwrapper
```

## Quick start

### Connection

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
$connection = $adapter->getConnection();
echo $connection->getCurrentSchema();
echo $connection->getHost();

$resource = $connection->getResource();
```

## API methods

### AdapterFactory

The `DbWrapper\AdapterFactory` allows to instanciate an Adapter from en existing connection link or resource.
 
| Methods                                       | Return             | Comment                             |
|-----------------------------------------------|--------------------|-------------------------------------|
| static `createAdapterFromResource($resource)` | `AdapterInterface` | From existing resource (mysqli, pdo) |
| static `createAdapterFromDbal2($dbal)`        | `AdapterInterface` | From doctrine/dbal connection |
| static `createAdapterFromCapsule5($capsule)`  | `AdapterInterface` | From Laravel connection |
| static `createAdapterFromZendDb2($zend)`      | `AdapterInterface` | From zend-db connection |


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


## Supported databases

### Native 

`soluble/dbwrapper` supports natively :

| Database   | PHP ext                                              |
|------------|------------------------------------------------------|
| Mysql      | mysqli, pdo_mysql                                    |
| MariaDb    | mysqli, pdo_mysql                                    |
| Sqlite     | pdo_sqlite                                           |

For examples, see the [native drivers doc](./docs/drivers/native-drivers.md)

### *Userland* implementations

Some of the supported databases can be (incomplete list) :

| Database   | Doctrine   | Laravel | Zend      |
|------------|------------|---------|-----------|
| Mysql      | Yes        | Yes     | Yes       |
| MariaDb    | Yes        | Yes     | Yes       | 
| Sqlite     | Yes        | Yes     | Yes       |
| Oracle     | Yes        | No      | Yes       |
| Sqlserver  | Yes        | Yes     | Yes       |
| Postgres   | Yes        | Yes     | Yes       |
(...)


For examples, see the [userland drivers doc](./docs/drivers/userland-drivers.md)

## Motivations

Initially the reason behind the development of `soluble/dbwrapper` was to get
a reliable and lightweight library to abstract the `PDO_mysql` and `mysqli` driver interfaces.

Later on, while developing some libraries, I feel the need for something more framework agnostic that could still
be integrated easily into any modern framework. The *userland* drivers idea was born.

## Contributing

Contribution and pull request are more than welcome, see the [contribution guide](./CONTRIBUTING.md)

## Coding standards

* [PSR 4 Autoloader](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md)
* [PSR 2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
