<?php

namespace Cekurte\Resource\Query\Language\Test\Processor;

use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\AndExpr;
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

        $this->assertInstanceOf(
            '\\Doctrine\\ORM\\QueryBuilder',
            $this->propertyGetValue($processor, 'queryBuilder')
        );

        $this->assertEquals(1, count($this->propertyGetValue($processor, 'queryBuilder')->getParameters()));
    }

    public function testIsProcessingExprAndExprOr()
    {
        $processor = $this->getProcessor();

        $this->assertFalse($this->invokeMethod($processor, 'isProcessingExprAndExprOr', []));

        $this->propertySetValue($processor, 'processingExprAndExprOr', true);

        $this->assertTrue($this->invokeMethod($processor, 'isProcessingExprAndExprOr', []));
    }

    public function testGetParamKeyByExpr()
    {
        $processor = $this->getProcessor();

        $paramKey = $this->invokeMethod($processor, 'getParamKeyByExpr', [
            new EqExpr('alias.field', 'value')
        ]);

        $this->assertEquals('aliasfieldEq', substr($paramKey, 0, -32));
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

    public function dataProviderProcessComparisonExpr()
    {
        return [
            ['eq',      '\Cekurte\Resource\Query\Language\Expr\EqExpr',      false],
            ['gte',     '\Cekurte\Resource\Query\Language\Expr\GteExpr',     false],
            ['gt',      '\Cekurte\Resource\Query\Language\Expr\GtExpr',      false],
            ['in',      '\Cekurte\Resource\Query\Language\Expr\InExpr',      false],
            ['like',    '\Cekurte\Resource\Query\Language\Expr\LikeExpr',    false],
            ['lte',     '\Cekurte\Resource\Query\Language\Expr\LteExpr',     false],
            ['lt',      '\Cekurte\Resource\Query\Language\Expr\LtExpr',      false],
            ['neq',     '\Cekurte\Resource\Query\Language\Expr\NeqExpr',     false],
            ['notin',   '\Cekurte\Resource\Query\Language\Expr\NotInExpr',   false],
            ['notlike', '\Cekurte\Resource\Query\Language\Expr\NotLikeExpr', false],
            ['eq',      '\Cekurte\Resource\Query\Language\Expr\EqExpr',      true],
            ['gte',     '\Cekurte\Resource\Query\Language\Expr\GteExpr',     true],
            ['gt',      '\Cekurte\Resource\Query\Language\Expr\GtExpr',      true],
            ['in',      '\Cekurte\Resource\Query\Language\Expr\InExpr',      true],
            ['like',    '\Cekurte\Resource\Query\Language\Expr\LikeExpr',    true],
            ['lte',     '\Cekurte\Resource\Query\Language\Expr\LteExpr',     true],
            ['lt',      '\Cekurte\Resource\Query\Language\Expr\LtExpr',      true],
            ['neq',     '\Cekurte\Resource\Query\Language\Expr\NeqExpr',     true],
            ['notin',   '\Cekurte\Resource\Query\Language\Expr\NotInExpr',   true],
            ['notlike', '\Cekurte\Resource\Query\Language\Expr\NotLikeExpr', true],
        ];
    }

    /**
     * @dataProvider dataProviderProcessComparisonExpr
     */
    public function testProcessComparisonExpr($expression, $class, $isProcessing)
    {
        $doctrineQueryExpr = $this
            ->getMockBuilder('\\Doctrine\\ORM\\Query\\Expr')
            ->setMethods([$expression, 'getValue'])
            ->getMock()
        ;

        $doctrineQueryExpr
            ->expects($this->once())
            ->method($expression)
            ->will($this->returnValue($this->getMock('\\Doctrine\\Common\\Collections\\Expr\\Expression')))
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

        if (!$isProcessing) {
            $queryBuilder
                ->expects($this->once())
                ->method('andWhere')
                ->will($this->returnValue(null))
            ;
        }

        $queryBuilder
            ->expects($this->once())
            ->method('setParameter')
            ->will($this->returnValue(null))
        ;

        $processor = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Processor\\DoctrineOrmProcessor')
            ->setConstructorArgs([$queryBuilder])
            ->setMethods(['getParamKeyByExpr', 'andWhere'])
            ->getMock()
        ;

        $this->propertySetValue($processor, 'processingExprAndExprOr', $isProcessing);

        $processor
            ->expects($this->once())
            ->method('getParamKeyByExpr')
            ->will($this->returnValue('fakeParamKey'))
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

        $where = $this->invokeMethod($processor, 'processComparisonExpr', [$expressionInterface]);

        if ($isProcessing) {
            $this->assertTrue($where instanceof \Doctrine\Common\Collections\Expr\Expression);
        }
    }

    public function testProcessOrExpr()
    {
        $doctrineQueryExpr = $this
            ->getMockBuilder('\\Doctrine\\ORM\\Query\\Expr')
            ->setMethods(['orX'])
            ->getMock()
        ;

        $doctrineQueryExpr
            ->expects($this->once())
            ->method('orX')
            ->will($this->returnValue($this->getMock('\\Doctrine\\Common\\Collections\\Expr\\Expression')))
        ;

        $queryBuilder = $this->getDoctrineOrmQueryBuilderAsMock()
            ->setMethods(['expr', 'orWhere'])
            ->getMock()
        ;

        $queryBuilder
            ->expects($this->once())
            ->method('expr')
            ->will($this->returnValue($doctrineQueryExpr))
        ;

        $queryBuilder
            ->expects($this->once())
            ->method('orWhere')
            ->will($this->returnValue(null))
        ;

        $processor = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Processor\\DoctrineOrmProcessor')
            ->setConstructorArgs([$queryBuilder])
            ->setMethods(['processComparisonExpr', 'processBetweenExpr', 'processAndExpr'])
            ->getMock()
        ;

        $processor
            ->expects($this->at(2))
            ->method('processComparisonExpr')
            ->will($this->returnValue(null))
        ;

        $processor
            ->expects($this->once())
            ->method('processBetweenExpr')
            ->will($this->returnValue(null))
        ;

        $processor
            ->expects($this->once())
            ->method('processAndExpr')
            ->will($this->returnValue(null))
        ;

        $andExpr = new OrExpr('field:eq:1|field:eq:2|field:between:1-2|:and:field:eq:1&field:eq:2');

        $this->invokeMethod($processor, 'processOrExpr', [$andExpr]);
    }

    public function testProcessAndExpr()
    {
        $doctrineQueryExpr = $this
            ->getMockBuilder('\\Doctrine\\ORM\\Query\\Expr')
            ->setMethods(['andX'])
            ->getMock()
        ;

        $doctrineQueryExpr
            ->expects($this->once())
            ->method('andX')
            ->will($this->returnValue($this->getMock('\\Doctrine\\Common\\Collections\\Expr\\Expression')))
        ;

        $queryBuilder = $this->getDoctrineOrmQueryBuilderAsMock()
            ->setMethods(['expr', 'andWhere'])
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

        $processor = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Processor\\DoctrineOrmProcessor')
            ->setConstructorArgs([$queryBuilder])
            ->setMethods(['processComparisonExpr', 'processBetweenExpr', 'processOrExpr'])
            ->getMock()
        ;

        $processor
            ->expects($this->at(2))
            ->method('processComparisonExpr')
            ->will($this->returnValue(null))
        ;

        $processor
            ->expects($this->once())
            ->method('processBetweenExpr')
            ->will($this->returnValue(null))
        ;

        $processor
            ->expects($this->once())
            ->method('processOrExpr')
            ->will($this->returnValue(null))
        ;

        $andExpr = new AndExpr('field:eq:1&field:eq:2&field:between:1-2&:or:field:eq:1|field:eq:2');

        $this->invokeMethod($processor, 'processAndExpr', [$andExpr]);
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

    public function testProcessWithAndExpression()
    {
        $processor = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Processor\\DoctrineOrmProcessor')
            ->setConstructorArgs([$this->getDoctrineOrmQueryBuilder()])
            ->setMethods(['processAndExpr'])
            ->getMock()
        ;

        $processor
            ->expects($this->once())
            ->method('processAndExpr')
            ->will($this->returnValue(null))
        ;

        $builder = new ExprBuilder();

        $processor->process($builder->andx('field:eq:1&field:eq:2'));
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

    public function testProcessWithBetweenExpressionReturningTheWhereExpression()
    {
        $doctrineQueryExpr = $this
            ->getMockBuilder('\\Doctrine\\ORM\\Query\\Expr')
            ->setMethods(['between'])
            ->getMock()
        ;

        $doctrineQueryExpr
            ->expects($this->once())
            ->method('between')
            ->will($this->returnValue($this->getMock('\\Doctrine\\Common\\Collections\\Expr\\Expression')))
        ;

        $queryBuilder = $this->getDoctrineOrmQueryBuilderAsMock()
            ->setMethods(['expr', 'setParameter'])
            ->getMock()
        ;

        $queryBuilder
            ->expects($this->once())
            ->method('expr')
            ->will($this->returnValue($doctrineQueryExpr))
        ;

        $queryBuilder
            ->expects($this->at(2))
            ->method('setParameter')
            ->will($this->returnValue(null))
        ;

        $processor = $this
            ->getMockBuilder('\\Cekurte\\Resource\\Query\\Language\\Processor\\DoctrineOrmProcessor')
            ->setConstructorArgs([$queryBuilder])
            ->getMock()
        ;

        $this->propertySetValue($processor, 'processingExprAndExprOr', true);

        $where = $this->invokeMethod($processor, 'processBetweenExpr', [new BetweenExpr('field', 1, 10)]);

        $this->assertTrue($where instanceof \Doctrine\Common\Collections\Expr\Expression);
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
