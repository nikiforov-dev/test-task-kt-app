<?php

namespace App\Filters\Product;

use App\Filters\AbstractFilter;
use App\Filters\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class ProductFromWeightFilter extends AbstractFilter implements FilterInterface
{
    public function apply(QueryBuilder $queryBuilder, mixed $filterData): void
    {
        $alias = $queryBuilder->getRootAliases()[0];

        if (is_array($filterData) && isset($filterData['fromWeight']) && is_int($filterData['fromWeight'])) {
            $queryBuilder->andWhere("{$alias}.weight >= :fromWeight")->setParameter('fromWeight', $filterData['fromWeight']);
        }
    }
}