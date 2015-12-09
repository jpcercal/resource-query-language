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
