<?php

namespace App\Filters;

use Doctrine\ORM\QueryBuilder;

interface FilterInterface
{
    /**
     * @param array $filterData
     *
     * @return bool
     */
    public function isValidType(array $filterData): bool;

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $expression
     * @param mixed $value
     * @param string $parameterName
     */
    public function filter(QueryBuilder $queryBuilder, string $expression, mixed $value, string $parameterName): void;

    /**
     * @param mixed $filterData
     *
     * @return bool
     */
    public function shouldApply(mixed $filterData): bool;

    /**
     * @param QueryBuilder $queryBuilder
     * @param mixed $filterData
     */
    public function apply(QueryBuilder $queryBuilder, mixed $filterData): void;

    /**
     * @param array $filterData
     */
    public function checkValid(array $filterData): void;

    /**
     * @param mixed $filterData
     *
     * @return mixed
     */
    public function getValue(mixed $filterData): mixed;
}