<?php

namespace Cekurte\Resource\Query\Language\Test\Processor;

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\BetweenExpr;
use Cekurte\Resource\Query\Language\Expr\EqExpr;
use Cekurte\Resource\Query\Language\Expr\OrExpr;
use Cekurte\Resource\Query\Language\Expr\SortExpr;
use Cekurte\Resource\Query\Language\Processor\DoctrineOrmProcessor;
use Cekurte\Tdd\ReflectionTestCase;

class DoctrineOrmProcessorTest extends ReflectionTestCase
{
    private function getProcessor()
    {
        return new DoctrineOrmProcessor($this->getDoctrineOrmQueryBuilder());
    }

    private function getDoctrineOrmQueryBuilder()
    {
        $entityManager = $this
            ->getMockBuilder('\\Doctrine\\ORM\\EntityManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return new \Doctrine\ORM\QueryBuilder($entityManager);
    }

    private function getDoctrineOrmQueryBuilderAsMock()
    {
        $entityManager = $this
            ->getMockBuilder('\\Doctrine\\ORM\\EntityManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return $this
            ->getMockBuilder('\\Doctrine\\ORM\\QueryBuilder')
            ->setConstructorArgs([$entityManager])
        ;
    }

    public function testConstructor()
    {
        $queryBuilder = $this->getDoctrineOrmQueryBuilder();

        $queryBuilder->setParameter('field', 'value');

        $processor = new DoctrineOrmProcessor($queryBuilder);

        $this->assertEquals(
            DoctrineOrmProcessor::WHERE_OPERATION_MODE_AND,
            $processor->getWhereOperationMode()
        );

        $this->assertInstanceOf(
            '\\Doctrine\\ORM\\QueryBuilder',
            $this->propertyGetValue($processor, 'queryBuilder')
        );

        $this->assertEquals(1, count($this->propertyGetValue($processor, 'queryBuilder')->getParameters()));
    }

    public function testSetWhereOperationMode()
    {
        $processor = $this->getProcessor();

        $this->assertEquals(
            DoctrineOrmProcessor::WHERE_OPERATION_MODE_AND,
            $processor->getWhereOperationMode()
        );

        $this->invokeMethod($processor, 'setWhereOperationMode', [
            DoctrineOrmProcessor::WHERE_OPERATION_MODE_OR
        ]);

        $this->assertEquals(
            DoctrineOrmProcessor::WHERE_OPERATION_MODE_OR,
            $processor->getWhereOperationMode()
        );
    }

    /**
     * @expectedException \Cekurte\Resource\Query\Language\Exception\ProcessorException
     * @expectedExceptionMessageRegExp /The where operation mode ".*?" is not allowed or not exists./
     */
    public function testSetWhereOperationModeProcessorException()
    {
        $processor = $this->getProcessor();

        $this->invokeMethod($processor, 'setWhereOperationMode', [
            'or'
        ]);
    }

    public function testGetParamKeyByExpr()
    {
        $processor = $this->getProcessor();

        $paramKey = $this->invokeMethod($processor, 'getParamKeyByExpr', [
            new EqExpr('alias.field', 'value')
        ]);

        $this->assertEquals('aliasfieldEq', substr($paramKey, 0, -13));
    }

    public function testProcessBetweenExpr()
    {
        $doctrineQueryExpr = $this
            ->getMockBuilder('\\Doctrine\\ORM\\Query\\Expr')
            ->setMethods(['between'])
            ->getMock()
        ;

        $doctrineQueryExpr
            ->expects($this->once())
            ->method('between')
            ->will($this->returnValue(null))
        ;

        $queryBuilder = $this->getDoctrineOrmQueryBuilderAsMock()
            ->setMethods(['expr', 'andWhere', 'setParameter'])
            ->getMock()
        ;

        $queryBuilder
            ->expects($this->once())
            ->method('expr')
            ->will($this->returnValue($doctrineQueryExpr))
        ;

        $queryBuilder
            ->expects($this->once())
            ->method('andWhere')
            ->will($this->returnValue(null))
        ;

        $queryBuilder
            ->expects($this->at(2))
            ->method('setParameter')
            ->will($this->returnValue(null))
        ;

        $processor = new DoctrineOrmProcessor($queryBuilder);

        $expressionInterface = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Expr\\BetweenExpr')
            ->disableOriginalConstructor()
            ->setMethods(['getField', 'getFrom', 'getTo'])
            ->getMock()
        ;

        $expressionInterface
            ->expects($this->at(2))
            ->method('getField')
            ->will($this->returnValue('field'))
        ;

        $expressionInterface
            ->expects($this->once())
            ->method('getFrom')
            ->will($this->returnValue(1))
        ;

        $expressionInterface
            ->expects($this->once())
            ->method('getTo')
            ->will($this->returnValue(1))
        ;

        $this->invokeMethod($processor, 'processBetweenExpr', [$expressionInterface]);
    }

    public function testPaginateSortExpr()
    {
        $queryBuilder = $this->getDoctrineOrmQueryBuilderAsMock()
            ->setMethods(['setFirstResult', 'setMaxResults'])
            ->getMock()
        ;

        $queryBuilder
            ->expects($this->once())
            ->method('setFirstResult')
            ->will($this->returnValue(null))
        ;

        $queryBuilder
            ->expects($this->once())
            ->method('setMaxResults')
            ->will($this->returnValue(null))
        ;

        $processor = new DoctrineOrmProcessor($queryBuilder);

        $expressionInterface = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Expr\\PaginateExpr')
            ->disableOriginalConstructor()
            ->setMethods(['getCurrentPageNumber', 'getMaxResultsPerPage'])
            ->getMock()
        ;

        $expressionInterface
            ->expects($this->once())
            ->method('getCurrentPageNumber')
            ->will($this->returnValue(1))
        ;

        $expressionInterface
            ->expects($this->once())
            ->method('getMaxResultsPerPage')
            ->will($this->returnValue(1))
        ;

        $this->invokeMethod($processor, 'processPaginateExpr', [$expressionInterface]);
    }

    public function testProcessSortExpr()
    {
        $queryBuilder = $this->getDoctrineOrmQueryBuilderAsMock()
            ->setMethods(['addOrderBy'])
            ->getMock()
        ;

        $queryBuilder
            ->expects($this->once())
            ->method('addOrderBy')
            ->will($this->returnValue(null))
        ;

        $processor = new DoctrineOrmProcessor($queryBuilder);

        $expressionInterface = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Expr\\SortExpr')
            ->disableOriginalConstructor()
            ->setMethods(['getField', 'getDirection'])
            ->getMock()
        ;

        $expressionInterface
            ->expects($this->once())
            ->method('getField')
            ->will($this->returnValue('field'))
        ;

        $expressionInterface
            ->expects($this->once())
            ->method('getDirection')
            ->will($this->returnValue('asc'))
        ;

        $this->invokeMethod($processor, 'processSortExpr', [$expressionInterface]);
    }

    public function testProcessOrExpr()
    {
        $processor = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Processor\\DoctrineOrmProcessor')
            ->setConstructorArgs([$this->getDoctrineOrmQueryBuilder()])
            ->setMethods(['setWhereOperationMode', 'process'])
            ->getMock()
        ;

        $processor
            ->expects($this->at(2))
            ->method('setWhereOperationMode')
            ->will($this->returnValue(null))
        ;

        $processor
            ->expects($this->once())
            ->method('process')
            ->will($this->returnValue(null))
        ;

        $expressionInterface = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Expr\\OrExpr')
            ->disableOriginalConstructor()
            ->setMethods(['getQueue'])
            ->getMock()
        ;

        $expressionInterface
            ->expects($this->once())
            ->method('getQueue')
            ->will($this->returnValue(new ExprQueue()))
        ;

        $this->invokeMethod($processor, 'processOrExpr', [$expressionInterface]);
    }

    public function dataProviderProcessComparisonExpr()
    {
        return [
            ['eq',      '\Cekurte\Resource\Query\Language\Expr\EqExpr'],
            ['gte',     '\Cekurte\Resource\Query\Language\Expr\GteExpr'],
            ['gt',      '\Cekurte\Resource\Query\Language\Expr\GtExpr'],
            ['in',      '\Cekurte\Resource\Query\Language\Expr\InExpr'],
            ['like',    '\Cekurte\Resource\Query\Language\Expr\LikeExpr'],
            ['lte',     '\Cekurte\Resource\Query\Language\Expr\LteExpr'],
            ['lt',      '\Cekurte\Resource\Query\Language\Expr\LtExpr'],
            ['neq',     '\Cekurte\Resource\Query\Language\Expr\NeqExpr'],
            ['notin',   '\Cekurte\Resource\Query\Language\Expr\NotInExpr'],
            ['notlike', '\Cekurte\Resource\Query\Language\Expr\NotLikeExpr'],
        ];
    }

    /**
     * @dataProvider dataProviderProcessComparisonExpr
     */
    public function testProcessComparisonExpr($expression, $class)
    {
        $doctrineQueryExpr = $this
            ->getMockBuilder('\\Doctrine\\ORM\\Query\\Expr')
            ->setMethods([$expression, 'getValue'])
            ->getMock()
        ;

        $doctrineQueryExpr
            ->expects($this->once())
            ->method($expression)
            ->will($this->returnValue(null))
        ;

        $queryBuilder = $this->getDoctrineOrmQueryBuilderAsMock()
            ->setMethods(['expr', 'andWhere', 'setParameter'])
            ->getMock()
        ;

        $queryBuilder
            ->expects($this->once())
            ->method('expr')
            ->will($this->returnValue($doctrineQueryExpr))
        ;

        $queryBuilder
            ->expects($this->once())
            ->method('andWhere')
            ->will($this->returnValue(null))
        ;

        $queryBuilder
            ->expects($this->once())
            ->method('setParameter')
            ->will($this->returnValue(null))
        ;

        $processor = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Processor\\DoctrineOrmProcessor')
            ->setConstructorArgs([$queryBuilder])
            ->setMethods(['getParamKeyByExpr', 'getWhereOperationMode', 'andWhere'])
            ->getMock()
        ;

        $processor
            ->expects($this->once())
            ->method('getParamKeyByExpr')
            ->will($this->returnValue('fakeParamKey'))
        ;

        $processor
            ->expects($this->once())
            ->method('getWhereOperationMode')
            ->will($this->returnValue('andWhere'))
        ;

        $queryBuilder
            ->expects($this->once())
            ->method('andWhere')
            ->will($this->returnValue(null))
        ;

        $expressionInterface = $this
            ->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods(['getField', 'getExpression', 'getValue'])
            ->getMock()
        ;

        $expressionInterface
            ->expects($this->once())
            ->method('getField')
            ->will($this->returnValue('field'))
        ;

        $expressionInterface
            ->expects($this->once())
            ->method('getExpression')
            ->will($this->returnValue($expression))
        ;

        $expressionInterface
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('value'))
        ;

        $this->invokeMethod($processor, 'processComparisonExpr', [$expressionInterface]);
    }

    public function testProcessWithBetweenExpression()
    {
        $processor = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Processor\\DoctrineOrmProcessor')
            ->setConstructorArgs([$this->getDoctrineOrmQueryBuilder()])
            ->setMethods(['processBetweenExpr'])
            ->getMock()
        ;

        $processor
            ->expects($this->once())
            ->method('processBetweenExpr')
            ->will($this->returnValue(null))
        ;

        $builder = new ExprBuilder();

        $processor->process($builder->between('field', 1, 10));
    }

    public function testProcessWithPaginateExpression()
    {
        $processor = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Processor\\DoctrineOrmProcessor')
            ->setConstructorArgs([$this->getDoctrineOrmQueryBuilder()])
            ->setMethods(['processPaginateExpr'])
            ->getMock()
        ;

        $processor
            ->expects($this->once())
            ->method('processPaginateExpr')
            ->will($this->returnValue(null))
        ;

        $builder = new ExprBuilder();

        $processor->process($builder->paginate(1, 10));
    }

    public function testProcessWithSortExpression()
    {
        $processor = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Processor\\DoctrineOrmProcessor')
            ->setConstructorArgs([$this->getDoctrineOrmQueryBuilder()])
            ->setMethods(['processSortExpr'])
            ->getMock()
        ;

        $processor
            ->expects($this->once())
            ->method('processSortExpr')
            ->will($this->returnValue(null))
        ;

        $builder = new ExprBuilder();

        $processor->process($builder->sort('field', 'asc'));
    }

    public function testProcessWithOrExpression()
    {
        $processor = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Processor\\DoctrineOrmProcessor')
            ->setConstructorArgs([$this->getDoctrineOrmQueryBuilder()])
            ->setMethods(['processOrExpr'])
            ->getMock()
        ;

        $processor
            ->expects($this->once())
            ->method('processOrExpr')
            ->will($this->returnValue(null))
        ;

        $builder = new ExprBuilder();

        $processor->process($builder->orx('field:eq:1|field:eq:2'));
    }

    public function testProcessWithEqExpression()
    {
        $processor = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Processor\\DoctrineOrmProcessor')
            ->setConstructorArgs([$this->getDoctrineOrmQueryBuilder()])
            ->setMethods(['processComparisonExpr'])
            ->getMock()
        ;

        $processor
            ->expects($this->once())
            ->method('processComparisonExpr')
            ->will($this->returnValue(null))
        ;

        $builder = new ExprBuilder();

        $processor->process($builder->eq('field', 'value'));
    }
}
