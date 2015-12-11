<?php

namespace Cekurte\Resource\Query\Language\Test;

use Cekurte\Resource\Query\Language\Expr\NeqExpr;
use Cekurte\Tdd\ReflectionTestCase;

class NeqExprTest extends ReflectionTestCase
{
    public function testImplementsExprInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\NeqExpr'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ExprInterface'
        ));
    }

    public function testIsAnInstanceOfAbstractExpr()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\EqExpr',
            new NeqExpr('field', 'value')
        );
    }

    public function testGetName()
    {
        $expr = new NeqExpr('field', 'value');

        $this->assertTrue(is_string($expr->getName()));

        $this->assertEquals('neq', $expr->getExpression());

        $this->assertEquals('!=', $expr->getOperator());
    }
}
