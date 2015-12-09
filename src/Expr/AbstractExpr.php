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
use Cekurte\Resource\Query\Language\Contract\ExprTemplateInterface;

/**
 * AbstractExpr
 *
 * @abstract
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
abstract class AbstractExpr implements ExprInterface, ExprTemplateInterface
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @var string
     */
    protected $expression;

    /**
     * @var string
     */
    protected $expressionSeparator;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @inheritdoc
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @inheritdoc
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @inheritdoc
     */
    public function getExpressionSeparator()
    {
        return empty($this->expressionSeparator) ? ':' : $this->expressionSeparator;
    }

    /**
     * @inheritdoc
     */
    public function getInputExpression()
    {
        return $this->getField()
            . $this->getExpressionSeparator()
            . $this->getExpression()
            . $this->getExpressionSeparator()
            . $this->getValue()
        ;
    }

    /**
     * @inheritdoc
     */
    public function getOutputExpression()
    {
        return trim($this->getField() . $this->getOperator() . $this->getValue());
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function getOperator()
    {
        return sprintf(' %s ', $this->operator);
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return sprintf(
            '[%s] %s %s [%s] %s [%s].',
            $this->getExpression(),
            $this->getName(),
            'was created with the following input expression',
            $this->getInputExpression(),
            'that generated this output expression',
            $this->getOutputExpression()
        );
    }
}
