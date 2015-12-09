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
 * InExpr
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
class InExpr extends AbstractExpr implements ExprInterface
{
    /**
     * @var array
     */
    protected $values;

    /**
     * @param  string       $field
     * @param  string|array $values
     */
    public function __construct($field, $values)
    {
        $this->expression = 'in';
        $this->operator   = 'in';

        $this->field = $field;
        $this->value = is_array($values) ? implode('+', $values) : $values;

        $this->values = $values;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'In';
    }
}
