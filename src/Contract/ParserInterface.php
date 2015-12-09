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

use Cekurte\Resource\Query\Language\Exception\ParserException;
use Cekurte\Resource\Query\Language\ExprQueue;

/**
 * ParserInterface
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
interface ParserInterface
{
    /**
     * Parse a data source to ExprQueue instance.
     *
     * @return ExprQueue
     *
     * @throws ParserException
     */
    public function parse();
}
