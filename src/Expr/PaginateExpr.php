<?php

/*
 * This file is part of the Cekurte package.
 *
 * (c) João Paulo Cercal <jpcercal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cekurte\Resource\Query\Language\Expr;

use Cekurte\Resource\Query\Language\Contract\ExprInterface;
use Cekurte\Resource\Query\Language\Exception\InvalidExprException;
use Cekurte\Resource\Query\Language\Expr\AbstractExpr;

/**
 * PaginateExpr
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
class PaginateExpr extends AbstractExpr implements ExprInterface
{
    /**
     * @var int
     */
    protected $currentPageNumber;

    /**
     * @var int
     */
    protected $maxResultsPerPage;

    /**
     * @param  int $currentPageNumber
     * @param  int $maxResultsPerPage
     *
     * @throws InvalidExprException
     */
    public function __construct($currentPageNumber, $maxResultsPerPage)
    {
        if (!is_int($currentPageNumber) && !is_numeric($currentPageNumber)) {
            throw new InvalidExprException('The value of "currentPageNumber" must be an int data type.');
        }

        if (!is_int($maxResultsPerPage) && !is_numeric($maxResultsPerPage)) {
            throw new InvalidExprException('The value of "maxResultsPerPage" must be an int data type.');
        }

        $currentPageNumber = $currentPageNumber + 0;
        $maxResultsPerPage = $maxResultsPerPage + 0;

        if (is_float($currentPageNumber)) {
            throw new InvalidExprException('The value of "currentPageNumber" must be an int data type.');
        }

        if (is_float($maxResultsPerPage)) {
            throw new InvalidExprException('The value of "maxResultsPerPage" must be an int data type.');
        }

        if ($currentPageNumber < 1) {
            throw new InvalidExprException('The value of "currentPageNumber" can not be less than one.');
        }

        if ($maxResultsPerPage < 1) {
            throw new InvalidExprException('The value of "maxResultsPerPage" can not be less than one.');
        }

        if ($maxResultsPerPage > 100) {
            throw new InvalidExprException('The value of "maxResultsPerPage" can not be greater than one hundred.');
        }

        $this->expression = 'paginate';
        $this->operator   = 'paginate';

        $this->field = null;
        $this->value = $currentPageNumber . '-' . $maxResultsPerPage;

        $this->currentPageNumber = $currentPageNumber;
        $this->maxResultsPerPage = $maxResultsPerPage;
    }

    /**
     * @return int
     */
    public function getCurrentPageNumber()
    {
        return $this->currentPageNumber;
    }

    /**
     * @return int
     */
    public function getMaxResultsPerPage()
    {
        return $this->maxResultsPerPage;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Paginate';
    }
}
