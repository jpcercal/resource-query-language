<?php

namespace Cekurte\Resource\Query\Language\Test\Expr;

use Cekurte\Resource\Query\Language\Expr\NotInExpr;
use Cekurte\Tdd\ReflectionTestCase;

class NotInExprTest extends ReflectionTestCase
{
    public function testImplementsExprInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\NotInExpr'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ExprInterface'
        ));
    }

    public function testIsAnInstanceOfAbstractExpr()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\InExpr',
            new NotInExpr('field', 'value')
        );
    }

    public function testGetName()
    {
        $expr = new NotInExpr('field', 'value');

        $this->assertTrue(is_string($expr->getName()));

        $this->assertEquals('notIn', $expr->getExpression());

        $this->assertEquals('notin', $expr->getOperator());
    }
}
