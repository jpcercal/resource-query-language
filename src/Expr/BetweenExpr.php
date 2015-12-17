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
 * BetweenExpr
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
class BetweenExpr extends AbstractExpr implements ExprInterface
{
    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $to;

    /**
     * @param string $field
     * @param string $from
     * @param string $to
     */
    public function __construct($field, $from, $to)
    {
        if (!is_string($field)) {
            throw new InvalidExprException('The value of "field" must be a string data type.');
        }

        if (empty($field)) {
            throw new InvalidExprException('The value of "field" can not be empty.');
        }

        if (!is_numeric($from)) {
            throw new InvalidExprException('The value of "from" must be a numeric data type.');
        }

        if (!is_numeric($to)) {
            throw new InvalidExprException('The value of "to" must be a numeric data type.');
        }

        if ($to < $from) {
            throw new InvalidExprException('The value of "to" can not be less "from".');
        }

        if ($from == $to) {
            throw new InvalidExprException('The value of "from" and "to" can not be equals.');
        }

        $this->expression = 'between';
        $this->operator   = '>==<';

        $this->field = $field;
        $this->value = $from . '-' . $to;

        $this->from = $from + 0;
        $this->to   = $to   + 0;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Between';
    }
}
