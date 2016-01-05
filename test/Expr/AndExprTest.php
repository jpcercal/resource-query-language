<?php

namespace Cekurte\Resource\Query\Language\Test\Expr;

use Cekurte\Resource\Query\Language\Expr\AndExpr;
use Cekurte\Tdd\ReflectionTestCase;

class AndExprTest extends ReflectionTestCase
{
    public function testImplementsExprInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\AndExpr'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ExprInterface'
        ));
    }

    public function testIsAnInstanceOfAbstractExpr()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\AbstractExpr',
            new AndExpr('field:eq:1&field:eq:2')
        );
    }

    public function dataProviderSuccess()
    {
        return [
            ['field:eq:1&field:eq:2'],
            ['field:gte:1&field:gte:2'],
            ['field:gt:1&field:gt:2'],
            ['field:in:1+2+3&field:in:4+5+6'],
            ['field:like:value%&field:like:othervalue%'],
            ['field:lte:1&field:lte:2'],
            ['field:lt:1&field:lt:2'],
            ['field:neq:1&field:neq:2'],
            ['field:notin:1+2+3&field:notin:4+5+6'],
            ['field:notlike:value%&field:notlike:othervalue%'],
            ['field:eq:1&field:eq:2&field:eq:3&field:eq:4'],
        ];
    }

    public function dataProviderError()
    {
        return [
            ['xxxxxxxxxx&field:eq:2&field:eq:3&field:eq:4'],
            ['field:eq:1&xxxxxxxxxx&field:eq:3&field:eq:4'],
            ['field:eq:1&field:eq:2&xxxxxxxxxx&field:eq:4'],
            ['field:eq:1&field:eq:2&field:eq:3&xxxxxxxxxx'],
            ['field:eq:1&field:eq:2&field:eq:3&field:eq:4&:or:otherfield:eq:1&otherfield:eq:2'],
            ['field:expression_that_exists:1'],
            ['field:eq:1'],
            ['field:gte:1'],
            ['field:gt:1'],
            ['field:in:1+2+3'],
            ['field:like:value%'],
            ['field:lte:1'],
            ['field:lt:1'],
            ['field:neq:1'],
            ['field:notin:1+2+3'],
            ['field:notlike:value%'],
            ['field:sort:asc'],
            ['field:sort:asc&field:eq:1'],
            [':paginate:1-10'],
            [':paginate:1-10&field:eq:1'],
            [''],
            [':'],
            ['::'],
            [':::'],
            ['::::'],
            [' :'],
            [': '],
            [' : '],
            ['&'],
            ['&&'],
            ['&&&'],
            ['&&&&'],
            [' &'],
            ['& '],
            [' & '],
            [null],
            [true],
            [false],
            [1],
            [1.5],
            [0],
            [-1],
            [-1.5],
            [[]],
        ];
    }

    /**
     * @dataProvider dataProviderSuccess
     */
    public function testConstructor($expression)
    {
        $expr = new AndExpr($expression);

        $this->assertTrue(is_string($expr->getName()));

        $this->assertEquals('and', $expr->getExpression());

        $this->assertEquals('and', $expr->getOperator());

        $this->assertNull($expr->getField());

        $this->assertTrue(is_array($expr->getValue()));

        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprQueue',
            $expr->getQueue()
        );
    }

    /**
     * @dataProvider dataProviderError
     *
     * @expectedException \Cekurte\Resource\Query\Language\Exception\QueryLanguageException
     */
    public function testConstructorExprException($expressions)
    {
        new AndExpr($expressions);
    }
}
