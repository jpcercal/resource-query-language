<?php

/*
 * This file is part of the Cekurte package.
 *
 * (c) João Paulo Cercal <jpcercal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cekurte\Resource\Query\Language\Parser;

use Cekurte\Resource\Query\Language\Contract\ParserInterface;
use Cekurte\Resource\Query\Language\Exception\ParserException;
use Cekurte\Resource\Query\Language\Expr;
use Cekurte\Resource\Query\Language\ExprBuilder;

/**
 * ArrayParser
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
class ArrayParser extends AbstractParser implements ParserInterface
{
    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @inheritdoc
     */
    public function parse()
    {
        $builder = new ExprBuilder();

        $items = $this->getData();

        foreach ($items as $item) {
            if (!isset($item['field'])) {
                throw new ParserException('The key "field" is not set.');
            }

            if (!isset($item['expression'])) {
                throw new ParserException('The key "expression" is not set.');
            }

            if (!isset($item['value'])) {
                throw new ParserException('The key "value" is not set.');
            }

            list($field, $expression, $value) = [
                $item['field'],
                $item['expression'],
                $item['value'],
            ];

            $this->process($builder, $field, $expression, $value);
        }

        return $builder;
    }
}
