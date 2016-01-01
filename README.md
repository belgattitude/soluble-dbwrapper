# DbWrapper

[![PHP Version](http://img.shields.io/badge/php-5.4+-ff69b4.svg)](https://packagist.org/packages/soluble/dbwrapper)
[![HHVM Status](http://hhvm.h4cc.de/badge/soluble/dbwrapper.png?style=flat)](http://hhvm.h4cc.de/package/soluble/dbwrapper)
[![Build Status](https://travis-ci.org/soluble/dbwrapper.png?branch=master)](https://travis-ci.org/soluble/dbwrapper)
[![Code Coverage](https://scrutinizer-ci.com/g/soluble/dbwrapper/badges/coverage.png?s=aaa552f6313a3a50145f0e87b252c84677c22aa9)](https://scrutinizer-ci.com/g/soluble/dbwrapper/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/soluble/dbwrapper/badges/quality-score.png?s=6f3ab91f916bf642f248e82c29857f94cb50bb33)](https://scrutinizer-ci.com/g/soluble/dbwrapper/)
[![Latest Stable Version](https://poser.pugx.org/soluble/dbwrapper/v/stable.svg)](https://packagist.org/packages/soluble/dbwrapper)
[![Total Downloads](https://poser.pugx.org/soluble/dbwrapper/downloads.png)](https://packagist.org/packages/soluble/dbwrapper)
[![License](https://poser.pugx.org/soluble/dbwrapper/license.png)](https://packagist.org/packages/soluble/dbwrapper)

## Introduction

Extra minimalist PHP database wrapper.

## Features

- Abstract pdo_mysql / mysqli queries.

## Requirements

- PHP engine 5.4+, 7.0+ or HHVM >= 3.2.
- Currently tester drivers (pdo_mysql, mysqli)

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

Initialize the `Schema\Source\MysqlInformationSchema` with a valid `PDO` or `mysqli` connection.

```php
<?php

use Soluble\Schema;

$conn = new \PDO("mysql:host=$hostname", $username, $password, [
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
]);

/* Alternatively, use a \mysqli connection instead of PDO */
// $conn = new \mysqli($hostname,$username,$password,$database);
// $conn->set_charset($charset);

$schema = new Schema\Source\MysqlInformationSchema($conn);

// By default the schema (database) is taken from current connection. 
// If you wnat to query a different schema, set it in the second parameter.
$otherDbSchema = new Schema\Source\MysqlInformationSchema($conn, 'otherDbSchema');
```


### API methods

Once a `Schema\Source\SchemaSourceInterface` is intitalized, you have access to the following methods

| Methods                         | Return        | Description                                 |
|---------------------------------|---------------|---------------------------------------------|
| `getSchemaConfig()`             | `ArrayObject` | Retrieve full extended schema config        |
| `getTables()`                   | `array`       | Retrieve table names                        |
| `getTablesInformation()`        | `array`       | Retrieve extended tables information        |
| `hasTable()`                    | `boolean`     | Whether table exists                        |
| `getColumns($table)`            | `array`       | Retrieve column names                       |
| `getColumnsInformation($table)` | `array`       | Retrieve extended columns information       |
| `getPrimaryKey($table)`         | `string`      | Retrieve primary key (unique)               |
| `getPrimaryKeys($table)`        | `array`       | Retrieve primary keys (multiple)            |
| `getUniqueKeys($table)`         | `array`       | Retrieve unique keys                        |
| `getForeignKeys($table)`        | `array`       | Retrieve foreign keys information           |
| `getReferences($table)`         | `array`       | Retrieve referencing tables (relations)     |
| `getIndexes($table)`            | `array`       | Retrieve indexes info                       |


## Examples


### Retrieve table informations in a database schema

```php
<?php

// Retrieve table names defined in schema
$tables = $schema->getTables();

// Retrieve full information of tables defined in schema
$infos = $schema->getTablesInformation();

// The resulting array looks like
[
 ["table_name_1"] => [
    ["name"]    => (string) 'Table name'
    ["columns"] => [ // Columns information, 
                     // @see AbstractSource::getColumnsInformation()
                     "col name_1" => ["name" => "", "type" => "", ...]',
                     "col name_2" => ["name" => "", "type" => "", ...]'
                   ]
    ["primary_keys"] => [ // Primary key column(s) or empty
                      "pk_col1", "pk_col2"
                   ],
    ["unique_keys"]  => [ // Uniques constraints or empty if none
                      "unique_index_name_1" => ["col1", "col3"],
                      "unique_index_name_2" => ["col4"]
                   ],
    ["foreign_keys"] => [ // Foreign keys columns and their references or empty if none
                       "col_1" => [
                                    "referenced_table"  => "Referenced table name",
                                    "referenced_column" => "Referenced column name",
                                    "constraint_name"   => "Constraint name i.e. 'FK_6A2CA10CBC21F742'"
                                  ],
                       "col_2" => [ // ...  
                                  ]
                      ],
    ["references"] => [ // Relations referencing this table
                        "ref_table:ref_column->column1" => [
                             "column"             => "Colum name in this table",
                             "referencing_table"  => "Referencing table name", 
                             "referencing_column" => "Column name in the referencing table", 
                             "constraint_name"    => "Constraint name i.e. 'FK_6A2CA10CBC21F742'"
                           ],
                        "ref_table:ref_column->column2" => [ 
                             //...
                           ]
                      ]
    ["indexes"]  => [],
    ["options"]  => [ // Specific table creation options
                      "comment"   => (string) "Table comment",
                      "collation" => (string) "Table collation, i.e. 'utf8_general_ci'",
                      "type"      => (string) "Table type, i.e: 'BASE TABLE'",
                      "engine"    => (string) "Engine type if applicable, i.e. 'InnoDB'",
                    ]
 ],
 ["table_name_2"] => [
   //...
 ]
]
     
// Test if table exists in schema
if ($schema->hasTable($table)) {
    //...
}
```


## Supported drivers

Currently only MySQL and MariaDB are supported. 

| Drivers            | Source class                                         |
|--------------------|------------------------------------------------------|
| pdo_mysql          | `Soluble\Schema\Source\MysqlInformationSchema`       |
| mysqli             | `Soluble\Schema\Source\MysqlInformationSchema`       |


## Contributing

Contribution are welcome see [contribution guide](./CONTRIBUTING.md)

## Coding standards

* [PSR 4 Autoloader](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md)
* [PSR 2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
* [PSR 1 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
* [PSR 0 Autoloading standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)





