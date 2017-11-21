
[![Build Status](https://travis-ci.org/isholao/container.svg?branch=1.x)](https://travis-ci.org/isholao/container)

Install
-------

To install with composer:

```sh
composer require isholao/container
```

Requires PHP 7.1 or newer.

Usage
-----

Here's a basic usage example:

```php

<?php

require '/path/to/vendor/autoload.php';

$c = new \Isholao\Container\Container();
$c->set('name','ishola'); // $c->name = 'ishola';

if($c->has('name'))
{
    echo $c->get('name'); // $c->name;
}

$c->protected('response', function (){});

$c->response();

```

Protecting an item. Define it will the protect method

```php

<?php

require '/path/to/vendor/autoload.php';

$c = new \Isholao\Container\Container();
$c->protect('name','ishola');

$c->name = 'ishola'; // throw error

```
