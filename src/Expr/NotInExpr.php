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
use Cekurte\Resource\Query\Language\Expr\InExpr;

/**
 * NotInExpr
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
class NotInExpr extends InExpr implements ExprInterface
{
    /**
     * @inheritdoc
     */
    public function __construct($field, $values)
    {
        parent::__construct($field, $values);

        $this->expression = 'notIn';
        $this->operator   = 'notin';
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Not in';
    }
}
