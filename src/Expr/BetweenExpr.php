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
        $this->expression = 'between';
        $this->operator   = '>==<';

        $this->field = $field;
        $this->value = $from . '-' . $to;

        $this->from = $from;
        $this->to   = $to;
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
