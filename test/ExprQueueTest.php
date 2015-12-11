<?php

namespace Cekurte\Resource\Query\Language\Test;

use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\EqExpr;

class ExprQueueTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsQueueInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Resource\\Query\\Language\\ExprQueue'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Cekurte\\Resource\\Query\\Language\\Contract\\QueueInterface'
        ));
    }

    public function testExprQueueIsAnInstanceOfSplQueue()
    {
        $this->assertInstanceOf(
            '\\SplQueue',
            new ExprQueue()
        );
    }

    /**
     * @expectedException \Cekurte\Resource\Query\Language\Exception\QueueException
     * @expectedExceptionMessage The $expr variable is not a instance of
     *                           Cekurte\Resource\Query\Language\Contract\ExprInterface.
     */
    public function testEnqueueQueueException()
    {
        $queue = new ExprQueue();

        $queue->enqueue('invalid_expression');
    }

    public function testEnqueue()
    {
        $queue = new ExprQueue();

        $this->assertInstanceOf(
            '\\Cekurte\\Resource\\Query\\Language\\ExprQueue',
            $queue->enqueue(new EqExpr('fake_field', 'fake_value'))
        );
    }
}
