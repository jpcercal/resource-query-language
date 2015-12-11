<?php

namespace Cekurte\Resource\Query\Language\Test;

use Cekurte\Resource\Query\Language\Expr\GtExpr;
use Cekurte\Tdd\ReflectionTestCase;

class GtExprTest extends ReflectionTestCase
{
    public function testImplementsExprInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\GtExpr'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ExprInterface'
        ));
    }

    public function testIsAnInstanceOfAbstractExpr()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\GteExpr',
            new GtExpr('field', 1)
        );
    }

    public function testGetName()
    {
        $expr = new GtExpr('field', 1);

        $this->assertTrue(is_string($expr->getName()));

        $this->assertEquals('gt', $expr->getExpression());

        $this->assertEquals('>', $expr->getOperator());
    }
}
