# Introduction

The *userland* drivers can be used to use an existing db library, such as Doctrine, Zend, Laravel.


## Motivation

The choice of an *userland* driver over a native one can be considered whether :

 - Your current project or library is already based on one of the supported bridged implementations.
 - The [native drivers](./native-drivers.md) does not exists (yet) for your database vendor (Oracle, PostgreSQL, Firebird, MS-SQL...).

 
## Current *userland* implemented drivers.

| Driver                   | Version | `DbWrapper\Adapter\AdapterInterface` implementations   |
|--------------------------|---------|--------------------------------------------------------|
| `zendframework/zend-db`  |     2.* | `Soluble\DbWrapper\Adapter\Zend\ZendDb2Adapter`        |
| `doctrine/dbal`          |     2.* | `Soluble\DbWrapper\Adapter\Doctrine\Dbal2Adapter`      |
| `illuminate/database`    |     5.* | `Soluble\DbWrapper\Adapter\Laravel\Capsule5Adapter`    |


## Dependencies

If your project or library is based on one of the supported frameworks, it's already there. Otherwise :

| Framework      | Driver                   | Composer requirement                                 |
|----------------|--------------------------|------------------------------------------------------|
| Zend Framework | `zendframework/zend-db`  | composer require zendframework/zend-db:~2            |
| Symfony        | `doctrine/dbal`          | composer require doctrine/dbal:~2                    |
| Laravel        | `illuminate/database`    | composer require illuminate/database:~5              |


## Connection examples

### Zend Framework 

The Zend Framework adapter rely on the "zendframework/zend-db" component.

```php
<?php

use Soluble\DbWrapper;

$params = [
    'driver'    => 'Mysqli',
    'hostname'  => 'hostname',
    'database'  => 'database',
    'username'  => 'username',
    'password'  => 'password',
    'charset'   => 'utf8'
];

$zendAdapter = new \Zend\Db\Adapter\Adapter($params);

$adapter = DbWrapper\AdapterFactory::createAdapterFromZendDb2($zendAdapter);

echo get_class($adapter); // -> `Soluble\DbWrapper\Adapter\Zend\ZendDb2Adapter`

```

### Laravel 

Capsule adapter provide a bridged access to `illuminate/database` component.

```php
<?php

use Soluble\DbWrapper;

$capsule = new \Illuminate\Database\Capsule\Manager();        
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'hostname',
    'database'  => 'database',
    'username'  => 'username',
    'password'  => 'password',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci'
]);

try {
    $adapter = DbWrapper\AdapterFactory::createAdapterFromCapsule5($capsule);
} catch (DbWrapper\Exception\UnsupportedDriverException $e) {
    // ...
} catch (DbWrapper\Exception\InvalidArgumentException $e) {
    // ...
}
1
echo get_class($adapter); // -> `Soluble\DbWrapper\Adapter\Laravel\Capsule5Adapter`

```

### Doctrine

Doctrine adapter provide a bridged access to `doctrine/dbal` component.

```php
<?php

use Soluble\DbWrapper;

$params = [
    'dbname'    => 'database',
    'user'      => 'username',
    'password'  => 'password',
    'host'      => 'hostname',
    'driver'    => 'mysqli',
    'charset'   => 'utf8'
];
$dbal = \Doctrine\DBAL\DriverManager::getConnection($params);  


try {
    $adapter = DbWrapper\AdapterFactory::createAdapterFromDbal2($dbal);
} catch (DbWrapper\Exception\UnsupportedDriverException $e) {
    // ...
} catch (DbWrapper\Exception\InvalidArgumentException $e) {
    // ...
}

echo get_class($adapter); // -> `Soluble\DbWrapper\Adapter\Doctrine\Dbal2Adapter`

```