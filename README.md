# RQL (Resource Query Language)

[![Build Status](https://img.shields.io/travis/cekurte/resource-query-language/master.svg?style=square)](http://travis-ci.org/cekurte/resource-query-language)
[![Code Climate](https://codeclimate.com/github/cekurte/resource-query-language/badges/gpa.svg)](https://codeclimate.com/github/cekurte/resource-query-language)
[![Coverage Status](https://coveralls.io/repos/github/cekurte/resource-query-language/badge.svg?branch=master)](https://coveralls.io/github/cekurte/resource-query-language?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/cekurte/rql.svg?style=square)](https://packagist.org/packages/cekurte/rql)
[![License](https://img.shields.io/packagist/l/cekurte/rql.svg?style=square)](https://packagist.org/packages/cekurte/rql)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d4950c4f-bfc3-4782-8a19-38a0176b5b0d/mini.png)](https://insight.sensiolabs.com/projects/d4950c4f-bfc3-4782-8a19-38a0176b5b0d)

- A Resource Query Language to PHP (with all methods covered by php unit tests), with this library you can perform queries using a unique input interface that will be converted and processed using the ProcessorInterface **contribute with this project**!

## Installation

- The package is available on [Packagist](http://packagist.org/packages/cekurte/rql).
- The source files is [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) compatible.
- Autoloading is [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md) compatible.
- Input parser data is [PSR-7](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md) compatible.

```shell
composer require cekurte/rql
```

**If you liked of this library, give me a *star =)*.**

## Documentation

This library was created to perform queries using a unique interface that will be converted and processed to the correct target. The target data can be an array, a database, a file (such as an ini, a xml or a json), a webservice, and more. Currently this project works only with the Doctrine ORM, then, you can perform queries in all databases that are compatible with the doctrine ORM project.

**We would be happy with their contribution, submit your pull request for new Processors like the Eloquent ORM, File Parser (INI, XML, JSON), API's (Facebook, LinkedIn,...) and more.**

### Expressions

Well, now we show how to use this library, to perform the query using the equality expression (more expressions will be show too).

```php
<?php

use Cekurte\Resource\Query\Language\Expr\EqExpr;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Processor\DoctrineOrmProcessor;

// ...
$qb = $yourDoctrineEntityRepository->createQueryBuilder('alias');

$queue = new ExprQueue();
$queue->enqueue(new EqExpr('alias.field', 'value'));

// You can enqueue all expressions that implemented
// the Cekurte\Resource\Query\Language\Contract\ExprInterface

(new DoctrineOrmProcessor($qb))->process($queue);

// And now you can use the Doctrine QueryBuilder normally
$results = $qb->getQuery()->getResult();

// ...
```

Ok, but why do not use the Doctrine QueryBuilder to build the queries and perform it? Because of the flexibility to search in the multiple data sources using the same interface changing the Processor class, that in this case is the DoctrineOrmProcessor.

Woow, you can see this scenario with various possibilities like me? Then, this library is for you guy. You share your aim with me and contribute with this project.

But, if i want build a collection of expression, i need to put the expressions using the queue? Yes, it is an valid answer for this question, but you can use the [ExprBuilder](https://github.com/cekurte/resource-query-language/blob/master/src/ExprBuilder.php) to perform it too. Like the following example:

```php
<?php

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\Processor\DoctrineOrmProcessor;

// ...
$qb = $yourDoctrineEntityRepository->createQueryBuilder('alias');

$builder = new ExprBuilder();
$builder->eq('field', 'value');

(new DoctrineOrmProcessor($qb))->process($builder);

// And now you can use the Doctrine QueryBuilder normally
$results = $qb->getQuery()->getResult();

// ...
```

Note that the [ExprBuilder](https://github.com/cekurte/resource-query-language/blob/master/src/ExprBuilder.php) class implements a shortcut to all available expressions.

Currently are available the following expressions:

- [BetweenExpr](#betweenexpr)
- [EqExpr](#eqexpr)
- [GteExpr](#gteexpr)
- [GtExpr](#gtexpr)
- [InExpr](#inexpr)
- [LikeExpr](#likeexpr)
- [LteExpr](#lteexpr)
- [LtExpr](#ltexpr)
- [NeqExpr](#neqexpr)
- [NotInExpr](#notinexpr)
- [NotLikeExpr](#notlikeexpr)
- [OrExpr](#orexpr)
- [PaginateExpr](#paginateexpr)
- [SortExpr](#sortexpr)

Above was listed the available query expressions, next you can see the use of expressions using the [ExprBuilder](https://github.com/cekurte/resource-query-language/blob/master/src/ExprBuilder.php) and the [ExprQueue](https://github.com/cekurte/resource-query-language/blob/master/src/ExprQueue.php).

#### BetweenExpr

The [BetweenExpr](https://github.com/cekurte/resource-query-language/blob/master/src/Expr/BetweenExpr.php) can be used to query a value that is in an interval of values.

```php
<?php

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\BetweenExpr;

// Using the ExprBuilder
$expr = new ExprBuilder();
$expr->between('field', 1, 10);

// OR using the ExprQueue...
$expr = new ExprQueue();
$expr->enqueue(new BetweenExpr('field', 1, 10));
```

#### EqExpr

The [EqExpr](https://github.com/cekurte/resource-query-language/blob/master/src/Expr/EqExpr.php) can be used to query a value using the equality operator.

```php
<?php

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\EqExpr;

// Using the ExprBuilder
$expr = new ExprBuilder();
$expr->eq('field', 'value');

// OR using the ExprQueue...
$expr = new ExprQueue();
$expr->enqueue(new EqExpr('field', 'value'));
```

#### GteExpr

The [GteExpr](https://github.com/cekurte/resource-query-language/blob/master/src/Expr/GteExpr.php) can be used to query a value using that must be greater than or equal to one.

```php
<?php

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\GteExpr;

// Using the ExprBuilder
$expr = new ExprBuilder();
$expr->gte('field', 1);

// OR using the ExprQueue...
$expr = new ExprQueue();
$expr->enqueue(new GteExpr('field', 1));
```

#### GtExpr

The [GtExpr](https://github.com/cekurte/resource-query-language/blob/master/src/Expr/GtExpr.php) can be used to query a value using that must be greater than one.

```php
<?php

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\GtExpr;

// Using the ExprBuilder
$expr = new ExprBuilder();
$expr->gt('field', 1);

// OR using the ExprQueue...
$expr = new ExprQueue();
$expr->enqueue(new GtExpr('field', 1));
```

#### InExpr

The [InExpr](https://github.com/cekurte/resource-query-language/blob/master/src/Expr/InExpr.php) can be used to query a value that can be equal to one, two or three.

```php
<?php

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\InExpr;

// Using the ExprBuilder
$expr = new ExprBuilder();
$expr->in('field', [1, 2, 3]);

// OR using the ExprQueue...
$expr = new ExprQueue();
$expr->enqueue(new InExpr('field', [1, 2, 3]));
```

#### LikeExpr

The [LikeExpr](https://github.com/cekurte/resource-query-language/blob/master/src/Expr/LikeExpr.php) can be used to query a value that must be equal (in the end only) "%value" where the % operator is a joker like the SQL commands.

```php
<?php

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\LikeExpr;

// Using the ExprBuilder
$expr = new ExprBuilder();
$expr->like('field', '%value');

// OR using the ExprQueue...
$expr = new ExprQueue();
$expr->enqueue(new LikeExpr('field', '%value'));
```

#### LteExpr

The [LteExpr](https://github.com/cekurte/resource-query-language/blob/master/src/Expr/LteExpr.php) can be used to query a value using that must be less than or equal to one.

```php
<?php

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\LteExpr;

// Using the ExprBuilder
$expr = new ExprBuilder();
$expr->lte('field', 1);

// OR using the ExprQueue...
$expr = new ExprQueue();
$expr->enqueue(new LteExpr('field', 1));
```

#### LtExpr

The [LtExpr](https://github.com/cekurte/resource-query-language/blob/master/src/Expr/LtExpr.php) can be used to query a value using that must be less than one.

```php
<?php

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\LtExpr;

// Using the ExprBuilder
$expr = new ExprBuilder();
$expr->lt('field', 1);

// OR using the ExprQueue...
$expr = new ExprQueue();
$expr->enqueue(new LtExpr('field', 1));
```

#### NeqExpr

The [NeqExpr](https://github.com/cekurte/resource-query-language/blob/master/src/Expr/NeqExpr.php) can be used to query a value using the not equal operator.

```php
<?php

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\NeqExpr;

// Using the ExprBuilder
$expr = new ExprBuilder();
$expr->neq('field', 'value');

// OR using the ExprQueue...
$expr = new ExprQueue();
$expr->enqueue(new NeqExpr('field', 'value'));
```

#### NotInExpr

The [NotInExpr](https://github.com/cekurte/resource-query-language/blob/master/src/Expr/NotInExpr.php) can be used to query a value that must be different of one, two or three.

```php
<?php

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\NotInExpr;

// Using the ExprBuilder
$expr = new ExprBuilder();
$expr->notin('field', [1, 2, 3]);

// OR using the ExprQueue...
$expr = new ExprQueue();
$expr->enqueue(new NotInExpr('field', [1, 2, 3]));
```

#### NotLikeExpr

The [NotLikeExpr](https://github.com/cekurte/resource-query-language/blob/master/src/Expr/NotLikeExpr.php) can be used to query a value that must be different (in the end only) "%value" where the % operator is a joker like the SQL commands.

```php
<?php

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\NotLikeExpr;

// Using the ExprBuilder
$expr = new ExprBuilder();
$expr->notlike('field', '%value');

// OR using the ExprQueue...
$expr = new ExprQueue();
$expr->enqueue(new NotLikeExpr('field', '%value'));
```

#### OrExpr

The [OrExpr](https://github.com/cekurte/resource-query-language/blob/master/src/Expr/OrExpr.php) can be used to query a value joining the above comparison expressions to perform the query. In the following example all fields that are filled with the value one or two will be returned.

```php
<?php

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\OrExpr;

// Using the ExprBuilder
$expr = new ExprBuilder();
$expr->orx(['field:eq:1', 'field:eq:2']);

// OR using the ExprQueue...
$expr = new ExprQueue();
$expr->enqueue(new OrExpr(['field:eq:1', 'field:eq:2']));
```

#### PaginateExpr

The [PaginateExpr](https://github.com/cekurte/resource-query-language/blob/master/src/Expr/PaginateExpr.php) can be used to paginate the results, the first parameter is the current page number and the second parameter is the limit of results per page.

```php
<?php

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\PaginateExpr;

// Using the ExprBuilder
$expr = new ExprBuilder();
$expr->paginate(1, 10);

// OR using the ExprQueue...
$expr = new ExprQueue();
$expr->enqueue(new PaginateExpr(1, 10));
```

#### SortExpr

The [SortExpr](https://github.com/cekurte/resource-query-language/blob/master/src/Expr/SortExpr.php) can be used to sort the results, the first parameter is the field that will be sorted and the second parameter is the direction (can be used the asc and desc like in the SQL commands).

```php
<?php

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\SortExpr;

// Using the ExprBuilder
$expr = new ExprBuilder();
$expr->sort('field', 'asc');

// OR using the ExprQueue...
$expr = new ExprQueue();
$expr->enqueue(new SortExpr('field', 'asc'));
```

### Parser

The parser can be used to parse an input data in different formats to the [ExprBuilder](https://github.com/cekurte/resource-query-language/blob/master/src/ExprBuilder.php). Currently are available three parser classes that implements the [ParserInterface](https://github.com/cekurte/resource-query-language/blob/master/src/Contracts/ParserInterface.php).

- [ArrayParser](#arrayparser)
- [RequestParser](#requestparser)
- [StringParser](#stringparser)

#### ArrayParser

The [ArrayParser](https://github.com/cekurte/resource-query-language/blob/master/src/Parser/ArrayParser.php) can be used to parse the array data to [ExprBuilder](https://github.com/cekurte/resource-query-language/blob/master/src/ExprBuilder.php), see the example in the below:

```php
<?php

use Cekurte\Resource\Query\Language\Parser\ArrayParser;

$data = [
    [
        'field'      => 'field',
        'expression' => 'between',
        'value'      => '1-3',
    ],
    [
        'field'      => 'field',
        'expression' => 'eq',
        'value'      => 'value',
    ],
    [
        'field'      => 'field',
        'expression' => 'gte',
        'value'      => '1',
    ],
    [
        'field'      => 'field',
        'expression' => 'gt',
        'value'      => '1',
    ],
    [
        'field'      => 'field',
        'expression' => 'in',
        'value'      => [1, 2, 3],
    ],
    [
        'field'      => 'field',
        'expression' => 'like',
        'value'      => '%value',
    ],
    [
        'field'      => 'field',
        'expression' => 'lte',
        'value'      => '1',
    ],
    [
        'field'      => 'field',
        'expression' => 'lt',
        'value'      => '1',
    ],
    [
        'field'      => 'field',
        'expression' => 'neq',
        'value'      => 'value',
    ],
    [
        'field'      => 'field',
        'expression' => 'notin',
        'value'      => [1, 2, 3],
    ],
    [
        'field'      => 'field',
        'expression' => 'notlike',
        'value'      => '%value',
    ],
    [
        'field'      => '',
        'expression' => 'or',
        'value'      => 'field:eq:1|field:eq:2',
    ],
    [
        'field'      => '',
        'expression' => 'paginate',
        'value'      => '1-10',
    ]
    [
        'field'      => 'field',
        'expression' => 'sort',
        'value'      => 'asc',
    ],
];

$parser = new ArrayParser($data);

/**
 * @var $exprBuilder Cekurte\Resource\Query\Language\ExprBuilder
 */
$exprBuilder = $parser->parse();
```

#### RequestParser

The [RequestParser](https://github.com/cekurte/resource-query-language/blob/master/src/Parser/RequestParser.php) can be used to parse an input data that is an instance of [RequestInterface](https://github.com/php-fig/http-message/blob/master/src/RequestInterface.php) (compatible with [PSR-7](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md)), see the example in the below:

```php
<?php

use Cekurte\Resource\Query\Language\Parser\RequestParser;

// Suppose that your URI of the request is:
// http://www.yourdomain.com/?q[]=field:eq:1&q[]=field:eq:2

$parser = new RequestParser(
    $yourRequestObjectThatImplementsPSR7RequestInterface
);

// If you need customize the query string parameter key that will
// be used, then you can set the parameter key using the following
// method (key "q" is default)
// $parser->setQueryStringParameter('expr');

/**
 * @var $exprBuilder Cekurte\Resource\Query\Language\ExprBuilder
 */
$exprBuilder = $parser->parse();
```

#### StringParser

The [StringParser](https://github.com/cekurte/resource-query-language/blob/master/src/Parser/StringParser.php) can be used to parse an input data that is a string, see the example in the below:

```php
<?php

use Cekurte\Resource\Query\Language\Parser\StringParser;

$data = ''
    . 'field:eq:1&'
    . 'field:eq:2&'
    . 'field:eq:3'
;

$parser = new StringParser($data);

// If you need customize the expression separator parameter key that will
// be used, then you can set the parameter key using the following
// method (key "&" is default)
// $parser->setSeparator('SEPARATOR');

/**
 * @var $exprBuilder Cekurte\Resource\Query\Language\ExprBuilder
 */
$exprBuilder = $parser->parse();
```

Contributing
------------

1. Give me a star **=)**
1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Make your changes
4. Run the tests, adding new ones for your own code if necessary (`vendor/bin/phpunit`)
5. Commit your changes (`git commit -am 'Added some feature'`)
6. Push to the branch (`git push origin my-new-feature`)
7. Create new Pull Request
