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
use Cekurte\Resource\Query\Language\Exception\QueueException;
use Cekurte\Resource\Query\Language\ExprQueue;

/**
 * QueueInterface
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
interface QueueInterface extends QueryLanguageInterface
{
    /**
     * Enqueue a ExprInterface.
     *
     * @return ExprQueue
     *
     * @throws QueueException
     */
    public function enqueue($expr);
}
