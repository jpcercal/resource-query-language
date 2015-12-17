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
 * AbstractParser
 *
 * @abstract
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
abstract class AbstractParser implements ParserInterface
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Process one expression an enqueue it using the ExprBuilder instance.
     *
     * @param  ExprBuilder $builder
     * @param  string      $field
     * @param  string      $expression
     * @param  string      $value
     *
     * @return ExprBuilder
     *
     * @throws ParserException
     */
    public function process(ExprBuilder $builder, $field, $expression, $value)
    {
        if (!Expr::isValidExpression($expression)) {
            throw new ParserException(sprintf(
                'The expression "%s" is not allowed or not exists.',
                $expression
            ));
        }

        switch ($expression) {
            case 'between':
                set_error_handler(function () use ($value) {
                    throw new ParserException(sprintf(
                        'The value of "between" expression "%s" is not valid.',
                        $value
                    ));
                });

                list($from, $to) = explode('-', $value);

                restore_error_handler();

                return $builder->between($field, $from, $to);
            case 'paginate':
                set_error_handler(function () use ($value) {
                    throw new ParserException(sprintf(
                        'The value of "paginate" expression "%s" is not valid.',
                        $value
                    ));
                });

                list($currentPageNumber, $maxResultsPerPage) = explode('-', $value);

                restore_error_handler();

                return $builder->paginate($currentPageNumber, $maxResultsPerPage);
            case 'or':
                return $builder->orx($value);
            default:
                return $builder->{$expression}($field, $value);
        }
    }

    protected function getValueToOrExpression($item)
    {
        return substr($item, strpos(sprintf(':%s:', 'or'), $item) + 4);
    }
}
