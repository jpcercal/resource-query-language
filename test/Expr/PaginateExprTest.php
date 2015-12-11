<?php

namespace Cekurte\Resource\Query\Language\Test;

use Cekurte\Resource\Query\Language\Expr\PaginateExpr;
use Cekurte\Tdd\ReflectionTestCase;

class PaginateExprTest extends ReflectionTestCase
{
    public function testImplementsExprInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\PaginateExpr'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ExprInterface'
        ));
    }

    public function testIsAnInstanceOfAbstractExpr()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\AbstractExpr',
            new PaginateExpr(1, 1)
        );
    }

    public function dataProviderSuccess()
    {
        return [
            [1,       1],
            [1,       10],
            [1,       20],
            [1,       100],
            [1,       1],
            [10,      10],
            [20,      20],
            [100,     100],
            [250,     1],
            [500,     1],
            [1000,    1],
            [10000,   1],
            [99999,   1],
            ['1',     '1'],
            ['1',     '10'],
            ['1',     '20'],
            ['1',     '100'],
            ['1',     '1'],
            ['10',    '10'],
            ['20',    '20'],
            ['100',   '100'],
            ['250',   '1'],
            ['500',   '1'],
            ['1000',  '1'],
            ['10000', '1'],
            ['99999', '1'],
        ];
    }

    public function dataProviderError()
    {
        return [
            [1,        101],
            [1,        102],
            [1,        110],
            [1,        150],
            [1,        250],
            [1,        500],
            [1,        9999],
            [1,        99999],
            [0,        1],
            [-1,       10],
            [1,        0],
            [1,        -1],
            [1.5,      1],
            [1,        1.5],
            [1.5,      1.5],
            ['',       1],
            [null,     1],
            [true,     1],
            [false,    1],
            [[],       1],
            ['value',  1],
            [1,        ''],
            [1,        null],
            [1,        true],
            [1,        false],
            [1,        []],
            [1,        'value'],
        ];
    }

    /**
     * @dataProvider dataProviderSuccess
     */
    public function testConstructor($page, $limit)
    {
        $expr = new PaginateExpr($page, $limit);

        $this->assertTrue(is_string($expr->getName()));

        $this->assertEquals('paginate', $expr->getExpression());

        $this->assertEquals('paginate', $expr->getOperator());

        $this->assertNull($expr->getField());

        $this->assertEquals($page . '-' . $limit, $expr->getValue());

        $this->assertEquals($page, $expr->getCurrentPageNumber());

        $this->assertEquals($limit, $expr->getMaxResultsPerPage());

        $this->assertTrue(is_int($expr->getCurrentPageNumber()));

        $this->assertTrue(is_int($expr->getMaxResultsPerPage()));
    }

    /**
     * @dataProvider dataProviderError
     *
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ExprException
     */
    public function testConstructorExprException($field, $value)
    {
        new PaginateExpr($field, $value);
    }
}
