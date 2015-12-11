<?php

namespace Cekurte\Resource\Query\Language\Test;

use Cekurte\Resource\Query\Language\Expr\BetweenExpr;
use Cekurte\Tdd\ReflectionTestCase;

class BetweenExprTest extends ReflectionTestCase
{
    public function testImplementsExprInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\BetweenExpr'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ExprInterface'
        ));
    }

    public function testIsAnInstanceOfAbstractExpr()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\AbstractExpr',
            new BetweenExpr('field', 1, 10)
        );
    }

    public function dataProviderSuccess()
    {
        return [
            ['field', 1,   2],
            ['field', 1.5, 3],
            ['field', 1,   3.5],
            ['field', 1.5, 3.5],
            ['field', -1,   2],
            ['field', -1.5, 3],
            ['field', -1,   3.5],
            ['field', -1.5, 3.5],
        ];
    }

    public function dataProviderError()
    {
        return [
            ['field', 1,     null],
            ['field', 1,     true],
            ['field', 1,     false],
            ['field', 1,     ''],
            ['field', 1,     []],
            ['field', null,  1],
            ['field', true,  1],
            ['field', false, 1],
            ['field', '',    1],
            ['',      '',    ''],
            [null,    null,  null],
            [true,    true,  true],
            [false,   false, false],
            [1,       1,     1],
            [1.5,     1.5,   1.5],
            [0,       0,     0],
            [-1,     -1,     -1],
            [-1.5,   -1.5,   -1.5],
            [[],     [],     []],
            ['field', 3,     1],
            ['field', 3.5,   3],
            ['field', 1,     -1],
            ['field', -1,    -1],
            ['field', 0,     0],
            ['field', 1,     1],
        ];
    }

    /**
     * @dataProvider dataProviderSuccess
     */
    public function testConstructor($field, $from, $to)
    {
        $expr = new BetweenExpr($field, $from, $to);

        $this->assertTrue(is_string($expr->getName()));

        $this->assertEquals('between', $expr->getExpression());

        $this->assertEquals('>==<', $expr->getOperator());

        $this->assertEquals($field, $expr->getField());

        $this->assertEquals($from . '-' . $to, $expr->getValue());

        $this->assertEquals($from, $expr->getFrom());

        $this->assertEquals($to, $expr->getTo());
    }

    /**
     * @dataProvider dataProviderError
     *
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ExprException
     */
    public function testConstructorExprException($field, $from, $to)
    {
        new BetweenExpr($field, $from, $to);
    }
}
