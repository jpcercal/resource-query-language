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
 * SortExpr
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
class SortExpr extends AbstractExpr implements ExprInterface
{
    /**
     * @param  string $field
     * @param  string $direction
     *
     * @throws InvalidExprException
     */
    public function __construct($field, $direction)
    {
        if (!in_array($direction = strtolower($direction), ['asc', 'desc'])) {
            throw new InvalidExprException('The direction must be "asc" or "desc".');
        }

        $this->expression = 'sort';
        $this->operator   = 'sort';

        $this->field = $field;
        $this->value = $direction;
    }

    public function getDirection()
    {
        return $this->getValue();
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Sort';
    }
}
