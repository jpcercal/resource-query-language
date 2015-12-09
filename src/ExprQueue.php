<?php

/*
 * This file is part of the Cekurte package.
 *
 * (c) João Paulo Cercal <jpcercal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cekurte\Resource\Query\Language;

use Cekurte\Resource\Query\Language\Contract\ExprInterface;
use Cekurte\Resource\Query\Language\Exception\ExprException;

/**
 * ExprQueue
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
class ExprQueue extends \SplQueue
{
    /**
     * @return ExprQueue
     *
     * @throws ExprException
     */
    public function enqueue($expr)
    {
        if (!$expr instanceof ExprInterface) {
            throw new ExprException(sprintf(
                'The $expr variable is not a instance of %s.',
                'Cekurte\Resource\Query\Language\Contract\ExprInterface'
            ));
        }

        parent::enqueue($expr);

        return $this;
    }
}
