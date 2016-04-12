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

/**
 * Expr
 *
 * @final
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
final class Expr
{
    /**
     * @static
     *
     * @return array
     */
    public static function getExpressions()
    {
        return [
            'between'  => '\Cekurte\Resource\Query\Language\Expr\BetweenExpr',
            'eq'       => '\Cekurte\Resource\Query\Language\Expr\EqExpr',
            'gte'      => '\Cekurte\Resource\Query\Language\Expr\GteExpr',
            'gt'       => '\Cekurte\Resource\Query\Language\Expr\GtExpr',
            'in'       => '\Cekurte\Resource\Query\Language\Expr\InExpr',
            'like'     => '\Cekurte\Resource\Query\Language\Expr\LikeExpr',
            'lte'      => '\Cekurte\Resource\Query\Language\Expr\LteExpr',
            'lt'       => '\Cekurte\Resource\Query\Language\Expr\LtExpr',
            'neq'      => '\Cekurte\Resource\Query\Language\Expr\NeqExpr',
            'notin'    => '\Cekurte\Resource\Query\Language\Expr\NotInExpr',
            'notlike'  => '\Cekurte\Resource\Query\Language\Expr\NotLikeExpr',
            'or'       => '\Cekurte\Resource\Query\Language\Expr\OrExpr',
            'paginate' => '\Cekurte\Resource\Query\Language\Expr\PaginateExpr',
            'sort'     => '\Cekurte\Resource\Query\Language\Expr\SortExpr',
        ];
    }

    /**
     * @static
     *
     * @param  string  $expression
     *
     * @return bool
     */
    public static function isValidExpression($expression)
    {
        return array_key_exists(strtolower($expression), self::getExpressions());
    }
}
