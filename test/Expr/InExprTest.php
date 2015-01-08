<?php

namespace Cekurte\Resource\Query\Language\Test\Expr;

use Cekurte\Resource\Query\Language\Expr\InExpr;
use Cekurte\Tdd\ReflectionTestCase;

class InExprTest extends ReflectionTestCase
{
    public function testImplementsExprInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\InExpr'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ExprInterface'
        ));
    }

    public function testIsAnInstanceOfAbstractExpr()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\AbstractExpr',
            new InExpr('field', 'value')
        );
    }

    public function dataProviderSuccess()
    {
        return [
            ['field', 'value',              ['value']],
            ['field', 'value with space',   ['value with space']],
            ['field', 'áéíóúãõ',            ['áéíóúãõ']],
            ['field', '123',                ['123']],
            ['field', 'null',               ['null']],
            ['field', '[]',                 ['[]']],
            ['field', 'value+',             ['value']],
            ['field', 'value1+value2',      ['value1', 'value2']],
            ['field', 1,                    [1]],
            ['field', 1.5,                  [1.5]],
            ['field', 0,                    [0]],
            ['field', -1,                   [-1]],
            ['field', -1.5,                 [-1.5]],
            ['field', ['value'],            ['value']],
            ['field', ['value with space'], ['value with space']],
            ['field', ['áéíóúãõ'],          ['áéíóúãõ']],
            ['field', ['123'],              ['123']],
            ['field', ['null'],             ['null']],
            ['field', ['[]'],               ['[]']],
            ['field', ['value'],            ['value']],
            ['field', ['value1', 'value2'], ['value1', 'value2']],
            ['field', [1],                  [1]],
            ['field', [1.5],                [1.5]],
            ['field', [0],                  [0]],
            ['field', [-1],                 [-1]],
            ['field', [-1.5],               [-1.5]],
        ];
    }

    public function dataProviderError()
    {
        return [
            ['field', ''],
            ['field', true],
            ['field', false],
            ['field', null],
            ['field', []],
            ['field', ['']],
            ['field', ['value', '']],
            ['field', ['value', true]],
            ['field', ['value', false]],
            ['field', ['value', null]],
            ['field', ['value', []]],
            ['field', ['value', ['value1', 'value2']]],
            ['field', [true]],
            ['field', [false]],
            ['field', [null]],
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
    public function testConstructor($field, $rawValue, $expectedValue)
    {
        $expr = new InExpr($field, $rawValue);

        $this->assertTrue(is_string($expr->getName()));

        $this->assertEquals('in', $expr->getExpression());

        $this->assertEquals('in', $expr->getOperator());

        $this->assertEquals($field, $expr->getField());

        $this->assertEquals($rawValue, $expr->getRawValue());

        $this->assertTrue(is_array($expr->getValue()));

        $this->assertEquals($expectedValue, $expr->getValue());
    }

    /**
     * @dataProvider dataProviderError
     *
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ExprException
     */
    public function testConstructorExprException($field, $rawValue)
    {
        new InExpr($field, $rawValue);
    }
}
