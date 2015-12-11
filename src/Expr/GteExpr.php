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
 * GteExpr
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
class GteExpr extends AbstractExpr implements ExprInterface
{
    /**
     * @param  string $field
     * @param  int    $value
     *
     * @throws InvalidExprException
     */
    public function __construct($field, $value)
    {
        if (!is_string($field)) {
            throw new InvalidExprException('The value of "field" must be a string data type.');
        }

        if (empty($field)) {
            throw new InvalidExprException('The value of "field" can not be empty.');
        }

        if (is_string($value)) {
            $value = trim($value);
        }

        if (!is_numeric($value)) {
            throw new InvalidExprException('The value must be a numeric data type.');
        }

        $this->expression = 'gte';
        $this->operator   = '>=';

        $this->field = $field;
        $this->value = $value + 0;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Greater than or equal';
    }
}
