<?php

/*
 * This file is part of the Cekurte package.
 *
 * (c) João Paulo Cercal <jpcercal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cekurte\Resource\Query\Language\Contract;

/**
 * ExprTemplateInterface
 *
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
interface ExprTemplateInterface
{
    /**
     * Get the field that will be filtered.
     *
     * @return string
     */
    public function getField();

    /**
     * Get the type of expression.
     *
     * @return string
     */
    public function getExpression();

    /**
     * Get the expression separator.
     *
     * @return string
     */
    public function getExpressionSeparator();

    /**
     * Get the value that will be used to filter the data.
     *
     * @return string
     */
    public function getValue();
}
