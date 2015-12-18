<?php

namespace Cekurte\Resource\Query\Language\Test\Expr;

use Cekurte\Resource\Query\Language\Expr\GteExpr;
use Cekurte\Tdd\ReflectionTestCase;

class GteExprTest extends ReflectionTestCase
{
    public function testImplementsExprInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\GteExpr'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ExprInterface'
        ));
    }

    public function testIsAnInstanceOfAbstractExpr()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\AbstractExpr',
            new GteExpr('field', 1)
        );
    }

    public function dataProviderSuccess()
    {
        return [
            ['field', '123'],
            ['field', ' 123'],
            ['field', '123 '],
            ['field', 1],
            ['field', 1.5],
            ['field', 0],
            ['field', -1],
            ['field', -1.5],
        ];
    }

    public function dataProviderError()
    {
        return [
            ['field', ''],
            ['field', null],
            ['field', true],
            ['field', false],
            ['field', []],
            ['',      ''],
            [null,    null],
            [true,    true],
            [false,   false],
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
    public function testConstructor($field, $value)
    {
        $expr = new GteExpr($field, $value);

        $this->assertTrue(is_string($expr->getName()));

        $this->assertEquals('gte', $expr->getExpression());

        $this->assertEquals('>=', $expr->getOperator());

        $this->assertEquals($field, $expr->getField());

        $this->assertEquals(is_string($value) ? trim($value) : $value, $expr->getValue());
    }

    /**
     * @dataProvider dataProviderError
     *
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ExprException
     */
    public function testConstructorExprException($field, $value)
    {
        new GteExpr($field, $value);
    }
}
