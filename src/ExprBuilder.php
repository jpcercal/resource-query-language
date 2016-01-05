<?php

/*
 * This file is part of the Cekurte package.
 *
 * (c) João Paulo Cercal <jpcercal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cekurte\Resource\Query\Language;

use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\AndExpr;
use Cekurte\Resource\Query\Language\Expr\BetweenExpr;
use Cekurte\Resource\Query\Language\Expr\EqExpr;
use Cekurte\Resource\Query\Language\Expr\GtExpr;
use Cekurte\Resource\Query\Language\Expr\GteExpr;
use Cekurte\Resource\Query\Language\Expr\InExpr;
use Cekurte\Resource\Query\Language\Expr\LikeExpr;
use Cekurte\Resource\Query\Language\Expr\LtExpr;
use Cekurte\Resource\Query\Language\Expr\LteExpr;
use Cekurte\Resource\Query\Language\Expr\NeqExpr;
use Cekurte\Resource\Query\Language\Expr\NotInExpr;
use Cekurte\Resource\Query\Language\Expr\NotLikeExpr;
use Cekurte\Resource\Query\Language\Expr\OrExpr;
use Cekurte\Resource\Query\Language\Expr\PaginateExpr;
use Cekurte\Resource\Query\Language\Expr\SortExpr;

/**
 * ExprBuilder
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
class ExprBuilder extends ExprQueue
{
    /**
     * @param  string|array $expressions
     *
     * @return ExprBuilder
     */
    public function andx($expressions)
    {
        $this->enqueue(new AndExpr($expressions));

        return $this;
    }

    /**
     * @param  string $field
     * @param  string $from
     * @param  string $to
     *
     * @return ExprBuilder
     */
    public function between($field, $from, $to)
    {
        $this->enqueue(new BetweenExpr($field, $from, $to));

        return $this;
    }

    /**
     * @param  string $field
     * @param  string $value
     *
     * @return ExprBuilder
     */
    public function eq($field, $value)
    {
        $this->enqueue(new EqExpr($field, $value));

        return $this;
    }

    /**
     * @param  string $field
     * @param  int    $value
     *
     * @return ExprBuilder
     */
    public function gte($field, $value)
    {
        $this->enqueue(new GteExpr($field, $value));

        return $this;
    }

    /**
     * @param  string $field
     * @param  int    $value
     *
     * @return ExprBuilder
     */
    public function gt($field, $value)
    {
        $this->enqueue(new GtExpr($field, $value));

        return $this;
    }

    /**
     * @param  string       $field
     * @param  string|array $rawValue
     *
     * @return ExprBuilder
     */
    public function in($field, $rawValue)
    {
        $this->enqueue(new InExpr($field, $rawValue));

        return $this;
    }

    /**
     * @param  string $field
     * @param  string $value
     *
     * @return ExprBuilder
     */
    public function like($field, $value)
    {
        $this->enqueue(new LikeExpr($field, $value));

        return $this;
    }

    /**
     * @param  string $field
     * @param  int    $value
     *
     * @return ExprBuilder
     */
    public function lte($field, $value)
    {
        $this->enqueue(new LteExpr($field, $value));

        return $this;
    }

    /**
     * @param  string $field
     * @param  int    $value
     *
     * @return ExprBuilder
     */
    public function lt($field, $value)
    {
        $this->enqueue(new LtExpr($field, $value));

        return $this;
    }

    /**
     * @param  string $field
     * @param  string $value
     *
     * @return ExprBuilder
     */
    public function neq($field, $value)
    {
        $this->enqueue(new NeqExpr($field, $value));

        return $this;
    }

    /**
     * @param  string       $field
     * @param  string|array $rawValue
     *
     * @return ExprBuilder
     */
    public function notIn($field, $rawValue)
    {
        $this->enqueue(new NotInExpr($field, $rawValue));

        return $this;
    }

    /**
     * @param  string $field
     * @param  string $value
     *
     * @return ExprBuilder
     */
    public function notLike($field, $value)
    {
        $this->enqueue(new NotLikeExpr($field, $value));

        return $this;
    }

    /**
     * @param  string|array $expressions
     *
     * @return ExprBuilder
     */
    public function orx($expressions)
    {
        $this->enqueue(new OrExpr($expressions));

        return $this;
    }

    /**
     * @param  int $currentPageNumber
     * @param  int $maxResultsPerPage
     *
     * @return ExprBuilder
     */
    public function paginate($currentPageNumber, $maxResultsPerPage)
    {
        $this->enqueue(new PaginateExpr($currentPageNumber, $maxResultsPerPage));

        return $this;
    }

    /**
     * @param  string $field
     * @param  string $direction
     *
     * @return ExprBuilder
     */
    public function sort($field, $direction)
    {
        $this->enqueue(new SortExpr($field, $direction));

        return $this;
    }
}
