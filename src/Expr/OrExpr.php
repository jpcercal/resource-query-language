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
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\AbstractExpr;
use Cekurte\Resource\Query\Language\Parser\StringParser;

/**
 * OrExpr
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
class OrExpr extends AbstractExpr implements ExprInterface
{
    /**
     * @var array
     */
    protected $expressions;

    /**
     * @var ExprQueue
     */
    protected $queue;

    /**
     * @param  string|array $expressions
     */
    public function __construct($expressions)
    {
        if (!is_string($expressions) && !is_array($expressions)) {
            throw new InvalidExprException('The value of "expressions" must not be a string or an array data type.');
        }

        if (empty($expressions)) {
            throw new InvalidExprException('The value of "expressions" can not be empty.');
        }

        if (is_string($expressions)) {
            $expressions = explode('|', trim($expressions));
        }

        $expressions = array_filter($expressions);

        if (count($expressions) < 2) {
            throw new InvalidExprException('The number of "expressions" must be greater than or equals two.');
        }

        $invalidExpressionsToOr = ['paginate', 'sort', 'or'];

        foreach ($expressions as $expression) {
            foreach ($invalidExpressionsToOr as $invalidExpression) {
                if (stripos($expression, sprintf(':%s:', $invalidExpression)) !== false) {
                    throw new InvalidExprException(sprintf(
                        'The value of "expressions" contains the "%s" expression.',
                        $invalidExpression
                    ));
                }
            }
        }

        $this->expression = 'or';
        $this->operator   = 'or';

        $this->field = null;
        $this->value = $expressions;

        $parser = new StringParser(implode('|', $expressions));

        $this->queue = $parser->setSeparator('|')->parse();
    }

    /**
     * @return ExprQueue
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Or';
    }
}
