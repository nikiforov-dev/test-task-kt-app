<?php

namespace App\Filters\Product;

use App\Filters\AbstractFilter;
use App\Filters\FilterInterface;

class ProductIdFilter extends AbstractFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    protected string $entityVariableName = 'id';

    /**
     * {@inheritdoc}
     */
    protected string $variable = 'id';
}