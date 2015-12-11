<?php

namespace Cekurte\Resource\Query\Language\Test;

use Cekurte\Resource\Query\Language\Expr\LikeExpr;
use Cekurte\Tdd\ReflectionTestCase;

class LikeExprTest extends ReflectionTestCase
{
    public function testImplementsExprInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\LikeExpr'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ExprInterface'
        ));
    }

    public function testIsAnInstanceOfAbstractExpr()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\AbstractExpr',
            new LikeExpr('field', 'value')
        );
    }

    public function dataProviderSuccess()
    {
        return [
            ['field', ''],
            ['field', 'value'],
            ['field', ' value'],
            ['field', 'value '],
            ['field', '%value'],
            ['field', 'value%'],
            ['field', '%value%'],
            ['field', 'value with space'],
            ['field', 'áéíóúãõ'],
            ['field', '123'],
            ['field', 'null'],
            ['field', 'true'],
            ['field', 'false'],
            ['field', '[]'],
        ];
    }

    public function dataProviderError()
    {
        return [
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
        $expr = new LikeExpr($field, $value);

        $this->assertTrue(is_string($expr->getName()));

        $this->assertEquals('like', $expr->getExpression());

        $this->assertEquals('like', $expr->getOperator());

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
        new LikeExpr($field, $value);
    }
}
