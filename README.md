# soluble/dbwrapper

[![PHP Version](http://img.shields.io/badge/php-5.3+-ff69b4.svg)](https://packagist.org/packages/soluble/dbwrapper)
[![HHVM Status](http://hhvm.h4cc.de/badge/soluble/dbwrapper.png?style=flat)](http://hhvm.h4cc.de/package/soluble/dbwrapper)
[![Build Status](https://travis-ci.org/belgattitude/soluble-dbwrapper.png?branch=master)](https://travis-ci.org/belgattitude/soluble-dbwrapper)
[![Code Coverage](https://scrutinizer-ci.com/g/belgattitude/soluble-dbwrapper/badges/coverage.png?s=aaa552f6313a3a50145f0e87b252c84677c22aa9)](https://scrutinizer-ci.com/g/belgattitude/soluble-dbwrapper)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/belgattitude/soluble-dbwrapper/badges/quality-score.png?s=6f3ab91f916bf642f248e82c29857f94cb50bb33)](https://scrutinizer-ci.com/g/belgattitude/soluble-dbwrapper)
[![Latest Stable Version](https://poser.pugx.org/soluble/dbwrapper/v/stable.svg)](https://packagist.org/packages/soluble/dbwrapper)
[![Total Downloads](https://poser.pugx.org/soluble/dbwrapper/downloads.png)](https://packagist.org/packages/soluble/dbwrapper)
[![License](https://poser.pugx.org/soluble/dbwrapper/license.png)](https://packagist.org/packages/soluble/dbwrapper)

## Introduction

Extra minimalist PHP database wrapper.

## Features

- Currently only abstract pdo_mysql / mysqli drivers with minimal functions.

## Requirements

- PHP engine 5.4+, 7.0+ or HHVM >= 3.2.
- PHP extensions pfo, pdo_mysql and  mysqli.

## Installation

Instant installation via [composer](http://getcomposer.org/).

```console
php composer require soluble/dbwrapper:0.*
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
    $adapter = DbWrapperAdapterFactory::createFromConnection($conn);
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

try {
    $adapter = DbWrapper\AdapterFactory::createFromConnection($conn);
} catch (DbWrapper\Exception\InvalidArgumentException $e) {
    // ...
}


```

### API methods

Once a `DbWrapper\Adapter\AdapterInterface is intitalized, you have access to the following methods

| Methods                         | Return        | Description                                 |
|---------------------------------|---------------|---------------------------------------------|
| `query($query)`                 | `ArrayObject` | Retrieve full query results                 |
| `execute($query)`               | `void`        | Execute command (set, ...)                  |
| `quoteValue($value)`            | `string`      | Quote value                                 |
| `getCurrentSchema()`            | `string|false`| Return current schema                       |




## Supported drivers

Currently only pdo_mysql and mysqli drivers  are supported. 

| Drivers            | Adapter interface implementation                     |
|--------------------|------------------------------------------------------|
| pdo_mysql          | `Soluble\DbAdapter\Adapter\MysqlAdapter`             |
| mysqli             | `Soluble\DbAdapter\Adapter\MysqlAdapter`             |


## Contributing

Contribution are welcome see [contribution guide](./CONTRIBUTING.md)

## Coding standards

* [PSR 4 Autoloader](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md)
* [PSR 2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
* [PSR 1 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
* [PSR 0 Autoloading standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)





