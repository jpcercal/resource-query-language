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

use Cekurte\Resource\Query\Language\Contract\QueryLanguageInterface;

/**
 * ExprInterface
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
interface ExprInterface extends QueryLanguageInterface
{
    /**
     * Get the operator.
     *
     * @return string
     */
    public function getOperator();

    /**
     * Get the query expression.
     *
     * @return string
     */
    public function getExpression();

    /**
     * Get the input expression.
     *
     * @return string
     */
    public function getInputExpression();

    /**
     * Get the output expression.
     *
     * @return string
     */
    public function getOutputExpression();

    /**
     * Get the operation name.
     *
     * @return string
     */
    public function getName();

    /**
     * Print the object data.
     *
     * @return string
     */
    public function __toString();
}
