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
 * InExpr
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
class InExpr extends AbstractExpr implements ExprInterface
{
    /**
     * @var array
     */
    protected $rawValue;

    /**
     * @param  string       $field
     * @param  string|array $rawValue
     */
    public function __construct($field, $rawValue)
    {
        if (!is_string($field)) {
            throw new InvalidExprException('The value of "field" must be a string data type.');
        }

        if (empty($field)) {
            throw new InvalidExprException('The value of "field" can not be empty.');
        }

        if ((is_string($rawValue) || is_array($rawValue)) && empty($rawValue)) {
            throw new InvalidExprException('The value of "rawValue" can not be empty.');
        }

        $validate = function ($rawValue) {
            if (is_bool($rawValue)) {
                throw new InvalidExprException('The value of "rawValue" contains a bool data type.');
            }

            if (is_null($rawValue)) {
                throw new InvalidExprException('The value of "rawValue" contains a null data type.');
            }
        };

        if (is_array($rawValue)) {
            foreach ($rawValue as $item) {
                if (is_array($item)) {
                    throw new InvalidExprException('The value of "rawValue" contains an array data type.');
                }

                if (is_string($item) && empty($item)) {
                    throw new InvalidExprException('The value of "rawValue" contains an empty value.');
                }

                $validate($item);
            }
        } else {
            $validate($rawValue);
        }

        $input = is_array($rawValue) ? $rawValue : explode('+', $rawValue);

        $this->expression = 'in';
        $this->operator   = 'in';

        $this->field = $field;
        $this->value = array_filter($input, function ($value) {
            return $value !== '';
        });

        $this->rawValue = $rawValue;
    }

    /**
     * @return array
     */
    public function getRawValue()
    {
        return $this->rawValue;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'In';
    }
}
