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
 * LikeExpr
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
class LikeExpr extends AbstractExpr implements ExprInterface
{
    /**
     * @param string $field
     * @param string $value
     */
    public function __construct($field, $value)
    {
        if (!is_string($field)) {
            throw new InvalidExprException('The value of "field" must be a string data type.');
        }

        if (empty($field)) {
            throw new InvalidExprException('The value of "field" can not be empty.');
        }

        if (!is_string($value)) {
            throw new InvalidExprException('The value of "value" must be a string data type.');
        }

        $this->expression = 'like';
        $this->operator   = 'like';

        $this->field = $field;
        $this->value = $value;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Like';
    }
}
