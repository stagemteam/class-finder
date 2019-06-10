# Class Finder

Extremely simple and fast Class Finder. 

The package allows you to get Fully Qualified Class Name (FQCN) from file or directory path.
There are no dependencies on the `Composer Autoloader`, `Reflection`, `RegEx`, or `get_declared_classes()`.

Many similar packages already implement this functionality but all of them suffer from overengineering or performance issues.
When your project becomes big and it has tens of thousands of classes with the huge numbers of config 
you need a simple and fast solution to operate all of it.  

## Installation
Use [Composer](https://getcomposer.org/) to install this library in your projects:
```bash
$ composer require stagem/class-finder
```

## Usage

### Find in file path
```php
<?php

use Stagem\ClassFinder\ClassFinder;

$class = (new ClassFinder())->getClassFromFile('/path/to/App/ClassName.php');

var_dump($class);
// App\ClassName
```

### Find in file path
```php
<?php 

use Stagem\ClassFinder\ClassFinder;

$classes = (new ClassFinder())->getClassesInDir('/path/to/App/Model');
var_dump($classes);
/**
 *  array (
 *    0 => '\\App\\Model\\Product',
 *    1 => '\\App\\Model\\Cart',
 *    2 => '\\App\\Model\\Order',
 *    3 => '\\App\\Model\\Shipment',
 *  )
 */  
```
