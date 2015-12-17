<?php

namespace Cekurte\Resource\Query\Language\Test\Parser;

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\Parser\RequestParser;
use Cekurte\Tdd\ReflectionTestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\Uri;

class RequestParserTest extends ReflectionTestCase
{
    private function getParserMock($data, array $methods = array())
    {
        $mock = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Parser\\RequestParser')
            ->disableOriginalConstructor()
            ->setMethods(array_merge(['getQueryStringParameter', 'getData'], $methods))
            ->getMock()
        ;

        $mock
            ->expects($this->once())
            ->method('getQueryStringParameter')
            ->will($this->returnValue('q'))
        ;

        $request = (new Request())
            ->withUri(new Uri('http://example.com' . $data))
            ->withMethod('GET')
        ;

        $mock
            ->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($request))
        ;

        return $mock;
    }

    public function testParseToOrExpression()
    {
        $mock = $this->getParserMock('?q[]=:or:field:eq:1|field:eq:2', [
            'getValueToOrExpression',
            'process',
        ]);

        $mock
            ->expects($this->once())
            ->method('getValueToOrExpression')
            ->will($this->returnValue(null))
        ;

        $mock
            ->expects($this->once())
            ->method('process')
            ->will($this->returnValue(null))
        ;

        $this->assertEquals(new ExprBuilder(), $mock->parse());
    }

    public function testRequestParserIsAnInstanceOfAbstractParser()
    {
        $request = (new Request())
            ->withUri(new Uri('http://example.com?q[]=field:eq:value'))
            ->withMethod('GET')
        ;

        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\Parser\\AbstractParser',
            new RequestParser($request)
        );
    }

    public function testConstructor()
    {
        $request = (new Request())
            ->withUri(new Uri('http://example.com?q[]=field:eq:value'))
            ->withMethod('GET')
        ;

        $parser = new RequestParser($request);

        $this->assertEquals($request, $parser->getData());
    }

    public function testQueryStringParameter()
    {
        $request = (new Request())
            ->withUri(new Uri('http://example.com?q[]=field:eq:value'))
            ->withMethod('GET')
        ;

        $parser = new RequestParser($request);

        $this->assertEquals('q', $parser->getQueryStringParameter());

        $parser->setQueryStringParameter('expr');

        $this->assertEquals('expr', $parser->getQueryStringParameter());
    }

    public function testParseReturnEmptyExprBuilderWhenQueryStringParameterIsNotSet()
    {
        $this->assertEquals(new ExprBuilder(), $this->getParserMock('')->parse());
    }

    public function testParseContinueWhenItemIsEmpty()
    {
        $this->assertEquals(new ExprBuilder(), $this->getParserMock('?q[]=')->parse());
    }

    public function testParse()
    {
        $mock = $this->getParserMock('?q[]=field:eq:value', [
            'process',
        ]);

        $mock
            ->expects($this->once())
            ->method('process')
            ->will($this->returnValue(null))
        ;

        $this->assertEquals(new ExprBuilder(), $mock->parse());
    }

    public function dataProviderError()
    {
        return [
            [' '],
            [[]],
            [null],
            [true],
            [false],
            [1],
            [1.5],
            [0],
            [-1],
            [-1.5],
        ];
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

    public function dataProviderDataNotIsAnArrayParseException()
    {
        return [
            ['field:eq:value'],
            ['field:neq:value'],
            [':or:field:eq:1|field:eq:2'],
        ];
    }

    /**
     * @dataProvider dataProviderError
     *
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ParserException
     */
    public function testError($data)
    {
        new RequestParser($data);
    }

    /**
     * @dataProvider dataProviderParseException
     *
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ParserException
     * @expectedExceptionMessageRegExp /The template of the current item ".*?" is invalid./
     */
    public function testParseException($data)
    {
        $this->getParserMock('?q[]=' . $data)->parse();
    }

    /**
     * @dataProvider dataProviderDataNotIsAnArrayParseException
     *
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ParserException
     * @expectedExceptionMessageRegExp /The query string with key ".*?" must be a array./
     */
    public function testDataNotIsAnArrayParseException($data)
    {
        $this->getParserMock('?q=' . $data)->parse();
    }
}
