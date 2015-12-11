<?php

namespace Cekurte\Resource\Query\Language\Test;

use Cekurte\Resource\Query\Language\Expr;

class ExprTest extends \PHPUnit_Framework_TestCase
{
    public function dataProviderIsValidExpression()
    {
        return [
            ['between'],
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
            ['or'],
            ['paginate'],
            ['sort'],
        ];
    }

    public function dataProviderIsNotValidExpression()
    {
        return [
            ['expr-that-not-exists'],
        ];
    }

    public function testStaticMethodGetExpressions()
    {
        $expressions = Expr::getExpressions();

        $this->assertTrue(is_array($expressions));

        $this->assertEquals(
            '\Cekurte\Resource\Query\Language\Expr\BetweenExpr',
            $expressions['between']
        );

        $this->assertEquals(
            '\Cekurte\Resource\Query\Language\Expr\EqExpr',
            $expressions['eq']
        );

        $this->assertEquals(
            '\Cekurte\Resource\Query\Language\Expr\GteExpr',
            $expressions['gte']
        );

        $this->assertEquals(
            '\Cekurte\Resource\Query\Language\Expr\GtExpr',
            $expressions['gt']
        );

        $this->assertEquals(
            '\Cekurte\Resource\Query\Language\Expr\InExpr',
            $expressions['in']
        );

        $this->assertEquals(
            '\Cekurte\Resource\Query\Language\Expr\LikeExpr',
            $expressions['like']
        );

        $this->assertEquals(
            '\Cekurte\Resource\Query\Language\Expr\LteExpr',
            $expressions['lte']
        );

        $this->assertEquals(
            '\Cekurte\Resource\Query\Language\Expr\LtExpr',
            $expressions['lt']
        );

        $this->assertEquals(
            '\Cekurte\Resource\Query\Language\Expr\NeqExpr',
            $expressions['neq']
        );

        $this->assertEquals(
            '\Cekurte\Resource\Query\Language\Expr\NotInExpr',
            $expressions['notin']
        );

        $this->assertEquals(
            '\Cekurte\Resource\Query\Language\Expr\NotLikeExpr',
            $expressions['notlike']
        );

        $this->assertEquals(
            '\Cekurte\Resource\Query\Language\Expr\OrExpr',
            $expressions['or']
        );

        $this->assertEquals(
            '\Cekurte\Resource\Query\Language\Expr\PaginateExpr',
            $expressions['paginate']
        );

        $this->assertEquals(
            '\Cekurte\Resource\Query\Language\Expr\SortExpr',
            $expressions['sort']
        );
    }

    /**
     * @dataProvider dataProviderIsValidExpression
     */
    public function testStaticMethodIsValidExpression($expression)
    {
        $this->assertTrue(Expr::isValidExpression($expression));
    }

    /**
     * @dataProvider dataProviderIsNotValidExpression
     */
    public function testStaticMethodIsNotValidExpression($expression)
    {
        $this->assertFalse(Expr::isValidExpression($expression));
    }
}
