<?php

namespace Cekurte\Resource\Query\Language\Test;

use Cekurte\Tdd\ReflectionTestCase;

class AbstractExprTest extends ReflectionTestCase
{
    private function getAbstractExprMock()
    {
        return $this
            ->getMockForAbstractClass('\\Cekurte\\Resource\\Query\\Language\\Expr\\AbstractExpr')
        ;
    }

    public function testImplementsExprInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\AbstractExpr'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ExprInterface'
        ));
    }

    public function testImplementsExprTemplateInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\Expr\\AbstractExpr'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ExprTemplateInterface'
        ));
    }

    public function testGetField()
    {
        $mock = $this->getAbstractExprMock();

        $this->assertNull($mock->getField());

        $this->propertySetValue($mock, 'field', 'FIELD');

        $this->assertEquals('FIELD', $mock->getField());
    }

    public function testGetExpression()
    {
        $mock = $this->getAbstractExprMock();

        $this->assertNull($mock->getExpression());

        $this->propertySetValue($mock, 'expression', 'EXPRESSION');

        $this->assertEquals('EXPRESSION', $mock->getExpression());
    }

    public function testGetExpressionSeparator()
    {
        $mock = $this->getAbstractExprMock();

        $this->assertEquals(':', $mock->getExpressionSeparator());

        $this->propertySetValue($mock, 'expressionSeparator', '#');

        $this->assertEquals('#', $mock->getExpressionSeparator());
    }

    public function testGetInputExpression()
    {
        $mock = $this->getAbstractExprMock();

        $this->propertySetValue($mock, 'field', 'FIELD');
        $this->propertySetValue($mock, 'expression', 'EXPRESSION');
        $this->propertySetValue($mock, 'expressionSeparator', ':');
        $this->propertySetValue($mock, 'value', 'VALUE');

        $this->assertEquals('FIELD:EXPRESSION:VALUE', $mock->getInputExpression());
    }

    public function testGetOutputExpression()
    {
        $mock = $this->getAbstractExprMock();

        $this->propertySetValue($mock, 'field', '  FIELD');
        $this->propertySetValue($mock, 'operator', '+-');
        $this->propertySetValue($mock, 'value', 'VALUE  ');

        $this->assertEquals('FIELD +- VALUE', $mock->getOutputExpression());
    }

    public function testGetValue()
    {
        $mock = $this->getAbstractExprMock();

        $this->assertNull($mock->getValue());

        $this->propertySetValue($mock, 'value', 'VALUE');

        $this->assertEquals('VALUE', $mock->getValue());
    }

    public function testGetOperator()
    {
        $mock = $this->getAbstractExprMock();

        $this->assertEquals('', $mock->getOperator());

        $this->propertySetValue($mock, 'operator', 'OPERATOR');

        $this->assertEquals('OPERATOR', $mock->getOperator());
    }

    public function testToString()
    {
        $mock = $this->getAbstractExprMock();

        $mock
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('NAME'))
        ;

        $this->propertySetValue($mock, 'field', 'FIELD');
        $this->propertySetValue($mock, 'expression', 'EXPRESSION');
        $this->propertySetValue($mock, 'expressionSeparator', ':');
        $this->propertySetValue($mock, 'value', 'VALUE');
        $this->propertySetValue($mock, 'operator', 'OPERATOR');

        $this->assertRegExp(
            '/\[EXPRESSION\] NAME .*? \[FIELD:EXPRESSION:VALUE\] .*? \[FIELD OPERATOR VALUE\]\./',
            (string) $mock
        );
    }
}
