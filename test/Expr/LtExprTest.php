<?php

namespace Cekurte\Resource\Query\Language\Test\Expr;

use Cekurte\Resource\Query\Language\Expr\LtExpr;
use Cekurte\Tdd\ReflectionTestCase;

class LtExprTest extends ReflectionTestCase
{
    public function testImplementsExprInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\LtExpr'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ExprInterface'
        ));
    }

    public function testIsAnInstanceOfAbstractExpr()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\GteExpr',
            new LtExpr('field', 1)
        );
    }

    public function testGetName()
    {
        $expr = new LtExpr('field', 1);

        $this->assertTrue(is_string($expr->getName()));

        $this->assertEquals('lt', $expr->getExpression());

        $this->assertEquals('<', $expr->getOperator());
    }
}
