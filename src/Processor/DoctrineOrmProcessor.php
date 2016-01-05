<?php

/*
 * This file is part of the Cekurte package.
 *
 * (c) João Paulo Cercal <jpcercal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cekurte\Resource\Query\Language\Processor;

use Cekurte\Resource\Query\Language\Contract\ExprInterface;
use Cekurte\Resource\Query\Language\Contract\ProcessorInterface;
use Cekurte\Resource\Query\Language\Exception\ProcessorException;
use Cekurte\Resource\Query\Language\ExprQueue;
use Cekurte\Resource\Query\Language\Expr\AndExpr;
use Cekurte\Resource\Query\Language\Expr\BetweenExpr;
use Cekurte\Resource\Query\Language\Expr\OrExpr;
use Cekurte\Resource\Query\Language\Expr\PaginateExpr;
use Cekurte\Resource\Query\Language\Expr\SortExpr;
use Doctrine\ORM\QueryBuilder;

/**
 * DoctrineOrmProcessor
 *
 * @author João Paulo Cercal <jpcercal@gmail.com>
 */
class DoctrineOrmProcessor implements ProcessorInterface
{
    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @var string
     */
    protected $processingExprAndExprOr;

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;

        $this->processingExprAndExprOr = false;
    }

    /**
     * @return bool
     */
    protected function isProcessingExprAndExprOr()
    {
        return $this->processingExprAndExprOr;
    }

    /**
     * @inheritdoc
     */
    public function process(ExprQueue $queue)
    {
        foreach ($queue as $expr) {
            if ($expr instanceof AndExpr) {
                $this->processAndExpr($expr);
            } elseif ($expr instanceof BetweenExpr) {
                $this->processBetweenExpr($expr);
            } elseif ($expr instanceof PaginateExpr) {
                $this->processPaginateExpr($expr);
            } elseif ($expr instanceof SortExpr) {
                $this->processSortExpr($expr);
            } elseif ($expr instanceof OrExpr) {
                $this->processOrExpr($expr);
            } elseif ($expr instanceof ExprInterface) {
                $this->processComparisonExpr($expr);
            }
        }

        return $this->queryBuilder;
    }

    /**
     * Get the parameter key that is used by doctrine to put the query parameter identifier.
     *
     * @param  ExprInterface $expr
     *
     * @return string
     */
    protected function getParamKeyByExpr(ExprInterface $expr)
    {
        return str_replace('.', '', $expr->getField()) . ucfirst($expr->getExpression()) . md5(uniqid(rand(), true));
    }

    /**
     * @param AndExpr $expr
     */
    protected function processAndExpr(AndExpr $expr)
    {
        $this->processingExprAndExprOr = true;

        $queue = $expr->getQueue();

        $where = [];

        foreach ($queue as $expr) {
            if ($expr instanceof OrExpr) {
                $this->processOrExpr($expr);
            } elseif ($expr instanceof BetweenExpr) {
                $where[] = $this->processBetweenExpr($expr);
            } elseif ($expr instanceof ExprInterface) {
                $where[] = $this->processComparisonExpr($expr);
            }
        }

        $this->queryBuilder->andWhere(call_user_func_array(
            [$this->queryBuilder->expr(), 'andX'],
            $where
        ));

        $this->processingExprAndExprOr = false;
    }

    /**
     * @param BetweenExpr $expr
     */
    protected function processBetweenExpr(BetweenExpr $expr)
    {
        $paramKey = $this->getParamKeyByExpr($expr);

        $from = $paramKey . 'From';
        $to   = $paramKey . 'To';

        $where = $this->queryBuilder->expr()->between($expr->getField(), ':' . $from, ':' . $to);

        $this->queryBuilder->setParameter($from, $expr->getFrom());

        $this->queryBuilder->setParameter($to, $expr->getTo());

        if ($this->isProcessingExprAndExprOr()) {
            return $where;
        }

        $this->queryBuilder->andWhere($where);
    }

    /**
     * @param PaginateExpr $expr
     */
    protected function processPaginateExpr(PaginateExpr $expr)
    {
        $this->queryBuilder->setFirstResult($expr->getCurrentPageNumber());

        $this->queryBuilder->setMaxResults($expr->getMaxResultsPerPage());
    }

    /**
     * @param SortExpr $expr
     */
    protected function processSortExpr(SortExpr $expr)
    {
        $this->queryBuilder->addOrderBy($expr->getField(), $expr->getDirection());
    }

    /**
     * @param OrExpr $expr
     */
    protected function processOrExpr(OrExpr $expr)
    {
        $this->processingExprAndExprOr = true;

        $queue = $expr->getQueue();

        $where = [];

        foreach ($queue as $expr) {
            if ($expr instanceof AndExpr) {
                $this->processAndExpr($expr);
            } elseif ($expr instanceof BetweenExpr) {
                $where[] = $this->processBetweenExpr($expr);
            } elseif ($expr instanceof ExprInterface) {
                $where[] = $this->processComparisonExpr($expr);
            }
        }

        $this->queryBuilder->orWhere(call_user_func_array(
            [$this->queryBuilder->expr(), 'orX'],
            $where
        ));

        $this->processingExprAndExprOr = false;
    }

    /**
     * @param ExprInterface $expr
     */
    protected function processComparisonExpr(ExprInterface $expr)
    {
        $paramKey = $this->getParamKeyByExpr($expr);

        $where = $this->queryBuilder->expr()->{$expr->getExpression()}($expr->getField(), ':' . $paramKey);

        $this->queryBuilder->setParameter($paramKey, $expr->getValue());

        if ($this->isProcessingExprAndExprOr()) {
            return $where;
        }

        $this->queryBuilder->andWhere($where);
    }
}
