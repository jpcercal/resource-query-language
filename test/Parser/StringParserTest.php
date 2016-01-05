<?php

namespace Cekurte\Resource\Query\Language\Test\Parser;

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\Parser\StringParser;
use Cekurte\Tdd\ReflectionTestCase;

class StringParserTest extends ReflectionTestCase
{
    private function getParserMock($data, array $methods = array())
    {
        $mock = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Parser\\StringParser')
            ->disableOriginalConstructor()
            ->setMethods(array_merge(['getSeparator', 'getData'], $methods))
            ->getMock()
        ;

        $mock
            ->expects($this->once())
            ->method('getSeparator')
            ->will($this->returnValue(';'))
        ;

        $mock
            ->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($data))
        ;

        return $mock;
    }

    public function testStringParserIsAnInstanceOfAbstractParser()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Parser\\AbstractParser',
            new StringParser('field:eq:value')
        );
    }

    public function testConstructor()
    {
        $parser = new StringParser('field:eq:value');

        $this->assertEquals('field:eq:value', $parser->getData());
    }

    public function testSeparator()
    {
        $parser = new StringParser('field:eq:value');

        $this->assertEquals(';', $parser->getSeparator());

        $parser->setSeparator('@');

        $this->assertEquals('@', $parser->getSeparator());
    }

    public function testParseContinueWhenItemIsEmpty()
    {
        $this->assertEquals(new ExprBuilder(), $this->getParserMock('')->parse());
    }

    public function testParseToOrExpression()
    {
        $mock = $this->getParserMock(':or:field:eq:1|field:eq:2', [
            'getValueOfComplexExpression',
            'process',
        ]);

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
        $mock = $this->getParserMock('field:eq:value', [
            'process',
        ]);

        $mock
            ->expects($this->once())
            ->method('process')
            ->will($this->returnValue(null))
        ;

        $this->assertEquals(new ExprBuilder(), $mock->parse());
    }

    public function dataProviderParseException()
    {
        return [
            [' '],
            [' :'],
            [' : '],
            [':'],
            ['fake'],
            [' fake'],
            ['fake '],
            [' fake '],
            [' fake:'],
            ['fake:'],
            ['fake: '],
            [' fake: '],
            [':fake'],
            [':fake '],
            [' :fake'],
            [' :fake '],
        ];
    }

    /**
     * @dataProvider dataProviderParseException
     *
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ParserException
     * @expectedExceptionMessageRegExp /The template of the current item ".*?" is invalid./
     */
    public function testParseException($data)
    {
        $this->getParserMock($data)->parse();
    }
}
