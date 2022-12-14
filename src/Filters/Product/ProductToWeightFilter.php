<?php

namespace App\Filters\Product;

use App\Filters\AbstractFilter;
use App\Filters\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class ProductToWeightFilter extends AbstractFilter implements FilterInterface
{
    public function apply(QueryBuilder $queryBuilder, mixed $filterData): void
    {
        $alias = $queryBuilder->getRootAliases()[0];

        if (is_array($filterData) && isset($filterData['toWeight']) && is_int($filterData['toWeight'])) {
            $queryBuilder->andWhere("{$alias}.weight <= :toWeight")->setParameter('toWeight', $filterData['toWeight']);
        }
    }
}