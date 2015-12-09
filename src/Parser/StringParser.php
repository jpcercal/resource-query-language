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
use Cekurte\Resource\Query\Language\Parser\AbstractParser;

/**
 * StringParser
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
class StringParser extends AbstractParser implements ParserInterface
{
    /**
     * @var string
     */
    protected $separator;

    /**
     * @param string $data
     */
    public function __construct($data)
    {
        $this->data = $data;

        $this->setSeparator('&');
    }

    /**
     * @param  string $separator
     *
     * @return StringParser
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * @inheritdoc
     */
    public function parse()
    {
        $builder = new ExprBuilder();

        $items = explode($this->getSeparator(), $this->getData());

        foreach ($items as $item) {
            if (substr_count($item = trim($item), ':') !== 2) {
                throw new ParserException(sprintf(
                    'The template of the current item "%s" is invalid.',
                    $item
                ));
            }

            list($field, $expression, $value) = explode(':', $item);

            $this->process($builder, $field, $expression, $value);
        }

        return $builder;
    }
}
