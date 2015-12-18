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
use Cekurte\Resource\Query\Language\ExprBuilder;
use Cekurte\Resource\Query\Language\Parser\AbstractParser;
use Psr\Http\Message\RequestInterface;

/**
 * RequestParser
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
class RequestParser extends AbstractParser implements ParserInterface
{
    /**
     * @var string
     */
    protected $queryStringParameter;

    /**
     * @param string $data
     */
    public function __construct(RequestInterface $data)
    {
        $this->data = $data;

        $this->setQueryStringParameter('q');
    }

    /**
     * @param  string $queryStringParameter
     *
     * @return StringParser
     */
    public function setQueryStringParameter($queryStringParameter)
    {
        $this->queryStringParameter = $queryStringParameter;

        return $this;
    }

    /**
     * @return string
     */
    public function getQueryStringParameter()
    {
        return $this->queryStringParameter;
    }

    /**
     * @inheritdoc
     */
    public function parse()
    {
        $builder = new ExprBuilder();

        $queryString = $this->getData()->getUri()->getQuery();

        $queryStringParameter = $this->getQueryStringParameter();

        parse_str(rawurldecode($queryString), $queryParams);

        if (!isset($queryParams[$queryStringParameter])) {
            return $builder;
        }

        if (!is_array($queryParams[$queryStringParameter])) {
            throw new ParserException(sprintf(
                'The query string with key "%s" must be a array.',
                $queryStringParameter
            ));
        }

        $items = $queryParams[$queryStringParameter];

        foreach ($items as $item) {
            if (empty($item)) {
                continue;
            }

            if (substr_count($item = trim($item), ':') < 2) {
                throw new ParserException(sprintf(
                    'The template of the current item "%s" is invalid.',
                    $item
                ));
            }

            list($field, $expression, $value) = explode(':', $item);

            if ($expression === 'or') {
                $value = $this->getValueToOrExpression($item);
            }

            $this->process($builder, $field, $expression, $value);
        }

        return $builder;
    }
}
