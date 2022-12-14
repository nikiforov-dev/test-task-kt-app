<?php

namespace App\Filters\Product;

use App\Filters\AbstractFilter;
use App\Filters\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class ProductNameFilter extends AbstractFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    protected string $entityVariableName = 'name';

    /**
     * {@inheritdoc}
     */
    protected string $variable = 'name';
}