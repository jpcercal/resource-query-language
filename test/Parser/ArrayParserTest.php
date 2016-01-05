<?php

namespace Cekurte\Resource\Query\Language\Test\Parser;

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\Parser\ArrayParser;
use Cekurte\Tdd\ReflectionTestCase;

class ArrayParserTest extends ReflectionTestCase
{
    private function getParserMock($data, array $methods = array())
    {
        $mock = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Parser\\ArrayParser')
            ->disableOriginalConstructor()
            ->setMethods(array_merge(['getData'], $methods))
            ->getMock()
        ;

        $mock
            ->expects($this->once())
            ->method('getData')
            ->will($this->returnValue([$data]))
        ;

        return $mock;
    }

    public function testArrayParserIsAnInstanceOfAbstractParser()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Parser\\AbstractParser',
            new ArrayParser([
                [
                    'field'      => 'field',
                    'expression' => 'eq',
                    'value'      => 'value',
                ],
            ])
        );
    }

    public function testConstructor()
    {
        $data = [
            [
                'field'      => 'field',
                'expression' => 'eq',
                'value'      => 'value',
            ],
        ];

        $parser = new ArrayParser($data);

        $this->assertEquals($data, $parser->getData());
    }

    public function testParseContinueWhenItemIsEmpty()
    {
        $this->assertEquals(new ExprBuilder(), $this->getParserMock([])->parse());
    }

    public function testParseToOrExpression()
    {
        $data = [
            'field'      => '',
            'expression' => 'or',
            'value'      => 'field:eq:1|field:eq:2',
        ];

        $methods = [
            'getValueOfComplexExpression',
            'process',
        ];

        $mock = $this->getParserMock($data, $methods);

        $mock
            ->expects($this->once())
            ->method('getValueOfComplexExpression')
            ->will($this->returnValue(null))
        ;

        $mock
            ->expects($this->once())
            ->method('process')
            ->will($this->returnValue(null))
        ;

        $this->assertEquals(new ExprBuilder(), $mock->parse());
    }

    public function testParse()
    {
        $data = [
            'field'      => 'field',
            'expression' => 'eq',
            'value'      => 'value',
        ];

        $methods = [
            'process',
        ];

        $mock = $this->getParserMock($data, $methods);

        $mock
            ->expects($this->once())
            ->method('process')
            ->will($this->returnValue(null))
        ;

        $this->assertEquals(new ExprBuilder(), $mock->parse());
    }

    public function dataProviderFieldParseException()
    {
        return [
            [[' ']],
            [[' :']],
            [[' : ']],
            [[':']],
            [['fake']],
            [[' fake']],
            [['fake ']],
            [[' fake ']],
            [[' fake:']],
            [['fake:']],
            [['fake: ']],
            [[' fake: ']],
            [[':fake']],
            [[':fake ']],
            [[' :fake']],
            [[' :fake ']],
            [['expression'   => '']],
            [['expression'   => '', 'value ' => '']],
            [['expression'   => '', ' value' => '']],
            [['expression'   => '', ' value ' => '']],
            [['expression'   => '', 'value' => '']],
            [[' expression'  => '']],
            [['expression '  => '', 'value ' => '']],
            [[' expression ' => '', ' value' => '']],
        ];
    }

    public function dataProviderExpressionParseException()
    {
        return [
            [['field' => '']],
            [['field' => '', 'expression ' => '']],
            [['field' => '', ' expression' => '']],
            [['field' => '', ' expression ' => '']],
            [['field' => '', 'value' => '']],
        ];
    }

    public function dataProviderValueParseException()
    {
        return [
            [['field' => '', 'expression' => '']],
            [['field' => '', 'expression' => '', 'value ' => '']],
            [['field' => '', 'expression' => '', ' value' => '']],
            [['field' => '', 'expression' => '', ' value ' => '']],
        ];
    }

    /**
     * @dataProvider dataProviderFieldParseException
     *
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ParserException
     * @expectedExceptionMessage The key "field" is not set.
     */
    public function testFieldParseException($data)
    {
        $this->getParserMock($data)->parse();
    }

    /**
     * @dataProvider dataProviderExpressionParseException
     *
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ParserException
     * @expectedExceptionMessage The key "expression" is not set.
     */
    public function testExpressionParseException($data)
    {
        $this->getParserMock($data)->parse();
    }

    /**
     * @dataProvider dataProviderValueParseException
     *
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ParserException
     * @expectedExceptionMessage The key "value" is not set.
     */
    public function testValueParseException($data)
    {
        $this->getParserMock($data)->parse();
    }
}
