<?php

namespace Cekurte\Resource\Query\Language\Test;

use Cekurte\Resource\Query\Language\Expr\SortExpr;
use Cekurte\Tdd\ReflectionTestCase;

class SortExprTest extends ReflectionTestCase
{
    public function testImplementsExprInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\SortExpr'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ExprInterface'
        ));
    }

    public function testIsAnInstanceOfAbstractExpr()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\AbstractExpr',
            new SortExpr('field', 'asc')
        );
    }

    public function dataProviderSuccess()
    {
        return [
            ['field', 'asc'],
            ['field', 'desc'],
            ['field', 'Asc'],
            ['field', 'Desc'],
            ['field', 'ASC'],
            ['field', 'DESC'],
            ['field', 'ASc'],
            ['field', 'DEsc'],
            ['field', 'ASc'],
            ['field', 'DESc'],
            ['field', 'aSC'],
            ['field', 'dESc'],
        ];
    }

    public function dataProviderError()
    {
        return [
            ['field', ''],
            ['field', '+'],
            ['field', '-'],
            ['field', '<'],
            ['field', '>'],
            ['field', '^'],
            ['field', ' asc'],
            ['field', 'asc '],
            ['field', ' desc'],
            ['field', 'desc '],
            ['field', 'ascdesc'],
            ['field', 'descasc'],
            ['field', 'asc-desc'],
            ['field', 'desc-asc'],
            ['field', 'asc+desc'],
            ['field', 'desc+asc'],
            ['field', null],
            ['field', true],
            ['field', false],
            ['field', []],
            ['',      ''],
            ['',      'asc'],
            ['',      'desc'],
            [null,    'asc'],
            [null,    'desc'],
            [true,    'asc'],
            [true,    'desc'],
            [false,   'asc'],
            [false,   'desc'],
            [null,    null],
            [true,    true],
            [false,   false],
            [null,    ''],
            [true,    ''],
            [false,   ''],
            [1,       1],
            [1.5,     1.5],
            [0,       0],
            [-1,     -1],
            [-1.5,   -1.5],
            [[],     []],
        ];
    }

    /**
     * @dataProvider dataProviderSuccess
     */
    public function testConstructor($field, $direction)
    {
        $expr = new SortExpr($field, $direction);

        $this->assertTrue(is_string($expr->getName()));

        $this->assertEquals('sort', $expr->getExpression());

        $this->assertEquals('sort', $expr->getOperator());

        $this->assertEquals($field, $expr->getField());

        $this->assertEquals(strtolower($direction), $expr->getValue());

        $this->assertEquals(strtolower($direction), $expr->getDirection());
    }

    /**
     * @dataProvider dataProviderError
     *
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ExprException
     */
    public function testConstructorExprException($field, $value)
    {
        new SortExpr($field, $value);
    }
}
