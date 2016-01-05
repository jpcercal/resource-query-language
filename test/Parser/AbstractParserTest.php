<?php

namespace Cekurte\Resource\Query\Language\Test\Parser;

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\Parser\AbstractParser;
use Cekurte\Tdd\ReflectionTestCase;

class AbstractParserTest extends ReflectionTestCase
{
    private function getParserMock()
    {
        return $this
            ->getMockForAbstractClass('\\Cekurte\\Resource\\Query\\Language\\Parser\\AbstractParser')
        ;
    }

    public function testAbstractParserIsAnInstanceOfAbstractParser()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Parser\\AbstractParser',
            $this->getParserMock()
        );
    }

    public function testGetData()
    {
        $mock = $this->getParserMock();

        $this->assertNull($mock->getData());

        $this->propertySetValue($mock, 'data', 'fake');

        $this->assertEquals('fake', $mock->getData());
    }

    public function testImplementsParseInterface()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\ParserInterface',
            $this->getParserMock()
        );
    }

    public function testGetValueOfComplexExpression()
    {
        $mock = $this->getParserMock();

        $value = 'field:eq:1|:field:eq:2';

        $this->assertEquals(
            $value,
            $this->invokeMethod($mock, 'getValueOfComplexExpression', ['or', ':or:' . $value])
        );
    }

    public function dataProviderExpressionIsNotAllowedParserException()
    {
        return [
            [new ExprBuilder(), 'field', 'expression', 'value'],
            [new ExprBuilder(), 'field', ' eq',        'value'],
            [new ExprBuilder(), 'field', 'eq ',        'value'],
            [new ExprBuilder(), 'field', ' eq ',       'value'],
        ];
    }

    /**
     * @dataProvider dataProviderExpressionIsNotAllowedParserException
     *
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ParserException
     * @expectedExceptionMessageRegExp /The expression ".*?" is not allowed or not exists./
     */
    public function testProcessExpressionIsNotAllowedParserException($exprBuilder, $field, $expression, $value)
    {
        $mock = $this->getParserMock();

        $this->invokeMethod($mock, 'process', [$exprBuilder, $field, $expression, $value]);
    }

    /**
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ParserException
     * @expectedExceptionMessageRegExp /The value of "between" expression ".*?" is not valid./
     */
    public function testProcessValueOfBetweenIsInvalid()
    {
        $mock = $this->getParserMock();

        $this->invokeMethod($mock, 'process', [new ExprBuilder(), 'field', 'between', 'value']);
    }

    public function testProcessValueOfBetweenExpression()
    {
        $mockExprBuilder = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\ExprBuilder')
            ->disableOriginalConstructor()
            ->setMethods(['between'])
            ->getMock()
        ;

        $mockExprBuilder
            ->expects($this->once())
            ->method('between')
            ->will($this->returnValue(null))
        ;

        $mock = $this->getParserMock();

        $this->invokeMethod($mock, 'process', [$mockExprBuilder, 'field', 'between', '1-10']);
    }

    /**
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ParserException
     * @expectedExceptionMessageRegExp /The value of "paginate" expression ".*?" is not valid./
     */
    public function testProcessValueOfPaginateIsInvalid()
    {
        $mock = $this->getParserMock();

        $this->invokeMethod($mock, 'process', [new ExprBuilder(), null, 'paginate', 'value']);
    }

    public function testProcessValueOfPaginateExpression()
    {
        $mockExprBuilder = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\ExprBuilder')
            ->disableOriginalConstructor()
            ->setMethods(['paginate'])
            ->getMock()
        ;

        $mockExprBuilder
            ->expects($this->once())
            ->method('paginate')
            ->will($this->returnValue(null))
        ;

        $mock = $this->getParserMock();

        $this->invokeMethod($mock, 'process', [$mockExprBuilder, null, 'paginate', '1-10']);
    }

    public function testProcessValueOfOrExpression()
    {
        $mockExprBuilder = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\ExprBuilder')
            ->disableOriginalConstructor()
            ->setMethods(['orx'])
            ->getMock()
        ;

        $mockExprBuilder
            ->expects($this->once())
            ->method('orx')
            ->will($this->returnValue(null))
        ;

        $mock = $this->getParserMock();

        $this->invokeMethod($mock, 'process', [$mockExprBuilder, null, 'or', 'field:eq:1|field:eq:2']);
    }

    public function testProcessValueOfAndExpression()
    {
        $mockExprBuilder = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\ExprBuilder')
            ->disableOriginalConstructor()
            ->setMethods(['andx'])
            ->getMock()
        ;

        $mockExprBuilder
            ->expects($this->once())
            ->method('andx')
            ->will($this->returnValue(null))
        ;

        $mock = $this->getParserMock();

        $this->invokeMethod($mock, 'process', [$mockExprBuilder, null, 'and', 'field:eq:1&field:eq:2']);
    }

    public function dataProviderProcessExpression()
    {
        return [
            ['eq'],
            ['gte'],
            ['gt'],
            ['in'],
            ['like'],
            ['lte'],
            ['lt'],
            ['neq'],
            ['notin'],
            ['notlike'],
            ['sort'],
        ];
    }

    /**
     * @dataProvider dataProviderProcessExpression
     */
    public function testProcessExpression($expression)
    {
        $mockExprBuilder = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\ExprBuilder')
            ->disableOriginalConstructor()
            ->setMethods([$expression])
            ->getMock()
        ;

        $mockExprBuilder
            ->expects($this->once())
            ->method($expression)
            ->will($this->returnValue(null))
        ;

        $mock = $this->getParserMock();

        $this->invokeMethod($mock, 'process', [$mockExprBuilder, 'field', $expression, 'value']);
    }
}
