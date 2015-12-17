<?php

namespace Cekurte\Resource\Query\Language\Test\Expr;

use Cekurte\Resource\Query\Language\Expr\NotLikeExpr;
use Cekurte\Tdd\ReflectionTestCase;

class NotLikeExprTest extends ReflectionTestCase
{
    public function testImplementsExprInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\NotLikeExpr'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ExprInterface'
        ));
    }

    public function testIsAnInstanceOfAbstractExpr()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\LikeExpr',
            new NotLikeExpr('field', 'value')
        );
    }

    public function testGetName()
    {
        $expr = new NotLikeExpr('field', 'value');

        $this->assertTrue(is_string($expr->getName()));

        $this->assertEquals('notLike', $expr->getExpression());

        $this->assertEquals('notlike', $expr->getOperator());
    }
}
