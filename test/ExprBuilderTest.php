<?php

namespace Cekurte\Resource\Query\Language\Test;

use Cekurte\Resource\Query\Language\Contract\ExprInterface;
use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\BetweenExpr;

class ExprBuilderTest extends \PHPUnit_Framework_TestCase
{
    private function getExprBuilderMock()
    {
        $mock = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\ExprBuilder')
            ->disableOriginalConstructor()
            ->setMethods(['enqueue'])
            ->getMock()
        ;

        $mock
            ->expects($this->once())
            ->method('enqueue')
            ->will($this->returnValue(null))
        ;

        return $mock;
    }

    public function testExprBuilderIsAnInstanceOfExprQueue()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprQueue',
            new ExprBuilder()
        );
    }

    public function testBetween()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprBuilder',
            $this->getExprBuilderMock()->between('fake_field', 1, 10)
        );
    }

    public function testEq()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprBuilder',
            $this->getExprBuilderMock()->eq('fake_field', 'value')
        );
    }

    public function testGte()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprBuilder',
            $this->getExprBuilderMock()->gte('fake_field', 1)
        );
    }

    public function testGt()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprBuilder',
            $this->getExprBuilderMock()->gt('fake_field', 1)
        );
    }

    public function testIn()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprBuilder',
            $this->getExprBuilderMock()->in('fake_field', [1, 2, 3])
        );
    }

    public function testLike()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprBuilder',
            $this->getExprBuilderMock()->like('fake_field', 'value')
        );
    }

    public function testLte()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprBuilder',
            $this->getExprBuilderMock()->lte('fake_field', 1)
        );
    }

    public function testLt()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprBuilder',
            $this->getExprBuilderMock()->lt('fake_field', 1)
        );
    }

    public function testNeq()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprBuilder',
            $this->getExprBuilderMock()->neq('fake_field', 'value')
        );
    }

    public function testNotIn()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprBuilder',
            $this->getExprBuilderMock()->notIn('fake_field', [1, 2, 3])
        );
    }

    public function testNotLike()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprBuilder',
            $this->getExprBuilderMock()->notLike('fake_field', 'value')
        );
    }

    public function testOrx()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprBuilder',
            $this->getExprBuilderMock()->orx('field:eq:1|field:eq:2')
        );
    }

    public function testPaginate()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprBuilder',
            $this->getExprBuilderMock()->paginate(1, 10)
        );
    }

    public function testSort()
    {
        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprBuilder',
            $this->getExprBuilderMock()->sort('fake_field', 'asc')
        );
    }
}
