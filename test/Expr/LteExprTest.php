<?php

namespace Cekurte\Resource\Query\Language\Test\Expr;

use Cekurte\Resource\Query\Language\Expr\LteExpr;
use Cekurte\Tdd\ReflectionTestCase;

class LteExprTest extends ReflectionTestCase
{
    public function testImplementsExprInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\LteExpr'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ExprInterface'
        ));
    }

    public function testIsAnInstanceOfAbstractExpr()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\GteExpr',
            new LteExpr('field', 1)
        );
    }

    public function testGetName()
    {
        $expr = new LteExpr('field', 1);

        $this->assertTrue(is_string($expr->getName()));

        $this->assertEquals('lte', $expr->getExpression());

        $this->assertEquals('<=', $expr->getOperator());
    }
}
