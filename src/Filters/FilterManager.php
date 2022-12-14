<?php

namespace App\Filters;

use App\Entity\Product;
use App\Filters\Product\ProductCategoryFilter;
use App\Filters\Product\ProductDescriptionFilter;
use App\Filters\Product\ProductFromWeightFilter;
use App\Filters\Product\ProductIdFilter;
use App\Filters\Product\ProductNameFilter;
use App\Filters\Product\ProductToWeightFilter;
use Doctrine\ORM\QueryBuilder;

class FilterManager
{
    /**
     * @var array
     */
    private array $filters;

    public function __construct()
    {
        $this->filters = [
            Product::class => [
                new ProductIdFilter(),
                new ProductNameFilter(),
                new ProductCategoryFilter(),
                new ProductDescriptionFilter(),
                new ProductFromWeightFilter(),
                new ProductToWeightFilter(),
            ],
        ];
    }

    /**
     * @param QueryBuilder $qb
     * @param array $filterData
     * @param string $entityClass
     *
     * @return void
     */
    public function applyFilters(QueryBuilder $qb, array $filterData, string $entityClass): void
    {
        foreach ($this->getFilterForClass($entityClass) as $filter) {
            $filter->apply($qb, $filterData);
        }
    }

    /**
     * @param string $entityClass
     *
     * @return FilterInterface[]
     */
    private function getFilterForClass(string $entityClass): array
    {
        return $this->filters[$entityClass];
    }

    /**
     * @param QueryBuilder $qb
     * @param array $orderByData
     * @return void
     */
    public function applyOrderBy(QueryBuilder $qb, array $orderByData): void
    {
        $alias = $qb->getRootAliases()[0];

        if (isset($orderByData['orderBy']) && isset($orderByData['direction'])) {
            $qb->orderBy("{$alias}.{$orderByData['orderBy']}", $orderByData['direction']);
        }
    }
}