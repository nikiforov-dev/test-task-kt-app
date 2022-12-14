<?php

namespace App\Filters\Product;

use App\Filters\AbstractFilter;
use App\Filters\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class ProductCategoryFilter extends AbstractFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    protected string $entityVariableName = 'category';

    /**
     * {@inheritdoc}
     */
    protected string $variable = 'category';
}