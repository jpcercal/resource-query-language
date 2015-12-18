<?php

namespace Cekurte\Resource\Query\Language\Test\Expr;

use Cekurte\Resource\Query\Language\Expr\EqExpr;
use Cekurte\Tdd\ReflectionTestCase;

class EqExprTest extends ReflectionTestCase
{
    public function testImplementsExprInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\EqExpr'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ExprInterface'
        ));
    }

    public function testIsAnInstanceOfAbstractExpr()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\AbstractExpr',
            new EqExpr('field', 'value')
        );
    }

    public function dataProviderSuccess()
    {
        return [
            ['field', true],
            ['field', false],
            ['field', ''],
            ['field', 'value'],
            ['field', ' value'],
            ['field', 'value '],
            ['field', 'value with space'],
            ['field', 'áéíóúãõ'],
            ['field', '123'],
            ['field', 'null'],
            ['field', '[]'],
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
            ['field', null],
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
        $expr = new EqExpr($field, $value);

        $this->assertTrue(is_string($expr->getName()));

        $this->assertEquals('eq', $expr->getExpression());

        $this->assertEquals('=', $expr->getOperator());

        $this->assertEquals($field, $expr->getField());

        $this->assertEquals($value, $expr->getValue());
    }

    /**
     * @dataProvider dataProviderError
     *
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ExprException
     */
    public function testConstructorExprException($field, $value)
    {
        new EqExpr($field, $value);
    }
}
