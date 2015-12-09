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
use Cekurte\Resource\Query\Language\ExprQueue;

/**
 * ProcessorInterface
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
interface ProcessorInterface extends QueryLanguageInterface
{
    /**
     * Process a ExprQueue to a specific target.
     *
     * @param  ExprQueue $queue
     *
     * @return mixed
     */
    public function process(ExprQueue $queue);
}
