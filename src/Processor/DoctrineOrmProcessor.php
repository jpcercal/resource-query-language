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
    const WHERE_OPERATION_MODE_AND = 'andWhere';

    const WHERE_OPERATION_MODE_OR  = 'orWhere';

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @var string
     */
    protected $whereOperationMode;

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;

        $this->setWhereOperationMode(self::WHERE_OPERATION_MODE_AND);
    }

    /**
     * @param  string $whereOperationMode
     *
     * @return DoctrineOrmProcessor
     *
     * @throws ProcessorException
     */
    protected function setWhereOperationMode($whereOperationMode)
    {
        if (!in_array($whereOperationMode, [self::WHERE_OPERATION_MODE_AND, self::WHERE_OPERATION_MODE_OR])) {
            throw new ProcessorException(sprintf(
                'The where operation mode "%s" is not allowed or not exists.',
                $whereOperationMode
            ));
        }

        $this->whereOperationMode = $whereOperationMode;

        return $this;
    }

    /**
     * @return string
     */
    public function getWhereOperationMode()
    {
        return $this->whereOperationMode;
    }

    /**
     * @inheritdoc
     */
    public function process(ExprQueue $queue)
    {
        foreach ($queue as $expr) {
            if ($expr instanceof BetweenExpr) {
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
        return str_replace('.', '', $expr->getField()) . ucfirst($expr->getExpression()) . uniqid();
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

        $whereOperationMode = $this->getWhereOperationMode();

        $this->queryBuilder->{$whereOperationMode}($where);

        $this->queryBuilder->setParameter($from, $expr->getFrom());

        $this->queryBuilder->setParameter($to, $expr->getTo());
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
        $this->setWhereOperationMode(self::WHERE_OPERATION_MODE_OR);

        $this->process($expr->getQueue());

        $this->setWhereOperationMode(self::WHERE_OPERATION_MODE_AND);
    }

    /**
     * @param ExprInterface $expr
     */
    protected function processComparisonExpr(ExprInterface $expr)
    {
        $paramKey = $this->getParamKeyByExpr($expr);

        $where = $this->queryBuilder->expr()->{$expr->getExpression()}($expr->getField(), ':' . $paramKey);

        $whereOperationMode = $this->getWhereOperationMode();

        $this->queryBuilder->{$whereOperationMode}($where);

        $this->queryBuilder->setParameter($paramKey, $expr->getValue());
    }
}
